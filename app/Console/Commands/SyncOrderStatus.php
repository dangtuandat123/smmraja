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
            
            $statusData = $statuses[$order->api_order_id] ?? null;
            
            if (!$statusData || isset($statusData['error'])) {
                $bar->advance();
                continue;
            }
            
            $newStatus = $smmService->mapStatus($statusData['status'] ?? '');
            $oldStatus = $order->status;
            
            if (!$newStatus || $newStatus === $oldStatus) {
                $bar->advance();
                continue;
            }
            
            // Update order
            $order->update([
                'status' => $newStatus,
                'start_count' => $statusData['start_count'] ?? $order->start_count,
                'remains' => $statusData['remains'] ?? $order->remains,
                'api_charge' => $statusData['charge'] ?? $order->api_charge,
            ]);
            
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
            
            // Handle refund for canceled/partial orders
            if (in_array($newStatus, ['canceled', 'refunded', 'partial']) && $oldStatus !== $newStatus) {
                $this->handleRefund($order, $statusData);
            }
            
            Log::info('Order status updated', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);
            
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
        
        return 0;
    }
    
    /**
     * Handle refund for canceled/partial orders
     */
    protected function handleRefund(Order $order, array $statusData): void
    {
        $refundAmount = 0;
        
        if ($order->status === 'canceled' || $order->status === 'refunded') {
            // Full refund
            $refundAmount = $order->total_price;
        } elseif ($order->status === 'partial') {
            // Partial refund based on remains
            $remains = (int) ($statusData['remains'] ?? 0);
            if ($remains > 0) {
                $refundAmount = $order->price_per_unit * $remains;
            }
        }
        
        if ($refundAmount > 0) {
            $user = $order->user;
            $user->addBalance(
                $refundAmount,
                'refund',
                "Hoàn tiền đơn hàng #{$order->id} ({$order->status})",
                $order->id
            );
            
            Notification::depositSuccess($user->id, $refundAmount);
            
            Log::info('Order refunded', [
                'order_id' => $order->id,
                'refund_amount' => $refundAmount,
            ]);
        }
    }
}
