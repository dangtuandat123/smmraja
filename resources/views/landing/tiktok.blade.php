@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])
@section('meta_keywords', $meta['keywords'])

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Service",
    "name": "Dịch vụ tăng View TikTok",
    "description": "{{ $meta['description'] }}",
    "provider": {
        "@type": "Organization",
        "name": "TikTos"
    },
    "areaServed": "VN",
    "serviceType": "Social Media Marketing"
}
</script>
@endsection

@section('content')
<section class="hero is-primary is-medium" style="background: linear-gradient(135deg, #000000 0%, #25f4ee 50%, #fe2c55 100%);">
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title is-1 has-text-white">
                <i class="fab fa-tiktok"></i> Tăng View TikTok
            </h1>
            <p class="subtitle is-4 has-text-white">
                Dịch vụ tăng view TikTok giá rẻ, video lên xu hướng nhanh chóng
            </p>
            <div class="buttons is-centered mt-5">
                <a href="{{ route('register') }}" class="button is-white is-large">
                    <span class="icon"><i class="fas fa-rocket"></i></span>
                    <span>Bắt đầu ngay</span>
                </a>
                <a href="{{ route('services.index') }}" class="button is-outlined is-white is-large">
                    <span class="icon"><i class="fas fa-list"></i></span>
                    <span>Xem dịch vụ</span>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <!-- Features -->
        <div class="columns is-multiline mb-6">
            <div class="column is-3">
                <div class="card has-text-centered p-4">
                    <span class="icon is-large has-text-danger"><i class="fas fa-fire fa-2x"></i></span>
                    <h3 class="title is-5 mt-3">Lên xu hướng</h3>
                    <p class="has-text-grey">Giúp video lên For You</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="card has-text-centered p-4">
                    <span class="icon is-large has-text-success"><i class="fas fa-eye fa-2x"></i></span>
                    <h3 class="title is-5 mt-3">View thật</h3>
                    <p class="has-text-grey">100% view thật, không bot</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="card has-text-centered p-4">
                    <span class="icon is-large has-text-warning"><i class="fas fa-bolt fa-2x"></i></span>
                    <h3 class="title is-5 mt-3">Siêu nhanh</h3>
                    <p class="has-text-grey">Xử lý tức thì</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="card has-text-centered p-4">
                    <span class="icon is-large has-text-info"><i class="fas fa-coins fa-2x"></i></span>
                    <h3 class="title is-5 mt-3">Giá rẻ</h3>
                    <p class="has-text-grey">Chỉ từ 1đ/view</p>
                </div>
            </div>
        </div>

        <!-- Services -->
        <h2 class="title is-3 has-text-centered mb-5">
            <span class="icon"><i class="fab fa-tiktok"></i></span>
            Dịch vụ TikTok phổ biến
        </h2>
        
        @if($services->count() > 0)
        <div class="columns is-multiline">
            @foreach($services as $service)
            <div class="column is-4">
                <div class="card">
                    <div class="card-content">
                        <h3 class="title is-6">{{ $service->name }}</h3>
                        <p class="has-text-grey is-size-7 mb-3">{{ Str::limit($service->description, 100) }}</p>
                        <div class="is-flex is-justify-content-space-between is-align-items-center">
                            <span class="tag is-danger is-medium">{{ $service->formatted_price }}</span>
                            <a href="{{ route('orders.create', ['service' => $service->id]) }}" class="button is-small is-danger is-outlined">
                                Đặt hàng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="notification is-info is-light has-text-centered">
            <p>Đang cập nhật dịch vụ. Vui lòng <a href="{{ route('services.index') }}">xem tất cả dịch vụ</a>.</p>
        </div>
        @endif

        <div class="has-text-centered mt-6">
            <a href="{{ route('services.index') }}" class="button is-danger is-large">
                <span class="icon"><i class="fas fa-arrow-right"></i></span>
                <span>Xem tất cả dịch vụ TikTok</span>
            </a>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section has-background-light">
    <div class="container">
        <h2 class="title is-3 has-text-centered mb-5">Câu hỏi thường gặp</h2>
        
        <div class="columns is-centered">
            <div class="column is-8">
                <div class="card mb-3">
                    <div class="card-content">
                        <h3 class="title is-5">Tăng view TikTok có lên xu hướng không?</h3>
                        <p>View nhiều giúp video có cơ hội lên For You Page, tăng khả năng viral.</p>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-content">
                        <h3 class="title is-5">View có giảm sau khi mua không?</h3>
                        <p>View TikTok là view thật, không bị giảm sau khi hoàn thành đơn hàng.</p>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-content">
                        <h3 class="title is-5">Có dịch vụ tăng like, follow TikTok không?</h3>
                        <p>Có. Chúng tôi cung cấp đầy đủ dịch vụ view, like, follow, comment TikTok.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
