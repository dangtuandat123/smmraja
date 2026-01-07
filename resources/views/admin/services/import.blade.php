@extends('layouts.admin')

@section('title', 'Import dịch vụ từ API')

@section('content')
<div class="level">
    <div class="level-left">
        <h2 class="title is-4 mb-0">Import dịch vụ từ SMM Raja API</h2>
    </div>
    <div class="level-right">
        <span class="tag is-info is-medium">
            <i class="fas fa-exchange-alt mr-2"></i>
            Tỷ giá: 1 USD = {{ number_format($exchangeRate, 0, ',', '.') }} VND
        </span>
    </div>
</div>

<form method="POST" action="{{ route('admin.services.doImport') }}">
    @csrf
    
    <div class="card mb-4">
        <div class="card-header">
            <p class="card-header-title">Cấu hình Import</p>
        </div>
        <div class="card-content">
            <div class="columns">
                <div class="column is-3">
                    <div class="field">
                        <label class="label">Danh mục đích *</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="category_id" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-2">
                    <div class="field">
                        <label class="label">Markup % *</label>
                        <div class="control">
                            <input class="input" type="number" name="markup_percent" value="30" min="0" max="1000" step="0.1" required>
                        </div>
                    </div>
                </div>
                <div class="column is-3">
                    <div class="field">
                        <label class="label"><i class="fas fa-search"></i> Tìm kiếm</label>
                        <div class="control">
                            <input class="input" type="text" id="searchInput" placeholder="Tìm theo tên, ID, loại...">
                        </div>
                    </div>
                </div>
                <div class="column is-2">
                    <div class="field">
                        <label class="label"><i class="fas fa-sort"></i> Sắp xếp</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select id="sortSelect">
                                    <option value="default">Mặc định</option>
                                    <option value="price_asc">Giá: Thấp → Cao</option>
                                    <option value="price_desc">Giá: Cao → Thấp</option>
                                    <option value="name_asc">Tên: A → Z</option>
                                    <option value="name_desc">Tên: Z → A</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-2">
                    <div class="field">
                        <label class="label">&nbsp;</label>
                        <button type="submit" class="button is-primary is-fullwidth">
                            <i class="fas fa-download mr-2"></i> Import
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Search stats -->
            <div class="level">
                <div class="level-left">
                    <span id="searchStats" class="tag is-info is-light">Tổng: {{ collect($groupedServices)->flatten(1)->count() }} dịch vụ</span>
                </div>
                <div class="level-right">
                    <button type="button" class="button is-small is-light" id="selectAllVisible">
                        <i class="fas fa-check-square mr-1"></i> Chọn tất cả hiển thị
                    </button>
                    <button type="button" class="button is-small is-light ml-2" id="deselectAll">
                        <i class="fas fa-square mr-1"></i> Bỏ chọn tất cả
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    @if(count($groupedServices) > 0)
        @foreach($groupedServices as $categoryName => $services)
            <div class="card mb-4 service-category-card" data-category="{{ Str::slug($categoryName) }}">
                <div class="card-header">
                    <p class="card-header-title">
                        <label class="checkbox mr-3">
                            <input type="checkbox" class="category-toggle" data-category="{{ Str::slug($categoryName) }}">
                        </label>
                        {{ $categoryName }} (<span class="category-count">{{ count($services) }}</span> dịch vụ)
                    </p>
                </div>
                <div class="card-content" style="padding: 0; max-height: 400px; overflow-y: auto;">
                    <table class="table is-fullwidth is-hoverable is-narrow mb-0">
                        <thead>
                            <tr>
                                <th width="40"></th>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Loại</th>
                                <th>Giá USD</th>
                                <th class="has-text-success">Giá VND/1000</th>
                                <th>Min/Max</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                                @php 
                                    $isImported = in_array($service['service'], $existingServiceIds);
                                    $priceVnd = round($service['rate'] * $exchangeRate, 0);
                                @endphp
                                <tr class="service-row {{ $isImported ? 'has-background-light' : '' }}" 
                                    data-search="{{ strtolower($service['service'] . ' ' . $service['name'] . ' ' . ($service['type'] ?? 'default')) }}"
                                    data-price="{{ $service['rate'] }}"
                                    data-name="{{ strtolower($service['name']) }}">
                                    <td>
                                        @if($isImported)
                                            <span class="tag is-success is-small">Đã có</span>
                                        @else
                                            <input type="checkbox" name="services[]" value="{{ $service['service'] }}" 
                                                   class="service-checkbox" data-category="{{ Str::slug($categoryName) }}">
                                        @endif
                                    </td>
                                    <td>{{ $service['service'] }}</td>
                                    <td>{{ Str::limit($service['name'], 45) }}</td>
                                    <td><span class="tag is-light is-small">{{ $service['type'] ?? 'Default' }}</span></td>
                                    <td class="has-text-grey">${{ number_format($service['rate'], 4) }}</td>
                                    <td class="has-text-success has-text-weight-semibold">{{ number_format($priceVnd, 0, ',', '.') }}đ</td>
                                    <td>{{ $service['min'] ?? 'N/A' }} / {{ $service['max'] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @else
        <div class="notification is-warning">
            <p>Không thể lấy danh sách dịch vụ từ API. Vui lòng kiểm tra cấu hình API key.</p>
        </div>
    @endif
</form>
@endsection

@section('scripts')
<script>
    const searchInput = document.getElementById('searchInput');
    const searchStats = document.getElementById('searchStats');
    const totalServices = {{ collect($groupedServices)->flatten(1)->count() }};
    
    // Search filter
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        let visibleCount = 0;
        
        document.querySelectorAll('.service-row').forEach(row => {
            const searchData = row.dataset.search;
            const matches = query === '' || searchData.includes(query);
            row.style.display = matches ? '' : 'none';
            if (matches) visibleCount++;
        });
        
        // Update stats
        if (query === '') {
            searchStats.textContent = `Tổng: ${totalServices} dịch vụ`;
            searchStats.className = 'tag is-info is-light';
        } else {
            searchStats.textContent = `Tìm thấy: ${visibleCount} / ${totalServices} dịch vụ`;
            searchStats.className = visibleCount > 0 ? 'tag is-success is-light' : 'tag is-warning is-light';
        }
        
        // Update category counts
        document.querySelectorAll('.service-category-card').forEach(card => {
            const category = card.dataset.category;
            const visibleInCat = card.querySelectorAll('.service-row[style=""], .service-row:not([style])').length;
            const countSpan = card.querySelector('.category-count');
            if (countSpan) {
                countSpan.textContent = visibleInCat;
            }
            // Hide category if no visible services
            card.style.display = visibleInCat > 0 ? '' : 'none';
        });
    });
    
    // Category toggle
    document.querySelectorAll('.category-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const category = this.dataset.category;
            document.querySelectorAll(`.service-checkbox[data-category="${category}"]`).forEach(cb => {
                // Only check visible rows
                const row = cb.closest('.service-row');
                if (row && row.style.display !== 'none') {
                    cb.checked = this.checked;
                }
            });
        });
    });
    
    // Select all visible
    document.getElementById('selectAllVisible').addEventListener('click', function() {
        document.querySelectorAll('.service-row').forEach(row => {
            if (row.style.display !== 'none') {
                const cb = row.querySelector('.service-checkbox');
                if (cb) cb.checked = true;
            }
        });
    });
    
    // Deselect all
    document.getElementById('deselectAll').addEventListener('click', function() {
        document.querySelectorAll('.service-checkbox').forEach(cb => {
            cb.checked = false;
        });
        document.querySelectorAll('.category-toggle').forEach(toggle => {
            toggle.checked = false;
        });
    });
    
    // Sort functionality
    const sortSelect = document.getElementById('sortSelect');
    sortSelect.addEventListener('change', function() {
        const sortBy = this.value;
        
        document.querySelectorAll('.service-category-card').forEach(card => {
            const tbody = card.querySelector('tbody');
            if (!tbody) return;
            
            const rows = Array.from(tbody.querySelectorAll('.service-row'));
            
            rows.sort((a, b) => {
                switch(sortBy) {
                    case 'price_asc':
                        return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                    case 'price_desc':
                        return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                    case 'name_asc':
                        return a.dataset.name.localeCompare(b.dataset.name);
                    case 'name_desc':
                        return b.dataset.name.localeCompare(a.dataset.name);
                    default:
                        return 0;
                }
            });
            
            // Re-append sorted rows
            rows.forEach(row => tbody.appendChild(row));
        });
    });
</script>
@endsection
