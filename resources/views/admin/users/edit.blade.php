@extends('layouts.admin')

@section('title', 'Sửa người dùng')

@section('content')
<div class="columns is-centered">
    <div class="column is-6">
        <div class="card">
            <div class="card-header"><p class="card-header-title">Sửa người dùng: {{ $user->name }}</p></div>
            <div class="card-content">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf @method('PUT')
                    
                    <div class="field">
                        <label class="label">Họ tên *</label>
                        <input class="input" type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    
                    <div class="field">
                        <label class="label">Email *</label>
                        <input class="input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    
                    <div class="field">
                        <label class="label">Số điện thoại</label>
                        <input class="input" type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                    </div>
                    

                    <div class="field">
                        <label class="label">Mật khẩu mới (để trống nếu không đổi)</label>
                        <input class="input" type="password" name="password" placeholder="••••••••">
                    </div>
                    
                    <div class="field">
                        <label class="checkbox">
                            <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}>
                            Kích hoạt tài khoản
                        </label>
                    </div>
                    
                    <div class="field is-grouped">
                        <button type="submit" class="button is-primary"><i class="fas fa-save mr-2"></i> Lưu</button>
                        <a href="{{ route('admin.users.index') }}" class="button is-light">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
