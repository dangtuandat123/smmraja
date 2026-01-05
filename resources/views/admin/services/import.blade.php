@extends('layouts.admin')

@section('title', 'Import dịch vụ từ API')

@section('content')
<h2 class="title is-4">Import dịch vụ từ SMM Raja API</h2>

<form method="POST" action="{{ route('admin.services.doImport') }}">
    @csrf
    
    <div class="card mb-4">
        <div class="card-header">
            <p class="card-header-title">Cấu hình Import</p>
        </div>
        <div class="card-content">
            <div class="columns">
                <div class="column is-4">
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
                <div class="column is-4">
                    <div class="field">
                        <label class="label">Markup % *</label>
                        <div class="control">
                            <input class="input" type="number" name="markup_percent" value="30" min="0" max="1000" step="0.1" required>
                        </div>
                        <p class="help">Phần trăm lợi nhuận thêm vào giá gốc</p>
                    </div>
                </div>
                <div class="column is-4">
                    <div class="field">
                        <label class="label">&nbsp;</label>
                        <button type="submit" class="button is-primary is-fullwidth">
                            <i class="fas fa-download mr-2"></i> Import đã chọn
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if(count($groupedServices) > 0)
        @foreach($groupedServices as $categoryName => $services)
            <div class="card mb-4">
                <div class="card-header">
                    <p class="card-header-title">
                        <label class="checkbox mr-3">
                            <input type="checkbox" class="category-toggle" data-category="{{ Str::slug($categoryName) }}">
                        </label>
                        {{ $categoryName }} ({{ count($services) }} dịch vụ)
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
                                <th>Giá (USD/1000)</th>
                                <th>Min/Max</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($services as $service)
                                @php $isImported = in_array($service['service'], $existingServiceIds); @endphp
                                <tr class="{{ $isImported ? 'has-background-light' : '' }}">
                                    <td>
                                        @if($isImported)
                                            <span class="tag is-success is-small">Đã có</span>
                                        @else
                                            <input type="checkbox" name="services[]" value="{{ $service['service'] }}" 
                                                   class="service-checkbox" data-category="{{ Str::slug($categoryName) }}">
                                        @endif
                                    </td>
                                    <td>{{ $service['service'] }}</td>
                                    <td>{{ Str::limit($service['name'], 50) }}</td>
                                    <td><span class="tag is-light is-small">{{ $service['type'] ?? 'Default' }}</span></td>
                                    <td>${{ number_format($service['rate'], 4) }}</td>
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
    document.querySelectorAll('.category-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const category = this.dataset.category;
            document.querySelectorAll(`.service-checkbox[data-category="${category}"]`).forEach(cb => {
                cb.checked = this.checked;
            });
        });
    });
</script>
@endsection
