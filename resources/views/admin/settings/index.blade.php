@extends('layouts.admin')

@section('title', 'Cài đặt hệ thống')

@section('content')
<h2 class="title is-4">Cài đặt hệ thống</h2>

<div class="tabs">
    <ul>
        <li class="is-active" data-tab="general"><a>Chung</a></li>
        <li data-tab="api"><a>API</a></li>
        <li data-tab="payment"><a>Thanh toán</a></li>
        <li data-tab="seo"><a>SEO</a></li>
        <li data-tab="contact"><a>Liên hệ</a></li>
    </ul>
</div>

<!-- General -->
<div id="tab-general" class="tab-content">
    <div class="card">
        <div class="card-content">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <input type="hidden" name="group" value="general">
                
                <div class="field">
                    <label class="label">Tên website</label>
                    <input class="input" type="text" name="site_name" value="{{ $settings['general']['site_name'] ?? config('app.name') }}">
                </div>
                
                <div class="field">
                    <label class="label">URL Logo</label>
                    <input class="input" type="text" name="site_logo" value="{{ $settings['general']['site_logo'] ?? '' }}">
                </div>
                
                <hr>
                
                <div class="field">
                    <label class="label">
                        <i class="fas fa-tools mr-1 has-text-warning"></i>
                        Chế độ bảo trì
                    </label>
                    <div class="control">
                        <label class="switch">
                            <input type="checkbox" name="maintenance_mode" value="1" 
                                {{ ($settings['general']['maintenance_mode'] ?? false) ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                        <span class="ml-3 {{ ($settings['general']['maintenance_mode'] ?? false) ? 'has-text-danger has-text-weight-bold' : 'has-text-grey' }}">
                            {{ ($settings['general']['maintenance_mode'] ?? false) ? 'ĐANG BẬT - Website đang bảo trì' : 'Tắt' }}
                        </span>
                    </div>
                    <p class="help">Khi bật, chỉ Admin mới có thể truy cập website. Người dùng sẽ thấy trang bảo trì.</p>
                </div>
                
                <button type="submit" class="button is-primary"><i class="fas fa-save mr-2"></i> Lưu cài đặt</button>
            </form>
        </div>
    </div>
</div>

<!-- API -->
<div id="tab-api" class="tab-content" style="display: none;">
    <div class="card">
        <div class="card-content">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <input type="hidden" name="group" value="api">
                
                <div class="notification is-warning is-light">
                    <strong><i class="fas fa-exclamation-triangle mr-2"></i>Bảo mật:</strong> 
                    Các API Key sẽ được bảo mật trong database. Không chia sẻ thông tin này.
                </div>
                
                <h4 class="title is-5 mt-4">SMM Raja API</h4>
                
                <div class="field">
                    <label class="label">API URL</label>
                    <input class="input" type="text" name="smmraja_api_url" value="{{ $settings['api']['smmraja_api_url'] ?? 'https://www.smmraja.com/api/v3' }}" placeholder="https://www.smmraja.com/api/v3">
                </div>
                
                <div class="field">
                    <label class="label">API Key</label>
                    <input class="input" type="password" name="smmraja_api_key" value="{{ $settings['api']['smmraja_api_key'] ?? '' }}" placeholder="Nhập API Key của SMM Raja">
                    <p class="help">Lấy API Key từ trang SMM Raja của bạn</p>
                </div>
                
                <hr>
                
                <h4 class="title is-5">Wallet API</h4>
                
                <div class="field">
                    <label class="label">Wallet API Key</label>
                    <input class="input" type="password" name="wallet_api_key" value="{{ $settings['api']['wallet_api_key'] ?? '' }}" placeholder="API Key cho webhook nạp tiền tự động">
                    <p class="help">Dùng để xác thực các request cộng/trừ tiền từ bên ngoài (ví dụ: webhook ngân hàng)</p>
                </div>
                
                <button type="submit" class="button is-primary"><i class="fas fa-save mr-2"></i> Lưu cài đặt</button>
            </form>
        </div>
    </div>
</div>

<!-- Payment -->
<div id="tab-payment" class="tab-content" style="display: none;">
    <div class="card">
        <div class="card-content">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <input type="hidden" name="group" value="payment">
                
                <div class="field">
                    <label class="label">VietQR Bank ID</label>
                    <input class="input" type="text" name="vietqr_bank_id" value="{{ $settings['payment']['vietqr_bank_id'] ?? '' }}" placeholder="970416">
                </div>
                
                <div class="field">
                    <label class="label">Số tài khoản</label>
                    <input class="input" type="text" name="vietqr_account_number" value="{{ $settings['payment']['vietqr_account_number'] ?? '' }}">
                </div>
                
                <div class="field">
                    <label class="label">Tên chủ tài khoản</label>
                    <input class="input" type="text" name="vietqr_account_name" value="{{ $settings['payment']['vietqr_account_name'] ?? '' }}">
                </div>
                
                <div class="field">
                    <label class="label">VietQR Template</label>
                    <input class="input" type="text" name="vietqr_template" value="{{ $settings['payment']['vietqr_template'] ?? 'rdXzPHV' }}">
                </div>
                
                <div class="field">
                    <label class="label">Nạp tối thiểu (VND)</label>
                    <input class="input" type="number" name="min_deposit" value="{{ $settings['payment']['min_deposit'] ?? 10000 }}">
                </div>
                
                <button type="submit" class="button is-primary"><i class="fas fa-save mr-2"></i> Lưu cài đặt</button>
            </form>
        </div>
    </div>
</div>

<!-- SEO -->
<div id="tab-seo" class="tab-content" style="display: none;">
    <div class="card">
        <div class="card-content">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <input type="hidden" name="group" value="seo">
                
                <div class="field">
                    <label class="label">Meta Title</label>
                    <input class="input" type="text" name="meta_title" value="{{ $settings['seo']['meta_title'] ?? '' }}">
                </div>
                
                <div class="field">
                    <label class="label">Meta Description</label>
                    <textarea class="textarea" name="meta_description" rows="3">{{ $settings['seo']['meta_description'] ?? '' }}</textarea>
                </div>
                
                <div class="field">
                    <label class="label">Meta Keywords</label>
                    <input class="input" type="text" name="meta_keywords" value="{{ $settings['seo']['meta_keywords'] ?? '' }}">
                </div>
                
                <div class="field">
                    <label class="label">Google Analytics Code</label>
                    <textarea class="textarea" name="google_analytics" rows="4">{{ $settings['seo']['google_analytics'] ?? '' }}</textarea>
                </div>
                
                <button type="submit" class="button is-primary"><i class="fas fa-save mr-2"></i> Lưu cài đặt</button>
            </form>
        </div>
    </div>
</div>

<!-- Contact -->
<div id="tab-contact" class="tab-content" style="display: none;">
    <div class="card">
        <div class="card-content">
            <form method="POST" action="{{ route('admin.settings.update') }}">
                @csrf
                <input type="hidden" name="group" value="contact">
                
                <div class="field">
                    <label class="label">Email liên hệ</label>
                    <input class="input" type="email" name="contact_email" value="{{ $settings['contact']['contact_email'] ?? '' }}">
                </div>
                
                <div class="field">
                    <label class="label">Số điện thoại</label>
                    <input class="input" type="text" name="contact_phone" value="{{ $settings['contact']['contact_phone'] ?? '' }}">
                </div>
                
                <div class="field">
                    <label class="label">Facebook URL</label>
                    <input class="input" type="text" name="facebook_url" value="{{ $settings['contact']['facebook_url'] ?? '' }}">
                </div>
                
                <div class="field">
                    <label class="label">Telegram URL</label>
                    <input class="input" type="text" name="telegram_url" value="{{ $settings['contact']['telegram_url'] ?? '' }}">
                </div>
                
                <div class="field">
                    <label class="label">Zalo URL</label>
                    <input class="input" type="text" name="zalo_url" value="{{ $settings['contact']['zalo_url'] ?? '' }}">
                </div>
                
                <button type="submit" class="button is-primary"><i class="fas fa-save mr-2"></i> Lưu cài đặt</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.tabs li').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.tabs li').forEach(t => t.classList.remove('is-active'));
            document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
            this.classList.add('is-active');
            document.getElementById('tab-' + this.dataset.tab).style.display = 'block';
        });
    });
</script>
@endsection

@section('styles')
<style>
    /* Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .3s;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .3s;
    }
    input:checked + .slider {
        background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
    }
    input:checked + .slider:before {
        transform: translateX(24px);
    }
    .slider.round {
        border-radius: 26px;
    }
    .slider.round:before {
        border-radius: 50%;
    }
</style>
@endsection
