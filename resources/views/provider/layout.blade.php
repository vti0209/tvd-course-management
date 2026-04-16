<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Provider Dashboard')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/provider-layout.css') }}">
    @yield('css')
</head>
<body>
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-brand">
                <i class="fas fa-graduation-cap"></i>
                <span>Provider</span>
            </div>

            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('provider.dashboard') }}" class="@if(request()->routeIs('provider.dashboard')) active @endif">
                        <i class="fas fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('provider.courses.index') }}" class="@if(request()->routeIs('provider.courses.*')) active @endif">
                        <i class="fas fa-book"></i>
                        <span>Khóa học</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('provider.students') }}" class="@if(request()->routeIs('provider.students')) active @endif">
                        <i class="fas fa-users"></i>
                        <span>Học viên</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('provider.earnings') }}" class="@if(request()->routeIs('provider.earnings')) active @endif">
                        <i class="fas fa-chart-line"></i>
                        <span>Doanh thu</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('provider.profile') }}" class="@if(request()->routeIs('provider.profile')) active @endif">
                        <i class="fas fa-user-circle"></i>
                        <span>Hồ sơ</span>
                    </a>
                </li>
                <li>
                    <hr style="border-color: rgba(255, 255, 255, 0.2); margin: 10px 0;">
                </li>
                <li>
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i>
                        <span>Về trang chủ</span>
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" style="border: none; background: none; width: 100%; text-align: left;">
                            <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Đăng xuất</span>
                            </a>
                        </button>
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="header-title">
                    <h1>@yield('page_title', 'Dashboard')</h1>
                    <p>@yield('page_subtitle', '')</p>
                </div>
                <div class="header-actions">
                    <div class="user-profile">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->full_name ?? Auth::user()->username, 0, 1)) }}
                        </div>
                        <div>
                            <p style="margin: 0; color: #333; font-weight: 500;">{{ Auth::user()->full_name ?? Auth::user()->username }}</p>
                            <p style="margin: 0; color: #999; font-size: 12px;">{{ Auth::user()->role }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('js')
</body>
</html>
