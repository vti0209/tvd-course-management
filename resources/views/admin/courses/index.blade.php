@extends('admin.layout')

@section('title', 'Quản lý khóa học')
@section('page_title', 'Quản lý khóa học')

@section('content')
<div class="courses-container">
    <!-- Header Section -->
    <div class="section-header">
        <div class="header-info">
            <h2>Danh sách khóa học</h2>
            <p>Quản lý và chỉnh sửa các khóa học trên hệ thống</p>
        </div>
        <a href="/admin/courses/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm khóa học mới
        </a>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <div class="filter-group">
            <input type="text" class="filter-input" placeholder="Tìm theo tên khóa học...">
        </div>
        <div class="filter-group">
            <select class="filter-select">
                <option value="">Tất cả danh mục</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <select class="filter-select">
                <option value="">Tất cả trạng thái</option>
                <option value="1">Hoạt động</option>
                <option value="0">Tạm dừng</option>
            </select>
        </div>
    </div>

    <!-- Courses Table -->
    <div class="courses-table-wrapper">
        <table class="courses-table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" class="select-all">
                    </th>
                    <th>Tên khóa học</th>
                    <th>Danh mục</th>
                    <th>Giảng viên</th>
                    <th>Giá</th>
                    <th>Thời lượng</th>
                    <th>Học viên</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($courses as $course)
                <tr class="course-row">
                    <td>
                        <input type="checkbox" class="row-checkbox">
                    </td>
                    <td>
                        <div class="course-name">
                            <div class="course-image">
                                @if($course->thumbnail)
                                <img src="{{ asset('images/' . $course->thumbnail) }}" alt="{{ $course->title }}"
                                    style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                <i class="fas fa-book"></i>
                                @endif
                            </div>
                            <div class="course-info">
                                <p class="name">{{ $course->title }}</p>
                                <p class="id">{{ $course->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge-category">{{ $course->category->name ?? 'N/A' }}</span>
                    </td>
                    <td>
                        <span class="instructor-name">{{ $course->instructor_name ?? 'Chưa xác định' }}</span>
                    </td>
                    <td>
                        <span class="price">{{ number_format($course->price ?? 0, 0, ',', '.') }}đ</span>
                    </td>
                    <td>
                        <span class="duration">{{ $course->duration ?? 'N/A' }}{{ $course->duration ? ' giờ' : '' }}</span>
                    </td>
                    <td>
                        <span class="student-count">{{ $course->users_count ?? 0 }}</span>
                    </td>
                    <td>
                        <span
                            class="badge {{ $course->status === 'active' ? 'badge-active' : ($course->status === 'pending' ? 'badge-pending' : 'badge-rejected') }}">
                            {{ $course->status === 'active' ? 'Hoạt động' : ($course->status === 'pending' ? 'Chờ duyệt' : 'Bị từ chối') }}
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="/admin/courses/{{ $course->id }}/edit" class="btn-action btn-edit"
                                title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button class="btn-action btn-delete" data-id="{{ $course->id }}" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Chưa có khóa học nào</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if ($courses->hasPages())
    <div class="pagination-wrapper">
        {{ $courses->links() }}
    </div>
    @endif
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Xóa khóa học</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa khóa học này? Hành động này không thể hoàn tác.
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-courses.css') }}">
@endpush

@push('scripts')
<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function() {
        const courseId = this.dataset.id;
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/admin/courses/${courseId}`;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    });
});
</script>
@endpush
@endsection