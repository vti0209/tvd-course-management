@extends('admin.layout')

@section('title', 'Chi tiết khóa học - Duyệt nội dung')
@section('page_title', 'Chi tiết khóa học')

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('admin.content-moderation.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Status Alert -->
    <div class="alert alert-info d-flex justify-content-between align-items-center" role="alert">
        <div>
            <strong>Trạng thái khóa học:</strong>
            @if($course->status === 'pending')
            <span class="badge bg-warning text-dark ms-2"><i class="fas fa-hourglass-half"></i> Chờ phê duyệt</span>
            @elseif($course->status === 'active')
            <span class="badge bg-success ms-2"><i class="fas fa-check-circle"></i> Đã phê duyệt</span>
            <small class="ms-2">(Ngày: {{ $course->approved_at?->format('d/m/Y H:i') }})</small>
            @else
            <span class="badge bg-danger ms-2"><i class="fas fa-times-circle"></i> Bị từ chối</span>
            <small class="ms-2">(Ngày: {{ $course->rejected_at?->format('d/m/Y H:i') }})</small>
            @endif
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Course Information -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-book"></i> Thông tin khóa học</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Tiêu đề khóa học</h6>
                            <h5>{{ $course->title }}</h5>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Danh mục</h6>
                            <p>
                                <span class="badge bg-secondary">{{ $course->category->name }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Giá khóa học</h6>
                            <h5 class="text-success">₫{{ number_format($course->price, 0, ',', '.') }}</h5>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Thời lượng</h6>
                            <p>{{ $course->duration ?? 'Không xác định' }} giờ</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted">Mô tả khóa học</h6>
                        <div
                            style="max-height: 300px; overflow-y: auto; padding: 10px; background: #f8f9fa; border-radius: 5px;">
                            {!! nl2br(e($course->description)) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted">Ngày tạo</h6>
                            <p>{{ $course->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Cập nhật lần cuối</h6>
                            <p>{{ $course->updated_at?->format('d/m/Y H:i') ?? 'Chưa có cập nhật' }}</p>
                        </div>
                    </div>

                    <!-- Thumbnail -->
                    @if($course->thumbnail)
                    <div class="mt-3">
                        <h6 class="text-muted mb-2">Ảnh bìa</h6>
                        <img src="{{ asset($course->thumbnail) }}" alt="{{ $course->title }}" class="img-fluid rounded"
                            style="max-height: 300px;">
                    </div>
                    @endif
                </div>
            </div>

            <!-- Provider Information -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user"></i> Thông tin nhà cung cấp</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Tên nhà cung cấp</h6>
                            <p><strong>{{ $course->provider->full_name ?? $course->provider->username }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Email</h6>
                            <p>
                                <a href="mailto:{{ $course->provider->email }}">{{ $course->provider->email }}</a>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Số điện thoại</h6>
                            <p>{{ $course->provider->phone ?? 'Chưa cung cấp' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Trạng thái</h6>
                            <p>
                                @if($course->provider->status === 'active')
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Hoạt động</span>
                                @elseif($course->provider->status === 'pending')
                                <span class="badge bg-warning text-dark"><i class="fas fa-hourglass-half"></i> Chờ
                                    duyệt</span>
                                @else
                                <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Bị khóa</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('admin.providers.show', $course->provider->id) }}"
                        class="btn btn-sm btn-outline-info">
                        <i class="fas fa-user"></i> Xem hồ sơ nhà cung cấp
                    </a>
                </div>
            </div>

            <!-- Course Content -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-book-open"></i> Nội dung khóa học</h5>
                </div>
                <div class="card-body">
                    @if($course->chapters->count() > 0)
                    <div class="accordion" id="chaptersAccordion">
                        @foreach($course->chapters as $index => $chapter)
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#chapter{{ $chapter->id }}">
                                    <strong>Chương {{ $index + 1 }}:</strong> {{ $chapter->title }}
                                    <span class="badge bg-secondary ms-2">{{ $chapter->lessons->count() }} bài</span>
                                </button>
                            </h2>
                            <div id="chapter{{ $chapter->id }}"
                                class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                data-bs-parent="#chaptersAccordion">
                                <div class="accordion-body">
                                    @if($chapter->lessons->count() > 0)
                                    <ul class="list-group">
                                        @foreach($chapter->lessons as $lesson)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-0">{{ $lesson->title }}</h6>
                                                <small class="text-muted">{{ $lesson->duration ?? '—' }} phút</small>
                                            </div>
                                            <span class="badge bg-info">Bài {{ $loop->index + 1 }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <p class="text-muted text-center">Chương này chưa có bài học</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> Khóa học này chưa có chương hoặc bài học nào.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Rejection Reason (if rejected) -->
            @if($course->status === 'rejected' && $course->rejection_reason)
            <div class="card mb-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="fas fa-times-circle"></i> Lý do từ chối</h5>
                </div>
                <div class="card-body">
                    <div
                        style="padding: 10px; background: #fff3cd; border-left: 4px solid #ff6b6b; border-radius: 5px;">
                        {!! nl2br(e($course->rejection_reason)) !!}
                    </div>
                </div>
            </div>
            @endif

            <!-- Approval History -->
            @if($course->status === 'active' && $course->approved_by)
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-check-circle"></i> Lịch sử phê duyệt</h5>
                </div>
                <div class="card-body">
                    <p class="mb-1">
                        <strong>Admin phê duyệt:</strong>
                        {{ $course->approvedBy?->full_name ?? $course->approvedBy?->email ?? 'N/A' }}
                    </p>
                    <p class="mb-0">
                        <strong>Thời gian:</strong> {{ $course->approved_at->format('d/m/Y H:i:s') }}
                    </p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar - Actions -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0"><i class="fas fa-chart-bar"></i> Thống kê nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-1">Số chương</p>
                        <h5>{{ $course->chapters->count() }}</h5>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Số bài học</p>
                        <h5>{{ $course->lessons->count() }}</h5>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1">Số người đăng ký</p>
                        <h5>{{ $course->enrollments->count() }}</h5>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            @if($canApprove)
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="fas fa-check-circle"></i> Phê duyệt khóa học</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.content-moderation.approve', $course->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="notes" class="form-label">Ghi chú (tùy chọn)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                placeholder="Thêm ghi chú về khóa học..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="fas fa-check"></i> Phê duyệt ngay
                        </button>
                    </form>
                </div>
            </div>
            @endif

            @if($canReject)
            <!-- Reject Form -->
            <div class="card mb-4 border-danger">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0"><i class="fas fa-times-circle"></i> Từ chối khóa học</h6>
                </div>
                <div class="card-body">
                    @if($course->status === 'active')
                    <div class="alert alert-warning mb-3" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> 
                        <strong>Cảnh báo:</strong> Khóa học này đã được phê duyệt và hiển thị công khai. 
                        Từ chối sẽ ẩn khóa học và thông báo cho nhà cung cấp.
                    </div>
                    @endif
                    <form action="{{ route('admin.content-moderation.reject', $course->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="reason" class="form-label">Lý do từ chối <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="reason"
                                name="reason" rows="4" placeholder="Nhập lý do từ chối (tối thiểu 10 ký tự)..."
                                required></textarea>
                            @error('reason')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-times"></i> Từ chối
                        </button>
                    </form>
                </div>
            </div>

            <!-- Request Changes Form -->
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-edit"></i> Yêu cầu sửa đổi</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.content-moderation.request-changes', $course->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="changes" class="form-label">Yêu cầu sửa đổi <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" id="changes"
                                name="reason" rows="4" placeholder="Nhập yêu cầu sửa đổi..." required></textarea>
                            @error('reason')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <small class="text-muted d-block mb-3">
                            <i class="fas fa-info-circle"></i> Khóa học sẽ vẫn ở trạng thái "Chờ duyệt" để nhà cung cấp
                            có thể sửa đổi và tái nộp.
                        </small>
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-edit"></i> Yêu cầu sửa đổi
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Success Alert -->
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    const alert = new bootstrap.Alert(document.querySelector('.alert-success'));
    setTimeout(() => alert.close(), 5000);
});
</script>
@endif

@endsection

<style>
.accordion-button:not(.collapsed) {
    background-color: #f8f9fa;
    color: inherit;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid #dee2e6;
}

.card-header {
    border-bottom: 1px solid #dee2e6;
}

.list-group-item:first-child {
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
}
</style>