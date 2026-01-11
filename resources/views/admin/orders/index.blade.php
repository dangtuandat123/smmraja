@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="level">
    <div class="level-left">
        <h2 class="title is-4">Đơn hàng</h2>
    </div>
    <div class="level-right">
        <form action="{{ route('admin.orders.checkStatus') }}" method="POST">
            @csrf
            <button type="submit" class="button is-info">
                <i class="fas fa-sync mr-2"></i> Cập nhật trạng thái
            </button>
        </form>
    </div>
</div>

<!-- Filters -->
<div class="tabs is-boxed mb-4">
    <ul>
        <li class="{{ !$status ? 'is-active' : '' }}">
            <a href="{{ route('admin.orders.index') }}">Tất cả ({{ $statusCounts['all'] }})</a>
        </li>
        <li class="{{ $status == 'pending' ? 'is-active' : '' }}">
            <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}">Chờ ({{ $statusCounts['pending'] }})</a>
        </li>
        <li class="{{ $status == 'processing' ? 'is-active' : '' }}">
            <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}">Đang xử lý ({{ $statusCounts['processing'] }})</a>
        </li>
        <li class="{{ $status == 'completed' ? 'is-active' : '' }}">
            <a href="{{ route('admin.orders.index', ['status' => 'completed']) }}">Hoàn thành ({{ $statusCounts['completed'] }})</a>
        </li>
        <li class="{{ $status == 'partial' ? 'is-active' : '' }}">
            <a href="{{ route('admin.orders.index', ['status' => 'partial']) }}">Một phần ({{ $statusCounts['partial'] }})</a>
        </li>
        <li class="{{ $status == 'canceled' ? 'is-active' : '' }}">
            <a href="{{ route('admin.orders.index', ['status' => 'canceled']) }}">Đã hủy ({{ $statusCounts['canceled'] }})</a>
        </li>
        <li class="{{ $status == 'refunded' ? 'is-active' : '' }}">
            <a href="{{ route('admin.orders.index', ['status' => 'refunded']) }}">Hoàn tiền ({{ $statusCounts['refunded'] }})</a>
        </li>
        <li class="{{ $status == 'error' ? 'is-active' : '' }}">
            <a href="{{ route('admin.orders.index', ['status' => 'error']) }}">Lỗi ({{ $statusCounts['error'] }})</a>
        </li>
    </ul>
</div>

<!-- Search -->
<div class="card mb-4">
    <div class="card-content py-3">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="columns is-vcentered">
            <div class="column is-4">
                <input class="input" type="text" name="search" placeholder="Tìm theo Order ID, API ID, Link..." value="{{ $search }}">
            </div>
            <div class="column">
                <button type="submit" class="button is-primary"><i class="fas fa-search mr-1"></i> Tìm</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-content" style="padding: 0; overflow-x: auto;">
        <style>
            .admin-orders-table th, .admin-orders-table td { white-space: nowrap; padding: 0.5rem 0.75rem; font-size: 0.875rem; }
            .admin-orders-table th:nth-child(1) { width: 60px; } /* ID */
            .admin-orders-table th:nth-child(2) { width: 80px; } /* API ID */
            .admin-orders-table th:nth-child(3) { width: 100px; } /* User */
            .admin-orders-table th:nth-child(4) { min-width: 150px; } /* Dịch vụ */
            .admin-orders-table th:nth-child(5) { width: 120px; } /* Link */
            .admin-orders-table th:nth-child(6), .admin-orders-table th:nth-child(7), .admin-orders-table th:nth-child(8) { width: 70px; text-align: right; } /* Qty, Start, Remains */
            .admin-orders-table td:nth-child(6), .admin-orders-table td:nth-child(7), .admin-orders-table td:nth-child(8) { text-align: right; }
            .admin-orders-table th:nth-child(9), .admin-orders-table th:nth-child(10), .admin-orders-table th:nth-child(11) { width: 90px; text-align: right; } /* Giá */
            .admin-orders-table td:nth-child(9), .admin-orders-table td:nth-child(10), .admin-orders-table td:nth-child(11) { text-align: right; }
        </style>
        <table class="table is-fullwidth is-hoverable is-striped mb-0 admin-orders-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>API ID</th>
                    <th>User</th>
                    <th>Dịch vụ</th>
                    <th>Link</th>
                    <th>Qty</th>
                    <th>Start</th>
                    <th>Remains</th>
                    <th>Giá bán</th>
                    <th>Giá gốc</th>
                    <th class="has-text-success">Lợi nhuận</th>
                    <th>Status</th>
                    <th>Actions</th>
                    <th>Ngày</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->api_order_id ?: '-' }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $order->user_id) }}">{{ $order->user->name ?? 'N/A' }}</a>
                        </td>
                        <td>{{ Str::limit($order->service->name ?? 'N/A', 25) }}</td>
                        <td>
                            <a href="{{ $order->link }}" target="_blank" title="{{ $order->link }}">
                                {{ Str::limit($order->link, 20) }} <i class="fas fa-external-link-alt fa-xs"></i>
                            </a>
                        </td>
                        <td>{{ number_format($order->quantity) }}</td>
                        <td>{{ $order->start_count !== null ? number_format($order->start_count) : '-' }}</td>
                        <td>{{ $order->remains !== null ? number_format($order->remains) : '-' }}</td>
                        <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                        @php
                            // Giá gốc API (đã quy đổi VND)
                            $apiCostVnd = $order->api_charge ? round($order->api_charge * \App\Services\ExchangeRateService::getRate()) : 0;
                            $profit = $order->total_price - $apiCostVnd;
                        @endphp
                        <td class="has-text-grey">{{ $apiCostVnd ? number_format($apiCostVnd, 0, ',', '.') . 'đ' : '-' }}</td>
                        <td class="{{ $profit > 0 ? 'has-text-success has-text-weight-bold' : ($profit < 0 ? 'has-text-danger' : '') }}">
                            {{ $apiCostVnd ? number_format($profit, 0, ',', '.') . 'đ' : '-' }}
                        </td>
                        <td><span class="tag is-{{ $order->status_color }}">{{ $order->status_display }}</span></td>
                        <td>
                            @if($order->refill_requested_at)
                                <span class="tag is-warning is-small" title="Yêu cầu bảo hành: {{ $order->refill_requested_at->format('d/m H:i') }}">
                                    <i class="fas fa-shield-alt mr-1"></i>Refill
                                </span>
                            @endif
                            @if($order->cancel_requested_at)
                                <span class="tag is-danger is-small" title="Yêu cầu hủy: {{ $order->cancel_requested_at->format('d/m H:i') }}">
                                    <i class="fas fa-times-circle mr-1"></i>Cancel
                                </span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('d/m H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="button is-small is-light">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="14" class="has-text-centered has-text-grey py-5">Không có đơn hàng nào</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{ $orders->links() }}
@endsection
