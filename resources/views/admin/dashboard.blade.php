@extends('admin.layout')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Tổng khóa học</p>
                <h3 class="stat-value">{{ \App\Models\Course::count() }}</h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Tổng người dùng</p>
                <h3 class="stat-value">{{ \App\Models\User::count() }}</h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4, #0891b2);">
                <i class="fas fa-list"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Danh mục khóa học</p>
                <h3 class="stat-value">{{ \App\Models\Category::count() }}</h3>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <p class="stat-label">Khóa học hoạt động</p>
                <h3 class="stat-value">{{ \App\Models\Course::where('active', true)->count() }}</h3>
            </div>
        </div>
    </div>

    <!-- Recent Courses Section -->
    <div class="dashboard-section">
        <div class="section-header">
            <div>
                <h2>Khóa học gần đây</h2>
                <p>Danh sách {{ \App\Models\Course::count() > 5 ? '5' : \App\Models\Course::count() }} khóa học mới nhất
                </p>
            </div>
            <a href="/admin/courses/create" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm mới
            </a>
        </div>

        <div class="courses-grid">
            @forelse(\App\Models\Course::latest()->take(8)->get() as $course)
            <div class="course-card">
                <div class="card-image">
                    @if($course->image)
                    <img src="{{ asset($course->image) }}" alt="{{ $course->title }}">
                    @else
                    <div class="image-placeholder">
                        <i class="fas fa-book"></i>
                    </div>
                    @endif
                    <span class="card-badge {{ $course->active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $course->active ? 'Hoạt động' : 'Tạm dừng' }}
                    </span>
                </div>
                <div class="card-content">
                    <h3 class="card-title">{{ $course->title }}</h3>
                    <p class="card-category">{{ $course->category->name ?? 'N/A' }}</p>
                    <p class="card-description">{{ Str::limit($course->description, 60) }}</p>
                    <div class="card-footer">
                        <span class="card-price">{{ number_format($course->price, 0, ',', '.') }}đ</span>
                        <div class="card-actions">
                            <a href="/admin/courses/{{ $course->id }}/edit" class="btn-icon" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state" style="grid-column: 1 / -1;">
                <i class="fas fa-inbox"></i>
                <p>Chưa có khóa học nào</p>
                <a href="/admin/courses/create" class="btn btn-primary">Tạo khóa học đầu tiên</a>
            </div>
            @endforelse
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endpush
@endsection