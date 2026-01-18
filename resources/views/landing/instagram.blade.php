@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])
@section('meta_keywords', $meta['keywords'])

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Service",
    "name": "Dịch vụ tăng Follow Instagram",
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
<section class="hero is-primary is-medium" style="background: linear-gradient(135deg, #833ab4 0%, #fd1d1d 50%, #fcb045 100%);">
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title is-1 has-text-white">
                <i class="fab fa-instagram"></i> Tăng Follow Instagram
            </h1>
            <p class="subtitle is-4 has-text-white">
                Dịch vụ tăng follow Instagram giá rẻ, uy tín #1 Việt Nam
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
                    <span class="icon is-large has-text-success"><i class="fas fa-bolt fa-2x"></i></span>
                    <h3 class="title is-5 mt-3">Siêu tốc độ</h3>
                    <p class="has-text-grey">Tăng follow trong vài phút</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="card has-text-centered p-4">
                    <span class="icon is-large has-text-primary"><i class="fas fa-shield-alt fa-2x"></i></span>
                    <h3 class="title is-5 mt-3">Bảo hành</h3>
                    <p class="has-text-grey">Bảo hành vĩnh viễn</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="card has-text-centered p-4">
                    <span class="icon is-large has-text-warning"><i class="fas fa-tag fa-2x"></i></span>
                    <h3 class="title is-5 mt-3">Giá rẻ nhất</h3>
                    <p class="has-text-grey">Giá cạnh tranh nhất thị trường</p>
                </div>
            </div>
            <div class="column is-3">
                <div class="card has-text-centered p-4">
                    <span class="icon is-large has-text-info"><i class="fas fa-headset fa-2x"></i></span>
                    <h3 class="title is-5 mt-3">Hỗ trợ 24/7</h3>
                    <p class="has-text-grey">Luôn sẵn sàng hỗ trợ</p>
                </div>
            </div>
        </div>

        <!-- Services -->
        <h2 class="title is-3 has-text-centered mb-5">
            <span class="icon has-text-primary"><i class="fab fa-instagram"></i></span>
            Dịch vụ Instagram phổ biến
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
                            <span class="tag is-primary is-medium">{{ $service->formatted_price }}</span>
                            <a href="{{ route('orders.create', ['service' => $service->id]) }}" class="button is-small is-primary is-outlined">
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

        <!-- CTA -->
        <div class="has-text-centered mt-6">
            <a href="{{ route('services.index') }}" class="button is-primary is-large">
                <span class="icon"><i class="fas fa-arrow-right"></i></span>
                <span>Xem tất cả dịch vụ Instagram</span>
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
                        <h3 class="title is-5">Tăng follow Instagram có an toàn không?</h3>
                        <p>Hoàn toàn an toàn. Chúng tôi sử dụng phương pháp tự nhiên, không vi phạm chính sách Instagram.</p>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-content">
                        <h3 class="title is-5">Bao lâu thì nhận được follow?</h3>
                        <p>Thông thường từ 5-30 phút sau khi đặt hàng, tùy thuộc vào số lượng.</p>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-content">
                        <h3 class="title is-5">Follow có bị tụt không?</h3>
                        <p>Chúng tôi bảo hành theo từng gói dịch vụ. Nếu tụt sẽ được bù miễn phí.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
