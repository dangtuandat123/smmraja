@extends('layouts.admin')

@section('title', 'Quản lý bài viết')

@section('content')
<div class="level">
    <div class="level-left">
        <h1 class="title">Quản lý bài viết</h1>
    </div>
    <div class="level-right">
        <a href="{{ route('admin.posts.create') }}" class="button is-primary">
            <span class="icon"><i class="fas fa-plus"></i></span>
            <span>Thêm bài viết</span>
        </a>
    </div>
</div>

@if(session('success'))
<div class="notification is-success is-light">
    <button class="delete"></button>
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-content">
        <div class="table-container">
            <table class="table is-fullwidth is-hoverable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Tác giả</th>
                        <th>Trạng thái</th>
                        <th>Lượt xem</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr>
                        <td>{{ $post->id }}</td>
                        <td>
                            <strong>{{ Str::limit($post->title, 50) }}</strong>
                            <br>
                            <small class="has-text-grey">/blog/{{ $post->slug }}</small>
                        </td>
                        <td>{{ $post->author->name ?? 'N/A' }}</td>
                        <td>
                            @if($post->status === 'published')
                                <span class="tag is-success">Đã đăng</span>
                            @else
                                <span class="tag is-warning">Nháp</span>
                            @endif
                        </td>
                        <td>{{ number_format($post->views) }}</td>
                        <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="buttons are-small">
                                @if($post->status === 'published')
                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="button is-info is-outlined" title="Xem">
                                    <span class="icon"><i class="fas fa-eye"></i></span>
                                </a>
                                @endif
                                <a href="{{ route('admin.posts.edit', $post) }}" class="button is-warning is-outlined" title="Sửa">
                                    <span class="icon"><i class="fas fa-edit"></i></span>
                                </a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa bài viết này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button is-danger is-outlined" title="Xóa">
                                        <span class="icon"><i class="fas fa-trash"></i></span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="has-text-centered has-text-grey">
                            Chưa có bài viết nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection
