<?php

namespace App\Console\Commands;

use App\Models\Setting;
use Illuminate\Console\Command;

class SyncSettingsFromEnv extends Command
{
    protected $signature = 'settings:sync-env';
    protected $description = 'Sync settings from .env to database';

    public function handle()
    {
        $settings = [
            // API
            ['key' => 'smmraja_api_url', 'value' => env('SMMRAJA_API_URL', 'https://www.smmraja.com/api/v3'), 'type' => 'text', 'group' => 'api', 'label' => 'SMM Raja API URL'],
            ['key' => 'smmraja_api_key', 'value' => env('SMMRAJA_API_KEY', ''), 'type' => 'password', 'group' => 'api', 'label' => 'SMM Raja API Key'],
            ['key' => 'wallet_api_key', 'value' => env('WALLET_API_KEY', ''), 'type' => 'password', 'group' => 'api', 'label' => 'Wallet API Key'],
            
            // Payment
            ['key' => 'vietqr_bank_id', 'value' => env('VIETQR_BANK_ID', '970416'), 'type' => 'text', 'group' => 'payment', 'label' => 'VietQR Bank ID'],
            ['key' => 'vietqr_account_number', 'value' => env('VIETQR_ACCOUNT_NUMBER', ''), 'type' => 'text', 'group' => 'payment', 'label' => 'Số tài khoản'],
            ['key' => 'vietqr_account_name', 'value' => env('VIETQR_ACCOUNT_NAME', ''), 'type' => 'text', 'group' => 'payment', 'label' => 'Tên chủ TK'],
            ['key' => 'vietqr_template', 'value' => env('VIETQR_TEMPLATE', 'rdXzPHV'), 'type' => 'text', 'group' => 'payment', 'label' => 'VietQR Template'],
            
            // General - exchange_rate removed (auto-fetched from API)
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
            $this->info("✓ {$setting['key']} = {$setting['value']}");
        }

        // Clear cache
        Setting::clearCache();

        $this->info("\n✅ Đã đồng bộ settings từ .env vào database!");
        
        return 0;
    }
}
