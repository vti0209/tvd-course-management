@extends('provider.layout')

@section('title', 'Quản lý khóa học')
@section('page_title', 'Danh sách khóa học của tôi')

@section('css')
    {{-- Gọi file CSS riêng --}}
    <link rel="stylesheet" href="{{ asset('css/provider-courses.css') }}">
@endsection

@section('content')
<div class="course-card-container">
    <div class="action-bar">
        <div class="page-title">
            <h2>Tất cả khóa học</h2>
        </div>

        <form action="{{ route('provider.courses.index') }}" method="GET" class="header-filter">
            <div class="search-input-group">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên khóa học...">
            </div>

            <select name="category_id" class="select-filter">
                <option value="">Danh mục</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn-search-icon" title="Lọc">
                <i class="fas fa-filter"></i>
            </button>

            @if(request('search') || request('category_id'))
                <a href="{{ route('provider.courses.index') }}" class="btn-reset" title="Xóa lọc">
                    <i class="fas fa-sync-alt"></i>
                </a>
            @endif
        </form>

        <a href="{{ route('provider.courses.create') }}" class="btn-create">
            <i class="fas fa-plus"></i> <span>Thêm mới</span>
        </a>
    </div>

    {{-- Thông báo thành công --}}
    @if(session('success'))
        <div style="padding: 15px; background: #dcfce7; color: #166534; border-radius: 8px; margin-bottom: 20px; border-left: 5px solid #22c55e;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Khóa học</th>
                    <th>Danh mục</th>
                    <th>Giá tiền</th>
                    <th>Trạng thái</th>
                    <th>Học viên</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                <tr>
                    <td>
                        <div class="course-info">
                            <img src="{{ asset($course->thumbnail) }}" 
                                 alt="{{ $course->title }}" 
                                 style="width: 100px; height: 60px; object-fit: cover;">
                            <div>
                                <div style="font-weight: 600; color: #1e293b;">{{ $course->title }}</div>
                                <small style="color: #64748b;">
                                    Cập nhật: {{ $course->updated_at?->format('d/m/Y') ?? 'Vừa xong' }}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $course->category->name ?? 'Chưa phân loại' }}</td>
                    <td><span style="font-weight: 600;">{{ number_format($course->price, 0, ',', '.') }}₫</span></td>
                    <td>
                        <span class="status-badge status-{{ $course->status }}">
                            @if($course->status == 'pending')
                                <i class="fas fa-clock"></i> Chờ duyệt
                            @elseif($course->status == 'approved' || $course->status == 'active')
                                <i class="fas fa-check-circle"></i> Đã duyệt
                            @elseif($course->status == 'rejected')
                                <i class="fas fa-times-circle"></i> Từ chối
                            @else
                                <i class="fas fa-edit"></i> Bản nháp
                            @endif
                        </span>
                    </td>
                    <td>
                        <i class="fas fa-users"></i> {{ $course->enrollments_count }}
                    </td>
                    <td>
                        <div style="display: flex; gap: 5px;">
                            <a href="{{ route('provider.courses.edit', $course->id) }}" class="btn-action btn-edit" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('provider.courses.destroy', $course->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa khóa học này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state" style="text-align: center; padding: 40px;">
                            <i class="fas fa-box-open" style="font-size: 3rem; color: #e2e8f0; margin-bottom: 10px; display: block;"></i>
                            <p style="color: #94a3b8;">Bạn chưa có khóa học nào.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $courses->links() }}
    </div>
</div>
@endsection