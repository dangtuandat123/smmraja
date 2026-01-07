@extends('layouts.app')

@section('title', 'Dịch vụ')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="title is-3">
            <i class="fas fa-list-ul has-text-primary"></i> Danh sách dịch vụ
        </h1>
        
        <div class="columns">
            <!-- Sidebar -->
            <div class="column is-3">
                <div class="card">
                    <div class="card-header">
                        <p class="card-header-title">Danh mục</p>
                    </div>
                    <div class="card-content" style="padding: 0.5rem;">
                        <aside class="menu">
                            <ul class="menu-list">
                                <li>
                                    <a href="{{ route('services.index') }}" class="{{ !$categorySlug ? 'is-active' : '' }}">
                                        <i class="fas fa-th-large mr-2"></i> Tất cả
                                    </a>
                                </li>
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('services.index', ['category' => $category->slug]) }}" 
                                           class="{{ $categorySlug == $category->slug ? 'is-active' : '' }}">
                                            <i class="fas {{ $category->icon ?? 'fa-folder' }} mr-2"></i>
                                            {{ $category->name }}
                                            <span class="tag is-light is-small ml-2">{{ $category->services_count }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </aside>
                    </div>
                </div>
                
                <!-- Search -->
                <div class="card mt-4">
                    <div class="card-content">
                        <form action="{{ route('services.index') }}" method="GET">
                            @if($categorySlug)
                                <input type="hidden" name="category" value="{{ $categorySlug }}">
                            @endif
                            <div class="field">
                                <div class="control has-icons-left">
                                    <input class="input" type="text" name="search" placeholder="Tìm dịch vụ..." value="{{ $search }}">
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                            </div>
                            <button type="submit" class="button is-primary is-fullwidth">
                                <i class="fas fa-search mr-2"></i> Tìm kiếm
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Services List -->
            <div class="column is-9">
                @if($services->count() > 0)
                    <div class="table-container">
                        <table class="table is-fullwidth is-hoverable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Dịch vụ</th>
                                    <th>Giá/1000</th>
                                    <th>Min</th>
                                    <th>Max</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($services as $service)
                                    <tr>
                                        <td>{{ $service->id }}</td>
                                        <td>
                                            <span class="tag is-light is-small mb-1">{{ $service->category->name }}</span><br>
                                            <strong>{{ $service->name }}</strong>
                                            @if($service->description)
                                                <p class="is-size-7 has-text-grey">{{ Str::limit($service->description, 80) }}</p>
                                            @endif
                                            <div class="mt-1">
                                                @if($service->refill)
                                                    <span class="tag is-success is-small">Bảo hành</span>
                                                @endif
                                                @if($service->cancel)
                                                    <span class="tag is-info is-small">Có thể hủy</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="price-tag">
                                                {{ number_format($service->price_vnd, 0, ',', '.') }}đ
                                            </span>
                                        </td>
                                        <td>{{ number_format($service->min) }}</td>
                                        <td>{{ number_format($service->max) }}</td>
                                        <td>
                                            @auth
                                                <a href="{{ route('orders.create', ['service' => $service->id]) }}" class="button is-primary is-small">
                                                    <i class="fas fa-cart-plus"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('login') }}" class="button is-light is-small">
                                                    <i class="fas fa-sign-in-alt"></i>
                                                </a>
                                            @endauth
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $services->links() }}
                @else
                    <div class="card">
                        <div class="card-content has-text-centered py-6">
                            <span class="icon is-large has-text-grey-light">
                                <i class="fas fa-search fa-3x"></i>
                            </span>
                            <p class="has-text-grey mt-3">Không tìm thấy dịch vụ nào</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
