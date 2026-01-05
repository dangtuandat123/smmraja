@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<section class="section">
    <div class="container">
        <div class="level">
            <div class="level-left">
                <div>
                    <h1 class="title is-3">
                        <i class="fas fa-tachometer-alt has-text-primary"></i> 
                        Xin chào, {{ $user->name }}!
                    </h1>
                    <p class="subtitle has-text-grey">Quản lý tài khoản của bạn</p>
                </div>
            </div>
            <div class="level-right">
                <a href="{{ route('wallet.index') }}" class="button is-primary is-medium">
                    <span class="icon"><i class="fas fa-plus"></i></span>
                    <span>Nạp tiền</span>
                </a>
            </div>
        </div>
        
        <!-- Stats -->
        <div class="columns is-multiline mb-5">
            <div class="column is-3">
                <div class="stat-card">
                    <div class="icon is-primary">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="value">{{ number_format($user->balance, 0, ',', '.') }}đ</div>
                    <div class="label">Số dư hiện tại</div>
                </div>
            </div>
            <div class="column is-3">
                <div class="stat-card">
                    <div class="icon is-info">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="value">{{ number_format($stats['total_orders']) }}</div>
                    <div class="label">Tổng đơn hàng</div>
                </div>
            </div>
            <div class="column is-3">
                <div class="stat-card">
                    <div class="icon is-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="value">{{ number_format($stats['pending_orders']) }}</div>
                    <div class="label">Đang xử lý</div>
                </div>
            </div>
            <div class="column is-3">
                <div class="stat-card">
                    <div class="icon is-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="value">{{ number_format($stats['completed_orders']) }}</div>
                    <div class="label">Hoàn thành</div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="columns mb-5">
            <div class="column">
                <div class="card">
                    <div class="card-content">
                        <h4 class="title is-5 mb-4">
                            <i class="fas fa-bolt has-text-warning"></i> Thao tác nhanh
                        </h4>
                        <div class="buttons">
                            <a href="{{ route('orders.create') }}" class="button is-primary">
                                <span class="icon"><i class="fas fa-cart-plus"></i></span>
                                <span>Mua hàng</span>
                            </a>
                            <a href="{{ route('orders.index') }}" class="button is-info is-light">
                                <span class="icon"><i class="fas fa-list"></i></span>
                                <span>Xem đơn hàng</span>
                            </a>
                            <a href="{{ route('wallet.index') }}" class="button is-success is-light">
                                <span class="icon"><i class="fas fa-coins"></i></span>
                                <span>Nạp tiền</span>
                            </a>
                            <a href="{{ route('services.index') }}" class="button is-light">
                                <span class="icon"><i class="fas fa-th-list"></i></span>
                                <span>Xem dịch vụ</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="card">
            <div class="card-content">
                <div class="level">
                    <div class="level-left">
                        <h4 class="title is-5">
                            <i class="fas fa-history has-text-info"></i> Đơn hàng gần đây
                        </h4>
                    </div>
                    <div class="level-right">
                        <a href="{{ route('orders.index') }}" class="button is-small is-light">
                            Xem tất cả <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
                
                @if($recentOrders->count() > 0)
                    <div class="table-container">
                        <table class="table is-fullwidth is-hoverable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Dịch vụ</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}">#{{ $order->id }}</a>
                                        </td>
                                        <td>{{ Str::limit($order->service->name ?? 'N/A', 40) }}</td>
                                        <td>{{ number_format($order->quantity) }}</td>
                                        <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                        <td>
                                            <span class="tag is-{{ $order->status_color }}">
                                                {{ $order->status_display }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="has-text-centered py-5">
                        <span class="icon is-large has-text-grey-light">
                            <i class="fas fa-inbox fa-3x"></i>
                        </span>
                        <p class="has-text-grey mt-3">Bạn chưa có đơn hàng nào</p>
                        <a href="{{ route('orders.create') }}" class="button is-primary mt-4">
                            <span class="icon"><i class="fas fa-cart-plus"></i></span>
                            <span>Đặt hàng ngay</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
