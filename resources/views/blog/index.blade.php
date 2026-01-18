@extends('layouts.app')

@section('title', $meta['title'])
@section('meta_description', $meta['description'])
@section('meta_keywords', $meta['keywords'])

@section('content')
<section class="hero is-primary is-small" style="background: var(--gradient);">
    <div class="hero-body">
        <div class="container">
            <h1 class="title has-text-white">
                <i class="fas fa-blog"></i> Blog
            </h1>
            <p class="subtitle has-text-white">Tin tức & Hướng dẫn SMM</p>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        @if($posts->count() > 0)
        <div class="columns is-multiline">
            @foreach($posts as $post)
            <div class="column is-4">
                <div class="card">
                    <div class="card-image">
                        <figure class="image is-16by9">
                            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}">
                        </figure>
                    </div>
                    <div class="card-content">
                        <h2 class="title is-5">
                            <a href="{{ route('blog.show', $post->slug) }}" class="has-text-dark">
                                {{ $post->title }}
                            </a>
                        </h2>
                        <p class="has-text-grey is-size-7 mb-3">
                            {{ $post->excerpt_text }}
                        </p>
                        <div class="is-flex is-justify-content-space-between is-align-items-center">
                            <span class="is-size-7 has-text-grey">
                                <i class="fas fa-calendar"></i> {{ $post->published_at->format('d/m/Y') }}
                            </span>
                            <span class="is-size-7 has-text-grey">
                                <i class="fas fa-eye"></i> {{ number_format($post->views) }}
                            </span>
                        </div>
                    </div>
                    <footer class="card-footer">
                        <a href="{{ route('blog.show', $post->slug) }}" class="card-footer-item">
                            Đọc tiếp <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </footer>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-5">
            {{ $posts->links() }}
        </div>
        @else
        <div class="notification is-info is-light has-text-centered">
            <p class="is-size-5">Chưa có bài viết nào.</p>
            <p class="mt-2">Hãy quay lại sau để xem những bài viết mới nhất!</p>
        </div>
        @endif
    </div>
</section>
@endsection
