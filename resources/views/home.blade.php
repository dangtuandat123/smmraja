@extends('layouts.app')

@section('title', 'Trang chủ')

@section('styles')
<style>
    /* Hero Section - Modern & Clean */
    .home-hero {
        background: var(--gradient);
        padding: 3rem 1.5rem 4rem;
        position: relative;
        overflow: hidden;
    }
    
    .home-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    
    .home-hero .hero-content {
        position: relative;
        z-index: 1;
    }
    
    .home-hero h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.75rem;
    }
    
    .home-hero .tagline {
        color: rgba(255,255,255,0.9);
        font-size: 1.1rem;
        margin-bottom: 1.5rem;
    }
    
    .home-hero .hero-buttons {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .home-hero .btn-primary {
        background: white;
        color: var(--primary);
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .home-hero .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    }
    
    .home-hero .btn-secondary {
        background: transparent;
        color: white;
        border: 2px solid rgba(255,255,255,0.5);
        padding: 0.7rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .home-hero .btn-secondary:hover {
        background: rgba(255,255,255,0.1);
        border-color: white;
    }
    
    /* Stats Row */
    .stats-row {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
        justify-content: flex-start;
    }
    
    .stat-item {
        text-align: center;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    .stat-item .number {
        font-size: 1.75rem;
        font-weight: 700;
        color: white !important;
    }
    
    .stat-item .label {
        font-size: 0.8rem;
        color: rgba(255,255,255,0.85) !important;
    }
    
    /* Quick Features */
    .quick-features {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        padding: 1.5rem;
        margin: -2rem 1rem 0;
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        position: relative;
        z-index: 10;
    }
    
    .feature-item {
        text-align: center;
        padding: 1rem 0.5rem;
    }
    
    .feature-item .icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-size: 1.25rem;
    }
    
    .feature-item .icon.is-primary { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
    .feature-item .icon.is-success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .feature-item .icon.is-warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .feature-item .icon.is-info { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    
    .feature-item h4 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1f2937 !important;
        margin-bottom: 0.25rem;
    }
    
    .feature-item p {
        font-size: 0.75rem;
        color: #6b7280 !important;
    }
    
    /* Section Headers */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }
    
    .section-header h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1f2937 !important;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .section-header .view-all {
        font-size: 0.85rem;
        color: var(--primary) !important;
        text-decoration: none;
        font-weight: 500;
    }
    
    /* Category Cards */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    
    .category-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        text-decoration: none;
        transition: all 0.3s;
        border: 1px solid #f0f0f0;
    }
    
    .category-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        border-color: var(--primary);
    }
    
    .category-card .icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary) !important;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-size: 1.25rem;
    }
    
    .category-card h4 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1f2937 !important;
        margin-bottom: 0.25rem;
    }
    
    .category-card .count {
        font-size: 0.75rem;
        color: #9ca3af !important;
    }
    
    /* Service Cards */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .service-card-new {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        border: 1px solid #f0f0f0;
        transition: all 0.3s;
    }
    
    .service-card-new:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        border-color: var(--primary);
    }
    
    .service-card-new .category-tag {
        display: inline-block;
        background: #f3f4f6;
        color: #6b7280 !important;
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        margin-bottom: 0.5rem;
    }
    
    .service-card-new h5 {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1f2937 !important;
        margin-bottom: 0.5rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .service-card-new .meta {
        font-size: 0.7rem;
        color: #9ca3af !important;
        margin-bottom: 0.5rem;
    }
    
    .service-card-new .price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .service-card-new .price {
        background: var(--gradient);
        color: white;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
    }
    
    .service-card-new .unit {
        font-size: 0.7rem;
        color: #9ca3af;
    }
    
    /* CTA Banner */
    .cta-banner {
        background: var(--gradient);
        border-radius: 16px;
        padding: 2rem 1.5rem;
        text-align: center;
        color: white;
        margin: 1.5rem;
    }
    
    .cta-banner h3 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .cta-banner p {
        opacity: 0.9;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    
    .cta-banner .btn {
        background: white;
        color: var(--primary);
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    /* Mobile Responsive */
    @media screen and (max-width: 768px) {
        .home-hero {
            padding: 2rem 1rem 3rem;
        }
        
        .home-hero h1 {
            font-size: 1.75rem;
        }
        
        .home-hero .tagline {
            font-size: 0.95rem;
        }
        
        .stats-row {
            justify-content: space-around;
        }
        
        .stat-item .number {
            font-size: 1.25rem;
        }
        
        .quick-features {
            grid-template-columns: repeat(2, 1fr);
            margin: -1.5rem 0.75rem 0;
            padding: 1rem;
            gap: 0.5rem;
        }
        
        .feature-item {
            padding: 0.75rem 0.25rem;
        }
        
        .feature-item .icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        
        .feature-item h4 {
            font-size: 0.8rem;
        }
        
        .categories-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .category-card {
            padding: 1rem;
        }
        
        .category-card .icon {
            width: 40px;
            height: 40px;
            font-size: 1rem;
        }
        
        .services-grid {
            grid-template-columns: 1fr;
        }
        
        .section-header h2 {
            font-size: 1.1rem;
        }
        
        .cta-banner {
            margin: 1rem;
            padding: 1.5rem 1rem;
        }
    }
</style>
@endsection

@section('content')
<!-- Hero Section -->
<section class="home-hero">
    <div class="container">
        <div class="hero-content">
            <h1>🚀 SMM Panel Việt Nam</h1>
            <p class="tagline">
                Tăng tương tác mạng xã hội<br>
                <strong>Nhanh • Tự động • Giá tốt nhất</strong>
            </p>
            
            <div class="hero-buttons">
                @auth
                    <a href="{{ route('orders.create') }}" class="btn-primary">
                        <i class="fas fa-cart-plus"></i> Đặt hàng ngay
                    </a>
                    <a href="{{ route('services.index') }}" class="btn-secondary">
                        Xem dịch vụ
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary">
                        <i class="fas fa-user-plus"></i> Đăng ký ngay
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary">
                        Đăng nhập
                    </a>
                @endauth
            </div>
            
            <div class="hero-stats" style="display: flex; gap: 2rem; margin-top: 2rem;">
                <div style="text-align: center;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: white;">{{ $categories->count() }}+</div>
                    <div style="font-size: 0.8rem; color: rgba(255,255,255,0.85);">Danh mục</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: white;">{{ $featuredServices->count() * 10 }}+</div>
                    <div style="font-size: 0.8rem; color: rgba(255,255,255,0.85);">Dịch vụ</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.75rem; font-weight: 700; color: white;">24/7</div>
                    <div style="font-size: 0.8rem; color: rgba(255,255,255,0.85);">Hỗ trợ</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Features -->
<div class="container">
    <div class="quick-features">
        <div class="feature-item">
            <div class="icon is-primary"><i class="fas fa-bolt"></i></div>
            <h4>Tự động</h4>
            <p>Xử lý 24/7</p>
        </div>
        <div class="feature-item">
            <div class="icon is-success"><i class="fas fa-shield-alt"></i></div>
            <h4>An toàn</h4>
            <p>Bảo mật cao</p>
        </div>
        <div class="feature-item">
            <div class="icon is-warning"><i class="fas fa-tags"></i></div>
            <h4>Giá rẻ</h4>
            <p>Tốt nhất TT</p>
        </div>
        <div class="feature-item">
            <div class="icon is-info"><i class="fas fa-headset"></i></div>
            <h4>Hỗ trợ</h4>
            <p>Telegram</p>
        </div>
    </div>
</div>

<!-- Categories Section -->
<section class="section" style="padding-top: 3rem;">
    <div class="container">
        <div class="section-header">
            <h2><i class="fas fa-th-large has-text-primary"></i> Danh mục</h2>
            <a href="{{ route('services.index') }}" class="view-all">Xem tất cả →</a>
        </div>
        
        <div class="categories-grid">
            @forelse($categories->take(8) as $category)
                <a href="{{ route('services.index', ['category' => $category->slug]) }}" class="category-card">
                    <div class="icon">
                        <i class="fas {{ $category->icon ?? 'fa-folder' }}"></i>
                    </div>
                    <h4>{{ $category->name }}</h4>
                    <span class="count">{{ $category->services_count }} dịch vụ</span>
                </a>
            @empty
                <p class="has-text-grey">Chưa có danh mục</p>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured Services -->
@if($featuredServices->count() > 0)
<section class="section" style="background: #f9fafb; padding-top: 2rem; padding-bottom: 2rem;">
    <div class="container">
        <div class="section-header">
            <h2><i class="fas fa-star has-text-warning"></i> Dịch vụ nổi bật</h2>
            <a href="{{ route('services.index') }}" class="view-all">Xem tất cả →</a>
        </div>
        
        <div class="services-grid">
            @foreach($featuredServices->take(6) as $service)
                @auth
                <a href="{{ route('orders.create', ['service' => $service->id]) }}" class="service-card-new" style="text-decoration: none; cursor: pointer;">
                @else
                <a href="{{ route('services.index', ['category' => $service->category->slug ?? '']) }}" class="service-card-new" style="text-decoration: none; cursor: pointer;">
                @endauth
                    <span class="category-tag">{{ $service->category->name ?? 'N/A' }}</span>
                    <h5>{{ Str::limit($service->name, 50) }}</h5>
                    <p class="meta">Min: {{ number_format($service->min) }} • Max: {{ number_format($service->max) }}</p>
                    <div class="price-row">
                        <span class="price">{{ number_format($service->price_vnd, 0, ',', '.') }}đ</span>
                        <span class="unit">/1000</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Banner -->
<div class="cta-banner">
    <h3>🎉 Bắt đầu ngay hôm nay!</h3>
    <p>Đăng ký miễn phí và trải nghiệm dịch vụ tốt nhất</p>
    @guest
        <a href="{{ route('register') }}" class="btn">
            <i class="fas fa-rocket"></i> Tạo tài khoản
        </a>
    @else
        <a href="{{ route('orders.create') }}" class="btn">
            <i class="fas fa-shopping-cart"></i> Đặt hàng ngay
        </a>
    @endguest
</div>
@endsection
