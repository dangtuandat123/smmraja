<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SMM Panel') - {{ config('app.name', 'SMM Panel') }}</title>
    
    <!-- Bulma CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #10b981;
            --accent: #f59e0b;
            --dark: #1f2937;
            --light: #f3f4f6;
            --gradient: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }
        
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        
        body {
            background: var(--light);
            min-height: 100vh;
        }
        
        .navbar {
            background: var(--gradient);
            box-shadow: 0 4px 20px rgba(99, 102, 241, 0.3);
        }
        
        .navbar-item, .navbar-link {
            color: white !important;
            font-weight: 500;
        }
        
        .navbar-item:hover, .navbar-link:hover {
            background: rgba(255,255,255,0.1) !important;
        }
        
        .navbar-burger span {
            background-color: white !important;
        }
        
        .button.is-primary {
            background: var(--gradient);
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .button.is-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }
        
        .card {
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            border: none;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }
        
        .hero-gradient {
            background: var(--gradient);
        }
        
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
        }
        
        .stat-card .icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }
        
        .stat-card .icon.is-primary { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
        .stat-card .icon.is-success { background: rgba(16, 185, 129, 0.1); color: var(--secondary); }
        .stat-card .icon.is-warning { background: rgba(245, 158, 11, 0.1); color: var(--accent); }
        .stat-card .icon.is-info { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        
        .stat-card .value {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark);
        }
        
        .stat-card .label {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .service-card {
            cursor: pointer;
            border: 2px solid transparent;
        }
        
        .service-card:hover, .service-card.is-selected {
            border-color: var(--primary);
        }
        
        .price-tag {
            background: var(--gradient);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .footer {
            background: var(--dark);
            color: white;
            padding: 3rem 1.5rem;
        }
        
        .tag.is-completed { background: #10b981; color: white; }
        .tag.is-processing { background: #3b82f6; color: white; }
        .tag.is-pending { background: #f59e0b; color: white; }
        .tag.is-error { background: #ef4444; color: white; }
        .tag.is-canceled { background: #6b7280; color: white; }
        
        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-in {
            animation: fadeInUp 0.6s ease-out forwards;
        }
        
        .balance-display {
            background: var(--gradient);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #a1a1a1; }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar is-spaced" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="{{ route('home') }}">
                    <strong style="font-size: 1.5rem;">
                        <i class="fas fa-bolt"></i> SMM Panel
                    </strong>
                </a>
                
                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navMenu">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>
            
            <div id="navMenu" class="navbar-menu">
                <div class="navbar-start">
                    <a class="navbar-item" href="{{ route('services.index') }}">
                        <i class="fas fa-list-ul mr-2"></i> Dịch vụ
                    </a>
                    @auth
                        <a class="navbar-item" href="{{ route('orders.create') }}">
                            <i class="fas fa-cart-plus mr-2"></i> Mua hàng
                        </a>
                    @endauth
                    <a class="navbar-item" href="{{ route('contact') }}">
                        <i class="fas fa-envelope mr-2"></i> Liên hệ
                    </a>
                </div>
                
                <div class="navbar-end">
                    @auth
                        <div class="navbar-item">
                            <span class="balance-display">
                                <i class="fas fa-wallet mr-1"></i>
                                {{ number_format(auth()->user()->balance, 0, ',', '.') }} VND
                            </span>
                        </div>
                        
                        <div class="navbar-item has-dropdown is-hoverable">
                            <a class="navbar-link">
                                <i class="fas fa-user-circle mr-1"></i>
                                {{ auth()->user()->name }}
                            </a>
                            
                            <div class="navbar-dropdown is-right">
                                <a class="navbar-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                                </a>
                                <a class="navbar-item" href="{{ route('orders.index') }}">
                                    <i class="fas fa-shopping-bag mr-2"></i> Đơn hàng
                                </a>
                                <a class="navbar-item" href="{{ route('wallet.index') }}">
                                    <i class="fas fa-coins mr-2"></i> Nạp tiền
                                </a>
                                <a class="navbar-item" href="{{ route('wallet.history') }}">
                                    <i class="fas fa-history mr-2"></i> Lịch sử GD
                                </a>
                                @if(auth()->user()->isAdmin())
                                    <hr class="navbar-divider">
                                    <a class="navbar-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-cog mr-2"></i> Quản trị
                                    </a>
                                @endif
                                <hr class="navbar-divider">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="navbar-item" style="width: 100%; border: none; background: none; cursor: pointer;">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="navbar-item">
                            <div class="buttons">
                                <a class="button is-light" href="{{ route('login') }}">
                                    <strong>Đăng nhập</strong>
                                </a>
                                <a class="button is-white is-outlined" href="{{ route('register') }}">
                                    Đăng ký
                                </a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    @if(session('success'))
        <div class="notification is-success is-light" style="margin: 0; border-radius: 0;">
            <button class="delete"></button>
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="notification is-danger is-light" style="margin: 0; border-radius: 0;">
            <button class="delete"></button>
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="notification is-danger is-light" style="margin: 0; border-radius: 0;">
            <button class="delete"></button>
            <ul style="margin-left: 1rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="columns">
                <div class="column is-4">
                    <h4 class="title is-5 has-text-white">
                        <i class="fas fa-bolt"></i> SMM Panel
                    </h4>
                    <p class="has-text-grey-light">
                        Dịch vụ SMM chất lượng cao với giá cả phải chăng. Tăng tương tác mạng xã hội nhanh chóng và hiệu quả.
                    </p>
                </div>
                <div class="column is-2">
                    <h6 class="title is-6 has-text-white">Liên kết</h6>
                    <ul style="list-style: none; margin: 0;">
                        <li><a href="{{ route('home') }}" class="has-text-grey-light">Trang chủ</a></li>
                        <li><a href="{{ route('services.index') }}" class="has-text-grey-light">Dịch vụ</a></li>
                        <li><a href="{{ route('contact') }}" class="has-text-grey-light">Liên hệ</a></li>
                    </ul>
                </div>
                <div class="column is-3">
                    <h6 class="title is-6 has-text-white">Hỗ trợ</h6>
                    <p class="has-text-grey-light">
                        <i class="fas fa-envelope mr-2"></i> support@smmpanel.vn<br>
                        <i class="fab fa-telegram mr-2"></i> @smmpanelvn
                    </p>
                </div>
                <div class="column is-3">
                    <h6 class="title is-6 has-text-white">Kết nối</h6>
                    <div class="buttons">
                        <a class="button is-small is-dark" href="#"><i class="fab fa-facebook-f"></i></a>
                        <a class="button is-small is-dark" href="#"><i class="fab fa-telegram"></i></a>
                        <a class="button is-small is-dark" href="#"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            <hr style="background: rgba(255,255,255,0.1);">
            <p class="has-text-centered has-text-grey-light">
                &copy; {{ date('Y') }} SMM Panel. All rights reserved.
            </p>
        </div>
    </footer>
    
    <script>
        // Navbar burger toggle
        document.addEventListener('DOMContentLoaded', () => {
            const burgers = document.querySelectorAll('.navbar-burger');
            burgers.forEach(burger => {
                burger.addEventListener('click', () => {
                    const target = document.getElementById(burger.dataset.target);
                    burger.classList.toggle('is-active');
                    target.classList.toggle('is-active');
                });
            });
            
            // Delete notification
            document.querySelectorAll('.notification .delete').forEach(del => {
                del.addEventListener('click', () => {
                    del.parentElement.remove();
                });
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>
