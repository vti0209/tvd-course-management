@extends('layouts.master')

@section('content')
<div class="course-page-clean py-5">
    <div class="container">
        <nav class="mb-4">
            <ol class="breadcrumb bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-primary"><i class="bi bi-house-door-fill me-1"></i>Trang chủ</a></li>
                <li class="breadcrumb-item active">{{ $course->category->name }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-9 mx-auto">
                <h1 class="course-title-main mb-4 fw-bold text-dark">{{ $course->title }}</h1>

                <div class="section-box mb-4 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-md-5">
                            <img src="{{ asset('images/' . $course->thumbnail) }}"
                                 class="img-fluid w-100 course-img"
                                 alt="{{ $course->title }}"
                                 style="min-height: 250px;">
                        </div>
                        <div class="col-md-7 p-4 bg-white d-flex flex-column justify-content-center">
                            <h4 class="mb-3 text-primary fw-bold">
                                <i class="bi bi-info-circle-fill me-2"></i>Giới thiệu khóa học
                            </h4>
                            <p class="text-muted lh-lg mb-0">
                                {{ $course->description ?? 'Nội dung mô tả đang được cập nhật.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="section-box mb-4 p-4">
                    <h4 class="mb-4 fw-bold">
                        <i class="bi bi-journal-bookmark-fill me-2 text-primary"></i>Chương trình đào tạo
                    </h4>

                    <div class="lesson-list">
                        @forelse($course->lessons as $index => $lesson)
                            <div class="lesson-item d-flex align-items-center justify-content-between p-3 mb-2 rounded-3 border-start border-4 {{ $isEnrolled ? 'border-success' : 'border-light' }}">
                                <div class="d-flex align-items-center">
                                    <span class="lesson-idx me-3">{{ sprintf('%02d', $index + 1) }}</span>
                                    <span class="lesson-title fw-medium text-dark">
                                        <i class="bi bi-play-circle me-2 opacity-75"></i>{{ $lesson->title }}
                                    </span>
                                </div>

                                @if($isEnrolled)
                                    <span class="badge bg-success-soft text-success rounded-pill px-3">
                                        <i class="bi bi-check-circle-fill me-1"></i>Đã mở khóa
                                    </span>
                                @else
                                    <i class="bi bi-lock-fill text-muted opacity-50"></i>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-inbox text-muted display-4"></i>
                                <p class="text-muted mt-2">Bài học đang được soạn thảo...</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="registration-card-bottom p-5 text-center shadow-sm">
                    <div class="mb-3">
                        <i class="bi bi-lightning-charge-fill text-warning display-5"></i>
                    </div>
                    <h3 class="mb-2 fw-bold">Bắt đầu học ngay hôm nay</h3>
                    <p class="text-muted mb-4">Tham gia cùng cộng đồng học viên và nâng cao kỹ năng của bạn.</p>

                    <div class="price-tag mb-4 text-center">
                        <span class="h2 fw-bold text-danger">{{ number_format($course->price) }} ₫</span>
                    </div>

                    <div class="d-grid col-md-6 mx-auto">
                        @auth
                            @if($isEnrolled)
                                <a href="{{ route('user.my_courses') }}" class="btn btn-success btn-lg rounded-pill shadow-sm py-3 transition-all">
                                    <i class="bi bi-arrow-right-circle me-2"></i>Vào học ngay
                                </a>
                            @else
                                <form action="{{ route('course.enroll', $course->id) }}" method="POST">
                                    @csrf
                                    <button class="btn btn-primary btn-lg rounded-pill shadow-sm py-3 w-100 transition-all">
                                        <i class="bi bi-cart-plus me-2"></i>Đăng ký khóa này
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-warning btn-lg rounded-pill shadow-sm py-3 transition-all">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập để đăng ký
                            </a>
                        @endauth
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
