<?php

namespace App\Console\Commands;

use App\Models\TelegramQueue;
use App\Services\TelegramService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendTelegramNotifications extends Command
{
    protected $signature = 'telegram:send {--limit=50 : Maximum messages to send}';
    protected $description = 'Send queued Telegram notifications';

    public function handle(TelegramService $telegram)
    {
        if (!$telegram->isConfigured()) {
            $this->warn('⚠️  Telegram chưa được cấu hình. Vui lòng cấu hình trong Admin Settings.');
            return 0;
        }

        $limit = (int) $this->option('limit');
        
        $messages = TelegramQueue::unsent()
            ->orderBy('created_at')
            ->limit($limit)
            ->get();

        if ($messages->isEmpty()) {
            $this->info('✓ Không có thông báo nào cần gửi.');
            return 0;
        }

        $this->info("📨 Đang gửi {$messages->count()} thông báo...");
        
        $sentCount = 0;
        $failedCount = 0;

        $bar = $this->output->createProgressBar($messages->count());
        $bar->start();

        foreach ($messages as $message) {
            $success = $telegram->sendMessage($message->message);
            
            if ($success) {
                $message->markAsSent();
                $sentCount++;
            } else {
                $failedCount++;
                Log::error('Telegram send failed', [
                    'queue_id' => $message->id,
                    'type' => $message->type,
                ]);
            }
            
            // Rate limiting - Telegram allows 30 messages per second
            usleep(50000); // 50ms delay
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($sentCount > 0) {
            $this->info("✅ Đã gửi thành công: {$sentCount}");
        }
        
        if ($failedCount > 0) {
            $this->error("❌ Gửi thất bại: {$failedCount}");
        }

        // Clean up old sent messages (older than 7 days)
        $deleted = TelegramQueue::where('sent', true)
            ->where('sent_at', '<', now()->subDays(7))
            ->delete();
            
        if ($deleted > 0) {
            $this->comment("🗑️  Đã xóa {$deleted} thông báo cũ.");
        }

        return 0;
    }
}
