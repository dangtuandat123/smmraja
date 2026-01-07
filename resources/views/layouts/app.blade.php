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
        
        /* Fix navbar dropdown */
        .navbar-dropdown {
            background: white !important;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            border-top: 2px solid var(--primary);
        }
        
        .navbar-dropdown .navbar-item {
            color: #374151 !important;
            font-weight: 400;
        }
        
        .navbar-dropdown .navbar-item:hover {
            background: #f3f4f6 !important;
            color: var(--primary) !important;
        }
        
        .navbar-dropdown .navbar-item i {
            color: #6b7280;
            width: 18px;
        }
        
        .navbar-divider {
            background-color: #e5e7eb;
            margin: 0.25rem 0;
        }
        
        /* Fix navbar dropdown trigger when hoverable */
        .navbar-item.has-dropdown:hover .navbar-link,
        .navbar-item.has-dropdown.is-active .navbar-link {
            background: white !important;
            color: var(--primary) !important;
            border-radius: 6px;
        }
        
        .navbar-item.has-dropdown:hover .navbar-link::after {
            border-color: var(--primary) !important;
        }
        
        /* ========== NOTIFICATION STYLES ========== */
        .notification-bell {
            background: rgba(255,255,255,0.15);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            padding: 0;
            position: relative;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .notification-bell .icon {
            margin: 0 !important;
        }
        
        .notification-bell:hover {
            background: rgba(255,255,255,0.25);
            transform: scale(1.05);
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            font-size: 0.65rem;
            font-weight: 600;
            min-width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .notification-menu {
            width: 320px;
            max-width: 90vw;
        }
        
        .notification-menu .dropdown-content {
            padding: 0;
            max-height: 400px;
            overflow: hidden;
        }
        
        .notification-header {
            padding: 0.75rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f9fafb;
        }
        
        .notification-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .notification-item {
            display: flex;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            cursor: pointer;
            transition: background 0.2s;
        }
        
        .notification-item:hover {
            background: #f9fafb;
        }
        
        .notification-item.unread {
            background: #eff6ff;
        }
        
        .notification-item .icon-wrap {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            flex-shrink: 0;
        }
        
        .notification-item .icon-wrap.is-success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
        .notification-item .icon-wrap.is-info { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
        .notification-item .icon-wrap.is-warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        .notification-item .icon-wrap.is-danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
        .notification-item .icon-wrap.is-primary { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
        
        .notification-item .content {
            flex: 1;
            min-width: 0;
        }
        
        .notification-item .title-text {
            font-weight: 600;
            font-size: 0.85rem;
            color: #1f2937;
            margin-bottom: 2px;
        }
        
        .notification-item .message-text {
            font-size: 0.8rem;
            color: #6b7280;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .notification-item .time-text {
            font-size: 0.7rem;
            color: #9ca3af;
            margin-top: 2px;
        }
        
        .notification-empty {
            padding: 2rem 1rem;
            text-align: center;
            color: #9ca3af;
        }
        
        /* ========== RESPONSIVE MOBILE ========== */
        @media screen and (max-width: 768px) {
            /* Navbar mobile */
            .navbar-menu {
                background: var(--gradient);
                padding: 1rem;
                border-radius: 0 0 12px 12px;
                box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
            }
            
            .navbar-menu.is-active {
                display: block;
            }
            
            .navbar-item, .navbar-link {
                padding: 0.75rem 1rem;
                border-radius: 8px;
            }
            
            .navbar-dropdown {
                background: white !important;
                border: none !important;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
                padding: 0.5rem;
                border-radius: 8px;
                margin-top: 0.5rem;
            }
            
            .navbar-dropdown .navbar-item {
                color: #374151 !important;
                padding: 0.6rem 1rem;
                border-radius: 6px;
            }
            
            .navbar-dropdown .navbar-item:hover {
                background: #f3f4f6 !important;
                color: var(--primary) !important;
            }
            
            .navbar-dropdown .navbar-item i {
                color: #6b7280;
            }
            
            .navbar-divider {
                background: #e5e7eb;
                margin: 0.25rem 0;
            }
            
            .navbar-item.has-dropdown:hover .navbar-link,
            .navbar-item.has-dropdown.is-active .navbar-link {
                background: rgba(255,255,255,0.15) !important;
                color: white !important;
            }
            
            /* Hero section */
            .hero-body {
                padding: 2rem 1rem;
            }
            
            .hero .title.is-1 {
                font-size: 2rem !important;
            }
            
            .hero .subtitle {
                font-size: 1rem !important;
            }
            
            /* Section padding */
            .section {
                padding: 2rem 1rem;
            }
            
            /* Cards */
            .card {
                border-radius: 12px;
                margin-bottom: 1rem;
            }
            
            .card-content {
                padding: 1rem;
            }
            
            /* Stat cards */
            .stat-card {
                padding: 1rem;
                margin-bottom: 0.75rem;
            }
            
            .stat-card .value {
                font-size: 1.5rem;
            }
            
            .stat-card .icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }
            
            /* Tables responsive */
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table {
                font-size: 0.85rem;
            }
            
            .table th, .table td {
                padding: 0.5rem;
                white-space: nowrap;
            }
            
            /* Buttons */
            .buttons .button {
                margin-bottom: 0.5rem;
            }
            
            .button.is-fullwidth-mobile {
                width: 100%;
            }
            
            /* Forms */
            .columns.is-multiline .column {
                padding: 0.5rem;
            }
            
            .field {
                margin-bottom: 1rem;
            }
            
            /* Balance display */
            .balance-display {
                font-size: 0.85rem;
                padding: 0.4rem 0.75rem;
            }
            
            /* Level on mobile */
            .level {
                flex-direction: column;
            }
            
            .level-left, .level-right {
                margin-bottom: 0.75rem;
            }
            
            /* Tabs scrollable */
            .tabs ul {
                flex-wrap: nowrap;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .tabs li {
                flex-shrink: 0;
            }
            
            /* Footer */
            .footer {
                padding: 2rem 1rem;
            }
            
            .footer .columns {
                text-align: center;
            }
            
            .footer .column {
                margin-bottom: 1.5rem;
            }
            
            /* Order form columns */
            .column.is-7, .column.is-5,
            .column.is-8, .column.is-4,
            .column.is-6 {
                width: 100%;
            }
            
            /* Price tag */
            .price-tag {
                font-size: 0.75rem;
                padding: 0.2rem 0.5rem;
            }
            
            /* Title sizes */
            .title.is-3 {
                font-size: 1.5rem !important;
            }
            
            .title.is-4 {
                font-size: 1.25rem !important;
            }
            
            /* Menu sidebar (services page) */
            .menu-list a {
                padding: 0.5rem 0.75rem;
                font-size: 0.9rem;
            }
            
            /* FIX: Notification dropdown on mobile */
            .navbar-menu .navbar-end {
                display: flex;
                flex-direction: column;
                align-items: stretch;
            }
            
            .navbar-menu .navbar-end .navbar-item {
                display: flex;
                justify-content: flex-start;
            }
            
            /* Notification dropdown on mobile - full width */
            .navbar-menu #notificationDropdown {
                width: 100%;
            }
            
            .navbar-menu #notificationDropdown .dropdown-menu {
                position: static !important;
                width: 100% !important;
                max-width: 100% !important;
                margin-top: 0.5rem;
            }
            
            .navbar-menu #notificationDropdown.is-active .dropdown-menu {
                display: block;
            }
            
            .navbar-menu .notification-menu {
                width: 100% !important;
                max-width: 100% !important;
            }
            
            /* Balance display on mobile - more prominent */
            .navbar-menu .balance-display {
                display: block;
                width: 100%;
                text-align: center;
                margin-bottom: 0.5rem;
            }
            
            /* User dropdown on mobile */
            .navbar-menu .navbar-item.has-dropdown {
                flex-direction: column;
                align-items: stretch;
            }
            
            .navbar-menu .navbar-item.has-dropdown .navbar-link {
                width: 100%;
            }
            
            .navbar-menu .navbar-item.has-dropdown .navbar-dropdown {
                position: static !important;
                display: none;
            }
            
            .navbar-menu .navbar-item.has-dropdown.is-active .navbar-dropdown {
                display: block;
            }
            
            /* Notification bell on mobile - adjust position */
            .navbar-menu .notification-bell {
                background: rgba(255,255,255,0.2);
                width: 100%;
                border-radius: 8px;
                justify-content: flex-start;
                padding: 0.75rem 1rem;
            }
            
            .navbar-menu .notification-bell::after {
                content: 'Thông báo';
                margin-left: 0.5rem;
                color: white;
            }
        }
        
        /* Small phones */
        @media screen and (max-width: 480px) {
            .navbar-brand .navbar-item strong {
                font-size: 1.2rem !important;
            }
            
            .hero .title.is-1 {
                font-size: 1.75rem !important;
            }
            
            .stat-card .value {
                font-size: 1.25rem;
            }
            
            .table {
                font-size: 0.8rem;
            }
            
            .button {
                font-size: 0.85rem;
            }
        }
        
        /* Tablet */
        @media screen and (min-width: 769px) and (max-width: 1023px) {
            .column.is-3 {
                width: 50%;
            }
        }
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
                        
                        <!-- Notification Bell -->
                        <div class="navbar-item">
                            <div class="dropdown is-right" id="notificationDropdown">
                                <div class="dropdown-trigger">
                                    <button class="button notification-bell" aria-haspopup="true" aria-controls="notification-menu" onclick="toggleNotifications()">
                                        <span class="icon">
                                            <i class="fas fa-bell"></i>
                                        </span>
                                        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                                    </button>
                                </div>
                                <div class="dropdown-menu notification-menu" id="notification-menu" role="menu">
                                    <div class="dropdown-content">
                                        <div class="notification-header">
                                            <strong>Thông báo</strong>
                                            <a href="#" onclick="markAllAsRead()" class="is-size-7">Đọc tất cả</a>
                                        </div>
                                        <hr class="dropdown-divider">
                                        <div id="notificationList" class="notification-list">
                                            <p class="has-text-centered has-text-grey py-4">Đang tải...</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        
        @auth
        // ========== NOTIFICATION SYSTEM ==========
        let notificationDropdownOpen = false;
        
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            notificationDropdownOpen = !notificationDropdownOpen;
            
            if (notificationDropdownOpen) {
                dropdown.classList.add('is-active');
                loadNotifications();
            } else {
                dropdown.classList.remove('is-active');
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            const dropdown = document.getElementById('notificationDropdown');
            if (dropdown && !dropdown.contains(e.target)) {
                dropdown.classList.remove('is-active');
                notificationDropdownOpen = false;
            }
        });
        
        function loadNotifications() {
            fetch('{{ route("notifications.index") }}')
                .then(res => res.json())
                .then(data => {
                    renderNotifications(data.notifications);
                    updateBadge(data.unread_count);
                })
                .catch(err => console.error('Failed to load notifications', err));
        }
        
        function renderNotifications(notifications) {
            const list = document.getElementById('notificationList');
            
            if (!notifications || notifications.length === 0) {
                list.innerHTML = '<div class="notification-empty"><i class="fas fa-bell-slash mb-2"></i><br>Chưa có thông báo</div>';
                return;
            }
            
            list.innerHTML = notifications.map(n => `
                <div class="notification-item ${n.is_read ? '' : 'unread'}" onclick="goToNotification(${n.id}, '${n.link || ''}')">
                    <div class="icon-wrap is-${n.color}">
                        <i class="fas ${n.icon}"></i>
                    </div>
                    <div class="content">
                        <div class="title-text">${n.title}</div>
                        <div class="message-text">${n.message}</div>
                        <div class="time-text">${n.time_ago}</div>
                    </div>
                </div>
            `).join('');
        }
        
        function updateBadge(count) {
            const badge = document.getElementById('notificationBadge');
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
        
        function goToNotification(id, link) {
            // Mark as read
            fetch(`/notifications/${id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            }).then(() => {
                if (link) {
                    window.location.href = link;
                } else {
                    loadNotifications();
                }
            });
        }
        
        function markAllAsRead() {
            event.preventDefault();
            fetch('{{ route("notifications.markAllAsRead") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                }
            }).then(() => {
                loadNotifications();
            });
        }
        
        // Poll for new notifications every 30 seconds
        function pollNotifications() {
            fetch('{{ route("notifications.unreadCount") }}')
                .then(res => res.json())
                .then(data => updateBadge(data.unread_count))
                .catch(err => {});
        }
        
        // Initial load
        pollNotifications();
        
        // Poll every 30 seconds
        setInterval(pollNotifications, 30000);
        @endauth
    </script>
    
    @yield('scripts')
</body>
</html>
