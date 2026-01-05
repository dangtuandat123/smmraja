<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Service;
use App\Models\Setting;
use Exception;

class SmmRajaService
{
    private string $apiUrl;
    private string $apiKey;
    private int $timeout = 30;

    public function __construct()
    {
        $this->apiUrl = config('services.smmraja.url', 'https://www.smmraja.com/api/v3');
        $this->apiKey = config('services.smmraja.key', '');
    }

    /**
     * Make API request
     */
    protected function request(array $params): array
    {
        $params['key'] = $this->apiKey;

        try {
            $response = Http::timeout($this->timeout)
                ->asForm()
                ->post($this->apiUrl, $params);

            $data = $response->json();

            // Check for API error
            if (is_array($data) && isset($data['error'])) {
                Log::error('SMMRaja API Error', [
                    'params' => array_merge($params, ['key' => '***']),
                    'error' => $data['error'],
                ]);
                throw new Exception($data['error']);
            }

            return $data ?? [];
        } catch (Exception $e) {
            Log::error('SMMRaja API Request Failed', [
                'params' => array_merge($params, ['key' => '***']),
                'exception' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get all services from API
     * 
     * @param bool $forceRefresh Force refresh cache
     * @return array
     */
    public function getServices(bool $forceRefresh = false): array
    {
        $cacheKey = 'smmraja.services';

        if ($forceRefresh) {
            Cache::forget($cacheKey);
        }

        return Cache::remember($cacheKey, 300, function () { // Cache 5 minutes
            return $this->request(['action' => 'services']);
        });
    }

    /**
     * Get a single service from API by ID
     */
    public function getServiceById(int $serviceId): ?array
    {
        $services = $this->getServices();
        
        foreach ($services as $service) {
            if ($service['service'] == $serviceId) {
                return $service;
            }
        }

        return null;
    }

    /**
     * Add order to API
     * 
     * @param Service $service Local service model
     * @param array $data Order data (link, quantity, comments, etc.)
     * @return int Order ID
     */
    public function addOrder(Service $service, array $data): int
    {
        // First, verify the API price hasn't increased significantly
        $this->verifyApiPrice($service);

        // Build payload based on service type
        $payload = $this->buildOrderPayload($service, $data);

        $response = $this->request($payload);

        if (!isset($response['order'])) {
            throw new Exception('Invalid response from API - missing order ID');
        }

        return (int) $response['order'];
    }

    /**
     * Verify API price hasn't increased beyond safe threshold
     * This prevents losses when API prices change
     */
    protected function verifyApiPrice(Service $service): void
    {
        $apiService = $this->getServiceById($service->api_service_id);

        if (!$apiService) {
            throw new Exception('Service not found in API');
        }

        $currentApiRate = (float) $apiService['rate'];
        $storedApiRate = (float) $service->api_rate;

        // If API rate increased by more than 20%, block the order
        if ($currentApiRate > $storedApiRate * 1.2) {
            Log::warning('SMMRaja API price increased significantly', [
                'service_id' => $service->id,
                'stored_rate' => $storedApiRate,
                'current_rate' => $currentApiRate,
            ]);
            throw new Exception('Giá dịch vụ đã thay đổi. Vui lòng thử lại sau.');
        }

        // Update stored rate if changed (within acceptable range)
        if ($currentApiRate != $storedApiRate) {
            $service->api_rate = $currentApiRate;
            $service->updatePriceVnd();
        }
    }

    /**
     * Build order payload based on service type
     */
    protected function buildOrderPayload(Service $service, array $data): array
    {
        $payload = [
            'action' => 'add',
            'service' => $service->api_service_id,
        ];

        $type = strtolower($service->type);

        switch ($type) {
            case 'default':
                $payload['link'] = $data['link'];
                $payload['quantity'] = $data['quantity'];
                if (!empty($data['runs'])) {
                    $payload['runs'] = $data['runs'];
                    $payload['interval'] = $data['interval'] ?? 60;
                }
                break;

            case 'custom comments':
            case 'custom comments package':
                $payload['link'] = $data['link'];
                $payload['comments'] = $this->formatList($data['comments'] ?? []);
                break;

            case 'mentions':
                $payload['link'] = $data['link'];
                $payload['quantity'] = $data['quantity'];
                if (!empty($data['username'])) {
                    $payload['username'] = $data['username'];
                }
                break;

            case 'mentions with hashtags':
                $payload['link'] = $data['link'];
                $payload['quantity'] = $data['quantity'];
                if (!empty($data['hashtag'])) {
                    $payload['hashtag'] = $data['hashtag'];
                } else {
                    $payload['hashtags'] = $this->formatList($data['hashtags'] ?? []);
                    $payload['usernames'] = $this->formatList($data['usernames'] ?? []);
                }
                break;

            case 'mentions custom list':
                $payload['link'] = $data['link'];
                $payload['quantity'] = $data['quantity'];
                $payload['usernames'] = $this->formatList($data['usernames'] ?? []);
                break;

            case 'mentions hashtag':
                $payload['link'] = $data['link'];
                $payload['quantity'] = $data['quantity'];
                $payload['hashtag'] = $data['hashtag'];
                break;

            case 'mentions user followers':
                $payload['link'] = $data['link'];
                $payload['quantity'] = $data['quantity'];
                $payload['username'] = $data['username'];
                break;

            case 'mentions media likers':
                $payload['link'] = $data['link'];
                $payload['quantity'] = $data['quantity'];
                $payload['media'] = $data['media'] ?? $data['link'];
                break;

            case 'comment likes':
                $payload['link'] = $data['link'];
                $payload['quantity'] = $data['quantity'];
                $payload['username'] = $data['username'];
                break;

            case 'poll':
                $payload['link'] = $data['link'];
                $payload['quantity'] = $data['quantity'];
                $payload['answer_number'] = $data['answer_number'];
                break;

            case 'comment replies':
                $payload['link'] = $data['link'];
                $payload['username'] = $data['username'];
                $payload['comments'] = $this->formatList($data['comments'] ?? []);
                break;

            case 'invites from groups':
                $payload['link'] = $data['link'];
                $payload['quantity'] = $data['quantity'];
                $payload['groups'] = $this->formatList($data['groups'] ?? []);
                break;

            case 'package':
                $payload['link'] = $data['link'];
                break;

            case 'subscriptions':
            case 'package subscriptions':
                $payload['username'] = $data['username'];
                $payload['min'] = $data['min'];
                $payload['max'] = $data['max'];
                $payload['delay'] = $data['delay'];
                if (!empty($data['posts'])) {
                    $payload['posts'] = $data['posts'];
                }
                if (!empty($data['expiry'])) {
                    $payload['expiry'] = $data['expiry'];
                }
                break;

            default:
                // Default fallback
                $payload['link'] = $data['link'];
                $payload['quantity'] = $data['quantity'];
                break;
        }

        return $payload;
    }

    /**
     * Format list items for API (join with newlines)
     */
    protected function formatList($items): string
    {
        if (is_string($items)) {
            return $items;
        }

        return implode("\n", array_filter($items));
    }

    /**
     * Get order status
     */
    public function getOrderStatus(int $orderId): array
    {
        return $this->request([
            'action' => 'status',
            'order' => $orderId,
        ]);
    }

    /**
     * Get multiple orders status
     */
    public function getMultipleOrderStatus(array $orderIds): array
    {
        // Try with 'orders' first (comma-separated)
        try {
            return $this->request([
                'action' => 'status',
                'orders' => implode(',', $orderIds),
            ]);
        } catch (Exception $e) {
            // Fallback to 'order' if 'orders' doesn't work
            return $this->request([
                'action' => 'status',
                'order' => implode(',', $orderIds),
            ]);
        }
    }

    /**
     * Get account balance
     */
    public function getBalance(): array
    {
        return $this->request(['action' => 'balance']);
    }

    /**
     * Request order refill
     */
    public function refillOrder(int $orderId): array
    {
        return $this->request([
            'action' => 'refill',
            'order' => $orderId,
        ]);
    }

    /**
     * Request order cancellation
     */
    public function cancelOrder(int $orderId): array
    {
        return $this->request([
            'action' => 'cancel',
            'order' => $orderId,
        ]);
    }

    /**
     * Map API status to local status
     */
    public function mapStatus(string $apiStatus): string
    {
        $statusMap = [
            'Pending' => 'pending',
            'Processing' => 'processing',
            'In progress' => 'in_progress',
            'Completed' => 'completed',
            'Partial' => 'partial',
            'Canceled' => 'canceled',
            'Refunded' => 'refunded',
        ];

        return $statusMap[$apiStatus] ?? strtolower($apiStatus);
    }
}
