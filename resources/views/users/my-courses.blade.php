@extends('layouts.master')

@section('content')
<div class="container mt-5 my-courses-container">
    <h2 class="mb-4 fw-bold">Khóa học của tôi</h2>

    <div class="row">
        @forelse($myCourses as $course)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0 course-card">
                    <img src="{{ asset('images/' . $course->thumbnail) }}"
                         class="course-card-img"
                         alt="{{ $course->name }}">

                    <div class="card-body course-card-body">
                        <h5 class="card-title course-title">{{ $course->title }}</h5>

                        <p class="card-text course-description">
                            {{ Str::limit($course->description, 80) }}
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('course.detail', $course->id) }}" class="btn btn-primary w-100 btn-learn-now">
                                Vào học ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <p class="lead text-muted">Bạn chưa đăng ký khóa học nào.</p>
                <a href="{{ url('/trangchu') }}" class="btn btn-primary px-4">Khám phá khóa học ngay</a>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $myCourses->links() }}
    </div>
</div>
@endsection
