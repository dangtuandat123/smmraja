@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('styles')
<style>
    /* Mobile responsive for order details */
    @media screen and (max-width: 768px) {
        .order-table th {
            width: 100px !important;
            font-size: 0.85rem;
        }
        .order-table td {
            word-break: break-word;
            font-size: 0.9rem;
        }
        .link-cell {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }
        .service-name {
            display: block;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .extra-data-pre {
            font-size: 0.75rem;
            overflow-x: auto;
        }
    }
</style>
@endsection

@section('content')
<section class="section">
    <div class="container">
        <nav class="breadcrumb" aria-label="breadcrumbs">
            <ul>
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('orders.index') }}">Đơn hàng</a></li>
                <li class="is-active"><a href="#">#{{ $order->id }}</a></li>
            </ul>
        </nav>
        
        <div class="columns">
            <div class="column is-8">
                <div class="card">
                    <div class="card-header">
                        <p class="card-header-title">
                            <i class="fas fa-info-circle mr-2"></i>
                            Chi tiết đơn hàng #{{ $order->id }}
                        </p>
                        <span class="card-header-icon">
                            <span class="tag is-{{ $order->status_color }} is-medium">
                                {{ $order->status_display }}
                            </span>
                        </span>
                    </div>
                    <div class="card-content">
                        <table class="table is-fullwidth order-table">
                            <tbody>
                                <tr>
                                    <th>Dịch vụ</th>
                                    <td>
                                        <span class="tag is-light">{{ $order->service->category->name ?? 'N/A' }}</span>
                                        <span class="service-name">{{ $order->service->name ?? 'N/A' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Link</th>
                                    <td>
                                        <a href="{{ $order->link }}" target="_blank" class="has-text-info link-cell" title="{{ $order->link }}">
                                            {{ $order->link }}
                                            <i class="fas fa-external-link-alt fa-xs ml-1"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Số lượng</th>
                                    <td>{{ number_format($order->quantity) }}</td>
                                </tr>
                                <tr>
                                    <th>Đơn giá</th>
                                    <td>{{ number_format($order->price_per_unit * 1000, 0, ',', '.') }} VND/1000</td>
                                </tr>
                                <tr>
                                    <th>Tổng tiền</th>
                                    <td class="has-text-weight-bold has-text-primary is-size-5">
                                        {{ number_format($order->total_price, 0, ',', '.') }} VND
                                    </td>
                                </tr>
                                @if($order->start_count !== null)
                                    <tr>
                                        <th>Số ban đầu</th>
                                        <td>{{ number_format($order->start_count) }}</td>
                                    </tr>
                                @endif
                                @if($order->remains !== null)
                                    <tr>
                                        <th>Còn lại</th>
                                        <td>{{ number_format($order->remains) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td>{{ $order->created_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Cập nhật</th>
                                    <td>{{ $order->updated_at->format('d/m/Y H:i:s') }}</td>
                                </tr>
                                @if($order->error_message)
                                    <tr>
                                        <th>Lỗi</th>
                                        <td class="has-text-danger">{{ $order->error_message }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        
                        @if($order->extra_data && count($order->extra_data) > 0)
                            <h5 class="title is-6 mt-5">Thông tin bổ sung</h5>
                            <div class="content">
                                <pre class="extra-data-pre" style="background: #f5f5f5; padding: 1rem; border-radius: 8px;">{{ json_encode($order->extra_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="column is-4">
                <!-- Actions -->
                <div class="card">
                    <div class="card-header">
                        <p class="card-header-title">
                            <i class="fas fa-cogs mr-2"></i> Hành động
                        </p>
                    </div>
                    <div class="card-content">
                        @if($order->canRefill())
                            <form action="{{ route('orders.refill', $order) }}" method="POST" class="mb-3">
                                @csrf
                                <button type="submit" class="button is-info is-fullwidth" 
                                        onclick="return confirm('Bạn có chắc muốn yêu cầu bảo hành đơn hàng này?')">
                                    <span class="icon"><i class="fas fa-shield-alt"></i></span>
                                    <span>Yêu cầu Bảo hành</span>
                                </button>
                            </form>
                        @endif
                        
                        @if($order->canCancel())
                            <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                @csrf
                                <button type="submit" class="button is-danger is-light is-fullwidth"
                                        onclick="return confirm('Bạn có chắc muốn hủy đơn hàng này?')">
                                    <span class="icon"><i class="fas fa-times"></i></span>
                                    <span>Hủy đơn hàng</span>
                                </button>
                            </form>
                        @endif
                        
                        @if(!$order->canRefill() && !$order->canCancel())
                            <p class="has-text-grey has-text-centered">
                                Không có hành động khả dụng
                            </p>
                        @endif
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="card mt-4">
                    <div class="card-content">
                        <a href="{{ route('orders.index') }}" class="button is-light is-fullwidth mb-2">
                            <span class="icon"><i class="fas fa-arrow-left"></i></span>
                            <span>Quay lại danh sách</span>
                        </a>
                        <a href="{{ route('orders.create') }}" class="button is-primary is-fullwidth">
                            <span class="icon"><i class="fas fa-plus"></i></span>
                            <span>Đặt hàng mới</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
