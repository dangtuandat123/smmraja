@extends('layouts.admin')

@section('title', 'Quản lý Hoàn tiền')

@section('content')
<div class="level">
    <div class="level-left">
        <h2 class="title is-4 mb-0">Quản lý Hoàn tiền</h2>
    </div>
    <div class="level-right">
        <div class="tags has-addons">
            <span class="tag is-warning is-medium">Chờ hoàn: {{ $counts['cancel_pending'] }}</span>
            <span class="tag is-info is-medium">Partial: {{ $counts['partial'] }}</span>
            <span class="tag is-dark is-medium">Đã hủy: {{ $counts['canceled'] }}</span>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="notification is-success is-light">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="notification is-danger is-light">{{ $errors->first() }}</div>
@endif

<div class="notification is-info is-light">
    <p><strong>Quy trình hoàn tiền:</strong></p>
    <ol>
        <li>Kiểm tra trạng thái đơn hàng trên <a href="https://www.smmraja.com" target="_blank">SMM Raja</a></li>
        <li>Nếu SMM Raja đã hoàn tiền cho bạn → Bấm "Hoàn tiền" cho user</li>
        <li>Nếu SMM Raja từ chối → Bấm "Từ chối"</li>
    </ol>
</div>

<div class="card">
    <div class="card-content" style="padding: 0;">
        <table class="table is-fullwidth is-striped is-hoverable mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Dịch vụ</th>
                    <th>API ID</th>
                    <th>Số tiền</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $order->user_id) }}">
                                {{ $order->user->name ?? 'N/A' }}
                            </a>
                        </td>
                        <td>{{ Str::limit($order->service->name ?? 'N/A', 30) }}</td>
                        <td>
                            <code>{{ $order->api_order_id }}</code>
                        </td>
                        <td>
                            <strong>{{ number_format($order->total_price, 0) }} VND</strong>
                            @if($order->remains)
                                <br><small class="has-text-grey">Remains: {{ $order->remains }}</small>
                            @endif
                        </td>
                        <td>
                            @if($order->status === 'cancel_pending')
                                <span class="tag is-warning">Chờ hoàn tiền</span>
                            @elseif($order->status === 'partial')
                                <span class="tag is-info">Partial</span>
                            @elseif($order->status === 'canceled')
                                <span class="tag is-dark">Đã hủy</span>
                            @else
                                <span class="tag">{{ $order->status_display }}</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('d/m H:i') }}</td>
                        <td>
                            <div class="buttons are-small">
                                @if(in_array($order->status, ['cancel_pending', 'partial', 'canceled']))
                                    {{-- Full refund button --}}
                                    <form method="POST" action="{{ route('admin.orders.approveRefund', $order) }}" 
                                          style="display: inline;" onsubmit="return confirm('Xác nhận hoàn {{ number_format($order->total_price, 0) }} VND cho đơn #{{ $order->id }}?')">
                                        @csrf
                                        <input type="hidden" name="refund_type" value="full">
                                        <button type="submit" class="button is-success is-small">
                                            <i class="fas fa-check mr-1"></i> Hoàn tiền
                                        </button>
                                    </form>
                                    
                                    @if($order->status === 'cancel_pending')
                                        {{-- Reject button --}}
                                        <form method="POST" action="{{ route('admin.orders.rejectRefund', $order) }}" 
                                              style="display: inline;" onsubmit="return confirm('Từ chối hoàn tiền cho đơn #{{ $order->id }}?')">
                                            @csrf
                                            <button type="submit" class="button is-danger is-small is-outlined">
                                                <i class="fas fa-times mr-1"></i> Từ chối
                                            </button>
                                        </form>
                                    @endif
                                @endif
                                
                                <a href="{{ route('admin.orders.show', $order) }}" class="button is-light is-small">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="has-text-centered has-text-grey py-5">
                            <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                            Không có đơn hàng nào cần hoàn tiền
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $orders->links() }}
</div>
@endsection
