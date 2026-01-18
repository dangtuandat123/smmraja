@extends('layouts.app')

@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt_text)
@section('meta_keywords', $post->meta_keywords ?? '')

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BlogPosting",
    "headline": "{{ $post->title }}",
    "description": "{{ $post->meta_description ?? $post->excerpt_text }}",
    "image": "{{ $post->thumbnail_url }}",
    "datePublished": "{{ $post->published_at->toIso8601String() }}",
    "dateModified": "{{ $post->updated_at->toIso8601String() }}",
    "author": {
        "@type": "Person",
        "name": "{{ $post->author->name ?? 'TikTos' }}"
    },
    "publisher": {
        "@type": "Organization",
        "name": "TikTos",
        "logo": {
            "@type": "ImageObject",
            "url": "{{ asset('og-image.png') }}"
        }
    },
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ url()->current() }}"
    }
}
</script>
@endsection

@section('content')
<section class="section">
    <div class="container">
        <div class="columns">
            <!-- Main Content -->
            <div class="column is-8">
                <nav class="breadcrumb" aria-label="breadcrumbs">
                    <ul>
                        <li><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li class="is-active"><a href="#" aria-current="page">{{ Str::limit($post->title, 30) }}</a></li>
                    </ul>
                </nav>

                <article class="content">
                    <h1 class="title is-2">{{ $post->title }}</h1>
                    
                    <div class="is-flex is-align-items-center mb-4">
                        <span class="mr-4">
                            <i class="fas fa-user"></i> {{ $post->author->name ?? 'Admin' }}
                        </span>
                        <span class="mr-4">
                            <i class="fas fa-calendar"></i> {{ $post->published_at->format('d/m/Y') }}
                        </span>
                        <span>
                            <i class="fas fa-eye"></i> {{ number_format($post->views) }} lượt xem
                        </span>
                    </div>

                    @if($post->thumbnail)
                    <figure class="image mb-5">
                        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" style="border-radius: 12px;">
                    </figure>
                    @endif

                    <div class="blog-content">
                        {!! $post->content !!}
                    </div>
                </article>

                <!-- Share buttons -->
                <div class="box mt-5">
                    <p class="has-text-weight-bold mb-3">Chia sẻ bài viết:</p>
                    <div class="buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="button is-info">
                            <span class="icon"><i class="fab fa-facebook"></i></span>
                            <span>Facebook</span>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" class="button is-link">
                            <span class="icon"><i class="fab fa-twitter"></i></span>
                            <span>Twitter</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="column is-4">
                <div class="box">
                    <h3 class="title is-5 mb-4">Bài viết liên quan</h3>
                    @forelse($relatedPosts as $related)
                    <div class="media">
                        <div class="media-left">
                            <figure class="image is-64x64">
                                <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}" style="border-radius: 8px; object-fit: cover;">
                            </figure>
                        </div>
                        <div class="media-content">
                            <a href="{{ route('blog.show', $related->slug) }}" class="has-text-dark">
                                <strong>{{ Str::limit($related->title, 50) }}</strong>
                            </a>
                            <p class="is-size-7 has-text-grey">{{ $related->published_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="has-text-grey">Chưa có bài viết liên quan.</p>
                    @endforelse
                </div>

                <!-- CTA Box -->
                <div class="box has-background-primary" style="background: var(--gradient) !important;">
                    <h3 class="title is-5 has-text-white">Bắt đầu ngay!</h3>
                    <p class="has-text-white mb-4">Đăng ký miễn phí và sử dụng dịch vụ SMM chất lượng cao.</p>
                    <a href="{{ route('register') }}" class="button is-white is-fullwidth">
                        <span class="icon"><i class="fas fa-user-plus"></i></span>
                        <span>Đăng ký ngay</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
