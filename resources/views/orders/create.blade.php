@extends('layouts.app')

@section('title', 'Mua hàng')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="title is-3 mb-5">
            <span class="icon-text">
                <span class="icon has-text-primary"><i class="fas fa-cart-plus"></i></span>
                <span>Đặt hàng mới</span>
            </span>
        </h1>
        
        <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
            @csrf
            
            <div class="columns">
                <!-- Left Column - Order Form -->
                <div class="column is-7">
                    <div class="card">
                        <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <p class="card-header-title has-text-white">
                                <i class="fas fa-edit mr-2"></i> Thông tin đặt hàng
                            </p>
                        </div>
                        <div class="card-content">
                            <!-- Category Selection -->
                            <div class="field">
                                <label class="label">
                                    <span class="icon-text">
                                        <span class="icon has-text-primary"><i class="fas fa-folder"></i></span>
                                        <span>Danh mục</span>
                                    </span>
                                </label>
                                <div class="control">
                                    <div class="dropdown searchable-dropdown is-fullwidth" id="categoryDropdown">
                                        <div class="dropdown-trigger is-fullwidth">
                                            <button type="button" class="button is-fullwidth is-medium dropdown-btn" aria-haspopup="true">
                                                <span class="selected-text">-- Chọn danh mục --</span>
                                                <span class="icon is-small">
                                                    <i class="fas fa-angle-down"></i>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="dropdown-menu is-fullwidth" role="menu">
                                            <div class="dropdown-content">
                                                <div class="dropdown-search">
                                                    <input class="input" type="text" placeholder="Tìm danh mục...">
                                                </div>
                                                <hr class="dropdown-divider">
                                                <div class="dropdown-items">
                                                    @foreach($categories as $category)
                                                        <a href="#" class="dropdown-item" data-value="{{ $category->id }}">
                                                            {{ $category->name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="category_id" id="categoryInput">
                                </div>
                            </div>
                            
                            <!-- Service Selection -->
                            <div class="field">
                                <label class="label">
                                    <span class="icon-text">
                                        <span class="icon has-text-info"><i class="fas fa-cog"></i></span>
                                        <span>Dịch vụ</span>
                                    </span>
                                </label>
                                <div class="control">
                                    <div class="dropdown searchable-dropdown is-fullwidth" id="serviceDropdown">
                                        <div class="dropdown-trigger is-fullwidth">
                                            <button type="button" class="button is-fullwidth is-medium dropdown-btn" aria-haspopup="true" disabled>
                                                <span class="selected-text">-- Chọn dịch vụ --</span>
                                                <span class="icon is-small">
                                                    <i class="fas fa-angle-down"></i>
                                                </span>
                                            </button>
                                        </div>
                                        <div class="dropdown-menu is-fullwidth" role="menu">
                                            <div class="dropdown-content">
                                                <div class="dropdown-search">
                                                    <input class="input" type="text" placeholder="Tìm dịch vụ...">
                                                </div>
                                                <hr class="dropdown-divider">
                                                <div class="dropdown-items">
                                                    <!-- Populated by JS -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="service_id" id="serviceInput" required>
                                </div>
                            </div>
                            
                            <!-- Service Description -->
                            <div id="serviceDescription" class="notification is-info is-light" style="display: none;">
                                <div class="is-flex is-align-items-start">
                                    <span class="icon has-text-info mr-2"><i class="fas fa-info-circle"></i></span>
                                    <p id="serviceDescText" class="is-size-7"></p>
                                </div>
                            </div>
                            
                            <!-- Link Input -->
                            <div class="field">
                                <label class="label">
                                    <span class="icon-text">
                                        <span class="icon has-text-success"><i class="fas fa-link"></i></span>
                                        <span>Link</span>
                                    </span>
                                </label>
                                <div class="control has-icons-left">
                                    <input class="input is-medium" 
                                           type="url" 
                                           name="link" 
                                           id="linkInput"
                                           placeholder="https://..." 
                                           value="{{ old('link') }}"
                                           required>
                                    <span class="icon is-small is-left">
                                        <i class="fas fa-globe"></i>
                                    </span>
                                </div>
                                <p class="help">Nhập link bài viết/profile cần tăng tương tác</p>
                            </div>
                            
                            <!-- Quantity Input -->
                            <div class="field" id="quantityField">
                                <label class="label">
                                    <span class="icon-text">
                                        <span class="icon has-text-warning"><i class="fas fa-sort-numeric-up"></i></span>
                                        <span>Số lượng</span>
                                    </span>
                                </label>
                                <div class="control">
                                    <input class="input is-medium" 
                                           type="number" 
                                           name="quantity" 
                                           id="quantityInput"
                                           placeholder="Nhập số lượng"
                                           value="{{ old('quantity') }}"
                                           min="1">
                                </div>
                                <p class="help" id="quantityHelp">Nhập số lượng trong khoảng cho phép</p>
                            </div>
                            
                            <!-- Extra Fields (dynamic based on service type) -->
                            <div id="extraFields"></div>
                            
                        </div>
                    </div>
                </div>
                
                <!-- Right Column - Order Summary -->
                <div class="column is-5">
                    <div class="card" style="position: sticky; top: 1rem;">
                        <div class="card-header" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                            <p class="card-header-title has-text-white">
                                <i class="fas fa-receipt mr-2"></i> Thông tin đơn hàng
                            </p>
                        </div>
                        <div class="card-content">
                            <!-- Balance -->
                            <div class="notification is-success is-light mb-4">
                                <div class="is-flex is-justify-content-space-between is-align-items-center">
                                    <span class="icon-text">
                                        <span class="icon"><i class="fas fa-wallet"></i></span>
                                        <span>Số dư hiện tại</span>
                                    </span>
                                    <strong class="is-size-5">
                                        {{ number_format(auth()->user()->balance, 0, ',', '.') }} <small>VND</small>
                                    </strong>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <!-- Service Info -->
                            <div id="orderSummary" style="display: none;">
                                <div class="mb-4">
                                    <p class="is-size-7 has-text-grey mb-1">
                                        <i class="fas fa-cog mr-1"></i> Dịch vụ đã chọn
                                    </p>
                                    <p class="has-text-weight-semibold" id="summaryServiceName">-</p>
                                </div>
                                
                                <div class="columns is-mobile mb-2">
                                    <div class="column">
                                        <div class="has-background-light p-3 has-text-centered" style="border-radius: 8px;">
                                            <p class="is-size-7 has-text-grey mb-1">
                                                <i class="fas fa-tag"></i> Giá/1000
                                            </p>
                                            <p class="has-text-weight-bold has-text-primary">
                                                <span id="summaryPrice">0</span> <small>VND</small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="column">
                                        <div class="has-background-light p-3 has-text-centered" style="border-radius: 8px;">
                                            <p class="is-size-7 has-text-grey mb-1">
                                                <i class="fas fa-sort-numeric-up"></i> Số lượng
                                            </p>
                                            <p class="has-text-weight-bold has-text-info">
                                                <span id="summaryQuantity">0</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="notification is-primary is-light mt-4">
                                    <div class="is-flex is-justify-content-space-between is-align-items-center">
                                        <span class="icon-text">
                                            <span class="icon"><i class="fas fa-money-bill-wave"></i></span>
                                            <span class="has-text-weight-bold">Tổng tiền</span>
                                        </span>
                                        <div class="has-text-right">
                                            <span class="title is-4 has-text-primary mb-0" id="summaryTotal">0</span>
                                            <span class="is-size-6 has-text-grey ml-1">VND</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="noServiceSelected" class="has-text-centered py-5">
                                <span class="icon is-large has-text-grey-lighter">
                                    <i class="fas fa-shopping-basket fa-3x"></i>
                                </span>
                                <p class="has-text-grey mt-3">Vui lòng chọn dịch vụ để xem thông tin</p>
                            </div>
                            
                            <button type="submit" class="button is-primary is-medium is-fullwidth mt-4" id="submitBtn" disabled style="border-radius: 8px;">
                                <span class="icon"><i class="fas fa-shopping-cart"></i></span>
                                <span>Đặt hàng ngay</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('styles')
<style>
    /* Searchable Dropdown Styles */
    .searchable-dropdown {
        width: 100%;
    }
    .searchable-dropdown .dropdown-trigger {
        width: 100%;
    }
    .searchable-dropdown .dropdown-btn {
        width: 100%;
        justify-content: space-between;
        text-align: left;
        background: white;
        border: 1px solid #dbdbdb;
        font-weight: normal;
        overflow: hidden;
    }
    .searchable-dropdown .dropdown-btn .selected-text {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        flex: 1;
        max-width: calc(100% - 30px);
    }
    .searchable-dropdown .dropdown-btn:hover {
        border-color: #b5b5b5;
    }
    .searchable-dropdown .dropdown-btn:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 0.125em rgba(99,102,241,.25);
    }
    .searchable-dropdown .dropdown-menu {
        width: 100%;
        padding-top: 0;
    }
    .searchable-dropdown .dropdown-content {
        max-height: 350px;
        overflow: hidden;
        padding: 0;
    }
    .searchable-dropdown .dropdown-search {
        padding: 0.75rem;
        position: sticky;
        top: 0;
        background: white;
        z-index: 1;
    }
    .searchable-dropdown .dropdown-search input {
        width: 100%;
    }
    .searchable-dropdown .dropdown-items {
        max-height: 250px;
        overflow-y: auto;
        padding: 0.5rem 0;
    }
    .searchable-dropdown .dropdown-item {
        padding: 0.75rem 1rem;
        white-space: normal;
        line-height: 1.4;
    }
    .searchable-dropdown .dropdown-item:hover {
        background: #f5f5ff;
        color: #6366f1;
    }
    .searchable-dropdown .dropdown-item.is-active {
        background: #6366f1;
        color: white;
    }
    .searchable-dropdown .dropdown-item .price {
        float: right;
        font-weight: 600;
        color: #10b981;
    }
    .searchable-dropdown .dropdown-item.is-active .price {
        color: #a7f3d0;
    }
    .searchable-dropdown .dropdown-divider {
        margin: 0;
    }
    .searchable-dropdown .no-results {
        padding: 1rem;
        text-align: center;
        color: #7a7a7a;
    }
</style>
@endsection

@section('scripts')
<script>
    const categories = @json($categories);
    let selectedService = null;
    const userBalance = {{ auth()->user()->balance }};
    
    // Build services map
    const servicesMap = {};
    categories.forEach(cat => {
        (cat.services || []).forEach(s => {
            servicesMap[s.id] = { ...s, category_id: cat.id };
        });
    });

    // Searchable Dropdown Class
    class SearchableDropdown {
        constructor(element, options = {}) {
            this.element = element;
            this.options = options;
            this.button = element.querySelector('.dropdown-btn');
            this.selectedText = element.querySelector('.selected-text');
            this.searchInput = element.querySelector('.dropdown-search input');
            this.itemsContainer = element.querySelector('.dropdown-items');
            this.hiddenInput = options.hiddenInput;
            this.value = null;
            
            this.init();
        }
        
        init() {
            // Toggle dropdown
            this.button.addEventListener('click', (e) => {
                e.preventDefault();
                if (!this.button.disabled) {
                    this.toggle();
                }
            });
            
            // Search filter
            this.searchInput.addEventListener('input', () => {
                this.filter(this.searchInput.value);
            });
            
            // Close on click outside
            document.addEventListener('click', (e) => {
                if (!this.element.contains(e.target)) {
                    this.close();
                }
            });
            
            // Item click
            this.itemsContainer.addEventListener('click', (e) => {
                const item = e.target.closest('.dropdown-item');
                if (item) {
                    e.preventDefault();
                    this.select(item.dataset.value, item.textContent.trim());
                }
            });
        }
        
        toggle() {
            this.element.classList.toggle('is-active');
            if (this.element.classList.contains('is-active')) {
                setTimeout(() => this.searchInput.focus(), 50);
            }
        }
        
        open() {
            this.element.classList.add('is-active');
            setTimeout(() => this.searchInput.focus(), 50);
        }
        
        close() {
            this.element.classList.remove('is-active');
            this.searchInput.value = '';
            this.filter('');
        }
        
        filter(query) {
            const items = this.itemsContainer.querySelectorAll('.dropdown-item');
            const q = query.toLowerCase();
            let found = false;
            
            items.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(q)) {
                    item.style.display = '';
                    found = true;
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show no results message
            let noResults = this.itemsContainer.querySelector('.no-results');
            if (!found) {
                if (!noResults) {
                    noResults = document.createElement('div');
                    noResults.className = 'no-results';
                    noResults.textContent = 'Không tìm thấy';
                    this.itemsContainer.appendChild(noResults);
                }
                noResults.style.display = '';
            } else if (noResults) {
                noResults.style.display = 'none';
            }
        }
        
        select(value, text) {
            this.value = value;
            // Extract only the service name, remove price if exists
            let displayText = text.replace(/\d+[.,]\d+.*đ.*$/g, '').trim();
            if (!displayText) displayText = text.split('\n')[0].trim();
            this.selectedText.textContent = displayText;
            
            console.log('Selected:', value, 'Text:', displayText);
            
            if (this.hiddenInput) {
                this.hiddenInput.value = value;
            }
            
            // Remove active from all, add to selected
            this.itemsContainer.querySelectorAll('.dropdown-item').forEach(item => {
                item.classList.remove('is-active');
                if (item.dataset.value == value) {
                    item.classList.add('is-active');
                }
            });
            
            this.close();
            
            if (this.options.onChange) {
                this.options.onChange(value);
            }
        }
        
        setItems(items) {
            this.itemsContainer.innerHTML = items.map(item => `
                <a href="#" class="dropdown-item" data-value="${item.value}">
                    ${item.text}
                    ${item.price ? `<span class="price">${item.price}</span>` : ''}
                </a>
            `).join('');
        }
        
        setValue(value) {
            const item = this.itemsContainer.querySelector(`[data-value="${value}"]`);
            if (item) {
                this.select(value, item.textContent);
            }
        }
        
        enable() {
            this.button.disabled = false;
        }
        
        disable() {
            this.button.disabled = true;
        }
        
        clear() {
            this.value = null;
            this.selectedText.textContent = this.options.placeholder || '-- Chọn --';
            if (this.hiddenInput) {
                this.hiddenInput.value = '';
            }
            this.itemsContainer.querySelectorAll('.dropdown-item').forEach(item => {
                item.classList.remove('is-active');
            });
        }
    }
    
    // Initialize Category Dropdown
    const categoryDropdown = new SearchableDropdown(document.getElementById('categoryDropdown'), {
        hiddenInput: document.getElementById('categoryInput'),
        placeholder: '-- Chọn danh mục --',
        onChange: function(categoryId) {
            updateServiceDropdown(categoryId);
            resetOrderSummary();
        }
    });
    
    // Initialize Service Dropdown
    const serviceDropdown = new SearchableDropdown(document.getElementById('serviceDropdown'), {
        hiddenInput: document.getElementById('serviceInput'),
        placeholder: '-- Chọn dịch vụ --',
        onChange: function(serviceId) {
            onServiceChange(serviceId);
        }
    });
    
    function updateServiceDropdown(categoryId) {
        serviceDropdown.clear();
        
        if (!categoryId) {
            serviceDropdown.disable();
            serviceDropdown.setItems([]);
            return;
        }
        
        const category = categories.find(c => c.id == categoryId);
        if (category && category.services) {
            const items = category.services.map(s => ({
                value: s.id,
                text: s.name,
                price: Math.round(s.price_vnd).toLocaleString('vi-VN') + 'đ/1000'
            }));
            serviceDropdown.setItems(items);
            serviceDropdown.enable();
        }
    }
    
    function onServiceChange(serviceId) {
        if (!serviceId) {
            resetOrderSummary();
            return;
        }
        
        selectedService = servicesMap[serviceId];
        if (!selectedService) return;
        
        // Update description with badges
        const descEl = document.getElementById('serviceDescription');
        const descText = document.getElementById('serviceDescText');
        
        let badges = '';
        if (selectedService.refill) {
            badges += '<span class="tag is-success is-small mr-1">Bảo hành</span>';
        }
        if (selectedService.cancel) {
            badges += '<span class="tag is-info is-small">Có thể hủy</span>';
        }
        
        let descHtml = `<strong>${selectedService.name}</strong>`;
        if (badges) {
            descHtml += `<br><div class="mt-1">${badges}</div>`;
        }
        if (selectedService.description) {
            descHtml += `<br><span class="has-text-grey">${selectedService.description}</span>`;
        }
        
        descText.innerHTML = descHtml;
        descEl.style.display = 'block';
        
        // Update quantity help
        document.getElementById('quantityHelp').textContent = `Min: ${selectedService.min.toLocaleString()} - Max: ${selectedService.max.toLocaleString()}`;
        document.getElementById('quantityInput').min = selectedService.min;
        document.getElementById('quantityInput').max = selectedService.max;
        document.getElementById('quantityInput').placeholder = `${selectedService.min} - ${selectedService.max}`;
        
        // Show order summary
        document.getElementById('noServiceSelected').style.display = 'none';
        document.getElementById('orderSummary').style.display = 'block';
        document.getElementById('summaryServiceName').textContent = selectedService.name;
        document.getElementById('summaryPrice').textContent = Math.round(selectedService.price_vnd).toLocaleString('vi-VN');
        
        // Generate extra fields based on type
        generateExtraFields(selectedService.type);
        
        updateTotal();
    }
    
    document.getElementById('quantityInput').addEventListener('input', updateTotal);
    document.getElementById('linkInput').addEventListener('input', updateTotal);
    
    function updateTotal() {
        if (!selectedService) return;
        
        const quantity = parseInt(document.getElementById('quantityInput').value) || 0;
        const pricePerUnit = selectedService.price_vnd / 1000;
        const total = Math.round(pricePerUnit * quantity);
        
        document.getElementById('summaryQuantity').textContent = quantity.toLocaleString('vi-VN');
        document.getElementById('summaryTotal').textContent = total.toLocaleString('vi-VN');
        
        const submitBtn = document.getElementById('submitBtn');
        const isValid = quantity >= selectedService.min && 
                       quantity <= selectedService.max && 
                       total <= userBalance &&
                       document.getElementById('linkInput').value;
        
        submitBtn.disabled = !isValid;
        
        if (total > userBalance) {
            submitBtn.innerHTML = '<span class="icon"><i class="fas fa-exclamation-triangle"></i></span><span>Số dư không đủ</span>';
            submitBtn.classList.remove('is-primary');
            submitBtn.classList.add('is-danger');
        } else {
            submitBtn.innerHTML = '<span class="icon"><i class="fas fa-check"></i></span><span>Đặt hàng</span>';
            submitBtn.classList.add('is-primary');
            submitBtn.classList.remove('is-danger');
        }
    }
    
    function resetOrderSummary() {
        selectedService = null;
        document.getElementById('noServiceSelected').style.display = 'block';
        document.getElementById('orderSummary').style.display = 'none';
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('extraFields').innerHTML = '';
        document.getElementById('serviceDescription').style.display = 'none';
    }
    
    function generateExtraFields(type) {
        const container = document.getElementById('extraFields');
        container.innerHTML = '';
        
        const typeLower = type.toLowerCase();
        
        if (typeLower.includes('comment') && !typeLower.includes('like')) {
            container.innerHTML = `
                <div class="field">
                    <label class="label"><i class="fas fa-comments has-text-info"></i> Danh sách bình luận</label>
                    <div class="control">
                        <textarea class="textarea" name="comments" rows="5" placeholder="Mỗi bình luận một dòng..."></textarea>
                    </div>
                    <p class="help">Nhập mỗi bình luận trên một dòng riêng</p>
                </div>
            `;
        }
        
        if (typeLower.includes('mention') && typeLower.includes('user')) {
            container.innerHTML += `
                <div class="field">
                    <label class="label"><i class="fas fa-at has-text-primary"></i> Username</label>
                    <div class="control">
                        <input class="input" type="text" name="username" placeholder="@username">
                    </div>
                </div>
            `;
        }
        
        if (typeLower.includes('hashtag')) {
            container.innerHTML += `
                <div class="field">
                    <label class="label"><i class="fas fa-hashtag has-text-info"></i> Hashtag</label>
                    <div class="control">
                        <input class="input" type="text" name="hashtag" placeholder="#hashtag">
                    </div>
                </div>
            `;
        }
        
        if (typeLower.includes('poll')) {
            container.innerHTML += `
                <div class="field">
                    <label class="label"><i class="fas fa-poll has-text-warning"></i> Số câu trả lời</label>
                    <div class="control">
                        <input class="input" type="number" name="answer_number" min="1" placeholder="1, 2, 3...">
                    </div>
                </div>
            `;
        }
    }
    
    // Auto-select service if passed via URL
    const preSelectedServiceId = {{ $selectedService ?? 'null' }};
    
    if (preSelectedServiceId) {
        const service = servicesMap[preSelectedServiceId];
        if (service) {
            console.log('Pre-selecting service:', preSelectedServiceId, service);
            
            // Find category name
            const category = categories.find(c => c.id == service.category_id);
            const categoryName = category ? category.name : '';
            
            // Set category first
            categoryDropdown.select(String(service.category_id), categoryName);
            
            // Populate services for this category
            updateServiceDropdown(service.category_id);
            
            // Then select service after items are populated
            setTimeout(() => {
                serviceDropdown.select(String(preSelectedServiceId), service.name);
                onServiceChange(preSelectedServiceId);
            }, 150);
        }
    }
</script>
@endsection
