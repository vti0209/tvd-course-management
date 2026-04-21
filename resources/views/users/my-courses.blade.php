@extends('layouts.master')

@section('content')
<div class="container mt-5 my-courses-container">
    <h2 class="mb-4 fw-bold">Khóa học của tôi</h2>

    <div class="row">
        @forelse($myCourses as $course)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 course-card">
                    {{-- Badge Trạng thái --}}
                    <div class="position-absolute top-0 end-0 m-2">
                        @if($course->pivot->status == 'active')
                            <span class="badge bg-success shadow-sm">Đang học</span>
                        @elseif($course->pivot->status == 'pending')
                            <span class="badge bg-warning text-dark shadow-sm">Chờ duyệt</span>
                        @elseif($course->pivot->status == 'inactive')
                            <span class="badge bg-danger shadow-sm">Đã khóa</span>
                        @endif
                    </div>

                    <img src="{{ asset($course->thumbnail) }}"
                        class="course-card-img"
                        alt="{{ $course->title }}"
                         style="height: 200px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;">

                    <div class="card-body d-flex flex-column course-card-body">
                        <h5 class="card-title course-title fw-bold">{{ $course->title }}</h5>

                        <p class="card-text course-description text-muted small">
                            {{ Str::limit($course->description, 80) }}
                        </p>

                        {{-- Hiển thị ngày đăng ký từ pivot --}}
                        <div class="mb-3 mt-2">
                            <small class="text-secondary">
                                <i class="bi bi-calendar-check me-1"></i>
                                Đã đăng ký: {{ $course->pivot->enrolled_at ? \Carbon\Carbon::parse($course->pivot->enrolled_at)->format('d/m/Y') : 'Chưa xác định' }}
                            </small>
                        </div>

                        <div class="mt-auto">
                            @if($course->pivot->status == 'active')
                                <a href="{{ route('course.detail', $course->id) }}" class="btn btn-primary w-100 btn-learn-now fw-bold">
                                    Vào học ngay
                                </a>
                            @elseif($course->pivot->status == 'pending')
                                <button class="btn btn-secondary w-100 disabled" style="cursor: not-allowed;">
                                    <i class="bi bi-clock-history me-1"></i> Đang chờ kích hoạt
                                </button>
                            @elseif($course->pivot->status == 'inactive')
                                <button class="btn btn-outline-danger w-100 disabled" style="cursor: not-allowed;">
                                    <i class="bi bi-lock-fill me-1"></i> Khóa học bị tạm khóa
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="Empty" style="width: 100px; opacity: 0.5" class="mb-3">
                <p class="lead text-muted">Bạn chưa đăng ký khóa học nào.</p>
                <a href="{{ url('/trangchu') }}" class="btn btn-primary px-4 rounded-pill">Khám phá khóa học ngay</a>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $myCourses->links() }}
    </div>
</div>
@endsection
