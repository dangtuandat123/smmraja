<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Notification;
use App\Services\SmmRajaService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncOrderStatus extends Command
{
    protected $signature = 'orders:sync-status {--limit=100 : Maximum orders to check}';
    protected $description = 'Sync order status from SMM Raja API';

    public function handle(SmmRajaService $smmService)
    {
        $limit = (int) $this->option('limit');
        
        // Get orders that need status check
        $orders = Order::needsStatusCheck()
            ->whereNotNull('api_order_id')
            ->limit($limit)
            ->get();
        
        if ($orders->isEmpty()) {
            $this->info('✓ Không có đơn hàng nào cần kiểm tra.');
            return 0;
        }
        
        $this->info("🔄 Đang kiểm tra {$orders->count()} đơn hàng...");
        
        // Get all order IDs
        $apiOrderIds = $orders->pluck('api_order_id')->filter()->toArray();
        
        try {
            $statuses = $smmService->getMultipleOrderStatus($apiOrderIds);
        } catch (\Exception $e) {
            $this->error('❌ Không thể kết nối API: ' . $e->getMessage());
            Log::error('SyncOrderStatus failed', ['error' => $e->getMessage()]);
            return 1;
        }
        
        // Debug: show API response
        if ($this->output->isVerbose()) {
            $this->info("API Response: " . json_encode($statuses, JSON_PRETTY_PRINT));
        }
        
        $updatedCount = 0;
        $completedCount = 0;
        $partialCount = 0;
        $canceledCount = 0;
        
        $bar = $this->output->createProgressBar($orders->count());
        $bar->start();
        
        foreach ($orders as $order) {
            if (!$order->api_order_id) {
                $bar->advance();
                continue;
            }
            
            // Handle both single order response and multiple orders response
            // Single: {"status":"...", "start_count":"...", ...}
            // Multiple: {"order_id":{"status":"...", ...}, ...}
            $statusData = $statuses[$order->api_order_id] ?? ($statuses['status'] ?? null ? $statuses : null);
            
            if (!$statusData || isset($statusData['error'])) {
                $bar->advance();
                continue;
            }
            
            $newStatus = $smmService->mapStatus($statusData['status'] ?? '');
            $oldStatus = $order->status;
            
            // Debug
            if ($this->output->isVerbose()) {
                $this->newLine();
                $this->info("Order #{$order->id}: API status={$statusData['status']}, start_count={$statusData['start_count']}, remains={$statusData['remains']}");
            }
            
            // Always update start_count and remains from API
            $updateData = [
                'start_count' => isset($statusData['start_count']) ? (int) $statusData['start_count'] : $order->start_count,
                'remains' => isset($statusData['remains']) ? (int) $statusData['remains'] : $order->remains,
                'api_charge' => isset($statusData['charge']) ? (float) $statusData['charge'] : $order->api_charge,
            ];
            
            // Update status if changed
            $statusChanged = $newStatus && $newStatus !== $oldStatus;
            if ($statusChanged) {
                $updateData['status'] = $newStatus;
                $updatedCount++;
            
                // Count by status type
                match($newStatus) {
                    'completed' => $completedCount++,
                    'partial' => $partialCount++,
                    'canceled', 'refunded' => $canceledCount++,
                    default => null,
                };
                
                // Send notification for important status changes
                if (in_array($newStatus, ['completed', 'partial', 'canceled', 'refunded'])) {
                    Notification::orderStatusChanged(
                        $order->user_id,
                        $order->id,
                        $newStatus
                    );
                }
                
                // Auto-refund when API returns canceled/refunded/partial status
                if (in_array($newStatus, ['canceled', 'refunded', 'partial'])) {
                    $this->handleRefund($order, $newStatus, $statusData);
                }
                
                Log::info('Order status updated', [
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ]);
            }
            
            // Always update order with latest API data
            $order->update($updateData);
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        // Summary
        $this->info("✅ Đã cập nhật {$updatedCount} đơn hàng!");
        
        if ($completedCount > 0) {
            $this->info("   ✓ Hoàn thành: {$completedCount}");
        }
        if ($partialCount > 0) {
            $this->warn("   ⚠ Partial: {$partialCount}");
        }
        if ($canceledCount > 0) {
            $this->warn("   ✗ Hủy/Hoàn tiền: {$canceledCount}");
        }
        
        // Check SMM Raja balance and warn if low
        $this->checkApiBalance($smmService);
        
        return 0;
    }
    
    /**
     * Check API balance and warn admins if low
     */
    protected function checkApiBalance(SmmRajaService $smmService): void
    {
        try {
            $balanceData = $smmService->getBalance();
            $balance = (float) ($balanceData['balance'] ?? 0);
            $currency = $balanceData['currency'] ?? 'USD';
            
            // Get warning threshold from settings (default $10)
            $threshold = (float) (\App\Models\Setting::get('balance_warning_threshold') ?? 10);
            
            $this->info("💰 Số dư SMM Raja: \${$balance} {$currency}");
            
            if ($balance < $threshold) {
                $this->error("⚠️  CẢNH BÁO: Số dư SMM Raja thấp! (< \${$threshold})");
                
                // Notify all admins
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Notification::system(
                        $admin->id,
                        '⚠️ Số dư SMM Raja thấp!',
                        "Số dư hiện tại: \${$balance} {$currency}. Vui lòng nạp thêm để đảm bảo dịch vụ hoạt động.",
                        'warning'
                    );
                }
                
                Log::warning('SMM Raja balance low', [
                    'balance' => $balance,
                    'currency' => $currency,
                    'threshold' => $threshold,
                ]);
            }
        } catch (\Exception $e) {
            $this->warn("⚠️  Không thể kiểm tra số dư API: " . $e->getMessage());
        }
    }
    
    /**
     * Handle refund for canceled/partial orders
     */
    protected function handleRefund(Order $order, string $newStatus, array $statusData): void
    {
        $refundAmount = 0;
        
        if ($newStatus === 'canceled' || $newStatus === 'refunded') {
            // Full refund - total_price is already integer
            $refundAmount = (int) $order->total_price;
        } elseif ($newStatus === 'partial') {
            // Partial refund based on remains - round to integer
            $remains = (int) ($statusData['remains'] ?? 0);
            if ($remains > 0) {
                $refundAmount = (int) round($order->price_per_unit * $remains);
            }
        }
        
        if ($refundAmount > 0) {
            $user = $order->user;
            $user->addBalance(
                $refundAmount,
                'refund',
                "Hoàn tiền đơn hàng #{$order->id} ({$newStatus})",
                $order->id
            );
            
            // Notification already sent via orderStatusChanged() above (line 105-109)
            // No need for separate refund notification
            
            Log::info('Order refunded', [
                'order_id' => $order->id,
                'refund_amount' => $refundAmount,
            ]);
        }
    }
}
