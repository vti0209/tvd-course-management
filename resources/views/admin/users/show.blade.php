@extends('admin.layout')

@section('title', 'Chi tiết người dùng - ' . ($user->full_name ?? $user->username))
@section('page_title', 'Chi tiết người dùng')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Thông tin người dùng</h5>
                    <a href="/admin/users" class="btn btn-sm btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3 text-center mb-3">
                            @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="{{ $user->full_name }}" class="img-fluid rounded-circle"
                                style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                style="width: 150px; height: 150px;">
                                <i class="fas fa-user-circle" style="font-size: 80px; color: #ccc;"></i>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <h4 class="mb-3">{{ $user->full_name ?? $user->username }}</h4>

                            <div class="mb-2">
                                <strong>Tên đăng nhập:</strong> {{ $user->username }}
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong> {{ $user->email }}
                            </div>
                            <div class="mb-2">
                                <strong>Điện thoại:</strong> {{ $user->phone ?? 'Chưa cập nhật' }}
                            </div>
                            <div class="mb-2">
                                <strong>Vai trò:</strong>
                                <span
                                    class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'provider' ? 'success' : 'info') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                            <div class="mb-2">
                                <strong>Trạng thái:</strong>
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                    {{ $user->status === 'active' ? 'Hoạt động' : 'Bị khóa' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3"><strong>Thông tin chi tiết</strong></h6>
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>#{{ $user->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Ngày tạo:</strong></td>
                                    <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Cập nhật lần cuối:</strong></td>
                                    <td>{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'Chưa cập nhật' }}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Email được xác thực:</strong></td>
                                    <td>
                                        @if($user->email_verified_at)
                                        <i class="fas fa-check-circle text-success"></i>
                                        {{ $user->email_verified_at->format('d/m/Y H:i') }}
                                        @else
                                        <i class="fas fa-times-circle text-danger"></i> Chưa xác thực
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        @if($user->isProvider())
                        <div class="col-md-6">
                            <h6 class="mb-3"><strong>Thông tin Provider</strong></h6>
                            @if($user->provider_info)
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Tài liệu phê duyệt:</strong></td>
                                    <td>
                                        <a href="{{ $user->provider_info }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file-download"></i> Xem tài liệu
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Trạng thái:</strong></td>
                                    <td>
                                        @if($user->status === 'active')
                                        <span class="badge bg-success">Đã phê duyệt</span>
                                        @elseif($user->status === 'pending')
                                        <span class="badge bg-warning">Chờ phê duyệt</span>
                                        @else
                                        <span class="badge bg-danger">Bị từ chối</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            @else
                            <p class="text-muted">Chưa có tài liệu provider</p>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($user->isUser() || $user->isProvider())
            <div class="card mt-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Khóa học</h5>
                </div>
                <div class="card-body">
                    @if($user->courses->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Tên khóa học</th>
                                    <th>Ngày đăng ký</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->courses as $course)
                                <tr>
                                    <td>#{{ $course->id }}</td>
                                    <td>
                                        <a href="/admin/content-moderation/{{ $course->id }}" target="_blank">
                                            {{ $course->title }}
                                        </a>
                                    </td>
                                    <td>{{ $course->pivot->enrolled_at ? $course->pivot->enrolled_at->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $course->pivot->payment_status === 'paid' ? 'success' : 'warning' }}">
                                            {{ $course->pivot->payment_status === 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center py-4">
                        <i class="fas fa-inbox" style="font-size: 32px;"></i>
                        <br>Chưa đăng ký khóa học nào
                    </p>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Hành động</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form method="POST" action="/admin/users/{{ $user->id }}/status" id="statusForm">
                            @csrf
                            <input type="hidden" name="status" id="statusInput"
                                value="{{ $user->status === 'active' ? 'blocked' : 'active' }}">
                            <button type="submit"
                                class="btn btn-{{ $user->status === 'active' ? 'danger' : 'success' }} btn-sm">
                                <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }}"></i>
                                {{ $user->status === 'active' ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}
                            </button>
                        </form>

                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i> Xóa người dùng
                        </button>

                        <a href="/admin/users" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>

                    <hr class="my-3">

                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle"></i>
                        <strong>Ghi chú:</strong> Thay đổi trạng thái sẽ ảnh hưởng đến quyền truy cập của người dùng.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger bg-opacity-10">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-danger"></i> Xác nhận xóa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Bạn có chắc chắn muốn xóa người dùng <strong>{{ $user->username }}</strong>
                        ({{ $user->full_name }}) không?
                    </p>
                    <div class="alert alert-danger small">
                        <i class="fas fa-exclamation-circle"></i>
                        <strong>Cảnh báo:</strong> Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan đến người
                        dùng này sẽ bị xóa.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form method="POST" action="/admin/users/{{ $user->id }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Xóa vĩnh viễn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('statusForm').addEventListener('submit', function(e) {
    if (!confirm('Bạn có chắc chắn muốn thay đổi trạng thái người dùng này không?')) {
        e.preventDefault();
    }
});
</script>
@endpush
@endsection