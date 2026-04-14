@extends('admin.layout')

@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')

@section('content')
<div class="dashboard-container">
    <div class="row">
        <!-- Users Stats -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h6>Tổng người dùng</h6>
                    <h3>{{ $stats['total_users'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Providers Stats -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-content">
                    <h6>Tổng Provider</h6>
                    <h3>{{ $stats['total_providers'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Pending Providers -->
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="/admin/providers" class="text-decoration-none">
                <div class="stat-card">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-content">
                        <h6>Provider chờ duyệt</h6>
                        <h3>{{ $stats['pending_providers'] }}</h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- Blocked Users -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon bg-danger">
                    <i class="fas fa-ban"></i>
                </div>
                <div class="stat-content">
                    <h6>Người dùng bị khóa</h6>
                    <h3>{{ $stats['blocked_users'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <!-- Courses Stats -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="fas fa-book"></i>
                </div>
                <div class="stat-content">
                    <h6>Tổng khóa học</h6>
                    <h3>{{ $stats['total_courses'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Pending Courses -->
        <div class="col-md-6 col-lg-3 mb-4">
            <a href="/admin/content-moderation" class="text-decoration-none">
                <div class="stat-card">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h6>Khóa học chờ duyệt</h6>
                        <h3>{{ $stats['pending_courses'] }}</h3>
                    </div>
                </div>
            </a>
        </div>

        <!-- Active Courses -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h6>Khóa học hoạt động</h6>
                    <h3>{{ $stats['active_courses'] }}</h3>
                </div>
            </div>
        </div>

        <!-- Rejected Courses -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="stat-card">
                <div class="stat-icon bg-danger">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <h6>Khóa học bị từ chối</h6>
                    <h3>{{ $stats['rejected_courses'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.dashboard-container {
    padding: 30px 0;
}

.stat-card {
    background: #fff;
    border-radius: 8px;
    padding: 20px;
    display: flex;
    align-items: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    margin-right: 15px;
    flex-shrink: 0;
}

.stat-icon.bg-primary {
    background: #007bff;
}

.stat-icon.bg-success {
    background: #28a745;
}

.stat-icon.bg-warning {
    background: #ffc107;
}

.stat-icon.bg-danger {
    background: #dc3545;
}

.stat-icon.bg-info {
    background: #17a2b8;
}

.stat-content h6 {
    color: #666;
    font-size: 12px;
    text-transform: uppercase;
    margin-bottom: 5px;
    font-weight: 600;
}

.stat-content h3 {
    margin: 0;
    color: #333;
    font-size: 28px;
    font-weight: bold;
}
</style>
@endpush
@endsection