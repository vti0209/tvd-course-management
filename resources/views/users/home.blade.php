@extends('layouts.master')

@section('title', 'Gemini Academy - Học tập trực tuyến')

@section('content')
<!-- Hero Section with Carousel -->
<section class="hero-section">
    <div class="container">
        <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('images/slide1.jfif') }}" class="width: 150px; height: 200px;" alt="Slide 1">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/slide2.png') }}" class="width: 300px; height: 200px;" alt="Slide 2">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/slide3.jfif') }}" class="width: 300px; height: 200px;" alt="Slide 3">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('images/slide4.png') }}" class="width: 300px; height: 200px;" alt="Slide 4">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
</section>

<!-- Latest Courses Section -->
<section class="courses-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Khóa học mới nhất</h2>
            <div class="title-line"></div>
        </div>

        <div class="courses-grid">
            @forelse($courses as $course)
            <div class="course-card">
                <div class="course-image">
                    <img src="{{ asset('images/' . $course->thumbnail) }}" alt="{{ $course->title }}">
                    <span class="course-badge">{{ $course->category->name ?? 'LavaNet' }}</span>
                </div>
                <div class="course-info">
                    <h3 class="course-title">{{ $course->title }}</h3>
                    <div class="course-meta">
                        <span class="course-duration"><i class="fas fa-clock"></i> {{ $course->duration ?? 'Liên hệ' }}
                            giờ</span>
                    </div>
                    <div class="course-price">{{ number_format($course->price) }}₫</div>
                    <a href="{{ route('course.detail', $course->id) }}" class="btn-detail">Xem chi tiết</a>
                </div>
            </div>
            @empty
            <p class="no-courses">Không có khóa học nào</p>
            @endforelse
        </div>


    </div>
</section>
@endsection