@extends('layouts.app')

@section('title', 'Dịch vụ')

@section('content')
<!-- Hero Banner -->
<section class="hero is-medium services-hero">
    <div class="hero-floating-shapes">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
    </div>
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title is-2 has-text-white hero-title">
                <i class="fas fa-rocket"></i> Chọn Dịch Vụ
            </h1>
            <p class="subtitle is-5 has-text-white hero-subtitle">
                {{ $services->total() }}+ dịch vụ SMM chất lượng cao, giá rẻ nhất thị trường
            </p>
            
            <!-- Quick Search -->
            <div class="columns is-centered mt-5">
                <div class="column is-8-tablet is-6-desktop">
                    <div class="field has-addons hero-search">
                        <div class="control is-expanded has-icons-left">
                            <input class="input is-medium" type="text" id="heroSearchInput" 
                                   placeholder="Tìm kiếm dịch vụ..." value="{{ $search }}">
                            <span class="icon is-left">
                                <i class="fas fa-search"></i>
                            </span>
                        </div>
                        <div class="control">
                            <button class="button is-warning is-medium" id="heroSearchBtn">
                                <span class="icon"><i class="fas fa-arrow-right"></i></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section services-section">
    <div class="container">
        <!-- Mobile Category Dropdown (Custom) -->
        <div class="is-hidden-tablet mb-4">
            <label class="label is-small has-text-grey">Danh mục</label>
            <div class="custom-dropdown" id="mobileCategoryDropdown">
                <button class="dropdown-trigger" type="button">
                    <span class="dropdown-value">
                        @if($categorySlug)
                            @php $currentCat = $categories->firstWhere('slug', $categorySlug); @endphp
                            <i class="fas {{ $currentCat->icon ?? 'fa-folder' }} mr-2"></i>
                            {{ $currentCat->name ?? 'Tất cả' }}
                        @else
                            <i class="fas fa-th-large mr-2"></i>
                            Tất cả
                        @endif
                    </span>
                    <span class="dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('services.index', request()->only(['sort', 'refill', 'cancel'])) }}" 
                       class="dropdown-item {{ !$categorySlug ? 'is-active' : '' }}">
                        <i class="fas fa-th-large mr-2"></i>
                        Tất cả
                        <span class="item-count">{{ $services->total() }}</span>
                    </a>
                    @foreach($categories as $category)
                    <a href="{{ route('services.index', array_merge(['category' => $category->slug], request()->only(['sort', 'refill', 'cancel']))) }}" 
                       class="dropdown-item {{ $categorySlug == $category->slug ? 'is-active' : '' }}">
                        <i class="fas {{ $category->icon ?? 'fa-folder' }} mr-2"></i>
                        {{ $category->name }}
                        <span class="item-count">{{ $category->services_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Desktop Category Pills -->
        <div class="category-pills-wrapper mb-5 is-hidden-mobile">
            <div class="category-pills">
                <a href="{{ route('services.index', request()->only(['sort', 'refill', 'cancel'])) }}" 
                   class="category-pill {{ !$categorySlug ? 'is-active' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>Tất cả</span>
                </a>
                @foreach($categories as $category)
                <a href="{{ route('services.index', array_merge(['category' => $category->slug], request()->only(['sort', 'refill', 'cancel']))) }}" 
                   class="category-pill {{ $categorySlug == $category->slug ? 'is-active' : '' }}">
                    <i class="fas {{ $category->icon ?? 'fa-folder' }}"></i>
                    <span>{{ $category->name }}</span>
                    <span class="pill-count">{{ $category->services_count }}</span>
                </a>
                @endforeach
            </div>
        </div>
        
        <!-- Filter Bar -->
        <div class="filter-bar mb-5">
            <div class="columns is-vcentered is-mobile">
                <div class="column">
                    <div class="filter-options">
                        <label class="filter-checkbox">
                            <input type="checkbox" class="filter-refill-checkbox" {{ $refill === '1' ? 'checked' : '' }}>
                            <span class="filter-label">
                                <i class="fas fa-shield-alt has-text-success"></i> Bảo hành
                            </span>
                        </label>
                        <label class="filter-checkbox">
                            <input type="checkbox" class="filter-cancel-checkbox" {{ $cancel === '1' ? 'checked' : '' }}>
                            <span class="filter-label">
                                <i class="fas fa-undo has-text-warning"></i> Có thể hủy
                            </span>
                        </label>
                    </div>
                </div>
                <div class="column is-narrow">
                    @php
                        $sortOptions = [
                            'default' => 'Mặc định',
                            'price_asc' => 'Giá thấp → cao',
                            'price_desc' => 'Giá cao → thấp',
                            'name_asc' => 'Tên A-Z',
                            'newest' => 'Mới nhất',
                        ];
                        $currentSortLabel = $sortOptions[$sort] ?? 'Mặc định';
                    @endphp
                    <div class="custom-dropdown custom-dropdown-sm" id="sortDropdown">
                        <button class="dropdown-trigger" type="button">
                            <span class="dropdown-value">
                                <i class="fas fa-sort-amount-down mr-1"></i>
                                {{ $currentSortLabel }}
                            </span>
                            <span class="dropdown-arrow"><i class="fas fa-chevron-down"></i></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            @foreach($sortOptions as $value => $label)
                            <a href="{{ route('services.index', array_merge(request()->except(['sort', 'page']), $value !== 'default' ? ['sort' => $value] : [])) }}" 
                               class="dropdown-item {{ $sort === $value || ($value === 'default' && !$sort) ? 'is-active' : '' }}">
                                {{ $label }}
                            </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services Grid -->
        <div id="servicesContainer">
            @if($services->count() > 0)
                @php $currentCategoryId = null; @endphp
                <div class="columns is-multiline">
                    @foreach($services as $service)
                    @if(!$categorySlug && $service->category_id !== $currentCategoryId)
                        @php $currentCategoryId = $service->category_id; @endphp
                        </div>
                        <!-- Category Header -->
                        <div class="category-header">
                            <div class="category-header-icon" style="background: {{ $service->category->icon_color ?? '#667eea' }}">
                                <i class="{{ $service->category->icon ?? 'fas fa-folder' }}"></i>
                            </div>
                            <h2 class="category-header-title">{{ $service->category->name ?? 'Khác' }}</h2>
                            <span class="category-header-count">{{ $service->category->services_count ?? '' }} dịch vụ</span>
                        </div>
                        <div class="columns is-multiline">
                    @endif
                    <div class="column is-4-desktop is-6-tablet is-12-mobile">
                        @auth
                        <a href="{{ route('orders.create', ['service' => $service->id]) }}" class="service-card-link">
                        @else
                        <a href="{{ route('login') }}" class="service-card-link">
                        @endauth
                            <div class="service-card">
                                <div class="service-card-header">
                                    <div class="service-icon" style="background: linear-gradient(135deg, {{ $service->icon_color ?? '#667eea' }} 0%, #764ba2 100%);">
                                        <i class="{{ $service->icon ?? 'fas fa-star' }}"></i>
                                    </div>
                                    <div class="service-id">#{{ $service->id }}</div>
                                </div>
                                
                                <div class="service-card-body">
                                    <h3 class="service-name">{{ $service->name }}</h3>
                                    
                                    <div class="service-badges">
                                        @if($service->refill)
                                        <span class="badge badge-success">
                                            ✓ Bảo hành
                                        </span>
                                        @endif
                                        @if($service->cancel)
                                        <span class="badge badge-warning">
                                            ✓ Hủy được
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="service-card-footer">
                                    <div class="service-price">
                                        <span class="price-value">{{ number_format($service->price_vnd, 0, ',', '.') }}đ</span>
                                        <span class="price-unit">/ 1000 lượt</span>
                                    </div>
                                    <span class="order-btn">
                                        <i class="fas fa-cart-plus"></i>
                                        <span>@auth Đặt hàng @else Đăng nhập @endauth</span>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($services->hasPages())
                <div class="pagination-container mt-5">
                    {{ $services->links() }}
                </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3>Không tìm thấy dịch vụ</h3>
                    <p>Thử tìm kiếm với từ khóa khác hoặc chọn danh mục khác</p>
                    <a href="{{ route('services.index') }}" class="button is-primary is-rounded mt-3">
                        <span class="icon"><i class="fas fa-redo"></i></span>
                        <span>Xem tất cả dịch vụ</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
/* Hero Section */
.services-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    position: relative;
    overflow: hidden;
}

.hero-floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    pointer-events: none;
}

.floating-shape {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 100px;
    height: 100px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.shape-2 {
    width: 150px;
    height: 150px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
}

.shape-3 {
    width: 80px;
    height: 80px;
    bottom: 20%;
    left: 50%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(10deg); }
}

.hero-title {
    text-shadow: 0 2px 10px rgba(0,0,0,0.2);
}

.hero-subtitle {
    opacity: 0.9;
}

.hero-search .input {
    border-radius: 50px 0 0 50px;
    border: none;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    padding-left: 3rem;
}

.hero-search .button {
    border-radius: 0 50px 50px 0;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

/* Services Section */
.services-section {
    background: linear-gradient(180deg, #f8f9ff 0%, #ffffff 100%);
    padding-top: 2rem;
}

/* Custom Dropdown */
.custom-dropdown {
    position: relative;
    width: 100%;
}

.custom-dropdown .dropdown-trigger {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 500;
    color: #334155;
    transition: all 0.2s ease;
}

.custom-dropdown .dropdown-trigger:hover {
    border-color: #667eea;
}

.custom-dropdown.is-open .dropdown-trigger {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
}

.custom-dropdown .dropdown-arrow {
    transition: transform 0.2s ease;
    color: #94a3b8;
}

.custom-dropdown.is-open .dropdown-arrow {
    transform: rotate(180deg);
}

.custom-dropdown .dropdown-menu {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    right: 0;
    background: white;
    border-radius: 10px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    z-index: 100;
    max-height: 300px;
    overflow-y: auto;
    display: none;
}

.custom-dropdown.is-open .dropdown-menu {
    display: block;
    animation: dropdownSlide 0.2s ease;
}

@keyframes dropdownSlide {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.custom-dropdown .dropdown-item {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    color: #475569;
    transition: all 0.15s ease;
    border-bottom: 1px solid #f1f5f9;
}

.custom-dropdown .dropdown-item:last-child {
    border-bottom: none;
}

.custom-dropdown .dropdown-item:hover {
    background: #f8fafc;
    color: #667eea;
}

.custom-dropdown .dropdown-item.is-active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.custom-dropdown .dropdown-item .item-count {
    margin-left: auto;
    background: rgba(0,0,0,0.08);
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.custom-dropdown .dropdown-item.is-active .item-count {
    background: rgba(255,255,255,0.2);
}

/* Small Dropdown Variant */
.custom-dropdown.custom-dropdown-sm .dropdown-trigger {
    padding: 0.5rem 0.75rem;
    font-size: 0.8rem;
    border-radius: 8px;
}

.custom-dropdown.custom-dropdown-sm .dropdown-menu {
    min-width: 160px;
}

.custom-dropdown .dropdown-menu-right {
    left: auto;
    right: 0;
}

/* Category Pills */
.category-pills-wrapper {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    margin: 0 -1.5rem;
    padding: 0 1.5rem;
}

.category-pills-wrapper::-webkit-scrollbar {
    display: none;
}

.category-pills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    padding-bottom: 0.5rem;
}

.category-pill {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.25rem;
    background: white;
    border-radius: 50px;
    color: #4a5568;
    font-weight: 500;
    white-space: nowrap;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.category-pill:hover {
    color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
}

.category-pill.is-active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
}

.category-pill .pill-count {
    background: rgba(0,0,0,0.1);
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    font-size: 0.75rem;
}

.category-pill.is-active .pill-count {
    background: rgba(255,255,255,0.2);
}

/* Category Header - khi hiển thị tất cả */
.category-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem 0;
    margin: 1.5rem 0 1rem;
    border-bottom: 2px solid #e5e7eb;
}

.category-header:first-of-type {
    margin-top: 0;
}

.category-header-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.category-header-title {
    font-size: 1.35rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    flex: 1;
}

.category-header-count {
    font-size: 0.85rem;
    color: #6b7280;
    background: #f3f4f6;
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
}

/* Filter Bar */
.filter-bar {
    background: white;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
}

.filter-options {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
}

.filter-checkbox {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.filter-checkbox input {
    width: 18px;
    height: 18px;
    accent-color: #667eea;
}

.filter-label {
    font-size: 0.9rem;
    color: #4a5568;
}

.sort-select select {
    border-color: #e2e8f0;
    font-size: 0.9rem;
}

/* Service Cards */
.service-card-link {
    display: block;
    text-decoration: none;
    color: inherit;
    height: 100%;
}

.service-card-link:hover {
    text-decoration: none;
}

.service-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    cursor: pointer;
}

.service-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(102, 126, 234, 0.2);
}

.service-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 0.75rem;
    padding-bottom: 0;
}

.service-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.9rem;
}

.service-id {
    background: #f1f5f9;
    color: #64748b;
    padding: 0.15rem 0.5rem;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
}

.service-card-body {
    padding: 0.75rem;
    flex-grow: 1;
}

.service-name {
    font-size: 0.85rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 0.25rem;
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.service-desc {
    font-size: 0.7rem;
    color: #64748b;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.service-badges {
    display: flex;
    gap: 0.35rem;
    flex-wrap: wrap;
    margin-bottom: 0.5rem;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.6rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.badge-success {
    background: #dcfce7;
    color: #16a34a;
}

.badge-warning {
    background: #fef3c7;
    color: #d97706;
}

.service-stats {
    display: flex;
    gap: 1rem;
}

.stat {
    display: flex;
    flex-direction: column;
}

.stat-label {
    font-size: 0.6rem;
    color: #94a3b8;
    text-transform: uppercase;
}

.stat-value {
    font-size: 0.75rem;
    font-weight: 600;
    color: #334155;
}

.service-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
    border-top: 1px solid #e2e8f0;
}

.service-price {
    display: flex;
    flex-direction: column;
    gap: 0;
}

.price-value {
    font-size: 1.25rem;
    font-weight: 900;
    color: #e53e3e;
    text-shadow: 0 0 10px rgba(229, 62, 62, 0.3);
    letter-spacing: -0.5px;
}

.price-unit {
    font-size: 0.6rem;
    color: #718096;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.order-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.3rem;
    padding: 0.4rem 0.8rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.75rem;
    transition: all 0.3s ease;
}

.order-btn:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    color: white;
}

.order-btn-light {
    background: #e2e8f0;
    color: #475569;
}

.order-btn-light:hover {
    background: #cbd5e1;
    color: #334155;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: #f1f5f9;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #94a3b8;
}

.empty-state h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #334155;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #64748b;
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
    .services-hero .hero-body {
        padding: 2rem 1rem;
    }
    
    .hero-title {
        font-size: 1.5rem !important;
    }
    
    .hero-subtitle {
        font-size: 0.9rem !important;
    }
    
    .filter-bar {
        padding: 0.75rem 1rem;
    }
    
    .filter-options {
        gap: 0.75rem;
    }
    
    .service-card {
        margin-bottom: 0;
    }
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hero Search
    const heroSearchInput = document.getElementById('heroSearchInput');
    const heroSearchBtn = document.getElementById('heroSearchBtn');
    
    function performSearch() {
        const searchValue = heroSearchInput.value.trim();
        const url = new URL(window.location.href);
        
        if (searchValue) {
            url.searchParams.set('search', searchValue);
        } else {
            url.searchParams.delete('search');
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
    
    heroSearchBtn?.addEventListener('click', performSearch);
    heroSearchInput?.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            performSearch();
        }
    });
    
    // Sort Select
    document.getElementById('sortSelect')?.addEventListener('change', function() {
        const url = new URL(window.location.href);
        if (this.value && this.value !== 'default') {
            url.searchParams.set('sort', this.value);
        } else {
            url.searchParams.delete('sort');
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });
    
    // Filter Checkboxes
    function handleFilterChange() {
        const url = new URL(window.location.href);
        
        const refillChecked = document.querySelector('.filter-refill-checkbox:checked');
        const cancelChecked = document.querySelector('.filter-cancel-checkbox:checked');
        
        if (refillChecked) {
            url.searchParams.set('refill', '1');
        } else {
            url.searchParams.delete('refill');
        }
        
        if (cancelChecked) {
            url.searchParams.set('cancel', '1');
        } else {
            url.searchParams.delete('cancel');
        }
        
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }
    
    document.querySelectorAll('.filter-refill-checkbox, .filter-cancel-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', handleFilterChange);
    });
    
    // Custom Dropdowns (Vanilla JS) - Handle all dropdowns
    document.querySelectorAll('.custom-dropdown').forEach(dropdown => {
        const trigger = dropdown.querySelector('.dropdown-trigger');
        
        if (trigger) {
            // Toggle dropdown on click
            trigger.addEventListener('click', function(e) {
                e.stopPropagation();
                // Close all other dropdowns first
                document.querySelectorAll('.custom-dropdown.is-open').forEach(d => {
                    if (d !== dropdown) d.classList.remove('is-open');
                });
                dropdown.classList.toggle('is-open');
            });
        }
    });
    
    // Close all dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dropdown')) {
            document.querySelectorAll('.custom-dropdown.is-open').forEach(d => {
                d.classList.remove('is-open');
            });
        }
    });
    
    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.custom-dropdown.is-open').forEach(d => {
                d.classList.remove('is-open');
            });
        }
    });
});
</script>
@endsection
