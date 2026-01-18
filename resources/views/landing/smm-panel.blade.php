@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])
@section('meta_keywords', $meta['keywords'])

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "TikTos - SMM Panel",
    "description": "{{ $meta['description'] }}",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('og-image.png') }}",
    "sameAs": [],
    "hasOfferCatalog": {
        "@type": "OfferCatalog",
        "name": "Dịch vụ SMM",
        "itemListElement": [
            @foreach($categories->take(5) as $cat)
            {
                "@type": "Offer",
                "itemOffered": {
                    "@type": "Service",
                    "name": "{{ $cat->name }}"
                }
            }@if(!$loop->last),@endif
            @endforeach
        ]
    }
}
</script>
@endsection

@section('content')
<section class="hero is-large" style="background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);">
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title is-1 has-text-white mb-4">
                <i class="fas fa-bolt"></i> SMM Panel Việt Nam #1
            </h1>
            <p class="subtitle is-3 has-text-white mb-5">
                Dịch vụ tăng tương tác mạng xã hội chất lượng cao, giá rẻ nhất
            </p>
            <p class="has-text-white is-size-5 mb-5">
                <i class="fab fa-facebook mx-2"></i>
                <i class="fab fa-instagram mx-2"></i>
                <i class="fab fa-tiktok mx-2"></i>
                <i class="fab fa-youtube mx-2"></i>
                <i class="fab fa-twitter mx-2"></i>
            </p>
            <div class="buttons is-centered">
                <a href="{{ route('register') }}" class="button is-white is-large">
                    <span class="icon"><i class="fas fa-user-plus"></i></span>
                    <span>Đăng ký miễn phí</span>
                </a>
                <a href="{{ route('services.index') }}" class="button is-outlined is-white is-large">
                    <span class="icon"><i class="fas fa-list"></i></span>
                    <span>Xem {{ $totalServices }}+ dịch vụ</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="section">
    <div class="container">
        <div class="columns is-multiline">
            <div class="column is-3">
                <div class="stat-card">
                    <div class="icon is-primary"><i class="fas fa-cogs"></i></div>
                    <div class="value">{{ $totalServices }}+</div>
                    <div class="label">Dịch vụ</div>
                </div>
            </div>
            <div class="column is-3">
                <div class="stat-card">
                    <div class="icon is-success"><i class="fas fa-users"></i></div>
                    <div class="value">10K+</div>
                    <div class="label">Khách hàng</div>
                </div>
            </div>
            <div class="column is-3">
                <div class="stat-card">
                    <div class="icon is-warning"><i class="fas fa-shopping-cart"></i></div>
                    <div class="value">100K+</div>
                    <div class="label">Đơn hàng</div>
                </div>
            </div>
            <div class="column is-3">
                <div class="stat-card">
                    <div class="icon is-info"><i class="fas fa-headset"></i></div>
                    <div class="value">24/7</div>
                    <div class="label">Hỗ trợ</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories -->
<section class="section has-background-light">
    <div class="container">
        <h2 class="title is-2 has-text-centered mb-6">Danh mục dịch vụ</h2>
        
        <div class="columns is-multiline">
            @foreach($categories as $category)
            <div class="column is-4">
                <a href="{{ route('services.index', ['category' => $category->slug]) }}" class="card" style="display: block;">
                    <div class="card-content has-text-centered">
                        <span class="icon is-large" style="color: {{ $category->icon_color ?? '#6366f1' }}">
                            <i class="{{ $category->icon ?? 'fas fa-folder' }} fa-2x"></i>
                        </span>
                        <h3 class="title is-5 mt-3">{{ $category->name }}</h3>
                        <p class="has-text-grey">{{ $category->services_count }} dịch vụ</p>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section">
    <div class="container">
        <h2 class="title is-2 has-text-centered mb-6">Tại sao chọn TikTos?</h2>
        
        <div class="columns is-multiline">
            <div class="column is-4">
                <div class="card p-5">
                    <span class="icon is-large has-text-primary"><i class="fas fa-rocket fa-2x"></i></span>
                    <h3 class="title is-4 mt-3">Tốc độ siêu nhanh</h3>
                    <p class="has-text-grey">Đơn hàng được xử lý tự động, giao hàng trong vài phút.</p>
                </div>
            </div>
            <div class="column is-4">
                <div class="card p-5">
                    <span class="icon is-large has-text-success"><i class="fas fa-shield-alt fa-2x"></i></span>
                    <h3 class="title is-4 mt-3">An toàn & Bảo mật</h3>
                    <p class="has-text-grey">Không cần mật khẩu, không ảnh hưởng tài khoản của bạn.</p>
                </div>
            </div>
            <div class="column is-4">
                <div class="card p-5">
                    <span class="icon is-large has-text-warning"><i class="fas fa-tag fa-2x"></i></span>
                    <h3 class="title is-4 mt-3">Giá cạnh tranh</h3>
                    <p class="has-text-grey">Giá rẻ nhất thị trường, chất lượng không đổi.</p>
                </div>
            </div>
            <div class="column is-4">
                <div class="card p-5">
                    <span class="icon is-large has-text-info"><i class="fas fa-redo fa-2x"></i></span>
                    <h3 class="title is-4 mt-3">Bảo hành</h3>
                    <p class="has-text-grey">Bảo hành theo từng gói dịch vụ, bù miễn phí nếu drop.</p>
                </div>
            </div>
            <div class="column is-4">
                <div class="card p-5">
                    <span class="icon is-large has-text-danger"><i class="fas fa-headset fa-2x"></i></span>
                    <h3 class="title is-4 mt-3">Hỗ trợ 24/7</h3>
                    <p class="has-text-grey">Đội ngũ hỗ trợ luôn sẵn sàng giúp đỡ bạn.</p>
                </div>
            </div>
            <div class="column is-4">
                <div class="card p-5">
                    <span class="icon is-large has-text-link"><i class="fas fa-credit-card fa-2x"></i></span>
                    <h3 class="title is-4 mt-3">Thanh toán dễ dàng</h3>
                    <p class="has-text-grey">Nạp tiền nhanh chóng qua ngân hàng, ví điện tử.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Landing Pages Links -->
<section class="section has-background-primary" style="background: var(--gradient);">
    <div class="container has-text-centered">
        <h2 class="title is-2 has-text-white mb-5">Dịch vụ nổi bật</h2>
        <div class="buttons is-centered">
            <a href="{{ route('landing.instagram') }}" class="button is-white is-medium">
                <span class="icon"><i class="fab fa-instagram"></i></span>
                <span>Tăng Follow Instagram</span>
            </a>
            <a href="{{ route('landing.facebook') }}" class="button is-white is-medium">
                <span class="icon"><i class="fab fa-facebook"></i></span>
                <span>Mua Like Facebook</span>
            </a>
            <a href="{{ route('landing.tiktok') }}" class="button is-white is-medium">
                <span class="icon"><i class="fab fa-tiktok"></i></span>
                <span>Tăng View TikTok</span>
            </a>
            <a href="{{ route('landing.youtube') }}" class="button is-white is-medium">
                <span class="icon"><i class="fab fa-youtube"></i></span>
                <span>Tăng View YouTube</span>
            </a>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="section">
    <div class="container has-text-centered">
        <h2 class="title is-2">Bắt đầu ngay hôm nay!</h2>
        <p class="subtitle is-4 has-text-grey mb-5">Đăng ký miễn phí và nhận ngay ưu đãi</p>
        <a href="{{ route('register') }}" class="button is-primary is-large">
            <span class="icon"><i class="fas fa-user-plus"></i></span>
            <span>Đăng ký ngay</span>
        </a>
    </div>
</section>
@endsection
