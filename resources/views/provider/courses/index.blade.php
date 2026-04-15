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
        <div>
            <h2>Tất cả khóa học</h2>
            <p>Danh sách các khóa học bạn đã gửi yêu cầu cho Admin</p>
        </div>
        <a href="{{ route('provider.courses.create') }}" class="btn-create">
            <i class="fas fa-plus"></i> Thêm khóa học mới
        </a>
    </div>

    {{-- Hiển thị thông báo thành công từ Controller --}}
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
                            {{-- Hiển thị ảnh thumbnail hoặc ảnh mặc định nếu trống --}}
                            <img src="{{ $course->thumbnail ? asset($course->thumbnail) : asset('images/default-course.png') }}" class="course-img">
                            <div>
                                <div style="font-weight: 600; color: #1e293b;">{{ $course->title }}</div>
                                {{-- Sửa lỗi format() on null bằng dấu ? --}}
                                <small style="color: #64748b;">
                                    Cập nhật: {{ $course->updated_at?->format('d/m/Y') ?? 'Vừa xong' }}
                                </small>
                            </div>
                        </div>
                    </td>
                    <td>{{ $course->category->name ?? 'Chưa phân loại' }}</td>
                    <td><span style="font-weight: 600;">{{ number_format($course->price, 0, ',', '.') }}₫</span></td>
                    <td>
                        {{-- Hiển thị Badge trạng thái theo quy trình kiểm duyệt --}}
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
                            <p style="color: #94a3b8;">Bạn chưa có khóa học nào. Hãy tạo khóa học đầu tiên!</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div style="margin-top: 20px;">
        {{ $courses->links() }}
    </div>
</div>
@endsection