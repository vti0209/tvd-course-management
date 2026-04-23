@extends('admin.layout')

@section('title', 'Chỉnh sửa hồ sơ - Gemini Academy')
@section('page_title', 'Chỉnh sửa hồ sơ')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-user-edit"></i> Chỉnh sửa thông tin cá nhân
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong><i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Avatar Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label class="form-label fw-bold">Ảnh đại diện</label>
                                <div class="avatar-upload-section">
                                    <div class="avatar-preview mb-3">
                                        @if($admin->avatar && $admin->avatar != 'default-avatar.png')
                                            <img id="avatarPreview" src="{{ asset('uploads/' . $admin->avatar) }}" alt="Avatar" class="rounded avatar-image">
                                        @else
                                            <div id="avatarPreview" class="avatar-placeholder rounded d-flex align-items-center justify-content-center">
                                                <i class="fas fa-user fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <input type="file" name="avatar" id="avatarInput" class="form-control @error('avatar') is-invalid @enderror" accept="image/*" onchange="previewAvatar(event)">
                                    <small class="text-muted d-block mt-2">Chọn một file ảnh (jpeg, png, jpg) không quá 2MB</small>
                                    @error('avatar')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="full_name" class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name', $admin->full_name) }}" required>
                                @error('full_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="phone" class="form-label fw-bold">Số điện thoại</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $admin->phone) }}" placeholder="Ví dụ: 0123456789">
                                @error('phone')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Vai trò</label>
                                <input type="text" class="form-control" value="{{ ucfirst($admin->role) }}" disabled>
                                <small class="text-muted">Vai trò không thể thay đổi</small>
                            </div>
                        </div>

                        <hr>

                        <!-- Change Password Section -->
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-lock"></i> Thay đổi mật khẩu
                        </h6>

                        <div class="alert alert-info mb-3" role="alert">
                            <i class="fas fa-info-circle"></i> Để không thay đổi mật khẩu, hãy để trống các trường mật khẩu
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-bold">Mật khẩu mới</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Nhập mật khẩu mới (nếu muốn)">
                                @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Tối thiểu 6 ký tự</small>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-bold">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu mới">
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.profile.show') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-upload-section {
        padding: 20px;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        background-color: #f8f9fa;
    }

    .avatar-preview {
        display: flex;
        justify-content: center;
        margin-bottom: 15px;
    }

    .avatar-image {
        max-width: 150px;
        max-height: 150px;
        object-fit: cover;
        border: 3px solid #007bff;
    }

    .avatar-placeholder {
        width: 150px;
        height: 150px;
        background-color: #fff;
        border: 3px solid #007bff;
    }

    .form-label {
        color: #333;
    }

    .text-danger {
        color: #dc3545;
    }
</style>

<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('avatarPreview');
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                } else {
                    // Replace placeholder with image
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'rounded avatar-image';
                    img.id = 'avatarPreview';
                    preview.parentElement.replaceChild(img, preview);
                }
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
