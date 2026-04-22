@extends('admin.layout')

@section('title', 'Chi tiết nhà cung cấp')
@section('page_title', 'Chi tiết nhà cung cấp')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('admin.providers.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Status Alert -->
    <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
        <div>
            <strong>Trạng thái nhà cung cấp:</strong>
            @if($provider->status === 'active')
            <span class="badge bg-success ms-2"><i class="fas fa-check-circle"></i> Đã phê duyệt</span>
            <small class="ms-2">(Ngày: {{ $provider->updated_at?->format('d/m/Y H:i') ?? 'N/A' }})</small>
            @elseif($provider->status === 'pending')
            <span class="badge bg-warning text-dark ms-2"><i class="fas fa-hourglass-half"></i> Chờ phê duyệt</span>
            @else
            <span class="badge bg-danger ms-2"><i class="fas fa-times-circle"></i> Bị khóa</span>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Provider Information -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Thông tin nhà cung cấp</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Tên đăng nhập</h6>
                            <p><strong>{{ $provider->username }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Tên đầy đủ</h6>
                            <p><strong>{{ $provider->full_name ?? 'Chưa cung cấp' }}</strong></p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Email</h6>
                            <p>
                                <a href="mailto:{{ $provider->email }}">{{ $provider->email }}</a>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Số điện thoại</h6>
                            <p>{{ $provider->phone ?? 'Chưa cung cấp' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
    <h6 class="text-muted">Ngày đăng ký</h6>
    <p>{{ $provider->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</p>
</div>
<div class="col-md-6">
    <h6 class="text-muted">Cập nhật lần cuối</h6>
    <p>{{ $provider->updated_at?->format('d/m/Y H:i') ?? 'Chưa có cập nhật' }}</p>
</div>
                    </div>

                    @if($provider->provider_info)
                    <div class="mb-3">
                        <h6 class="text-muted">Tài liệu xác minh</h6>
                        <a href="{{ asset($provider->provider_info) }}" class="btn btn-sm btn-info" target="_blank">
                            <i class="fas fa-file-download"></i> Xem tài liệu
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Courses Information -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">📚 Khóa học ({{ $stats['total_courses'] }})</h5>
                </div>
                <div class="card-body">
                    @if($courses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Danh mục</th>
                                    <th>Trạng thái</th>
                                    <th>Học viên</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($courses as $course)
                                <tr>
                                    <td>
                                        <strong>{{ Str::limit($course->title, 30) }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $course->category->name }}</span>
                                    </td>
                                    <td>
                                        @if($course->status === 'pending')
                                        <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half"></i>
                                            Chờ duyệt</span>
                                        @elseif($course->status === 'active')
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Đã phê
                                            duyệt</span>
                                        @else
                                        <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Bị từ
                                            chối</span>
                                        @endif
                                    </td>
                                    <td>{{ $course->enrollments->count() }}</td>
                                    <td>{{ $course->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.content-moderation.show', $course->id) }}"
                                            class="btn btn-sm btn-info" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info" role="alert">
                        <i class="fas fa-info-circle"></i> Nhà cung cấp này chưa có khóa học nào.
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar - Statistics -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Thống kê</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">Tổng khóa học</p>
                        <h5>{{ $stats['total_courses'] }}</h5>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Khóa học hoạt động</p>
                        <h5 class="text-success">{{ $stats['active_courses'] }}</h5>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Khóa học chờ duyệt</p>
                        <h5 class="text-warning">{{ $stats['pending_courses'] }}</h5>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Khóa học bị từ chối</p>
                        <h5 class="text-danger">{{ $stats['rejected_courses'] }}</h5>
                    </div>
                    <hr>
                    <div class="mb-0">
                        <p class="text-muted mb-1">Tổng học viên</p>
                        <h5>{{ $stats['total_students'] }}</h5>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($provider->status === 'pending')
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0">✅ Phê duyệt nhà cung cấp</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.providers.approve', $provider->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check"></i> Phê duyệt ngay
                        </button>
                    </form>
                </div>
            </div>

            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">❌ Từ chối nhà cung cấp</h6>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                        data-bs-target="#rejectModal">
                        <i class="fas fa-times"></i> Từ chối
                    </button>
                </div>
            </div>

            <!-- Reject Modal -->
            <div class="modal fade" id="rejectModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Từ chối nhà cung cấp</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="{{ route('admin.providers.reject', $provider->id) }}">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="reason" class="form-label">Lý do từ chối <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('reason') is-invalid @enderror" id="reason"
                                        name="reason" rows="4" placeholder="Nhập lý do từ chối..." required></textarea>
                                    @error('reason')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-danger">Từ chối</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

<style>
.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid #dee2e6;
}

.card-header {
    border-bottom: 1px solid #dee2e6;
}

.table-hover tbody tr:hover {
    background-color: #f5f5f5;
}
</style>