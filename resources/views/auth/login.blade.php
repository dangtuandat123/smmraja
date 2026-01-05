@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-5">
                <div class="card animate-in">
                    <div class="card-content">
                        <div class="has-text-centered mb-5">
                            <span class="icon is-large has-text-primary">
                                <i class="fas fa-user-circle fa-3x"></i>
                            </span>
                            <h2 class="title is-3 mt-3">Đăng nhập</h2>
                            <p class="has-text-grey">Chào mừng bạn quay lại!</p>
                        </div>
                        
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="field">
                                <label class="label">Email</label>
                                <div class="control has-icons-left">
                                    <input class="input is-medium @error('email') is-danger @enderror" 
                                           type="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           placeholder="email@example.com" 
                                           required 
                                           autofocus>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                </div>
                                @error('email')
                                    <p class="help is-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="field">
                                <label class="label">Mật khẩu</label>
                                <div class="control has-icons-left">
                                    <input class="input is-medium @error('password') is-danger @enderror" 
                                           type="password" 
                                           name="password" 
                                           placeholder="••••••••" 
                                           required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="field">
                                <label class="checkbox">
                                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>
                            
                            <div class="field">
                                <button type="submit" class="button is-primary is-medium is-fullwidth">
                                    <span class="icon"><i class="fas fa-sign-in-alt"></i></span>
                                    <span>Đăng nhập</span>
                                </button>
                            </div>
                        </form>
                        
                        <hr>
                        
                        <p class="has-text-centered">
                            Chưa có tài khoản? 
                            <a href="{{ route('register') }}" class="has-text-primary has-text-weight-semibold">
                                Đăng ký ngay
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
