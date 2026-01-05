@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Stats -->
<div class="columns is-multiline mb-5">
    <div class="column is-3">
        <div class="stat-box">
            <div class="is-flex is-justify-content-space-between is-align-items-center">
                <div>
                    <div class="value">{{ number_format($stats['total_users']) }}</div>
                    <div class="label">Người dùng</div>
                </div>
                <div class="icon-wrapper" style="background: rgba(99, 102, 241, 0.1);">
                    <i class="fas fa-users has-text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="column is-3">
        <div class="stat-box">
            <div class="is-flex is-justify-content-space-between is-align-items-center">
                <div>
                    <div class="value">{{ number_format($stats['total_orders']) }}</div>
                    <div class="label">Tổng đơn hàng</div>
                </div>
                <div class="icon-wrapper" style="background: rgba(16, 185, 129, 0.1);">
                    <i class="fas fa-shopping-bag has-text-success"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="column is-3">
        <div class="stat-box">
            <div class="is-flex is-justify-content-space-between is-align-items-center">
                <div>
                    <div class="value">{{ number_format($stats['today_orders']) }}</div>
                    <div class="label">Đơn hôm nay</div>
                </div>
                <div class="icon-wrapper" style="background: rgba(245, 158, 11, 0.1);">
                    <i class="fas fa-calendar-day has-text-warning"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="column is-3">
        <div class="stat-box">
            <div class="is-flex is-justify-content-space-between is-align-items-center">
                <div>
                    <div class="value">{{ number_format($stats['total_revenue'], 0, ',', '.') }}đ</div>
                    <div class="label">Doanh thu</div>
                </div>
                <div class="icon-wrapper" style="background: rgba(239, 68, 68, 0.1);">
                    <i class="fas fa-dollar-sign has-text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="columns">
    <!-- Recent Orders -->
    <div class="column is-8">
        <div class="card">
            <div class="card-header">
                <p class="card-header-title">
                    <i class="fas fa-shopping-bag mr-2"></i> Đơn hàng gần đây
                </p>
                <a href="{{ route('admin.orders.index') }}" class="card-header-icon">
                    Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="card-content" style="padding: 0;">
                <table class="table is-fullwidth is-hoverable mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Dịch vụ</th>
                            <th>Giá</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}">#{{ $order->id }}</a></td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td>{{ Str::limit($order->service->name ?? 'N/A', 30) }}</td>
                                <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                <td>
                                    <span class="tag is-{{ $order->status_color }} is-small">
                                        {{ $order->status_display }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="has-text-centered has-text-grey">Chưa có đơn hàng</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Recent Deposits -->
    <div class="column is-4">
        <div class="card">
            <div class="card-header">
                <p class="card-header-title">
                    <i class="fas fa-coins mr-2"></i> Nạp tiền gần đây
                </p>
            </div>
            <div class="card-content">
                @forelse($recentTransactions as $trans)
                    <div class="level is-mobile mb-3">
                        <div class="level-left">
                            <div>
                                <p class="has-text-weight-semibold">{{ $trans->user->name ?? 'N/A' }}</p>
                                <p class="is-size-7 has-text-grey">{{ $trans->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="level-right">
                            <span class="has-text-success has-text-weight-bold">
                                +{{ number_format($trans->amount, 0, ',', '.') }}đ
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="has-text-grey has-text-centered">Chưa có giao dịch</p>
                @endforelse
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-4">
            <div class="card-header">
                <p class="card-header-title">
                    <i class="fas fa-bolt mr-2"></i> Thao tác nhanh
                </p>
            </div>
            <div class="card-content">
                <a href="{{ route('admin.services.import') }}" class="button is-primary is-fullwidth mb-2">
                    <i class="fas fa-download mr-2"></i> Import dịch vụ
                </a>
                <a href="{{ route('admin.services.syncPrices') }}" class="button is-info is-light is-fullwidth mb-2" onclick="event.preventDefault(); document.getElementById('sync-form').submit();">
                    <i class="fas fa-sync mr-2"></i> Đồng bộ giá
                </a>
                <form id="sync-form" action="{{ route('admin.services.syncPrices') }}" method="POST" style="display: none;">@csrf</form>
                <a href="{{ route('admin.orders.checkStatus') }}" class="button is-warning is-light is-fullwidth" onclick="event.preventDefault(); document.getElementById('check-form').submit();">
                    <i class="fas fa-refresh mr-2"></i> Cập nhật trạng thái đơn
                </a>
                <form id="check-form" action="{{ route('admin.orders.checkStatus') }}" method="POST" style="display: none;">@csrf</form>
            </div>
        </div>
    </div>
</div>
@endsection
