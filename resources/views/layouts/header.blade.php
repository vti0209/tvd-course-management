<header class="bg-dark">
    <div class="container d-flex justify-content-between align-items-center py-2">

        <!-- Logo -->
        <a href="/" class="text-white fw-bold text-decoration-none fs-4">
            TVD
        </a>

        <!-- Menu -->
        <nav class="d-flex align-items-center gap-4">

            <a href="/" class="text-white text-decoration-none">
                Trang chủ
            </a>

            <a href="/courses" class="text-white text-decoration-none">
                Khóa học
            </a>

            @auth
                <!-- Khóa học của tôi + số lượng -->
                <a href="/my-courses" class="text-white text-decoration-none">
                    Khóa học của tôi
                    ({{ auth()->user()->courses->count() ?? 0 }})
                </a>

                <!-- Dropdown user -->
                <div class="dropdown">
                    <a class="text-white dropdown-toggle text-decoration-none" href="#" data-bs-toggle="dropdown">
                        {{ auth()->user()->name }}
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="/profile">
                                Thông tin cá nhân
                            </a>
                        </li>
                        <li>
                            <form action="/logout" method="POST">
                                @csrf
                                <button class="dropdown-item">
                                    Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

            @else
                <!-- Guest -->
                <a href="/login" class="btn btn-outline-light btn-sm">
                    Đăng nhập
                </a>

                <a href="/register" class="btn btn-info btn-sm">
                    Đăng ký
                </a>
            @endauth

        </nav>

    </div>
</header>
