@extends('layouts.app')

@section('title', 'Trang chủ')

@section('styles')
<style>
    /* Hero Section - Premium Design */
    .home-hero {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
        padding: 4rem 1.5rem 5rem;
        position: relative;
        overflow: hidden;
        min-height: 500px;
    }
    
    /* Floating Social Icons */
    .floating-icons {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
    }
    
    .floating-icon {
        position: absolute;
        font-size: 2rem;
        color: rgba(255,255,255,0.15);
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(10deg); }
    }
    
    .home-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 600px;
        height: 600px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
        animation: pulse-bg 4s ease-in-out infinite;
    }
    
    .home-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
    }
    
    @keyframes pulse-bg {
        0%, 100% { transform: scale(1); opacity: 0.08; }
        50% { transform: scale(1.1); opacity: 0.12; }
    }
    
    .home-hero .hero-content {
        position: relative;
        z-index: 1;
        text-align: center;
    }
    
    /* Hero Badge */
    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(10px);
        padding: 8px 16px;
        border-radius: 50px;
        margin-bottom: 1.5rem;
        color: white;
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .pulse-dot {
        width: 8px;
        height: 8px;
        background: #22c55e;
        border-radius: 50%;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
        100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }
    
    .home-hero h1 {
        font-size: 3rem;
        font-weight: 800;
        color: white;
        margin-bottom: 1rem;
        line-height: 1.2;
    }
    
    .gradient-text {
        background: linear-gradient(90deg, #ffd700, #ff6b6b, #4ecdc4);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        animation: gradient-shift 3s ease infinite;
        background-size: 200% auto;
    }
    
    @keyframes gradient-shift {
        0% { background-position: 0% center; }
        50% { background-position: 100% center; }
        100% { background-position: 0% center; }
    }
    
    .home-hero .tagline {
        color: rgba(255,255,255,0.95);
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
    }
    
    /* Service Tags */
    .service-tags {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 0.5rem;
        margin-bottom: 2rem;
    }
    
    .service-tag {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(5px);
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }
    
    .service-tag:hover {
        background: rgba(255,255,255,0.25);
        transform: translateY(-2px);
    }
    
    .home-hero .hero-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 2.5rem;
    }
    
    .home-hero .btn-primary {
        background: white;
        color: #764ba2;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s;
        font-size: 1rem;
    }
    
    .btn-glow {
        box-shadow: 0 0 20px rgba(255,255,255,0.4), 0 4px 15px rgba(0,0,0,0.1);
        animation: glow 2s ease-in-out infinite;
    }
    
    @keyframes glow {
        0%, 100% { box-shadow: 0 0 20px rgba(255,255,255,0.4), 0 4px 15px rgba(0,0,0,0.1); }
        50% { box-shadow: 0 0 30px rgba(255,255,255,0.6), 0 4px 20px rgba(0,0,0,0.15); }
    }
    
    .home-hero .btn-primary:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .home-hero .btn-secondary {
        background: transparent;
        color: white;
        border: 2px solid rgba(255,255,255,0.5);
        padding: 0.95rem 1.5rem;
        border-radius: 50px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .home-hero .btn-secondary:hover {
        background: rgba(255,255,255,0.15);
        border-color: white;
    }
    
    /* Hero Stats */
    .hero-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        justify-content: center;
        max-width: 600px;
        margin: 0 auto;
    }
    
    .hero-stats .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        padding: 16px 24px;
        border-radius: 12px;
        text-align: center;
        min-width: 100px;
    }
    
    .hero-stats .stat-icon {
        width: 40px;
        height: 40px;
        background: rgba(255,255,255,0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }
    
    .hero-stats .stat-info {
        text-align: center;
        width: 100%;
    }
    
    .hero-stats .stat-info .number {
        display: block !important;
        text-align: center !important;
        font-size: 1.25rem !important;
        font-weight: 700 !important;
        color: white !important;
        line-height: 1.2 !important;
        margin-bottom: 4px !important;
        /* Reset Bulma overrides */
        background-color: transparent !important;
        border-radius: 0 !important;
        height: auto !important;
        min-width: auto !important;
        padding: 0 !important;
        margin-right: 0 !important;
        vertical-align: baseline !important;
        align-items: unset !important;
        justify-content: unset !important;
    }
    
    .hero-stats .stat-info .label {
        display: block;
        text-align: center;
        font-size: 0.75rem;
        color: white !important;
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
            padding: 2.5rem 1rem 3.5rem;
            min-height: auto;
        }
        
        .floating-icon {
            font-size: 1.25rem;
            opacity: 0.5;
        }
        
        .hero-badge {
            font-size: 0.8rem;
            padding: 6px 12px;
        }
        
        .home-hero h1 {
            font-size: 2rem;
        }
        
        .home-hero .tagline {
            font-size: 1rem;
        }
        
        .service-tags {
            gap: 0.4rem;
        }
        
        .service-tag {
            font-size: 0.75rem;
            padding: 5px 10px;
        }
        
        .home-hero .btn-primary {
            padding: 0.85rem 1.5rem;
            font-size: 0.9rem;
        }
        
        .hero-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
            max-width: 100%;
        }
        
        .hero-stats .stat-item {
            padding: 12px 10px;
            gap: 6px;
        }
        
        .hero-stats .stat-icon {
            width: 32px;
            height: 32px;
            font-size: 0.85rem;
        }
        
        .hero-stats .stat-info .number {
            font-size: 1rem;
        }
        
        .hero-stats .stat-info .label {
            font-size: 0.65rem;
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
    <!-- Floating Social Icons Background -->
    <div class="floating-icons">
        <i class="fab fa-facebook floating-icon" style="top: 10%; left: 10%; animation-delay: 0s;"></i>
        <i class="fab fa-instagram floating-icon" style="top: 20%; right: 15%; animation-delay: 1s;"></i>
        <i class="fab fa-tiktok floating-icon" style="top: 60%; left: 5%; animation-delay: 2s;"></i>
        <i class="fab fa-youtube floating-icon" style="top: 70%; right: 10%; animation-delay: 0.5s;"></i>
        <i class="fab fa-twitter floating-icon" style="top: 40%; left: 20%; animation-delay: 1.5s;"></i>
        <i class="fas fa-heart floating-icon" style="top: 15%; right: 30%; animation-delay: 2.5s;"></i>
        <i class="fas fa-thumbs-up floating-icon" style="top: 80%; left: 25%; animation-delay: 0.8s;"></i>
        <i class="fas fa-eye floating-icon" style="top: 50%; right: 25%; animation-delay: 1.2s;"></i>
    </div>
    
    <div class="container">
        <div class="hero-content">
            <div class="hero-badge">
                <span class="pulse-dot"></span>
                <span>🔥 #1 SMM Panel Việt Nam</span>
            </div>
            
            <h1>Tăng Tương Tác<br><span class="gradient-text">Mạng Xã Hội</span></h1>
            
            <p class="tagline">
                <span class="typewriter">Like • Follow • View • Comment • Share • Subscribe</span>
            </p>
            
            <div class="service-tags">
                <span class="service-tag"><i class="fab fa-facebook"></i> Facebook</span>
                <span class="service-tag"><i class="fab fa-instagram"></i> Instagram</span>
                <span class="service-tag"><i class="fab fa-tiktok"></i> TikTok</span>
                <span class="service-tag"><i class="fab fa-youtube"></i> YouTube</span>
                <span class="service-tag"><i class="fab fa-telegram"></i> Telegram</span>
            </div>
            
            <div class="hero-buttons">
                @auth
                    <a href="{{ route('orders.create') }}" class="btn-primary btn-glow">
                        <i class="fas fa-rocket"></i> Tăng tương tác ngay
                    </a>
                    <a href="{{ route('services.index') }}" class="btn-secondary">
                        <i class="fas fa-list"></i> Xem dịch vụ
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn-primary btn-glow">
                        <i class="fas fa-user-plus"></i> Đăng ký miễn phí
                    </a>
                    <a href="{{ route('login') }}" class="btn-secondary">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập
                    </a>
                @endauth
            </div>
            
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                    <div class="stat-info">
                        <div class="number">{{ number_format($categories->count() * 50) }}+</div>
                        <div class="label">Đơn hàng</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-cogs"></i></div>
                    <div class="stat-info">
                        <div class="number">{{ $featuredServices->count() * 10 }}+</div>
                        <div class="label">Dịch vụ</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <div class="number">1000+</div>
                        <div class="label">Khách hàng</div>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-headset"></i></div>
                    <div class="stat-info">
                        <div class="number">24/7</div>
                        <div class="label">Hỗ trợ</div>
                    </div>
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
