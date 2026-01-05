@extends('layouts.admin')

@section('title', 'Quản lý dịch vụ')

@section('content')
<div class="level">
    <div class="level-left">
        <h2 class="title is-4">Dịch vụ</h2>
    </div>
    <div class="level-right">
        <form action="{{ route('admin.services.syncPrices') }}" method="POST" class="mr-2">
            @csrf
            <button type="submit" class="button is-info is-light">
                <i class="fas fa-sync mr-2"></i> Đồng bộ giá
            </button>
        </form>
        <a href="{{ route('admin.services.import') }}" class="button is-primary">
            <i class="fas fa-download mr-2"></i> Import từ API
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-content py-3">
        <form action="{{ route('admin.services.index') }}" method="GET" class="columns is-vcentered">
            <div class="column is-3">
                <div class="select is-fullwidth">
                    <select name="category">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="column is-4">
                <input class="input" type="text" name="search" placeholder="Tìm kiếm..." value="{{ $search }}">
            </div>
            <div class="column">
                <button type="submit" class="button is-primary">
                    <i class="fas fa-search mr-1"></i> Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-content" style="padding: 0;">
        <table class="table is-fullwidth is-hoverable is-striped mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>API ID</th>
                    <th>Tên</th>
                    <th>Danh mục</th>
                    <th>Giá gốc (USD)</th>
                    <th>Markup %</th>
                    <th>Giá bán (VND)</th>
                    <th>Trạng thái</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($services as $service)
                    <tr>
                        <td>{{ $service->id }}</td>
                        <td>{{ $service->api_service_id }}</td>
                        <td>
                            <strong>{{ Str::limit($service->name, 40) }}</strong><br>
                            <span class="is-size-7 has-text-grey">Min: {{ $service->min }} | Max: {{ $service->max }}</span>
                        </td>
                        <td>{{ $service->category->name ?? 'N/A' }}</td>
                        <td>${{ number_format($service->api_rate, 4) }}</td>
                        <td>{{ $service->markup_percent }}%</td>
                        <td><strong>{{ number_format($service->price_vnd, 0, ',', '.') }}đ</strong></td>
                        <td>
                            @if($service->is_active)
                                <span class="tag is-success is-small">ON</span>
                            @else
                                <span class="tag is-light is-small">OFF</span>
                            @endif
                        </td>
                        <td>
                            <div class="buttons are-small">
                                <a href="{{ route('admin.services.edit', $service) }}" class="button is-info is-light">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.services.destroy', $service) }}" method="POST" 
                                      onsubmit="return confirm('Xác nhận xóa?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="button is-danger is-light"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="has-text-centered has-text-grey py-5">Chưa có dịch vụ nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $services->links() }}
@endsection
