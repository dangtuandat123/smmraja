@extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@section('content')
<div class="level">
    <div class="level-left">
        <h2 class="title is-4">Danh mục dịch vụ</h2>
    </div>
    <div class="level-right">
        <a href="{{ route('admin.categories.create') }}" class="button is-primary">
            <i class="fas fa-plus mr-2"></i> Thêm danh mục
        </a>
    </div>
</div>

<div class="card">
    <div class="card-content" style="padding: 0;">
        <table class="table is-fullwidth is-hoverable mb-0">
            <thead>
                <tr>
                    <th width="60">ID</th>
                    <th>Tên</th>
                    <th>Slug</th>
                    <th>Icon</th>
                    <th>Số dịch vụ</th>
                    <th>Thứ tự</th>
                    <th>Trạng thái</th>
                    <th width="120"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td><i class="fas {{ $category->icon ?? 'fa-folder' }}"></i></td>
                        <td>{{ $category->services_count }}</td>
                        <td>{{ $category->sort_order }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="tag is-success">Hoạt động</span>
                            @else
                                <span class="tag is-light">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <div class="buttons are-small">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="button is-info is-light">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" 
                                      onsubmit="return confirm('Xác nhận xóa danh mục này?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="button is-danger is-light">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="has-text-centered has-text-grey py-5">Chưa có danh mục nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{ $categories->links() }}
@endsection
