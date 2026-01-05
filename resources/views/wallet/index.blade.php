@extends('layouts.app')

@section('title', 'Nạp tiền')

@section('content')
<section class="section">
    <div class="container">
        <h1 class="title is-3">
            <i class="fas fa-coins has-text-warning"></i> Nạp tiền
        </h1>
        
        <div class="columns">
            <div class="column is-6">
                <div class="card">
                    <div class="card-header" style="background: var(--gradient);">
                        <p class="card-header-title has-text-white">
                            <i class="fas fa-qrcode mr-2"></i> Quét mã QR để thanh toán
                        </p>
                    </div>
                    <div class="card-content has-text-centered">
                        <div class="mb-4">
                            <img src="{{ $qrUrl }}" alt="VietQR Code" style="max-width: 280px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                        </div>
                        
                        <div class="notification is-info is-light" style="text-align: left;">
                            <p class="mb-2"><strong>Thông tin chuyển khoản:</strong></p>
                            <table class="table is-fullwidth is-borderless" style="background: transparent;">
                                <tr>
                                    <td width="40%">Ngân hàng:</td>
                                    <td class="has-text-weight-bold">ACB</td>
                                </tr>
                                <tr>
                                    <td>Số tài khoản:</td>
                                    <td class="has-text-weight-bold">{{ $accountNumber }}</td>
                                </tr>
                                <tr>
                                    <td>Chủ tài khoản:</td>
                                    <td class="has-text-weight-bold">{{ $accountName }}</td>
                                </tr>
                                <tr>
                                    <td>Nội dung CK:</td>
                                    <td>
                                        <span class="tag is-warning is-medium has-text-weight-bold" id="transferContent">
                                            {{ $transferContent }}
                                        </span>
                                        <button class="button is-small is-light ml-2" onclick="copyContent()">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="notification is-warning is-light">
                            <p><i class="fas fa-exclamation-triangle mr-2"></i> 
                            <strong>Lưu ý:</strong> Nhập đúng nội dung chuyển khoản để được cộng tiền tự động!</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="column is-6">
                <!-- Current Balance -->
                <div class="card mb-4">
                    <div class="card-content">
                        <div class="level is-mobile">
                            <div class="level-left">
                                <div>
                                    <p class="heading">Số dư hiện tại</p>
                                    <p class="title is-2 has-text-primary">
                                        {{ number_format($user->balance, 0, ',', '.') }}
                                        <span class="is-size-5">VND</span>
                                    </p>
                                </div>
                            </div>
                            <div class="level-right">
                                <span class="icon is-large has-text-success">
                                    <i class="fas fa-wallet fa-2x"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Instructions -->
                <div class="card mb-4">
                    <div class="card-header">
                        <p class="card-header-title">
                            <i class="fas fa-info-circle mr-2"></i> Hướng dẫn nạp tiền
                        </p>
                    </div>
                    <div class="card-content">
                        <div class="content">
                            <ol>
                                <li>Mở ứng dụng ngân hàng trên điện thoại</li>
                                <li>Quét mã QR hoặc chuyển khoản thủ công</li>
                                <li><strong>Nhập đúng nội dung:</strong> <code>{{ $transferContent }}</code></li>
                                <li>Tiền sẽ được cộng tự động trong 1-5 phút</li>
                                <li>Liên hệ hỗ trợ nếu quá 10 phút chưa nhận được tiền</li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Transactions -->
                <div class="card">
                    <div class="card-header">
                        <p class="card-header-title">
                            <i class="fas fa-history mr-2"></i> Giao dịch gần đây
                        </p>
                        <a href="{{ route('wallet.history') }}" class="card-header-icon">
                            Xem tất cả <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                    <div class="card-content">
                        @if($recentTransactions->count() > 0)
                            @foreach($recentTransactions as $trans)
                                <div class="level is-mobile mb-3">
                                    <div class="level-left">
                                        <div>
                                            <p class="has-text-weight-semibold">{{ $trans->description }}</p>
                                            <p class="is-size-7 has-text-grey">{{ $trans->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="level-right">
                                        <span class="has-text-{{ $trans->amount >= 0 ? 'success' : 'danger' }} has-text-weight-bold">
                                            {{ $trans->amount >= 0 ? '+' : '' }}{{ number_format($trans->amount, 0, ',', '.') }}đ
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="has-text-grey has-text-centered">Chưa có giao dịch</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    function copyContent() {
        const content = document.getElementById('transferContent').textContent;
        navigator.clipboard.writeText(content).then(() => {
            alert('Đã copy nội dung chuyển khoản!');
        });
    }
</script>
@endsection
