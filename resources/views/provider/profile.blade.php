@extends('provider.layout')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">

            <h1>Hồ sơ nhà cung cấp</h1>

            {{-- Thông báo --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <hr>

            <div class="card mt-3">
                <div class="card-body">
                    {{-- Hiển thị Ảnh đại diện --}}
                    <div class="text-center mb-4">
                        <img src="{{ $provider->avatar ? asset('storage/' . $provider->avatar) : asset('images/default-avatar.png') }}" 
                             alt="Avatar" 
                             class="rounded-circle img-thumbnail" 
                             style="width: 150px; height: 150px; object-fit: cover;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên</label>
                        <input type="text" class="form-control bg-light" value="{{ $provider->full_name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control bg-light" value="{{ $provider->email }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tên đăng nhập</label>
                        <input type="text" class="form-control bg-light" value="{{ $provider->username }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Điện thoại</label>
                        <input type="text" class="form-control bg-light" value="{{ $provider->phone ?? 'Chưa cập nhật' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Trạng thái</label>
                        <input type="text" class="form-control bg-light" value="{{ ucfirst($provider->status) }}" readonly>
                    </div>

                </div>
            </div>

            <div class="mt-3 text-end">
                <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editModal">
                    <i class="fas fa-edit"></i> Chỉnh sửa thông tin
                </button>

                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#passwordModal">
                    <i class="fas fa-key"></i> Đổi mật khẩu
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal chỉnh sửa thông tin & Avatar --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('provider.updateProfile') }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chỉnh sửa thông tin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- Phần chọn ảnh đại diện --}}
                    <div class="mb-3 text-center">
                        <label class="form-label d-block text-start fw-bold">Hình đại diện</label>
                        <img id="preview-avatar" 
                             src="{{ $provider->avatar ? asset('storage/' . $provider->avatar) : asset('images/default-avatar.png') }}" 
                             alt="Preview" 
                             class="rounded-circle mb-2" 
                             style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #ddd;">
                        <input type="file" name="avatar" class="form-control" id="avatar-input" accept="image/*">
                        <div class="form-text">Định dạng: JPG, PNG, GIF. Tối đa 2MB.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Họ và tên</label>
                        <input type="text" name="full_name" class="form-control" value="{{ $provider->full_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email (Không thể thay đổi)</label>
                        <input type="email" class="form-control" value="{{ $provider->email }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Số điện thoại</label>
                        <input type="text" class="form-control" value="{{ $provider->phone }}" disabled>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal đổi mật khẩu --}}
<div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('provider.changePassword') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Đổi mật khẩu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="password" name="old_password" class="form-control" placeholder="Mật khẩu cũ" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="new_password" class="form-control" placeholder="Mật khẩu mới" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="confirm_password" class="form-control" placeholder="Xác nhận mật khẩu mới" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-warning">Xác nhận đổi</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Script để preview ảnh ngay khi chọn file --}}
<script>
    document.getElementById('avatar-input').onchange = evt => {
        const [file] = document.getElementById('avatar-input').files
        if (file) {
            document.getElementById('preview-avatar').src = URL.createObjectURL(file)
        }
    }
</script>

@endsection