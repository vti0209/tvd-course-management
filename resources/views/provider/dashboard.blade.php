@extends('provider.layout')

@section('title', 'Dashboard - Provider')
@section('page_title', 'Dashboard')
@section('page_subtitle', date('d/m/Y'))

@section('css')
<link rel="stylesheet" href="{{ asset('css/provider-dashboard.css') }}">
@endsection

@section('content')
<!-- Stats Grid -->
<div class="stats-grid">
    <!-- Total Courses -->
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <i class="fas fa-book"></i>
        </div>
        <div>
            <p class="stat-label">Tổng Khóa Học</p>
            <h3 class="stat-value">{{ $stats['total_courses'] }}</h3>
        </div>
    </div>

    <!-- Active Courses -->
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);">
            <i class="fas fa-check-circle"></i>
        </div>
        <div>
            <p class="stat-label">Khóa Học Hoạt Động</p>
            <h3 class="stat-value">{{ $stats['active_courses'] }}</h3>
        </div>
    </div>

    <!-- Total Students -->
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
            <i class="fas fa-users"></i>
        </div>
        <div>
            <p class="stat-label">Tổng Học Viên</p>
            <h3 class="stat-value">{{ $stats['total_students'] }}</h3>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="stat-card">
        <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <i class="fas fa-dollar-sign"></i>
        </div>
        <div>
            <p class="stat-label">Tổng Doanh Thu</p>
            <h3 class="stat-value">{{ number_format($stats['total_revenue'], 0, ',', '.') }}₫</h3>
        </div>
    </div>
</div>

<!-- Courses Section -->
<div class="chart-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; font-weight: 600; color: #333;">
            <i class="fas fa-book" style="margin-right: 10px;"></i>Khóa Học Của Tôi
        </h3>
        <a href="{{ route('provider.courses.create') }}" class="btn btn-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
            <i class="fas fa-plus"></i> Tạo Khóa Học
        </a>
    </div>

    @if($courses->count() > 0)
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Tiêu Đề</th>
                    <th>Danh Mục</th>
                    <th>Giá</th>
                    <th>Trạng Thái</th>
                    <th>Học Viên</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courses->take(5) as $course)
                <tr>
                    <td>
                        <strong>{{ $course->title }}</strong>
                    </td>
                    <td>{{ $course->category->name ?? 'N/A' }}</td>
                    <td>{{ number_format($course->price, 0, ',', '.') }}₫</td>
                    <td>
                        <span class="status-badge status-{{ $course->status }}">
                            @if($course->status == 'active' || $course->status == 'approved')
                                <i class="fas fa-check-circle"></i> Đã duyệt
                            @elseif($course->status == 'pending')
                                <i class="fas fa-clock"></i> Chờ duyệt
                            @else
                                {{ ucfirst($course->status) }}
                            @endif
                        </span>
                    </td>
                    <td>
                        <strong>{{ $course->enrollments->count() }}</strong>
                    </td>
                    <td>
                        <a href="{{ route('provider.courses.edit', $course->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button class="btn btn-sm btn-danger" onclick="deleteCourse({{ $course->id }})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="empty-state">
        <i class="fas fa-book"></i>
        <p>Bạn chưa tạo khóa học nào</p>
        <a href="{{ route('provider.courses.create') }}" class="btn btn-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; margin-top: 15px;">
            <i class="fas fa-plus"></i> Tạo Khóa Học Đầu Tiên
        </a>
    </div>
    @endif
</div>

<!-- Recent Enrollments -->
<div class="table-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; font-weight: 600; color: #333;">
            <i class="fas fa-list" style="margin-right: 10px;"></i>Đăng Ký Gần Đây
        </h3>
        <a href="{{ route('provider.students') }}" style="color: #667eea; text-decoration: none;">Xem tất cả →</a>
    </div>

    @if($recent_enrollments->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Học Viên</th>
                <th>Khóa Học</th>
                <th>Ngày Đăng Ký</th>
                <th>Trạng Thái Thanh Toán</th>
                <th>Số Tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recent_enrollments as $enrollment)
            <tr>
                <td>
                    <strong>{{ $enrollment->user->full_name ?? $enrollment->user->username }}</strong>
                </td>
                <td>{{ $enrollment->course->title }}</td>
                <td>{{ $enrollment->enrolled_at->format('d/m/Y H:i') }}</td>
                <td>
                    <span class="status-badge status-{{ $enrollment->payment_status }}">
                        {{ ucfirst($enrollment->payment_status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán') }}
                    </span>
                </td>
                <td><strong>{{ number_format($enrollment->price_at_purchase, 0, ',', '.') }}₫</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="empty-state">
        <i class="fas fa-inbox"></i>
        <p>Chưa có đăng ký nào</p>
    </div>
    @endif
</div>

@endsection

@section('js')
<script>
    function deleteCourse(courseId) {
        if (confirm('Bạn chắc chắn muốn xóa khóa học này?')) {
            fetch(`/provider/courses/${courseId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).then(() => location.reload());
        }
    }
</script>
@endsection
