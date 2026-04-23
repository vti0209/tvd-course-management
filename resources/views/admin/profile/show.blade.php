@extends('admin.layout')

@section('title', 'Hồ sơ Admin - Gemini Academy')
@section('page_title', 'Hồ sơ của tôi')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-3">
            <!-- Profile Card -->
            <div class="card profile-card shadow-sm">
                <div class="card-body text-center">
                    @if($admin->avatar && $admin->avatar != 'default-avatar.png')
                        <img src="{{ asset('uploads/' . $admin->avatar) }}" alt="Avatar" class="rounded-circle profile-avatar mb-3">
                    @else
                        <div class="profile-avatar-placeholder rounded-circle mx-auto mb-3">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                    
                    <h5 class="card-title">{{ $admin->full_name ?? 'Admin' }}</h5>
                    <p class="text-muted">{{ $admin->role ?? 'Quản trị viên' }}</p>
                    <p class="text-muted small">{{ $admin->email }}</p>
                    
                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary btn-sm w-100 mt-3">
                        <i class="fas fa-edit"></i> Chỉnh sửa hồ sơ
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <!-- Profile Details -->
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle"></i> Thông tin chi tiết
                    </h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Có lỗi xảy ra!</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Họ và tên</label>
                            <p class="text-dark fw-500">{{ $admin->full_name ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Email</label>
                            <p class="text-dark fw-500">{{ $admin->email }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Số điện thoại</label>
                            <p class="text-dark fw-500">{{ $admin->phone ?? 'Chưa cập nhật' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Vai trò</label>
                            <p class="text-dark fw-500">
                                <span class="badge bg-danger">{{ $admin->role ?? 'Quản trị viên' }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Trạng thái</label>
                            <p class="text-dark fw-500">
                                @if($admin->status == 'active')
                                    <span class="badge bg-success">Hoạt động</span>
                                @elseif($admin->status == 'blocked')
                                    <span class="badge bg-danger">Bị chặn</span>
                                @else
                                    <span class="badge bg-warning">{{ $admin->status }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Ngày tạo tài khoản</label>
                            <p class="text-dark fw-500">{{ optional($admin->created_at)->format('d/m/Y H:i') ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Cập nhật lần cuối</label>
                            <p class="text-dark fw-500">{{ optional($admin->updated_at)->format('d/m/Y H:i') ?? 'Chưa cập nhật' }}</p>
                        </div>
                    </div>

                    <hr>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Chỉnh sửa thông tin
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-card {
        border: none;
        border-top: 4px solid #007bff;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border: 3px solid #007bff;
    }

    .profile-avatar-placeholder {
        width: 120px;
        height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f0f0;
        border: 3px solid #007bff;
        margin-left: auto;
        margin-right: auto;
        font-size: 50px;
        color: #007bff;
    }

    .fw-500 {
        font-weight: 500;
    }
</style>
@endsection
