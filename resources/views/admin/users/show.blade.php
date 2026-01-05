@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')

@section('content')
<nav class="breadcrumb"><ul>
    <li><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
    <li class="is-active"><a href="#">{{ $user->name }}</a></li>
</ul></nav>

<div class="columns">
    <div class="column is-4">
        <div class="card">
            <div class="card-content has-text-centered">
                <span class="icon is-large has-text-primary" style="font-size: 4rem;">
                    <i class="fas fa-user-circle"></i>
                </span>
                <h3 class="title is-4 mt-3">{{ $user->name }}</h3>
                <p class="has-text-grey">{{ $user->email }}</p>
                <p>{{ $user->phone ?: '-' }}</p>
                <div class="mt-4">
                    <span class="tag is-{{ $user->role == 'admin' ? 'danger' : 'info' }} is-medium">{{ ucfirst($user->role) }}</span>
                    @if($user->is_active)<span class="tag is-success is-medium">Active</span>@else<span class="tag is-light is-medium">Locked</span>@endif
                </div>
            </div>
        </div>
        
        <!-- Adjust Balance -->
        <div class="card mt-4">
            <div class="card-header"><p class="card-header-title">Điều chỉnh số dư</p></div>
            <div class="card-content">
                <p class="is-size-4 has-text-weight-bold has-text-success mb-4">{{ number_format($user->balance, 0, ',', '.') }} VND</p>
                <form action="{{ route('admin.users.adjustBalance', $user) }}" method="POST">
                    @csrf
                    <div class="field">
                        <div class="control">
                            <input class="input" type="number" name="amount" placeholder="Số tiền" required>
                        </div>
                    </div>
                    <div class="field">
                        <div class="select is-fullwidth">
                            <select name="type">
                                <option value="deposit">Nạp tiền</option>
                                <option value="withdraw">Trừ tiền</option>
                                <option value="admin_adjust">Điều chỉnh</option>
                            </select>
                        </div>
                    </div>
                    <div class="field">
                        <input class="input" type="text" name="note" placeholder="Ghi chú (tùy chọn)">
                    </div>
                    <button type="submit" class="button is-primary is-fullwidth">Cập nhật số dư</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="column is-8">
        <!-- Stats -->
        <div class="columns mb-4">
            <div class="column"><div class="stat-box"><div class="value">{{ number_format($stats['total_orders']) }}</div><div class="label">Đơn hàng</div></div></div>
            <div class="column"><div class="stat-box"><div class="value">{{ number_format($stats['total_spent'], 0, ',', '.') }}đ</div><div class="label">Đã chi tiêu</div></div></div>
            <div class="column"><div class="stat-box"><div class="value">{{ number_format($stats['total_deposited'], 0, ',', '.') }}đ</div><div class="label">Đã nạp</div></div></div>
        </div>
        
        <!-- Recent Orders -->
        <div class="card mb-4">
            <div class="card-header"><p class="card-header-title">Đơn hàng gần đây</p></div>
            <div class="card-content" style="padding:0;">
                <table class="table is-fullwidth is-hoverable mb-0">
                    <thead><tr><th>ID</th><th>Dịch vụ</th><th>Giá</th><th>Trạng thái</th><th>Ngày</th></tr></thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a></td>
                                <td>{{ Str::limit($order->service->name ?? 'N/A', 30) }}</td>
                                <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                <td><span class="tag is-{{ $order->status_color }} is-small">{{ $order->status_display }}</span></td>
                                <td>{{ $order->created_at->format('d/m H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="has-text-centered has-text-grey">Chưa có đơn hàng</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <div class="card">
            <div class="card-header"><p class="card-header-title">Giao dịch gần đây</p></div>
            <div class="card-content" style="padding:0;">
                <table class="table is-fullwidth is-hoverable mb-0">
                    <thead><tr><th>ID</th><th>Loại</th><th>Mô tả</th><th>Số tiền</th><th>Ngày</th></tr></thead>
                    <tbody>
                        @forelse($recentTransactions as $trans)
                            <tr>
                                <td>#{{ $trans->id }}</td>
                                <td><span class="tag is-{{ $trans->type_color }} is-small">{{ $trans->type_display }}</span></td>
                                <td>{{ Str::limit($trans->description, 40) }}</td>
                                <td class="has-text-{{ $trans->amount >= 0 ? 'success' : 'danger' }}">{{ $trans->amount >= 0 ? '+' : '' }}{{ number_format($trans->amount, 0, ',', '.') }}đ</td>
                                <td>{{ $trans->created_at->format('d/m H:i') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="has-text-centered has-text-grey">Chưa có giao dịch</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
