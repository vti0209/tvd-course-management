@extends('layouts.master')

@section('content')
<section class="courses-section">
    <div class="container">
        <div class="section-header">
            <h1 class="section-title">Danh sách khóa học</h1>
            <div class="title-line"></div>
        </div>

        <div class="courses-grid">
    @forelse($courses as $course)
        <div class="course-card">
            <div class="course-image">
                {{-- Fix ảnh --}}
                <img src="{{ asset('images/' . $course->thumbnail) }}" alt="{{ $course->title }}">
                <span class="course-badge">{{ $course->category->name ?? 'LavaNet' }}</span>
            </div>
            <div class="course-info">
                <h3 class="course-title">{{ $course->title }}</h3>
                <div class="course-meta">
                    <span class="course-duration"><i class="fas fa-clock"></i> {{ $course->duration ?? 'Liên hệ' }} giờ</span>
                </div>
                <div class="course-price">{{ number_format($course->price) }}₫</div>
                <a href="/courses/{{ $course->id }}" class="btn-detail">Xem chi tiết →</a>
            </div>
        </div>
    @empty
        <p class="no-courses">Không có khóa học nào</p>
    @endforelse
</div>

{{-- Phân trang --}}
        <div class="pagination-wrapper">
            {{ $courses->links() }}
        </div>
    </div>
</section>
@endsection