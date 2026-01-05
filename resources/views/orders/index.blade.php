@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<section class="section">
    <div class="container">
        <div class="level">
            <div class="level-left">
                <div>
                    <h1 class="title is-3">
                        <i class="fas fa-shopping-bag has-text-primary"></i> Đơn hàng của tôi
                    </h1>
                </div>
            </div>
            <div class="level-right">
                <a href="{{ route('orders.create') }}" class="button is-primary">
                    <span class="icon"><i class="fas fa-plus"></i></span>
                    <span>Đặt hàng mới</span>
                </a>
            </div>
        </div>
        
        <!-- Status Tabs -->
        <div class="tabs is-boxed mb-4">
            <ul>
                <li class="{{ !$status ? 'is-active' : '' }}">
                    <a href="{{ route('orders.index') }}">
                        Tất cả <span class="tag is-light ml-2">{{ $statusCounts['all'] }}</span>
                    </a>
                </li>
                <li class="{{ $status == 'pending' ? 'is-active' : '' }}">
                    <a href="{{ route('orders.index', ['status' => 'pending']) }}">
                        Chờ xử lý <span class="tag is-warning ml-2">{{ $statusCounts['pending'] }}</span>
                    </a>
                </li>
                <li class="{{ $status == 'processing' ? 'is-active' : '' }}">
                    <a href="{{ route('orders.index', ['status' => 'processing']) }}">
                        Đang xử lý <span class="tag is-info ml-2">{{ $statusCounts['processing'] }}</span>
                    </a>
                </li>
                <li class="{{ $status == 'completed' ? 'is-active' : '' }}">
                    <a href="{{ route('orders.index', ['status' => 'completed']) }}">
                        Hoàn thành <span class="tag is-success ml-2">{{ $statusCounts['completed'] }}</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Orders Table -->
        <div class="card">
            <div class="card-content">
                @if($orders->count() > 0)
                    <div class="table-container">
                        <table class="table is-fullwidth is-hoverable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Dịch vụ</th>
                                    <th>Link</th>
                                    <th>Số lượng</th>
                                    <th>Giá</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}" class="has-text-weight-semibold">
                                                #{{ $order->id }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="tag is-light is-small mb-1">{{ $order->service->category->name ?? 'N/A' }}</span><br>
                                            {{ Str::limit($order->service->name ?? 'N/A', 35) }}
                                        </td>
                                        <td>
                                            <a href="{{ $order->link }}" target="_blank" class="has-text-info" title="{{ $order->link }}">
                                                {{ Str::limit($order->link, 25) }}
                                                <i class="fas fa-external-link-alt fa-xs ml-1"></i>
                                            </a>
                                        </td>
                                        <td>{{ number_format($order->quantity) }}</td>
                                        <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                        <td>
                                            <span class="tag is-{{ $order->status_color }}">
                                                {{ $order->status_display }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}" class="button is-small is-light">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $orders->links() }}
                @else
                    <div class="has-text-centered py-6">
                        <span class="icon is-large has-text-grey-light">
                            <i class="fas fa-inbox fa-3x"></i>
                        </span>
                        <p class="has-text-grey mt-3">Không có đơn hàng nào</p>
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
