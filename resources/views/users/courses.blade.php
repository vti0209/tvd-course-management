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
            {{-- Fix ảnh --}}
            <img src="{{ asset('images/' . $course->thumbnail) }}" alt="{{ $course->title }}">
            <span class="course-badge">{{ $course->category->name ?? 'LavaNet' }}</span>
        </div>
        <div class="course-info">
            <h3 class="course-title">{{ $course->title }}</h3>
            <div class="course-meta">
                <span class="course-duration">
                    <i class="fas fa-clock"></i>
                        {{ $course->duration ?? $course->chapters->sum(fn($ch) => $ch->lessons->count()) ?? 0 }} giờ
                </span>
            </div>
            <div class="course-price">{{ number_format($course->price) }}₫</div>
            <a href="/courses/{{ $course->id }}" class="btn-detail">Xem chi tiết</a>
        </div>
    </div>
    @empty
    <p class="no-courses">Không có khóa học nào</p>
    @endforelse
</div>

{{-- Phân trang --}}
<div class="pagination">
    {{ $courses->links() }}
</div>
@endsection
