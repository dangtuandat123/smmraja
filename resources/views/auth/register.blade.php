@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-5">
                <div class="card animate-in">
                    <div class="card-content">
                        <div class="has-text-centered mb-5">
                            <span class="icon is-large has-text-primary">
                                <i class="fas fa-user-plus fa-3x"></i>
                            </span>
                            <h2 class="title is-3 mt-3">Đăng ký</h2>
                            <p class="has-text-grey">Tạo tài khoản mới miễn phí</p>
                        </div>
                        
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            
                            <div class="field">
                                <label class="label">Họ và tên</label>
                                <div class="control has-icons-left">
                                    <input class="input is-medium @error('name') is-danger @enderror" 
                                           type="text" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Nguyễn Văn A" 
                                           required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-user"></i>
                                    </span>
                                </div>
                                @error('name')
                                    <p class="help is-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="field">
                                <label class="label">Email</label>
                                <div class="control has-icons-left">
                                    <input class="input is-medium @error('email') is-danger @enderror" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="email@example.com" 
                                           required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                @error('email')
                                    <p class="help is-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="field">
                                <label class="label">Số điện thoại (tùy chọn)</label>
                                <div class="control has-icons-left">
                                    <input class="input is-medium" 
                                           type="text" 
                                           name="phone" 
                                           value="{{ old('phone') }}" 
                                           placeholder="0901234567">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="field">
                                <label class="label">Mật khẩu</label>
                                <div class="control has-icons-left">
                                    <input class="input is-medium @error('password') is-danger @enderror" 
                                           type="password" 
                                           name="password" 
                                           placeholder="Ít nhất 6 ký tự" 
                                           required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <p class="help is-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="field">
                                <label class="label">Xác nhận mật khẩu</label>
                                <div class="control has-icons-left">
                                    <input class="input is-medium" 
                                           type="password" 
                                           name="password_confirmation" 
                                           placeholder="Nhập lại mật khẩu" 
                                           required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <!-- reCAPTCHA -->
                            <div class="field">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                @error('g-recaptcha-response')
                                    <p class="help is-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="field">
                                <button type="submit" class="button is-primary is-medium is-fullwidth">
                                    <span class="icon"><i class="fas fa-user-plus"></i></span>
                                    <span>Đăng ký</span>
                                </button>
                            </div>
                        </form>
                        
                        <hr>
                        
                        <p class="has-text-centered">
                            Đã có tài khoản? 
                            <a href="{{ route('login') }}" class="has-text-primary has-text-weight-semibold">
                                Đăng nhập
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endsection
