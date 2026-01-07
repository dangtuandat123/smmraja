<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::firstOrCreate(
            ['email' => 'admin@smmpanel.vn'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'balance' => 0,
                'is_active' => true,
            ]
        );

        // Create Demo User
        User::firstOrCreate(
            ['email' => 'demo@smmpanel.vn'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('demo123'),
                'role' => 'user',
                'balance' => 500000,
                'is_active' => true,
            ]
        );

        // Create default settings
        $defaultSettings = [
            // General
            ['key' => 'site_name', 'value' => 'SMM Panel', 'type' => 'text', 'group' => 'general', 'label' => 'Tên website'],
            ['key' => 'exchange_rate', 'value' => '27000', 'type' => 'number', 'group' => 'general', 'label' => 'Tỷ giá USD/VND'],
            
            // API Settings
            ['key' => 'smmraja_api_url', 'value' => 'https://www.smmraja.com/api/v3', 'type' => 'text', 'group' => 'api', 'label' => 'SMM Raja API URL'],
            ['key' => 'smmraja_api_key', 'value' => '', 'type' => 'password', 'group' => 'api', 'label' => 'SMM Raja API Key'],
            ['key' => 'wallet_api_key', 'value' => '', 'type' => 'password', 'group' => 'api', 'label' => 'Wallet API Key'],
            
            // Payment
            ['key' => 'vietqr_bank_id', 'value' => '970416', 'type' => 'text', 'group' => 'payment', 'label' => 'VietQR Bank ID'],
            ['key' => 'vietqr_account_number', 'value' => '', 'type' => 'text', 'group' => 'payment', 'label' => 'Số tài khoản'],
            ['key' => 'vietqr_account_name', 'value' => '', 'type' => 'text', 'group' => 'payment', 'label' => 'Tên chủ TK'],
            ['key' => 'vietqr_template', 'value' => 'rdXzPHV', 'type' => 'text', 'group' => 'payment', 'label' => 'VietQR Template'],
            ['key' => 'min_deposit', 'value' => '10000', 'type' => 'number', 'group' => 'payment', 'label' => 'Nạp tối thiểu'],
            
            // SEO
            ['key' => 'meta_title', 'value' => 'SMM Panel - Dịch vụ tăng tương tác mạng xã hội', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Title'],
            ['key' => 'meta_description', 'value' => 'SMM Panel cung cấp dịch vụ tăng like, follow, view chất lượng cao với giá rẻ nhất thị trường.', 'type' => 'textarea', 'group' => 'seo', 'label' => 'Meta Description'],
            
            // Contact
            ['key' => 'contact_email', 'value' => 'support@smmpanel.vn', 'type' => 'text', 'group' => 'contact', 'label' => 'Email'],
            ['key' => 'telegram_url', 'value' => 'https://t.me/smmpanelvn', 'type' => 'text', 'group' => 'contact', 'label' => 'Telegram'],
        ];

        foreach ($defaultSettings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✓ Created admin user: admin@smmpanel.vn / admin123');
        $this->command->info('✓ Created demo user: demo@smmpanel.vn / demo123');
        $this->command->info('✓ Created default settings');
    }
}
