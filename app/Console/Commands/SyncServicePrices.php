<?php

namespace App\Console\Commands;

use App\Models\Service;
use App\Services\SmmRajaService;
use App\Services\ExchangeRateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncServicePrices extends Command
{
    protected $signature = 'services:sync-prices {--force : Force update even if price is the same}';
    protected $description = 'Sync service prices from SMM Raja API';

    public function handle(SmmRajaService $smmService)
    {
        $this->info('🔄 Đang lấy dữ liệu từ SMM Raja API...');
        
        try {
            $apiServices = $smmService->getServices(true);
        } catch (\Exception $e) {
            $this->error('❌ Không thể kết nối API: ' . $e->getMessage());
            Log::error('SyncServicePrices failed', ['error' => $e->getMessage()]);
            return 1;
        }

        $exchangeRate = ExchangeRateService::getRate();
        $this->info("💱 Tỷ giá hiện tại: 1 USD = " . number_format($exchangeRate, 0) . " VND");
        
        // Create lookup map for API services
        $apiServiceMap = [];
        foreach ($apiServices as $apiService) {
            $apiServiceMap[$apiService['service']] = $apiService;
        }
        
        $services = Service::all();
        $updateCount = 0;
        $unavailableCount = 0;
        $force = $this->option('force');
        
        $bar = $this->output->createProgressBar($services->count());
        $bar->start();

        foreach ($services as $service) {
            $apiService = $apiServiceMap[$service->api_service_id] ?? null;
            
            // Check if service no longer exists in API
            if (!$apiService) {
                if ($service->is_active) {
                    $service->update(['is_active' => false]);
                    $unavailableCount++;
                    Log::warning('Service disabled - no longer in API', [
                        'service_id' => $service->id,
                        'api_service_id' => $service->api_service_id,
                        'name' => $service->name,
                    ]);
                }
                $bar->advance();
                continue;
            }

            $newApiRate = (float) $apiService['rate'];
            $oldApiRate = (float) $service->api_rate;
            $newMin = (int) ($apiService['min'] ?? $service->min);
            $newMax = (int) ($apiService['max'] ?? $service->max);
            $newRefill = (bool) ($apiService['refill'] ?? false);
            $newCancel = (bool) ($apiService['cancel'] ?? false);
            $newApiName = $apiService['name'] ?? $service->api_name;
            
            // Check if any field changed
            $priceChanged = $newApiRate != $oldApiRate;
            $minChanged = $newMin != $service->min;
            $maxChanged = $newMax != $service->max;
            $refillChanged = $newRefill != $service->refill;
            $cancelChanged = $newCancel != $service->cancel;
            $nameChanged = $newApiName != $service->api_name;
            
            $hasChanges = $priceChanged || $minChanged || $maxChanged || $refillChanged || $cancelChanged || $nameChanged;
            
            // Update if any field changed or force flag is set
            if ($force || $hasChanges) {
                $service->api_rate = $newApiRate;
                $service->min = $newMin;
                $service->max = $newMax;
                $service->refill = $newRefill;
                $service->cancel = $newCancel;
                $service->api_name = $newApiName;
                $service->updatePriceVnd($exchangeRate);
                
                if ($hasChanges) {
                    $updateCount++;
                    Log::info('Service updated from API', [
                        'service_id' => $service->id,
                        'price_changed' => $priceChanged,
                        'min_changed' => $minChanged,
                        'max_changed' => $maxChanged,
                        'refill_changed' => $refillChanged,
                        'cancel_changed' => $cancelChanged,
                        'name_changed' => $nameChanged,
                    ]);
                }
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        // Summary
        if ($updateCount > 0) {
            $this->info("✅ Đã cập nhật giá cho {$updateCount} dịch vụ!");
        } else {
            $this->info("✓ Tất cả giá đã đồng bộ, không có thay đổi.");
        }
        
        if ($unavailableCount > 0) {
            $this->warn("⚠️ Đã vô hiệu hóa {$unavailableCount} dịch vụ không còn tồn tại trên API.");
        }
        
        return 0;
    }
}
