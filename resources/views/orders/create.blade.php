@extends('layouts.app')

@section('title', 'Mua hàng')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="title is-3">
            <i class="fas fa-cart-plus has-text-primary"></i> Đặt hàng mới
        </h1>
        
        <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
            @csrf
            
            <div class="columns">
                <!-- Left Column - Order Form -->
                <div class="column is-7">
                    <div class="card">
                        <div class="card-content">
                            <!-- Category Selection -->
                            <div class="field">
                                <label class="label">
                                    <i class="fas fa-folder has-text-primary"></i> Danh mục
                                </label>
                                <div class="control">
                                    <div class="select is-fullwidth is-medium">
                                        <select id="categorySelect" required>
                                            <option value="">-- Chọn danh mục --</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" 
                                                    {{ $selectedCategory == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Service Selection -->
                            <div class="field">
                                <label class="label">
                                    <i class="fas fa-cog has-text-info"></i> Dịch vụ
                                </label>
                                <div class="control">
                                    <div class="select is-fullwidth is-medium">
                                        <select name="service_id" id="serviceSelect" required>
                                            <option value="">-- Chọn dịch vụ --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Service Description -->
                            <div id="serviceDescription" class="notification is-light" style="display: none;">
                                <p id="serviceDescText"></p>
                            </div>
                            
                            <!-- Link Input -->
                            <div class="field">
                                <label class="label">
                                    <i class="fas fa-link has-text-success"></i> Link
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
                                    <i class="fas fa-sort-numeric-up has-text-warning"></i> Số lượng
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
                        <div class="card-header">
                            <p class="card-header-title">
                                <i class="fas fa-receipt mr-2"></i> Thông tin đơn hàng
                            </p>
                        </div>
                        <div class="card-content">
                            <!-- Balance -->
                            <div class="level is-mobile mb-4">
                                <div class="level-left">
                                    <span class="has-text-grey">Số dư hiện tại:</span>
                                </div>
                                <div class="level-right">
                                    <span class="has-text-weight-bold has-text-success">
                                        {{ number_format(auth()->user()->balance, 0, ',', '.') }} VND
                                    </span>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <!-- Service Info -->
                            <div id="orderSummary" style="display: none;">
                                <div class="mb-4">
                                    <p class="is-size-7 has-text-grey">Dịch vụ</p>
                                    <p class="has-text-weight-semibold" id="summaryServiceName">-</p>
                                </div>
                                
                                <div class="level is-mobile">
                                    <div class="level-left">
                                        <span class="has-text-grey">Giá/1000:</span>
                                    </div>
                                    <div class="level-right">
                                        <span id="summaryPrice">0</span> VND
                                    </div>
                                </div>
                                
                                <div class="level is-mobile">
                                    <div class="level-left">
                                        <span class="has-text-grey">Số lượng:</span>
                                    </div>
                                    <div class="level-right">
                                        <span id="summaryQuantity">0</span>
                                    </div>
                                </div>
                                
                                <hr>
                                
                                <div class="level is-mobile">
                                    <div class="level-left">
                                        <strong>Tổng tiền:</strong>
                                    </div>
                                    <div class="level-right">
                                        <span class="title is-4 has-text-primary" id="summaryTotal">0</span>
                                        <span class="ml-1">VND</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="noServiceSelected" class="has-text-centered py-4">
                                <span class="icon is-large has-text-grey-light">
                                    <i class="fas fa-hand-pointer fa-2x"></i>
                                </span>
                                <p class="has-text-grey mt-2">Vui lòng chọn dịch vụ</p>
                            </div>
                            
                            <button type="submit" class="button is-primary is-medium is-fullwidth mt-4" id="submitBtn" disabled>
                                <span class="icon"><i class="fas fa-check"></i></span>
                                <span>Đặt hàng</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@section('scripts')
<script>
    const categories = @json($categories);
    let selectedService = null;
    const userBalance = {{ auth()->user()->balance }};
    
    document.getElementById('categorySelect').addEventListener('change', function() {
        const categoryId = this.value;
        const serviceSelect = document.getElementById('serviceSelect');
        
        serviceSelect.innerHTML = '<option value="">-- Chọn dịch vụ --</option>';
        
        if (!categoryId) return;
        
        const category = categories.find(c => c.id == categoryId);
        if (category && category.services) {
            category.services.forEach(service => {
                const opt = document.createElement('option');
                opt.value = service.id;
                opt.textContent = `${service.name} - ${Number(service.price_vnd).toLocaleString('vi-VN')}đ/1000`;
                opt.dataset.service = JSON.stringify(service);
                serviceSelect.appendChild(opt);
            });
        }
        
        resetOrderSummary();
    });
    
    document.getElementById('serviceSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (!selectedOption.value) {
            resetOrderSummary();
            return;
        }
        
        selectedService = JSON.parse(selectedOption.dataset.service);
        
        // Update description
        const descEl = document.getElementById('serviceDescription');
        const descText = document.getElementById('serviceDescText');
        if (selectedService.description) {
            descText.innerHTML = `<strong>${selectedService.name}</strong><br>${selectedService.description}`;
            descEl.style.display = 'block';
        } else {
            descEl.style.display = 'none';
        }
        
        // Update quantity help
        document.getElementById('quantityHelp').textContent = `Min: ${selectedService.min.toLocaleString()} - Max: ${selectedService.max.toLocaleString()}`;
        document.getElementById('quantityInput').min = selectedService.min;
        document.getElementById('quantityInput').max = selectedService.max;
        document.getElementById('quantityInput').placeholder = `${selectedService.min} - ${selectedService.max}`;
        
        // Show order summary
        document.getElementById('noServiceSelected').style.display = 'none';
        document.getElementById('orderSummary').style.display = 'block';
        document.getElementById('summaryServiceName').textContent = selectedService.name;
        document.getElementById('summaryPrice').textContent = Number(selectedService.price_vnd).toLocaleString('vi-VN');
        
        // Generate extra fields based on type
        generateExtraFields(selectedService.type);
        
        updateTotal();
    });
    
    document.getElementById('quantityInput').addEventListener('input', updateTotal);
    
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
    
    document.getElementById('linkInput').addEventListener('input', updateTotal);
</script>
@endsection
