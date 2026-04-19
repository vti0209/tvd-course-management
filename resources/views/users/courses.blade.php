@extends('layouts.master')

@section('content')
<div class="section-header">
    <hr>
    @if(!empty($searchHeading))
        <h2 class="section-title">{{ $searchHeading }}</h2>
    @elseif(!empty($filterHeading))
        <h2 class="section-title">{{ $filterHeading }}</h2>
    @else
        <h2 class="section-title">Tất cả khóa học</h2>
    @endif

    @if(!empty($searchHeading) && !empty($filterHeading))
        <p class="search-summary mb-0">{{ $filterHeading }}</p>
    @endif
    <div class="title-line"></div>
</div>

<div class="courses-grid">
    @forelse($courses as $course)
    <div class="course-card">
        <div class="course-image">
            {{-- FIX ẢNH TẠI ĐÂY --}}
            <img src="{{ asset($course->thumbnail) }}" alt="{{ $course->title }}">
            <span class="course-badge">{{ $course->category->name ?? 'LavaNet' }}</span>
        </div>
        <div class="course-info">
            <h3 class="course-title">{{ $course->title }}</h3>
            <div class="course-meta">
                <span class="course-duration">
                    <i class="fas fa-clock"></i>
                    {{-- Ưu tiên hiển thị duration, nếu không có thì tính tổng --}}
                    {{ $course->duration ?? 0 }} giờ
                </span>
            </div>
            <div class="course-price">{{ number_format($course->price) }}₫</div>
            
            {{-- Dùng route để an toàn hơn --}}
            <a href="{{ route('course.detail', $course->id) }}" class="btn-detail">Xem chi tiết</a>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <p class="no-courses text-muted">Không có khóa học nào phù hợp với tìm kiếm của bạn.</p>
    </div>
    @endforelse
</div>

{{-- Phân trang --}}
<div class="pagination d-flex justify-content-center mt-4">
    {{ $courses->links() }}
</div>
@endsection