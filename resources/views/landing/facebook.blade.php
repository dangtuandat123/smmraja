@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])
@section('meta_keywords', $meta['keywords'])

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Service",
    "name": "Dịch vụ mua Like Facebook",
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
<section class="hero is-primary is-medium" style="background: linear-gradient(135deg, #1877f2 0%, #42a5f5 100%);">
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title is-1 has-text-white">
                <i class="fab fa-facebook"></i> Mua Like Facebook
            </h1>
            <p class="subtitle is-4 has-text-white">
                Dịch vụ tăng like Facebook giá rẻ, uy tín #1 Việt Nam
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
                    <span class="icon is-large has-text-info"><i class="fas fa-thumbs-up fa-2x"></i></span>
                    <h3 class="title is-5 mt-3">Like thật</h3>
                    <p class="has-text-grey">100% từ tài khoản thật</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="card has-text-centered p-4">
                    <span class="icon is-large has-text-success"><i class="fas fa-users fa-2x"></i></span>
                    <h3 class="title is-5 mt-3">Người Việt</h3>
                    <p class="has-text-grey">Like từ người dùng Việt Nam</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="card has-text-centered p-4">
                    <span class="icon is-large has-text-warning"><i class="fas fa-clock fa-2x"></i></span>
                    <h3 class="title is-5 mt-3">Nhanh chóng</h3>
                    <p class="has-text-grey">Xử lý trong 5-30 phút</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="card has-text-centered p-4">
                    <span class="icon is-large has-text-danger"><i class="fas fa-heart fa-2x"></i></span>
                    <h3 class="title is-5 mt-3">Đa dạng</h3>
                    <p class="has-text-grey">Like bài viết, fanpage, ảnh</p>
                </div>
            </div>
        </div>

        <!-- Services -->
        <h2 class="title is-3 has-text-centered mb-5">
            <span class="icon has-text-info"><i class="fab fa-facebook"></i></span>
            Dịch vụ Facebook phổ biến
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
                            <span class="tag is-info is-medium">{{ $service->formatted_price }}</span>
                            <a href="{{ route('orders.create', ['service' => $service->id]) }}" class="button is-small is-info is-outlined">
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
            <a href="{{ route('services.index') }}" class="button is-info is-large">
                <span class="icon"><i class="fas fa-arrow-right"></i></span>
                <span>Xem tất cả dịch vụ Facebook</span>
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
                        <h3 class="title is-5">Mua like Facebook có bị khóa tài khoản không?</h3>
                        <p>Không. Chúng tôi sử dụng phương pháp an toàn, không ảnh hưởng đến tài khoản của bạn.</p>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-content">
                        <h3 class="title is-5">Like có bị mất không?</h3>
                        <p>Tùy gói dịch vụ sẽ có bảo hành. Like bảo hành sẽ được bù nếu bị tụt.</p>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-content">
                        <h3 class="title is-5">Có thể like fanpage được không?</h3>
                        <p>Có. Chúng tôi có dịch vụ like fanpage, like bài viết, like ảnh đầy đủ.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
