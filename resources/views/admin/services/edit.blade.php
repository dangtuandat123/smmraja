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
