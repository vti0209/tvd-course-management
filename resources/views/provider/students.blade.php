@extends('provider.layout')

@section('title', 'Quản lý học viên')

@section('css')
    {{-- Gọi các file CSS riêng biệt --}}
    <link rel="stylesheet" href="{{ asset('css/provider-courses.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/provider-students.css') }}?v={{ time() }}">
@endsection

@section('content')
<div class="student-management-wrapper">

    {{-- KHỐI CÔNG CỤ (Action Bar) --}}
    <div class="action-bar">
        <div class="page-title">
            <h2>Danh sách học viên</h2>
        </div>

        <form action="{{ route('provider.students') }}" method="GET" class="header-filter">
            {{-- Tìm kiếm đa năng --}}
            <div class="search-input-group">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên hoặc email...">
            </div>

            {{-- Lọc trạng thái --}}
            <select name="status" class="select-filter">
                <option value="">Trạng thái học</option>
                <option value="learning" {{ request('status') == 'learning' ? 'selected' : '' }}>Đang học</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
            </select>

            <button type="submit" class="btn-search-icon">
                <i class="fas fa-filter"></i>
            </button>

            @if(request('search') || request('status'))
                <a href="{{ route('provider.students') }}" class="btn-reset">
                    <i class="fas fa-sync-alt"></i>
                </a>
            @endif
        </form>
    </div>

    {{-- KHỐI BẢNG DỮ LIỆU --}}
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="col-student">Học viên</th>
                    <th class="col-course">Khóa học đã đăng ký</th>
                    <th class="col-date">Ngày đăng ký</th>
                    <th class="col-learning">Trạng thái học</th>
                    <th class="col-payment">Thanh toán</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enrollments as $enrollment)
                <tr>
                    <td>
                        <div class="student-profile">
                            <span class="name">{{ $enrollment->user->full_name ?? $enrollment->user->username }}</span>
                            <span class="email">{{ $enrollment->user->email }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="course-info-cell">
                            {{ $enrollment->course->title }}
                        </div>
                    </td>
                    <td>
                        {{ optional($enrollment->enrolled_at)->format('d/m/Y') ?? 'N/A' }}
                    </td>
                    <td>
                        <form action="{{ route('provider.updateStudentStatus', [$enrollment->course_id, $enrollment->user_id]) }}" method="POST">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="form-select form-select-sm border-0 shadow-sm"
                                    style="cursor: pointer; border-radius: 20px; padding: 5px 10px; font-size: 12px;
                                    {{-- Sửa $student->pivot->status thành $enrollment->status --}}
                                    background-color: {{ $enrollment->status == 'active' ? '#d4edda' : '#fff3cd' }};
                                    color: {{ $enrollment->status == 'active' ? '#155724' : '#856404' }};">

                                    <option value="active" {{ $enrollment->status == 'active' ? 'selected' : '' }}>
                                        ● Đang học
                                    </option>
                                    <option value="pending" {{ $enrollment->status == 'pending' ? 'selected' : '' }}>
                                        ● Chờ duyệt
                                    </option>
                                    <option value="inactive" {{ $enrollment->status == 'inactive' ? 'selected' : '' }}>
                                        ● Đã khóa
                                    </option>
                                </select>
                        </form>
                    </td>
                    <td>
                        <span class="badge {{ $enrollment->payment_status == 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                            <i class="fas {{ $enrollment->payment_status == 'paid' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                            {{ $enrollment->payment_status == 'paid' ? 'Đã thanh toán' : 'Chờ xử lý' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="empty-data">
                        <i class="fas fa-users-slash"></i>
                        <p>Không tìm thấy học viên nào phù hợp.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PHÂN TRANG --}}
    <div class="pagination-area">
        {{ $enrollments->appends(request()->query())->links() }}
    </div>
</div>
@endsection
