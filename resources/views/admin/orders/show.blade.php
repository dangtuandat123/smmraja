@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<nav class="breadcrumb"><ul>
    <li><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
    <li class="is-active"><a href="#">#{{ $order->id }}</a></li>
</ul></nav>

<div class="columns">
    <div class="column is-8">
        <div class="card">
            <div class="card-header">
                <p class="card-header-title">Thông tin đơn hàng</p>
                <span class="card-header-icon">
                    <span class="tag is-{{ $order->status_color }} is-medium">{{ $order->status_display }}</span>
                </span>
            </div>
            <div class="card-content">
                <table class="table is-fullwidth">
                    <tr><th width="30%">Order ID</th><td>#{{ $order->id }}</td></tr>
                    <tr><th>API Order ID</th><td>{{ $order->api_order_id ?: '-' }}</td></tr>
                    <tr><th>Người dùng</th><td><a href="{{ route('admin.users.show', $order->user_id) }}">{{ $order->user->name ?? 'N/A' }} ({{ $order->user->email ?? '' }})</a></td></tr>
                    <tr><th>Dịch vụ</th><td>{{ $order->service->name ?? 'N/A' }}</td></tr>
                    <tr><th>Link</th><td><a href="{{ $order->link }}" target="_blank">{{ $order->link }}</a></td></tr>
                    <tr><th>Số lượng</th><td>{{ number_format($order->quantity) }}</td></tr>
                    <tr><th>Đơn giá (VND/1000)</th><td>{{ number_format($order->price_per_unit * 1000, 0, ',', '.') }}đ</td></tr>
                    <tr><th>Tổng tiền</th><td class="has-text-weight-bold has-text-primary is-size-5">{{ number_format($order->total_price, 0, ',', '.') }}đ</td></tr>
                    <tr><th>API Charge</th><td>${{ number_format($order->api_charge, 4) }}</td></tr>
                    <tr><th>Start Count</th><td>{{ $order->start_count ?? '-' }}</td></tr>
                    <tr><th>Remains</th><td>{{ $order->remains ?? '-' }}</td></tr>
                    <tr><th>Ngày tạo</th><td>{{ $order->created_at->format('d/m/Y H:i:s') }}</td></tr>
                    @if($order->error_message)
                        <tr><th>Lỗi</th><td class="has-text-danger">{{ $order->error_message }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
    
    <div class="column is-4">
        @if($apiStatus)
            <div class="card">
                <div class="card-header"><p class="card-header-title">Trạng thái API</p></div>
                <div class="card-content">
                    @if(isset($apiStatus['error']))
                        <p class="has-text-danger">{{ $apiStatus['error'] }}</p>
                    @else
                        <table class="table is-fullwidth is-bordered">
                            <tr><th>Status</th><td>{{ $apiStatus['status'] ?? '-' }}</td></tr>
                            <tr><th>Start Count</th><td>{{ $apiStatus['start_count'] ?? '-' }}</td></tr>
                            <tr><th>Remains</th><td>{{ $apiStatus['remains'] ?? '-' }}</td></tr>
                            <tr><th>Charge</th><td>${{ $apiStatus['charge'] ?? '-' }}</td></tr>
                        </table>
                    @endif
                </div>
            </div>
        @endif
        
        <div class="card mt-4">
            <div class="card-content">
                <a href="{{ route('admin.orders.index') }}" class="button is-light is-fullwidth">
                    <i class="fas fa-arrow-left mr-2"></i> Quay lại
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
