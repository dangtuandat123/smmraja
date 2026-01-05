@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
<!-- Hero Section -->
<section class="hero is-medium hero-gradient">
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title is-1 has-text-white animate-in" style="font-weight: 700;">
                SMM Panel Việt Nam
            </h1>
            <p class="subtitle is-4 has-text-white-bis animate-in" style="animation-delay: 0.1s;">
                Dịch vụ tăng tương tác mạng xã hội<br>
                <strong class="has-text-white">Nhanh chóng • Uy tín • Giá rẻ</strong>
            </p>
            <div class="buttons is-centered animate-in" style="animation-delay: 0.2s;">
                @auth
                    <a href="{{ route('orders.create') }}" class="button is-white is-medium">
                        <span class="icon"><i class="fas fa-cart-plus"></i></span>
                        <span>Đặt hàng ngay</span>
                    </a>
                @else
                    <a href="{{ route('register') }}" class="button is-white is-medium">
                        <span class="icon"><i class="fas fa-user-plus"></i></span>
                        <span>Đăng ký ngay</span>
                    </a>
                    <a href="{{ route('login') }}" class="button is-white is-outlined is-medium">
                        <span>Đăng nhập</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="section">
    <div class="container">
        <div class="columns is-multiline">
            <div class="column is-3">
                <div class="stat-card animate-in">
                    <div class="icon is-primary">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="value">Tự động</div>
                    <div class="label">Xử lý đơn hàng 24/7</div>
                </div>
            </div>
            <div class="column is-3">
                <div class="stat-card animate-in" style="animation-delay: 0.1s;">
                    <div class="icon is-success">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="value">An toàn</div>
                    <div class="label">Bảo mật thông tin</div>
                </div>
            </div>
            <div class="column is-3">
                <div class="stat-card animate-in" style="animation-delay: 0.2s;">
                    <div class="icon is-warning">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="value">Giá rẻ</div>
                    <div class="label">Cạnh tranh nhất thị trường</div>
                </div>
            </div>
            <div class="column is-3">
                <div class="stat-card animate-in" style="animation-delay: 0.3s;">
                    <div class="icon is-info">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="value">Hỗ trợ</div>
                    <div class="label">24/7 qua Telegram</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="section" style="background: white;">
    <div class="container">
        <h2 class="title is-3 has-text-centered mb-6">
            <i class="fas fa-th-large has-text-primary"></i> Danh mục dịch vụ
        </h2>
        
        <div class="columns is-multiline">
            @forelse($categories as $category)
                <div class="column is-3">
                    <a href="{{ route('services.index', ['category' => $category->slug]) }}" class="card" style="display: block;">
                        <div class="card-content has-text-centered">
                            <span class="icon is-large has-text-primary">
                                <i class="fas {{ $category->icon ?? 'fa-folder' }} fa-2x"></i>
                            </span>
                            <h4 class="title is-5 mt-3 mb-2">{{ $category->name }}</h4>
                            <p class="has-text-grey">{{ $category->services_count }} dịch vụ</p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="column">
                    <p class="has-text-centered has-text-grey">Chưa có danh mục nào.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured Services -->
@if($featuredServices->count() > 0)
<section class="section">
    <div class="container">
        <h2 class="title is-3 has-text-centered mb-6">
            <i class="fas fa-star has-text-warning"></i> Dịch vụ nổi bật
        </h2>
        
        <div class="columns is-multiline">
            @foreach($featuredServices as $service)
                <div class="column is-3">
                    <div class="card service-card">
                        <div class="card-content">
                            <span class="tag is-light mb-3">{{ $service->category->name ?? 'N/A' }}</span>
                            <h5 class="title is-6 mb-2" style="line-height: 1.4;">{{ Str::limit($service->name, 50) }}</h5>
                            <p class="has-text-grey is-size-7 mb-3">
                                Min: {{ number_format($service->min) }} | Max: {{ number_format($service->max) }}
                            </p>
                            <div class="level is-mobile">
                                <div class="level-left">
                                    <span class="price-tag">{{ number_format($service->price_vnd, 0, ',', '.') }}đ</span>
                                </div>
                                <div class="level-right">
                                    <span class="is-size-7 has-text-grey">/1000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="has-text-centered mt-5">
            <a href="{{ route('services.index') }}" class="button is-primary is-medium">
                <span class="icon"><i class="fas fa-arrow-right"></i></span>
                <span>Xem tất cả dịch vụ</span>
            </a>
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="section hero-gradient">
    <div class="container has-text-centered">
        <h2 class="title is-3 has-text-white">Bắt đầu ngay hôm nay!</h2>
        <p class="subtitle has-text-white-bis mb-5">
            Đăng ký và nhận ưu đãi cho đơn hàng đầu tiên
        </p>
        @guest
            <a href="{{ route('register') }}" class="button is-white is-medium">
                <span class="icon"><i class="fas fa-rocket"></i></span>
                <span>Tạo tài khoản miễn phí</span>
            </a>
        @else
            <a href="{{ route('orders.create') }}" class="button is-white is-medium">
                <span class="icon"><i class="fas fa-shopping-cart"></i></span>
                <span>Đặt hàng ngay</span>
            </a>
        @endguest
    </div>
</section>
@endsection
