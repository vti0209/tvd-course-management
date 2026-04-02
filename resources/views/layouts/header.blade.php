<header class="main-header">
    <div class="container">
        <div class="header-wrapper">
            <!-- Logo -->
            <a href="/" class="logo">
                <span class="logo-text">GEMINI<span class="logo-accent">ACEDAMY</span></span>
            </a>

            <!-- Navigation Menu -->
            <nav class="main-nav">
                <a href="/" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">Trang chủ</a>
                <a href="/courses" class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}">Khóa học</a>
                <a href="/my-courses" class="nav-link">Khóa học của tôi</a>
                <a href="/about" class="nav-link">Về chúng tôi</a>
                <a href="/contact" class="nav-link">Liên hệ</a>
            </nav>

            <!-- Auth Buttons -->
            <div class="auth-buttons">
                @auth
                    <div class="dropdown user-dropdown">
                        <button class="user-btn" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle"></i>
                            <span>{{ auth()->user()->name }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/profile"><i class="fas fa-user"></i> Thông tin cá nhân</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="/logout" method="POST">
                                    @csrf
                                    <button class="dropdown-item"><i class="fas fa-sign-out-alt"></i> Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="/login" class="btn-login">Đăng nhập</a>
                    <a href="/register" class="btn-register">Đăng ký</a>
                @endauth
            </div>
        </div>
    </div>
</header>
