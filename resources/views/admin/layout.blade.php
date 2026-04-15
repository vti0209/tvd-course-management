<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Gemini Academy')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>

<body>
    <div class="admin-container">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <a href="/" class="sidebar-logo">
                    <i class="fas fa-book"></i>
                    <span>ADMIN</span>
                </a>
            </div>

            <nav class="sidebar-nav">
                <a href="/admin/dashboard" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/admin/users" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Quản lý người dùng</span>
                </a>
                <a href="/admin/providers"
                    class="nav-item {{ request()->routeIs('admin.providers.*') ? 'active' : '' }}">
                    <i class="fas fa-user-tie"></i>
                    <span>Phê duyệt Provider</span>
                </a>
                <a href="/admin/content-moderation"
                    class="nav-item {{ request()->routeIs('admin.content-moderation.*') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Duyệt nội dung</span>
                </a>
                <a href="/admin/categories"
                    class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-list"></i>
                    <span>Quản lý danh mục</span>
                </a>
                <a href="/admin/withdrawals"
                    class="nav-item {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i>
                    <span>Quản lý rút tiền</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                @auth
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-details">
                        <p class="user-name">{{ auth()->user()->full_name }}</p>
                        <p class="user-role">{{ auth()->user()->role ?? 'Quản trị viên' }}</p>
                    </div>
                </div>
                <form action="/logout" method="POST" class="logout-form">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </button>
                </form>
                @else
                <div class="user-info">
                    <div class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="user-details">
                        <p class="user-name">Admin</p>
                    </div>
                </div>
                @endauth
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-main">
            <div class="admin-header">
                <div class="header-left">
                    <button class="sidebar-toggle" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">@yield('page_title', 'Admin')</h1>
                </div>
                <div class="header-right">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Tìm kiếm...">
                    </div>
                </div>
            </div>

            <div class="admin-content">
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Lỗi!</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.querySelector('.admin-sidebar').classList.toggle('collapsed');
    });
    </script>
    @stack('scripts')
</body>

</html>