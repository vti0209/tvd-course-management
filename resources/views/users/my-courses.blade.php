@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Khóa học của tôi</h2>
    <div class="row">
        @forelse($myCourses as $course)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('uploads/'.$course->image) }}" class="card-img-top" alt="{{ $course->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->name }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($course->description, 80) }}</p>
                        <a href="{{ route('course.detail', $course->id) }}" class="btn btn-primary w-100">
                            Vào học ngay
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p class="lead">Bạn chưa đăng ký khóa học nào.</p>
                <a href="{{ url('/trangchu') }}" class="btn btn-outline-primary">Khám phá khóa học ngay</a>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $myCourses->links() }}
    </div>
</div>
@endsection
