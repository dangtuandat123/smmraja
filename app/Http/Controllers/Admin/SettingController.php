<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\TelegramService;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = [
            'general' => Setting::getByGroup('general'),
            'api' => Setting::getByGroup('api'),
            'telegram' => Setting::getByGroup('telegram'),
            'seo' => Setting::getByGroup('seo'),
            'contact' => Setting::getByGroup('contact'),
            'payment' => Setting::getByGroup('payment'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $group = $request->get('group', 'general');

        $settingsConfig = $this->getSettingsConfig();

        foreach ($settingsConfig[$group] ?? [] as $key => $config) {
            $value = $request->get($key);
            
            if ($config['type'] === 'boolean') {
                $value = $request->boolean($key) ? '1' : '0';
            }

            Setting::set($key, $value, $config['type'], $group, $config['label']);
        }

        return back()->with('success', 'Cài đặt đã được lưu!');
    }

    /**
     * Test Telegram notification
     */
    public function testTelegram(TelegramService $telegram)
    {
        if (!$telegram->isConfigured()) {
            return back()->withErrors(['telegram' => 'Telegram chưa được cấu hình. Vui lòng nhập Bot Token và Chat ID.']);
        }

        $success = $telegram->sendMessage(
            "✅ <b>Test thành công!</b>\n\n"
            . "Telegram Bot đã được cấu hình đúng.\n"
            . "🕐 " . now()->format('H:i d/m/Y')
        );

        if ($success) {
            return back()->with('success', '✅ Gửi tin nhắn test thành công! Kiểm tra Telegram của bạn.');
        } else {
            return back()->withErrors(['telegram' => '❌ Gửi thất bại! Kiểm tra lại Bot Token và Chat ID.']);
        }
    }

    /**
     * Get settings configuration
     */
    protected function getSettingsConfig(): array
    {
        return [
            'general' => [
                'site_name' => ['type' => 'text', 'label' => 'Tên website'],
                'site_logo' => ['type' => 'text', 'label' => 'URL Logo'],
                'site_favicon' => ['type' => 'text', 'label' => 'URL Favicon'],
                'currency_symbol' => ['type' => 'text', 'label' => 'Ký hiệu tiền tệ'],
                'maintenance_mode' => ['type' => 'boolean', 'label' => 'Chế độ bảo trì'],
            ],
            'api' => [
                'smmraja_api_url' => ['type' => 'text', 'label' => 'SMM Raja API URL'],
                'smmraja_api_key' => ['type' => 'password', 'label' => 'SMM Raja API Key'],
                'wallet_api_key' => ['type' => 'password', 'label' => 'Wallet API Key'],
            ],
            'telegram' => [
                'telegram_enabled' => ['type' => 'boolean', 'label' => 'Bật Telegram'],
                'telegram_bot_token' => ['type' => 'text', 'label' => 'Bot Token'],
                'telegram_chat_id' => ['type' => 'text', 'label' => 'Chat ID'],
                'balance_warning_threshold' => ['type' => 'number', 'label' => 'Ngưỡng cảnh báo ($)'],
            ],
            'seo' => [
                'meta_title' => ['type' => 'text', 'label' => 'Meta Title'],
                'meta_description' => ['type' => 'textarea', 'label' => 'Meta Description'],
                'meta_keywords' => ['type' => 'text', 'label' => 'Meta Keywords'],
                'google_analytics' => ['type' => 'textarea', 'label' => 'Google Analytics Code'],
            ],
            'contact' => [
                'contact_email' => ['type' => 'text', 'label' => 'Email liên hệ'],
                'contact_phone' => ['type' => 'text', 'label' => 'Số điện thoại'],
                'contact_address' => ['type' => 'textarea', 'label' => 'Địa chỉ'],
                'facebook_url' => ['type' => 'text', 'label' => 'Facebook URL'],
                'telegram_url' => ['type' => 'text', 'label' => 'Telegram URL'],
                'zalo_url' => ['type' => 'text', 'label' => 'Zalo URL'],
            ],
            'payment' => [
                'vietqr_bank_id' => ['type' => 'text', 'label' => 'VietQR Bank ID'],
                'vietqr_account_number' => ['type' => 'text', 'label' => 'Số tài khoản'],
                'vietqr_account_name' => ['type' => 'text', 'label' => 'Tên chủ tài khoản'],
                'vietqr_template' => ['type' => 'text', 'label' => 'VietQR Template'],
                'min_deposit' => ['type' => 'number', 'label' => 'Nạp tối thiểu (VND)'],
            ],
        ];
    }
}

