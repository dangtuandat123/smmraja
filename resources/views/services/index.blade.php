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
            <div class="column is-3-desktop is-12-mobile">
                <!-- Mobile: Dropdown Category -->
                <div class="is-hidden-desktop mb-4">
                    <div class="field">
                        <div class="control has-icons-left is-expanded">
                            <div class="select is-fullwidth is-medium">
                                <select id="mobileCategorySelect">
                                    <option value="" data-category="" {{ !$categorySlug ? 'selected' : '' }}>
                                        📋 Tất cả danh mục
                                    </option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}" data-category="{{ $category->slug }}" 
                                                {{ $categorySlug == $category->slug ? 'selected' : '' }}>
                                            {{ $category->name }} ({{ $category->services_count }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="icon is-left">
                                <i class="fas fa-layer-group"></i>
                            </span>
                        </div>
                    </div>
                    
                    <!-- Mobile Search & Sort -->
                    <form action="{{ route('services.index') }}" method="GET" class="mb-2" id="mobileSearchForm">
                        @if($categorySlug)
                            <input type="hidden" name="category" value="{{ $categorySlug }}">
                        @endif
                        <input type="hidden" name="sort" id="mobileSortInput" value="{{ $sort }}">
                        <div class="field has-addons mb-2">
                            <div class="control is-expanded">
                                <input class="input" type="text" name="search" 
                                       placeholder="Tìm dịch vụ..." value="{{ $search }}">
                            </div>
                            <div class="control">
                                <button type="submit" class="button is-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="field">
                            <div id="mobileSortDropdown" class="custom-dropdown"></div>
                        </div>
                    </form>
                </div>
                
                <!-- Desktop: Sidebar Card -->
                <div class="is-hidden-mobile">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <p class="card-header-title has-text-white">
                                <i class="fas fa-layer-group mr-2"></i> Danh mục
                            </p>
                        </div>
                        <div class="card-content" style="padding: 0;">
                            <aside class="menu">
                                <ul class="menu-list" id="categoryMenu">
                                    <li>
                                        <a href="{{ route('services.index') }}" 
                                           class="category-link is-flex is-justify-content-space-between is-align-items-center {{ !$categorySlug ? 'is-active' : '' }}"
                                           data-category=""
                                           style="padding: 0.75rem 1rem; border-radius: 0;">
                                            <span>
                                                <i class="fas fa-th-large mr-2"></i> Tất cả
                                            </span>
                                        </a>
                                    </li>
                                    @foreach($categories as $category)
                                        <li>
                                            <a href="{{ route('services.index', ['category' => $category->slug]) }}" 
                                               class="category-link is-flex is-justify-content-space-between is-align-items-center {{ $categorySlug == $category->slug ? 'is-active' : '' }}"
                                               data-category="{{ $category->slug }}"
                                               style="padding: 0.75rem 1rem; border-radius: 0;">
                                                <span>
                                                    <i class="fas {{ $category->icon ?? 'fa-folder' }} mr-2"></i>
                                                    {{ $category->name }}
                                                </span>
                                                <span class="tag is-rounded is-small {{ $categorySlug == $category->slug ? 'is-white' : 'is-primary is-light' }}">
                                                    {{ $category->services_count }}
                                                </span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </aside>
                        </div>
                    </div>
                    
                    <!-- Desktop Search & Sort -->
                    <div class="card mt-4">
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <p class="card-header-title has-text-white">
                                <i class="fas fa-search mr-2"></i> Tìm kiếm
                            </p>
                        </div>
                        <div class="card-content" style="padding: 1rem;">
                            <form action="{{ route('services.index') }}" method="GET" id="desktopSearchForm">
                                @if($categorySlug)
                                    <input type="hidden" name="category" value="{{ $categorySlug }}">
                                @endif
                                <input type="hidden" name="sort" id="desktopSortInput" value="{{ $sort }}">
                                <div class="field">
                                    <div class="control">
                                        <input class="input" type="text" name="search" 
                                               placeholder="Nhập tên dịch vụ..." value="{{ $search }}">
                                    </div>
                                </div>
                                <div class="field">
                                    <label class="label is-small has-text-grey">Sắp xếp theo</label>
                                    <div id="desktopSortDropdown" class="custom-dropdown"></div>
                                </div>
                                <button type="submit" class="button is-primary is-fullwidth">
                                    <i class="fas fa-search mr-2"></i> Tìm kiếm
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Services List -->
            <div class="column is-9-desktop is-12-mobile" id="servicesContainer">
                @if($services->count() > 0)
                    <!-- Desktop Table View -->
                    <div class="is-hidden-mobile">
                        <div class="card">
                            <div class="table-container">
                                <table class="table is-fullwidth is-hoverable is-striped">
                                    <thead>
                                        <tr style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <th class="has-text-white" style="width: 60px;">ID</th>
                                            <th class="has-text-white">Dịch vụ</th>
                                            <th class="has-text-white has-text-right" style="width: 120px;">Giá/1000</th>
                                            <th class="has-text-white has-text-centered" style="width: 80px;">Min</th>
                                            <th class="has-text-white has-text-centered" style="width: 80px;">Max</th>
                                            <th class="has-text-white" style="width: 60px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($services as $service)
                                            <tr>
                                                <td class="has-text-grey">{{ $service->id }}</td>
                                                <td>
                                                    <div class="is-flex is-align-items-start">
                                                        <div>
                                                            <p class="has-text-weight-semibold mb-1">{{ $service->name }}</p>
                                                            @if($service->description)
                                                                <p class="is-size-7 has-text-grey mb-1">{{ Str::limit($service->description, 100) }}</p>
                                                            @endif
                                                            <div class="tags mb-0">
                                                                @if($service->refill)
                                                                    <span class="tag is-success is-small">
                                                                        <i class="fas fa-shield-alt mr-1"></i> Bảo hành
                                                                    </span>
                                                                @endif
                                                                @if($service->cancel)
                                                                    <span class="tag is-info is-small">
                                                                        <i class="fas fa-times-circle mr-1"></i> Có thể hủy
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="has-text-right">
                                                    <span class="has-text-weight-bold has-text-primary is-size-5">
                                                        {{ number_format($service->price_vnd, 0, ',', '.') }}
                                                    </span>
                                                    <small class="has-text-grey">đ</small>
                                                </td>
                                                <td class="has-text-centered">{{ number_format($service->min) }}</td>
                                                <td class="has-text-centered">{{ number_format($service->max) }}</td>
                                                <td>
                                                    @auth
                                                        <a href="{{ route('orders.create', ['service' => $service->id]) }}" 
                                                           class="button is-primary is-small is-rounded"
                                                           title="Đặt hàng">
                                                            <i class="fas fa-cart-plus"></i>
                                                        </a>
                                                    @else
                                                        <a href="{{ route('login') }}" 
                                                           class="button is-light is-small is-rounded"
                                                           title="Đăng nhập để đặt hàng">
                                                            <i class="fas fa-sign-in-alt"></i>
                                                        </a>
                                                    @endauth
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Card View -->
                    <div class="is-hidden-desktop">
                        @foreach($services as $service)
                            <div class="card mb-3">
                                <div class="card-content" style="padding: 1rem;">
                                    <div class="is-flex is-justify-content-space-between is-align-items-start mb-2">
                                        <div class="is-flex-grow-1">
                                            <p class="has-text-weight-bold">{{ $service->name }}</p>
                                            <p class="is-size-7 has-text-grey">ID: {{ $service->id }}</p>
                                        </div>
                                        <div class="has-text-right">
                                            <p class="has-text-primary has-text-weight-bold is-size-5">
                                                {{ number_format($service->price_vnd, 0, ',', '.') }}<small class="has-text-grey">đ</small>
                                            </p>
                                            <p class="is-size-7 has-text-grey">/1000</p>
                                        </div>
                                    </div>
                                    
                                    @if($service->description)
                                        <p class="is-size-7 has-text-grey mb-2">{{ Str::limit($service->description, 80) }}</p>
                                    @endif
                                    
                                    <div class="is-flex is-justify-content-space-between is-align-items-center">
                                        <div class="tags mb-0">
                                            <span class="tag is-light is-small">Min: {{ number_format($service->min) }}</span>
                                            <span class="tag is-light is-small">Max: {{ number_format($service->max) }}</span>
                                            @if($service->refill)
                                                <span class="tag is-success is-small">Bảo hành</span>
                                            @endif
                                            @if($service->cancel)
                                                <span class="tag is-info is-small">Hủy được</span>
                                            @endif
                                        </div>
                                        @auth
                                            <a href="{{ route('orders.create', ['service' => $service->id]) }}" 
                                               class="button is-primary is-small">
                                                <i class="fas fa-cart-plus mr-1"></i> Đặt
                                            </a>
                                        @else
                                            <a href="{{ route('login') }}" class="button is-light is-small">
                                                <i class="fas fa-sign-in-alt mr-1"></i> Đăng nhập
                                            </a>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-4">
                        {{ $services->links() }}
                    </div>
                @else
                    <div class="card">
                        <div class="card-content has-text-centered py-6">
                            <span class="icon is-large has-text-grey-light">
                                <i class="fas fa-search fa-3x"></i>
                            </span>
                            <p class="has-text-grey mt-3 is-size-5">Không tìm thấy dịch vụ nào</p>
                            <p class="has-text-grey-light is-size-7">Thử tìm kiếm với từ khóa khác</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
    .custom-dropdown {
        position: relative;
        width: 100%;
    }
    .custom-dropdown .dropdown-trigger {
        width: 100%;
    }
    .custom-dropdown .dropdown-btn {
        width: 100%;
        justify-content: space-between;
        text-align: left;
        background: white;
        border: 1px solid #dbdbdb;
        border-radius: 4px;
        padding: 0.5rem 0.75rem;
        cursor: pointer;
        display: flex;
        align-items: center;
    }
    .custom-dropdown .dropdown-btn:hover {
        border-color: #b5b5b5;
    }
    .custom-dropdown .dropdown-btn.is-active {
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.2);
    }
    .custom-dropdown .dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 100;
        display: none;
        margin-top: 4px;
        background: white;
        border-radius: 6px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    .custom-dropdown.is-active .dropdown-menu {
        display: block;
    }
    .custom-dropdown .dropdown-item {
        display: flex;
        align-items: center;
        padding: 0.6rem 1rem;
        cursor: pointer;
        transition: all 0.15s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
    }
    .custom-dropdown .dropdown-item:hover {
        background: #f5f5f5;
    }
    .custom-dropdown .dropdown-item.is-active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .custom-dropdown .dropdown-item .icon {
        margin-right: 0.5rem;
        width: 1.2rem;
    }
</style>
@endsection

@section('scripts')
<script>
    const sortOptions = [
        { value: 'default', label: 'Mặc định', icon: 'fa-list' },
        { value: 'price_asc', label: 'Giá thấp → cao', icon: 'fa-sort-amount-up' },
        { value: 'price_desc', label: 'Giá cao → thấp', icon: 'fa-sort-amount-down' },
        { value: 'name_asc', label: 'Tên A-Z', icon: 'fa-sort-alpha-down' },
        { value: 'newest', label: 'Mới nhất', icon: 'fa-clock' }
    ];
    
    const currentSort = '{{ $sort }}';
    
    class CustomDropdown {
        constructor(element, options, currentValue, onSelect) {
            this.element = element;
            this.options = options;
            this.currentValue = currentValue;
            this.onSelect = onSelect;
            this.init();
        }
        
        init() {
            const current = this.options.find(o => o.value === this.currentValue) || this.options[0];
            
            this.element.innerHTML = `
                <div class="dropdown-trigger">
                    <button type="button" class="dropdown-btn">
                        <span class="selected-text">
                            <i class="fas ${current.icon} mr-2"></i>${current.label}
                        </span>
                        <span class="icon is-small">
                            <i class="fas fa-chevron-down"></i>
                        </span>
                    </button>
                </div>
                <div class="dropdown-menu">
                    ${this.options.map(opt => `
                        <button type="button" class="dropdown-item ${opt.value === this.currentValue ? 'is-active' : ''}" data-value="${opt.value}">
                            <span class="icon"><i class="fas ${opt.icon}"></i></span>
                            ${opt.label}
                        </button>
                    `).join('')}
                </div>
            `;
            
            this.btn = this.element.querySelector('.dropdown-btn');
            this.menu = this.element.querySelector('.dropdown-menu');
            this.selectedText = this.element.querySelector('.selected-text');
            
            this.btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.toggle();
            });
            
            this.element.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    const value = item.dataset.value;
                    const opt = this.options.find(o => o.value === value);
                    this.select(value, opt);
                });
            });
            
            document.addEventListener('click', (e) => {
                if (!this.element.contains(e.target)) {
                    this.close();
                }
            });
        }
        
        toggle() {
            this.element.classList.toggle('is-active');
            this.btn.classList.toggle('is-active');
        }
        
        close() {
            this.element.classList.remove('is-active');
            this.btn.classList.remove('is-active');
        }
        
        select(value, opt) {
            this.currentValue = value;
            this.selectedText.innerHTML = `<i class="fas ${opt.icon} mr-2"></i>${opt.label}`;
            
            this.element.querySelectorAll('.dropdown-item').forEach(item => {
                item.classList.toggle('is-active', item.dataset.value === value);
            });
            
            this.close();
            
            if (this.onSelect) {
                this.onSelect(value);
            }
        }
    }
    
    // Desktop dropdown
    const desktopSortContainer = document.getElementById('desktopSortDropdown');
    if (desktopSortContainer) {
        new CustomDropdown(desktopSortContainer, sortOptions, currentSort, (value) => {
            document.getElementById('desktopSortInput').value = value;
            loadServicesAjax(value);
        });
    }
    
    // Mobile dropdown
    const mobileSortContainer = document.getElementById('mobileSortDropdown');
    if (mobileSortContainer) {
        new CustomDropdown(mobileSortContainer, sortOptions, currentSort, (value) => {
            document.getElementById('mobileSortInput').value = value;
            loadServicesAjax(value);
        });
    }
    
    // AJAX load services
    function loadServicesAjax(sortValue) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortValue);
        
        // Update URL without refresh
        window.history.pushState({}, '', url);
        
        // Show loading
        const container = document.getElementById('servicesContainer');
        if (container) {
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';
        }
        
        // Fetch with AJAX
        fetch(url.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse HTML and extract services content
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            // Update entire services container
            const newContent = doc.getElementById('servicesContainer');
            if (newContent && container) {
                container.innerHTML = newContent.innerHTML;
            }
            
            // Remove loading
            if (container) {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
        })
        .catch(error => {
            console.error('Error loading services:', error);
            // Fallback: reload page
            window.location.href = url.toString();
        });
    }
    
    // AJAX load by category
    function loadCategoryAjax(categorySlug) {
        const url = new URL(window.location.origin + '/services');
        if (categorySlug) {
            url.searchParams.set('category', categorySlug);
        }
        // Keep current sort
        const currentSortValue = document.getElementById('desktopSortInput')?.value || 
                                  document.getElementById('mobileSortInput')?.value || 'default';
        if (currentSortValue && currentSortValue !== 'default') {
            url.searchParams.set('sort', currentSortValue);
        }
        
        // Update URL
        window.history.pushState({}, '', url);
        
        // Show loading
        const container = document.getElementById('servicesContainer');
        if (container) {
            container.style.opacity = '0.5';
            container.style.pointerEvents = 'none';
        }
        
        // Update active state in desktop menu
        document.querySelectorAll('.category-link').forEach(link => {
            const linkCategory = link.dataset.category || '';
            if (linkCategory === categorySlug) {
                link.classList.add('is-active');
                const tag = link.querySelector('.tag');
                if (tag) {
                    tag.classList.remove('is-primary', 'is-light');
                    tag.classList.add('is-white');
                }
            } else {
                link.classList.remove('is-active');
                const tag = link.querySelector('.tag');
                if (tag) {
                    tag.classList.remove('is-white');
                    tag.classList.add('is-primary', 'is-light');
                }
            }
        });
        
        // Fetch
        fetch(url.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newContent = doc.getElementById('servicesContainer');
            if (newContent && container) {
                container.innerHTML = newContent.innerHTML;
            }
            if (container) {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            window.location.href = url.toString();
        });
    }
    
    // Desktop category links
    document.querySelectorAll('.category-link').forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const category = link.dataset.category || '';
            loadCategoryAjax(category);
        });
    });
    
    // Mobile category dropdown
    const mobileCatSelect = document.getElementById('mobileCategorySelect');
    if (mobileCatSelect) {
        mobileCatSelect.addEventListener('change', () => {
            const category = mobileCatSelect.value || '';
            loadCategoryAjax(category);
        });
    }
</script>
@endsection


