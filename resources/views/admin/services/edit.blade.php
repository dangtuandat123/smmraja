@extends('layouts.admin')

@section('title', 'Sửa dịch vụ')

@section('content')
<div class="columns">
    <div class="column is-8">
        <div class="card">
            <div class="card-header">
                <p class="card-header-title">Sửa dịch vụ: {{ $service->name }}</p>
            </div>
            <div class="card-content">
                <form method="POST" action="{{ route('admin.services.update', $service) }}">
                    @csrf @method('PUT')
                    
                    <div class="field">
                        <label class="label">Danh mục *</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select name="category_id" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $service->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Tên dịch vụ *</label>
                        <div class="control">
                            <input class="input" type="text" name="name" value="{{ old('name', $service->name) }}" required>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Mô tả</label>
                        <div class="control">
                            <textarea class="textarea" name="description" rows="3">{{ old('description', $service->description) }}</textarea>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">
                            <i class="fas fa-cog mr-1"></i>Loại dịch vụ *
                            <span class="tag is-warning is-light ml-2">Override API</span>
                        </label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                @php
                                    $types = [
                                        'Default' => 'Mặc định (Default)',
                                        'Custom Comments' => 'Bình luận tùy chỉnh (Custom Comments)',
                                        'Custom Comments Package' => 'Gói bình luận tùy chỉnh',
                                        'Mentions' => 'Đề cập (Mentions)',
                                        'Mentions with Hashtags' => 'Đề cập với Hashtags',
                                        'Mentions Custom List' => 'Đề cập danh sách tùy chỉnh',
                                        'Mentions Hashtag' => 'Đề cập Hashtag',
                                        'Mentions User Followers' => 'Đề cập Follower người dùng',
                                        'Mentions Media Likers' => 'Đề cập người Like',
                                        'Comment Likes' => 'Like bình luận (Comment Likes)',
                                        'Comment Replies' => 'Trả lời bình luận (Comment Replies)',
                                        'Poll' => 'Bình chọn (Poll)',
                                        'Invites from Groups' => 'Mời từ nhóm',
                                        'Package' => 'Gói dịch vụ (Package)',
                                        'Subscriptions' => 'Đăng ký theo dõi (Subscriptions)',
                                    ];
                                @endphp
                                <select name="type" required>
                                    @foreach($types as $value => $label)
                                        <option value="{{ $value }}" {{ old('type', $service->type) == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <p class="help">
                            <span class="has-text-warning"><i class="fas fa-exclamation-triangle mr-1"></i></span>
                            Loại từ API: <strong>{{ $service->type }}</strong> - Thay đổi nếu API ghi sai
                        </p>
                    </div>
                    
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label">Markup % *</label>
                                <div class="control">
                                    <input class="input" type="number" name="markup_percent" 
                                           value="{{ old('markup_percent', $service->markup_percent) }}" 
                                           min="0" max="1000" step="0.1" required>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Min *</label>
                                <div class="control">
                                    <input class="input" type="number" name="min" value="{{ old('min', $service->min) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label">Max *</label>
                                <div class="control">
                                    <input class="input" type="number" name="max" value="{{ old('max', $service->max) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label class="label">Thứ tự sắp xếp</label>
                        <div class="control">
                            <input class="input" type="number" name="sort_order" value="{{ old('sort_order', $service->sort_order) }}">
                        </div>
                    </div>
                    
                    <div class="columns">
                        <div class="column">
                            <div class="field">
                                <label class="label"><i class="fas fa-icons mr-1"></i>Icon (FontAwesome)</label>
                                <div class="control has-icons-left">
                                    <input class="input" type="text" name="icon" id="iconInput"
                                           value="{{ old('icon', $service->icon) }}" 
                                           placeholder="fas fa-heart">
                                    <span class="icon is-left" id="iconPreview">
                                        <i class="{{ $service->icon ?? 'fas fa-box' }}"></i>
                                    </span>
                                </div>
                                <p class="help">Ví dụ: fas fa-heart, fab fa-facebook, fas fa-thumbs-up</p>
                            </div>
                        </div>
                        <div class="column">
                            <div class="field">
                                <label class="label"><i class="fas fa-palette mr-1"></i>Màu Icon</label>
                                <div class="control">
                                    <input type="hidden" name="icon_color" id="colorInput" 
                                           value="{{ old('icon_color', $service->icon_color ?? '#6366f1') }}">
                                    <div class="color-swatches is-flex is-flex-wrap-wrap" style="gap: 8px;">
                                        @php
                                            $colors = [
                                                '#ef4444' => 'Đỏ',
                                                '#f97316' => 'Cam',
                                                '#eab308' => 'Vàng',
                                                '#22c55e' => 'Xanh lá',
                                                '#14b8a6' => 'Ngọc',
                                                '#3b82f6' => 'Xanh dương',
                                                '#6366f1' => 'Tím',
                                                '#ec4899' => 'Hồng',
                                                '#8b5cf6' => 'Violet',
                                                '#64748b' => 'Xám',
                                                '#000000' => 'Đen',
                                                '#ffffff' => 'Trắng',
                                            ];
                                            $currentColor = old('icon_color', $service->icon_color ?? '#6366f1');
                                        @endphp
                                        @foreach($colors as $hex => $name)
                                            <div class="color-swatch {{ $currentColor == $hex ? 'is-selected' : '' }}" 
                                                 data-color="{{ $hex }}" 
                                                 title="{{ $name }}"
                                                 style="width: 32px; height: 32px; background-color: {{ $hex }}; border-radius: 6px; cursor: pointer; border: 2px solid {{ $currentColor == $hex ? '#000' : '#ddd' }}; {{ $hex == '#ffffff' ? 'border-color: #ccc;' : '' }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="notification is-light mb-4" id="iconDemo">
                        <strong>Xem trước:</strong> 
                        <span class="icon is-medium" id="iconPreviewLarge" style="color: {{ $service->icon_color ?? '#6366f1' }}">
                            <i class="{{ $service->icon ?? 'fas fa-box' }} fa-lg"></i>
                        </span>
                        <span id="iconPreviewName">{{ $service->name }}</span>
                    </div>
                    
                    <div class="field">
                        <label class="checkbox">
                            <input type="checkbox" name="is_active" value="1" {{ $service->is_active ? 'checked' : '' }}>
                            Kích hoạt
                        </label>
                    </div>
                    
                    <div class="field is-grouped">
                        <div class="control">
                            <button type="submit" class="button is-primary">
                                <i class="fas fa-save mr-2"></i> Lưu
                            </button>
                        </div>
                        <div class="control">
                            <a href="{{ route('admin.services.index') }}" class="button is-light">Hủy</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="column is-4">
        <div class="card">
            <div class="card-header">
                <p class="card-header-title">Thông tin API</p>
            </div>
            <div class="card-content">
                <table class="table is-fullwidth is-bordered">
                    <tr><th>API Service ID</th><td>{{ $service->api_service_id }}</td></tr>
                    <tr><th>Tên gốc</th><td>{{ $service->api_name }}</td></tr>
                    <tr><th>Loại</th><td>{{ $service->type }}</td></tr>
                    <tr><th>Giá gốc (USD)</th><td>${{ number_format($service->api_rate, 4) }}</td></tr>
                    <tr><th>Giá bán (VND)</th><td><strong>{{ number_format($service->price_vnd, 0, ',', '.') }}đ</strong></td></tr>
                    <tr><th>Refill</th><td>{{ $service->refill ? 'Có' : 'Không' }}</td></tr>
                    <tr><th>Cancel</th><td>{{ $service->cancel ? 'Có' : 'Không' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const iconInput = document.getElementById('iconInput');
    const colorInput = document.getElementById('colorInput');
    const iconPreview = document.getElementById('iconPreview');
    const iconPreviewLarge = document.getElementById('iconPreviewLarge');
    const swatches = document.querySelectorAll('.color-swatch');
    
    // Icon input change
    iconInput.addEventListener('input', function() {
        const iconClass = this.value || 'fas fa-box';
        iconPreview.innerHTML = `<i class="${iconClass}"></i>`;
        iconPreviewLarge.innerHTML = `<i class="${iconClass} fa-lg"></i>`;
    });
    
    // Color swatch click
    swatches.forEach(swatch => {
        swatch.addEventListener('click', function() {
            const color = this.dataset.color;
            colorInput.value = color;
            iconPreviewLarge.style.color = color;
            
            // Update selected state
            swatches.forEach(s => {
                s.style.border = '2px solid #ddd';
                if (s.dataset.color === '#ffffff') s.style.borderColor = '#ccc';
            });
            this.style.border = '2px solid #000';
        });
    });
});
</script>
@endsection
