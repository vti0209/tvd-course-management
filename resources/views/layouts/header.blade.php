<header class="main-header">
    <div class="container">
        <div class="header-wrapper">
            <a href="/" class="logo">
                <span class="logo-text">GEMINI<span class="logo-accent">ACEDAMY</span></span>
            </a>

            <nav class="main-nav">
                <a href="/trangchu" class="nav-link {{ request()->routeIs('trangchu') ? 'active' : '' }}">Trang chủ</a>
                <a href="/courses" class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}">Khóa học</a>

                @auth
                    <a href="{{ route('user.my_courses') }}"
                       class="nav-link {{ request()->routeIs('user.my_courses') ? 'active' : '' }}">
                       Khóa học của tôi
                    </a>
                @endauth

                <a href="/about" class="nav-link">Về chúng tôi</a>
                <a href="/contact" class="nav-link">Liên hệ</a>
            </nav>

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

        <form action="{{ route('courses.search') }}" method="GET" class="search-form mt-3">
            <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm kiếm khóa học..." class="search-input">

            <select name="category_id" class="search-select">
                <option value="">Danh mục</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</header>
