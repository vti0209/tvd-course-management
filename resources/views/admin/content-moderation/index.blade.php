@extends('admin.layout')

@section('title', 'Duyệt nội dung khóa học')
@section('page_title', 'Duyệt nội dung khóa học')

@section('content')
<div class="container-fluid">
    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending"
                type="button" role="tab">
                <i class="fas fa-clock"></i> Chờ duyệt
                {{ $pendingCourses->total() > 0 ? '(' . $pendingCourses->total() . ')' : '' }}
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                <i class="fas fa-list"></i> Tất cả khóa học
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Pending Courses Tab -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            <div class="card">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0">Khóa học chờ phê duyệt</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tên khóa học</th>
                                <th>Nhà cung cấp</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingCourses as $course)
                            <tr>
                                <td><strong>#{{ $course->id }}</strong></td>
                                <td>{{ Str::limit($course->title, 40) }}</td>
                                <td>{{ $course->provider->full_name ?? $course->provider->username }}</td>
                                <td><span class="badge bg-secondary">{{ $course->category->name }}</span></td>
                                <td>{{ number_format($course->price, 0, ',', '.') }}đ</td>
                                <td>{{ $course->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="/admin/content-moderation/{{ $course->id }}" class="btn btn-sm btn-info"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                        data-bs-target="#approveModal{{ $course->id }}" title="Phê duyệt">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#rejectModal{{ $course->id }}" title="Từ chối">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </td>
                            </tr>

                            <!-- Approve Modal -->
                            <div class="modal fade" id="approveModal{{ $course->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Phê duyệt khóa học</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST"
                                            action="{{ route('admin.content-moderation.approve', $course->id) }}">
                                            @csrf
                                            <div class="modal-body">
                                                <p>Phê duyệt khóa học <strong>{{ $course->title }}</strong>?</p>
                                                <p class="text-muted small">Khóa học sẽ hiển thị công khai trên hệ thống
                                                </p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-success">Phê duyệt</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Reject Modal -->
                            <div class="modal fade" id="rejectModal{{ $course->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST"
                                            action="{{ route('admin.content-moderation.reject', $course->id) }}">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Từ chối khóa học</h5>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Bạn chắc chắn muốn từ chối khóa học
                                                    <strong>{{ $course->title }}</strong>?
                                                </p>

                                                <div class="mb-3">
                                                    <label class="form-label">Lý do từ chối:</label>
                                                    <textarea class="form-control" name="reason" rows="3" required
                                                        placeholder="Nhập lý do để thông báo cho giảng viên..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Hủy</button>
                                                <button type="submit" class="btn btn-danger">Xác nhận từ chối</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                                    <p class="text-muted mt-2">Không có khóa học chờ duyệt</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pendingCourses->total() > 0)
                <div class="card-footer bg-white">
                    {{ $pendingCourses->links() }}
                </div>
                @endif
            </div>
        </div>

        <!-- All Courses Tab -->
        <div class="tab-pane fade" id="all" role="tabpanel">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Tất cả khóa học</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Tên khóa học</th>
                                <th>Nhà cung cấp</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courses as $course)
                            <tr>
                                <td><strong>#{{ $course->id }}</strong></td>
                                <td>{{ Str::limit($course->title, 40) }}</td>
                                <td>{{ $course->provider->full_name ?? $course->provider->username }}</td>
                                <td><span class="badge bg-secondary">{{ $course->category->name }}</span></td>
                                <td>{{ number_format($course->price, 0, ',', '.') }}đ</td>
                                <td>
                                    @if($course->status === 'active')
                                    <span class="badge bg-success">Đang hoạt động</span>
                                    @elseif($course->status === 'pending')
                                    <span class="badge bg-warning">Chờ duyệt</span>
                                    @else
                                    <span class="badge bg-danger">Bị từ chối</span>
                                    @endif
                                </td>
                                <td>{{ $course->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="/admin/content-moderation/{{ $course->id }}" class="btn btn-sm btn-info"
                                        title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-inbox text-muted" style="font-size: 48px;"></i>
                                    <p class="text-muted mt-2">Không có khóa học nào</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($courses->total() > 0)
                <div class="card-footer bg-white">
                    {{ $courses->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection