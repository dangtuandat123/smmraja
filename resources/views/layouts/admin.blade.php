<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - SMM Panel Admin</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --sidebar-width: 260px;
            --dark: #1f2937;
        }
        
        * { font-family: 'Inter', sans-serif; }
        
        body { background: #f1f5f9; min-height: 100vh; }
        
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--dark);
            padding: 1.5rem 0;
            overflow-y: auto;
            z-index: 100;
        }
        
        .admin-sidebar .brand {
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }
        
        .admin-sidebar .brand a {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .admin-sidebar .menu-label {
            color: rgba(255,255,255,0.4);
            font-size: 0.75rem;
            text-transform: uppercase;
            padding: 0 1.5rem;
            margin-top: 1.5rem;
        }
        
        .admin-sidebar .menu-list a {
            color: rgba(255,255,255,0.7);
            padding: 0.75rem 1.5rem;
            border-radius: 0;
            transition: all 0.2s;
        }
        
        .admin-sidebar .menu-list a:hover,
        .admin-sidebar .menu-list a.is-active {
            background: var(--primary);
            color: white;
        }
        
        .admin-sidebar .menu-list a i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        .admin-content {
            margin-left: var(--sidebar-width);
            padding: 0;
            min-height: 100vh;
        }
        
        .admin-header {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .admin-main { padding: 2rem; }
        
        .stat-box {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stat-box .icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .stat-box .value { font-size: 1.75rem; font-weight: 700; }
        .stat-box .label { color: #6b7280; font-size: 0.875rem; }
        
        .card { border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: none; }
        .card-header { border-bottom: 1px solid #f3f4f6; }
        
        .button.is-primary {
            background: var(--primary);
            border: none;
        }
        
        .button.is-primary:hover {
            background: #4f46e5;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="brand">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-bolt"></i> SMM Admin
            </a>
        </div>
        
        <aside class="menu">
            <p class="menu-label">Menu chính</p>
            <ul class="menu-list">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
            </ul>
            
            <p class="menu-label">Dịch vụ</p>
            <ul class="menu-list">
                <li>
                    <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'is-active' : '' }}">
                        <i class="fas fa-folder"></i> Danh mục
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.index', 'admin.services.edit') ? 'is-active' : '' }}">
                        <i class="fas fa-cogs"></i> Dịch vụ
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.services.import') }}" class="{{ request()->routeIs('admin.services.import') ? 'is-active' : '' }}">
                        <i class="fas fa-download"></i> Import từ API
                    </a>
                </li>
            </ul>
            
            <p class="menu-label">Quản lý</p>
            <ul class="menu-list">
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'is-active' : '' }}">
                        <i class="fas fa-shopping-bag"></i> Đơn hàng
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'is-active' : '' }}">
                        <i class="fas fa-users"></i> Người dùng
                    </a>
                </li>
            </ul>
            
            <p class="menu-label">Hệ thống</p>
            <ul class="menu-list">
                <li>
                    <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'is-active' : '' }}">
                        <i class="fas fa-cog"></i> Cài đặt
                    </a>
                </li>
                <li>
                    <a href="{{ route('home') }}" target="_blank">
                        <i class="fas fa-external-link-alt"></i> Xem website
                    </a>
                </li>
            </ul>
        </aside>
    </aside>
    
    <!-- Main Content -->
    <div class="admin-content">
        <header class="admin-header">
            <div>
                <h1 class="title is-5 mb-0">@yield('title', 'Admin')</h1>
            </div>
            <div class="is-flex is-align-items-center">
                <span class="mr-4" id="apiBalance">
                    <i class="fas fa-database"></i> API: Loading...
                </span>
                <div class="dropdown is-hoverable is-right">
                    <div class="dropdown-trigger">
                        <button class="button is-white">
                            <span class="icon"><i class="fas fa-user-circle"></i></span>
                            <span>{{ auth()->user()->name }}</span>
                            <span class="icon is-small"><i class="fas fa-angle-down"></i></span>
                        </button>
                    </div>
                    <div class="dropdown-menu">
                        <div class="dropdown-content">
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <i class="fas fa-user mr-2"></i> Tài khoản
                            </a>
                            <hr class="dropdown-divider">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: none;">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        @if(session('success'))
            <div class="notification is-success is-light" style="margin: 1rem 2rem 0; border-radius: 8px;">
                <button class="delete"></button>
                {{ session('success') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="notification is-danger is-light" style="margin: 1rem 2rem 0; border-radius: 8px;">
                <button class="delete"></button>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <main class="admin-main">
            @yield('content')
        </main>
    </div>
    
    <script>
        document.querySelectorAll('.notification .delete').forEach(del => {
            del.addEventListener('click', () => del.parentElement.remove());
        });
        
        // Load API Balance
        fetch('{{ route("admin.api.balance") }}')
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('apiBalance').innerHTML = 
                        '<i class="fas fa-database has-text-success"></i> API: $' + parseFloat(data.balance).toFixed(2);
                } else {
                    document.getElementById('apiBalance').innerHTML = 
                        '<i class="fas fa-database has-text-danger"></i> API: Error';
                }
            })
            .catch(() => {
                document.getElementById('apiBalance').innerHTML = 
                    '<i class="fas fa-database has-text-warning"></i> API: N/A';
            });
    </script>
    @yield('scripts')
</body>
</html>
