@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="level">
    <div class="level-left"><h2 class="title is-4">Người dùng</h2></div>
</div>

<div class="card mb-4">
    <div class="card-content py-3">
        <form action="{{ route('admin.users.index') }}" method="GET" class="columns is-vcentered">
            <div class="column is-4">
                <input class="input" type="text" name="search" placeholder="Tìm theo tên, email, SĐT..." value="{{ $search }}">
            </div>
            <div class="column is-2">
                <div class="select is-fullwidth">
                    <select name="role">
                        <option value="">Tất cả</option>
                        <option value="user" {{ $role == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
            </div>
            <div class="column"><button type="submit" class="button is-primary"><i class="fas fa-search mr-1"></i> Tìm</button></div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-content" style="padding: 0;">
        <table class="table is-fullwidth is-hoverable mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>SĐT</th>
                    <th>Số dư</th>
                    <th>Đơn hàng</th>
                    <th>Role</th>
                    <th>Trạng thái</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?: '-' }}</td>
                        <td class="has-text-success">{{ number_format($user->balance, 0, ',', '.') }}đ</td>
                        <td>{{ $user->orders_count }}</td>
                        <td><span class="tag is-{{ $user->role == 'admin' ? 'danger' : 'info' }}">{{ $user->role }}</span></td>
                        <td>
                            @if($user->is_active)<span class="tag is-success">Active</span>@else<span class="tag is-light">Locked</span>@endif
                        </td>
                        <td>
                            <div class="buttons are-small">
                                <a href="{{ route('admin.users.show', $user) }}" class="button is-light"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="button is-info is-light"><i class="fas fa-edit"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="has-text-centered has-text-grey py-5">Không có người dùng</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $users->links() }}
@endsection
