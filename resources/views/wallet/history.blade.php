@extends('layouts.app')

@section('title', 'Lịch sử giao dịch')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="title is-3">
            <i class="fas fa-history has-text-info"></i> Lịch sử giao dịch
        </h1>
        
        <!-- Type Tabs -->
        <div class="tabs is-boxed mb-4">
            <ul>
                <li class="{{ !$type ? 'is-active' : '' }}">
                    <a href="{{ route('wallet.history') }}">
                        Tất cả <span class="tag is-light ml-2">{{ $typeCounts['all'] }}</span>
                    </a>
                </li>
                <li class="{{ $type == 'deposit' ? 'is-active' : '' }}">
                    <a href="{{ route('wallet.history', ['type' => 'deposit']) }}">
                        Nạp tiền <span class="tag is-success ml-2">{{ $typeCounts['deposit'] }}</span>
                    </a>
                </li>
                <li class="{{ $type == 'order' ? 'is-active' : '' }}">
                    <a href="{{ route('wallet.history', ['type' => 'order']) }}">
                        Đặt hàng <span class="tag is-primary ml-2">{{ $typeCounts['order'] }}</span>
                    </a>
                </li>
                <li class="{{ $type == 'refund' ? 'is-active' : '' }}">
                    <a href="{{ route('wallet.history', ['type' => 'refund']) }}">
                        Hoàn tiền <span class="tag is-info ml-2">{{ $typeCounts['refund'] }}</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="card">
            <div class="card-content">
                @if($transactions->count() > 0)
                    <div class="table-container">
                        <table class="table is-fullwidth is-hoverable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Loại</th>
                                    <th>Mô tả</th>
                                    <th>Số tiền</th>
                                    <th>Số dư sau</th>
                                    <th>Ngày</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $trans)
                                    <tr>
                                        <td>#{{ $trans->id }}</td>
                                        <td>
                                            <span class="tag is-{{ $trans->type_color }}">
                                                {{ $trans->type_display }}
                                            </span>
                                        </td>
                                        <td>{{ $trans->description }}</td>
                                        <td class="has-text-{{ $trans->amount >= 0 ? 'success' : 'danger' }} has-text-weight-bold">
                                            {{ $trans->amount >= 0 ? '+' : '' }}{{ number_format($trans->amount, 0, ',', '.') }}đ
                                        </td>
                                        <td>{{ number_format($trans->balance_after, 0, ',', '.') }}đ</td>
                                        <td>{{ $trans->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{ $transactions->links() }}
                @else
                    <div class="has-text-centered py-6">
                        <span class="icon is-large has-text-grey-light">
                            <i class="fas fa-receipt fa-3x"></i>
                        </span>
                        <p class="has-text-grey mt-3">Chưa có giao dịch nào</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
