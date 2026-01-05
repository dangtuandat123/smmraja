@extends('layouts.admin')

@section('title', 'Cài đặt hệ thống')

@section('content')
<h2 class="title is-4">Cài đặt hệ thống</h2>

<div class="tabs">
    <ul>
        <li class="is-active" data-tab="general"><a>Chung</a></li>
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
                
                <div class="field">
                    <label class="label">Tỷ giá USD/VND</label>
                    <input class="input" type="number" name="exchange_rate" value="{{ $settings['general']['exchange_rate'] ?? 25000 }}">
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
