@extends('layouts.app')

@section('title', 'Liên hệ')

@section('content')
<section class="section">
    <div class="container">
        <div class="columns is-centered">
            <div class="column is-8">
                <h1 class="title is-3 has-text-centered">
                    <i class="fas fa-envelope has-text-primary"></i> Liên hệ với chúng tôi
                </h1>
                <p class="subtitle has-text-centered has-text-grey">
                    Có câu hỏi? Hãy gửi tin nhắn cho chúng tôi
                </p>
                
                <div class="columns mt-5">
                    <div class="column is-6">
                        <div class="card">
                            <div class="card-content">
                                <h4 class="title is-5 mb-4">Gửi tin nhắn</h4>
                                
                                <form method="POST" action="{{ route('contact.send') }}">
                                    @csrf
                                    
                                    <div class="field">
                                        <label class="label">Họ tên</label>
                                        <div class="control has-icons-left">
                                            <input class="input" type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-user"></i>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="field">
                                        <label class="label">Email</label>
                                        <div class="control has-icons-left">
                                            <input class="input" type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                                            <span class="icon is-small is-left">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="field">
                                        <label class="label">Tiêu đề</label>
                                        <div class="control">
                                            <input class="input" type="text" name="subject" value="{{ old('subject') }}" required>
                                        </div>
                                    </div>
                                    
                                    <div class="field">
                                        <label class="label">Nội dung</label>
                                        <div class="control">
                                            <textarea class="textarea" name="message" rows="5" required>{{ old('message') }}</textarea>
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="button is-primary is-fullwidth">
                                        <span class="icon"><i class="fas fa-paper-plane"></i></span>
                                        <span>Gửi tin nhắn</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="column is-6">
                        <div class="card">
                            <div class="card-content">
                                <h4 class="title is-5 mb-4">Thông tin liên hệ</h4>
                                
                                @php
                                    $contactEmail = \App\Models\Setting::get('contact_email');
                                    $contactPhone = \App\Models\Setting::get('contact_phone');
                                    $telegramUrl = \App\Models\Setting::get('telegram_url');
                                    $facebookUrl = \App\Models\Setting::get('facebook_url');
                                    $zaloUrl = \App\Models\Setting::get('zalo_url');
                                @endphp
                                
                                @if($contactEmail)
                                <div class="media mb-4">
                                    <div class="media-left">
                                        <span class="icon is-large has-text-primary">
                                            <i class="fas fa-envelope fa-2x"></i>
                                        </span>
                                    </div>
                                    <div class="media-content">
                                        <p class="title is-6">Email</p>
                                        <p class="subtitle is-6">
                                            <a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                                        </p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($contactPhone)
                                <div class="media mb-4">
                                    <div class="media-left">
                                        <span class="icon is-large has-text-success">
                                            <i class="fas fa-phone fa-2x"></i>
                                        </span>
                                    </div>
                                    <div class="media-content">
                                        <p class="title is-6">Điện thoại</p>
                                        <p class="subtitle is-6">
                                            <a href="tel:{{ $contactPhone }}">{{ $contactPhone }}</a>
                                        </p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($telegramUrl)
                                <div class="media mb-4">
                                    <div class="media-left">
                                        <span class="icon is-large has-text-info">
                                            <i class="fab fa-telegram fa-2x"></i>
                                        </span>
                                    </div>
                                    <div class="media-content">
                                        <p class="title is-6">Telegram</p>
                                        <p class="subtitle is-6">
                                            <a href="{{ $telegramUrl }}" target="_blank">{{ str_replace(['https://t.me/', 'https://telegram.me/'], '@', $telegramUrl) }}</a>
                                        </p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($facebookUrl)
                                <div class="media mb-4">
                                    <div class="media-left">
                                        <span class="icon is-large has-text-link">
                                            <i class="fab fa-facebook fa-2x"></i>
                                        </span>
                                    </div>
                                    <div class="media-content">
                                        <p class="title is-6">Facebook</p>
                                        <p class="subtitle is-6">
                                            <a href="{{ $facebookUrl }}" target="_blank">Facebook Page</a>
                                        </p>
                                    </div>
                                </div>
                                @endif
                                
                                @if($zaloUrl)
                                <div class="media mb-4">
                                    <div class="media-left">
                                        <span class="icon is-large has-text-primary">
                                            <i class="fas fa-comment-dots fa-2x"></i>
                                        </span>
                                    </div>
                                    <div class="media-content">
                                        <p class="title is-6">Zalo</p>
                                        <p class="subtitle is-6">
                                            <a href="{{ $zaloUrl }}" target="_blank">Chat Zalo</a>
                                        </p>
                                    </div>
                                </div>
                                @endif
                                
                                <hr>
                                
                                <div class="notification is-info is-light">
                                    <p><i class="fas fa-clock mr-2"></i> <strong>Giờ làm việc:</strong></p>
                                    <p>Thứ 2 - Chủ nhật: 8:00 - 22:00</p>
                                    <p class="is-size-7 has-text-grey mt-2">Phản hồi trong vòng 24 giờ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
