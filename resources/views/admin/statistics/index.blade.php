@extends('layouts.admin')

@section('title', 'Thống kê')

@section('content')
<div class="level">
    <div class="level-left">
        <h2 class="title is-4">
            <i class="fas fa-chart-line mr-2"></i>Thống kê
        </h2>
    </div>
    <div class="level-right">
        <form method="GET" class="field has-addons">
            <div class="control">
                <div class="select">
                    <select name="period" onchange="this.form.submit()">
                        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Theo tháng</option>
                        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Theo năm</option>
                        <option value="all" {{ $period == 'all' ? 'selected' : '' }}>Toàn thời gian</option>
                    </select>
                </div>
            </div>
            @if($period == 'month')
            <div class="control">
                <div class="select">
                    <select name="month" onchange="this.form.submit()">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            @endif
            @if($period != 'all')
            <div class="control">
                <div class="select">
                    <select name="year" onchange="this.form.submit()">
                        @for($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

<p class="has-text-grey mb-4">
    <i class="fas fa-calendar mr-1"></i>
    Khoảng thời gian: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
</p>

<!-- Stats Cards -->
<div class="columns is-multiline">
    <div class="column is-3">
        <div class="card stat-card">
            <div class="card-content">
                <div class="level is-mobile">
                    <div class="level-left">
                        <div>
                            <p class="heading">Doanh thu</p>
                            <p class="title is-4">{{ number_format($totalRevenue, 0, ',', '.') }}đ</p>
                            @if($revenueChange != 0)
                            <p class="is-size-7 {{ $revenueChange > 0 ? 'has-text-success' : 'has-text-danger' }}">
                                <i class="fas fa-{{ $revenueChange > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ number_format(abs($revenueChange), 1) }}% so với kỳ trước
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="level-right">
                        <span class="icon is-large has-text-primary">
                            <i class="fas fa-coins fa-2x"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="column is-3">
        <div class="card stat-card">
            <div class="card-content">
                <div class="level is-mobile">
                    <div class="level-left">
                        <div>
                            <p class="heading">Lợi nhuận</p>
                            <p class="title is-4 {{ $totalProfit >= 0 ? 'has-text-success' : 'has-text-danger' }}">
                                {{ number_format($totalProfit, 0, ',', '.') }}đ
                            </p>
                            <p class="is-size-7 has-text-grey">
                                Chi phí: {{ number_format($totalApiCost, 0, ',', '.') }}đ
                            </p>
                        </div>
                    </div>
                    <div class="level-right">
                        <span class="icon is-large has-text-success">
                            <i class="fas fa-chart-pie fa-2x"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="column is-3">
        <div class="card stat-card">
            <div class="card-content">
                <div class="level is-mobile">
                    <div class="level-left">
                        <div>
                            <p class="heading">Tổng nạp tiền</p>
                            <p class="title is-4">{{ number_format($totalDeposits, 0, ',', '.') }}đ</p>
                            @if($depositChange != 0)
                            <p class="is-size-7 {{ $depositChange > 0 ? 'has-text-success' : 'has-text-danger' }}">
                                <i class="fas fa-{{ $depositChange > 0 ? 'arrow-up' : 'arrow-down' }}"></i>
                                {{ number_format(abs($depositChange), 1) }}% so với kỳ trước
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="level-right">
                        <span class="icon is-large has-text-info">
                            <i class="fas fa-wallet fa-2x"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="column is-3">
        <div class="card stat-card">
            <div class="card-content">
                <div class="level is-mobile">
                    <div class="level-left">
                        <div>
                            <p class="heading">Đơn hàng</p>
                            <p class="title is-4">{{ number_format($totalOrders) }}</p>
                            <p class="is-size-7 has-text-success">
                                <i class="fas fa-check mr-1"></i>Hoàn thành: {{ number_format($completedOrders) }}
                            </p>
                            @if($canceledOrders > 0 || $refundedOrders > 0 || $partialOrders > 0)
                            <p class="is-size-7 has-text-danger">
                                <i class="fas fa-times mr-1"></i>Hủy: {{ $canceledOrders }} | Hoàn tiền: {{ $refundedOrders }} | Một phần: {{ $partialOrders }}
                            </p>
                            @endif
                        </div>
                    </div>
                    <div class="level-right">
                        <span class="icon is-large has-text-warning">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="column is-3">
        <div class="card stat-card">
            <div class="card-content">
                <div class="level is-mobile">
                    <div class="level-left">
                        <div>
                            <p class="heading">User mới</p>
                            <p class="title is-4">{{ number_format($newUsers) }}</p>
                            <p class="is-size-7 has-text-grey">
                                Tổng: {{ number_format($totalUsers) }} users
                            </p>
                        </div>
                    </div>
                    <div class="level-right">
                        <span class="icon is-large has-text-link">
                            <i class="fas fa-users fa-2x"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="column is-3">
        <div class="card stat-card">
            <div class="card-content">
                <div class="level is-mobile">
                    <div class="level-left">
                        <div>
                            <p class="heading">Lượt nạp tiền</p>
                            <p class="title is-4">{{ number_format($depositCount) }}</p>
                            <p class="is-size-7 has-text-grey">
                                TB: {{ $depositCount > 0 ? number_format($totalDeposits / $depositCount, 0, ',', '.') . 'đ' : '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="level-right">
                        <span class="icon is-large has-text-danger">
                            <i class="fas fa-receipt fa-2x"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="column is-3">
        <div class="card stat-card">
            <div class="card-content">
                <div class="level is-mobile">
                    <div class="level-left">
                        <div>
                            <p class="heading">Lượt truy cập</p>
                            <p class="title is-4">{{ number_format($pageViews) }}</p>
                            <p class="is-size-7 has-text-grey">
                                Unique: {{ number_format($uniqueVisitors) }} IP
                            </p>
                        </div>
                    </div>
                    <div class="level-right">
                        <span class="icon is-large has-text-purple">
                            <i class="fas fa-eye fa-2x"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="columns chart-row">
    <div class="column is-8">
        <div class="card">
            <div class="card-header">
                <p class="card-header-title">
                    <i class="fas fa-chart-area mr-2"></i>Biểu đồ doanh thu & lợi nhuận
                </p>
            </div>
            <div class="card-content" style="padding: 1rem;">
                <canvas id="revenueChart" height="180"></canvas>
            </div>
        </div>
    </div>
    <div class="column is-4">
        <div class="card">
            <div class="card-header">
                <p class="card-header-title">
                    <i class="fas fa-wallet mr-2"></i>Biểu đồ nạp tiền
                </p>
            </div>
            <div class="card-content" style="padding: 1rem;">
                <canvas id="depositChart" height="180"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Top Lists -->
<div class="columns top-row is-multiline">
    <!-- Top Services -->
    <div class="column is-4">
        <div class="card">
            <div class="card-header">
                <p class="card-header-title">
                    <i class="fas fa-trophy mr-2 has-text-warning"></i>Top dịch vụ
                </p>
            </div>
            <div class="card-content" style="max-height: 400px; overflow-y: auto;">
                @forelse($topServices as $index => $item)
                <div class="level is-mobile py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="level-left" style="max-width: 60%;">
                        <span class="tag is-{{ $index < 3 ? 'warning' : 'light' }} mr-2">{{ $index + 1 }}</span>
                        <span class="is-size-7" title="{{ $item->service->name ?? 'N/A' }}">
                            {{ Str::limit($item->service->name ?? 'N/A', 25) }}
                        </span>
                    </div>
                    <div class="level-right has-text-right">
                        <div>
                            <p class="is-size-7 has-text-weight-bold">{{ number_format($item->revenue, 0, ',', '.') }}đ</p>
                            <p class="is-size-7 has-text-grey">{{ $item->order_count }} đơn</p>
                        </div>
                    </div>
                </div>
                @empty
                <p class="has-text-grey has-text-centered">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Top Depositors -->
    <div class="column is-4">
        <div class="card">
            <div class="card-header">
                <p class="card-header-title">
                    <i class="fas fa-medal mr-2 has-text-info"></i>Top nạp tiền
                </p>
            </div>
            <div class="card-content" style="max-height: 400px; overflow-y: auto;">
                @forelse($topDepositors as $index => $item)
                <div class="level is-mobile py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="level-left">
                        <span class="tag is-{{ $index < 3 ? 'info' : 'light' }} mr-2">{{ $index + 1 }}</span>
                        <div>
                            <p class="is-size-7 has-text-weight-bold">{{ $item->user->name ?? 'N/A' }}</p>
                            <p class="is-size-7 has-text-grey">{{ $item->user->email ?? '' }}</p>
                        </div>
                    </div>
                    <div class="level-right">
                        <p class="is-size-7 has-text-weight-bold has-text-success">{{ number_format($item->total_deposit, 0, ',', '.') }}đ</p>
                    </div>
                </div>
                @empty
                <p class="has-text-grey has-text-centered">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Top Spenders -->
    <div class="column is-4">
        <div class="card">
            <div class="card-header">
                <p class="card-header-title">
                    <i class="fas fa-star mr-2 has-text-danger"></i>Top mua hàng
                </p>
            </div>
            <div class="card-content" style="max-height: 400px; overflow-y: auto;">
                @forelse($topSpenders as $index => $item)
                <div class="level is-mobile py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="level-left">
                        <span class="tag is-{{ $index < 3 ? 'danger' : 'light' }} mr-2">{{ $index + 1 }}</span>
                        <div>
                            <p class="is-size-7 has-text-weight-bold">{{ $item->user->name ?? 'N/A' }}</p>
                            <p class="is-size-7 has-text-grey">{{ $item->order_count }} đơn</p>
                        </div>
                    </div>
                    <div class="level-right">
                        <p class="is-size-7 has-text-weight-bold has-text-primary">{{ number_format($item->total_spent, 0, ',', '.') }}đ</p>
                    </div>
                </div>
                @empty
                <p class="has-text-grey has-text-centered">Chưa có dữ liệu</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Equal height cards */
    .columns.is-multiline {
        display: flex;
        flex-wrap: wrap;
    }
    .columns.is-multiline > .column {
        display: flex;
    }
    .columns.is-multiline > .column > .card {
        width: 100%;
        display: flex;
        flex-direction: column;
    }
    .columns.is-multiline > .column > .card > .card-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    /* Stat cards */
    .stat-card {
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
        min-height: 120px;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    .stat-card .card-content {
        padding: 1.25rem;
    }
    .stat-card .heading {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }
    .stat-card .title.is-4 {
        font-size: 1.5rem !important;
        margin-bottom: 0.25rem !important;
    }
    .stat-card .level {
        margin-bottom: 0;
    }
    .stat-card .level-left {
        flex-shrink: 1;
    }
    .stat-card .level-right {
        flex-shrink: 0;
    }
    
    /* Chart cards equal height */
    .chart-row {
        display: flex;
        align-items: stretch;
    }
    .chart-row > .column > .card {
        height: 100%;
    }
    
    /* Top lists equal height */
    .top-row {
        display: flex;
        align-items: stretch;
    }
    .top-row > .column > .card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .top-row > .column > .card > .card-content {
        flex: 1;
        overflow-y: auto;
    }
    
    .border-bottom {
        border-bottom: 1px solid #f5f5f5;
    }
    
    /* Responsive */
    @media screen and (max-width: 768px) {
        .stat-card {
            min-height: auto;
        }
        .stat-card .title.is-4 {
            font-size: 1.25rem !important;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue & Profit Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [
                {
                    label: 'Doanh thu',
                    data: @json($chartData['revenue']),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Lợi nhuận',
                    data: @json($chartData['profit']),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + 'đ';
                        }
                    }
                }
            }
        }
    });

    // Deposit Chart
    const depositCtx = document.getElementById('depositChart').getContext('2d');
    new Chart(depositCtx, {
        type: 'bar',
        data: {
            labels: @json($chartData['labels']),
            datasets: [{
                label: 'Nạp tiền',
                data: @json($chartData['deposits']),
                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('vi-VN') + 'đ';
                        }
                    }
                }
            }
        }
    });
</script>
@endsection
