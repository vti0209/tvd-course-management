```blade
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

                    <div class="mb-3">
                        <label class="form-label">Tên</label>
                        <input type="text" class="form-control" value="{{ $provider->full_name }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $provider->email }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control" value="{{ $provider->username }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Điện thoại</label>
                        <input type="text" class="form-control" value="{{ $provider->phone ?? 'Chưa cập nhật' }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <input type="text" class="form-control" value="{{ ucfirst($provider->status) }}" readonly>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <div class="mt-3 text-end">
    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#editModal">
        Chỉnh sửa thông tin
    </button>

    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#passwordModal">
        Đổi mật khẩu
    </button>
</div>

{{-- Modal chỉnh sửa --}}
<div class="modal fade" id="editModal">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('provider.updateProfile') }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5>Chỉnh sửa thông tin</h5>
            </div>
            <div class="modal-body">

                <input type="text" name="full_name" class="form-control mb-2" value="{{ $provider->full_name }}">

                <input type="email" class="form-control mb-2" value="{{ $provider->email }}" disabled>

                <input type="text" class="form-control mb-2" value="{{ $provider->phone }}" disabled>

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                <button class="btn btn-primary">Lưu</button>
            </div>
        </div>
    </form>
  </div>
</div>

{{-- Modal đổi mật khẩu --}}
<div class="modal fade" id="passwordModal">
  <div class="modal-dialog">
    <form method="POST" action="{{ route('provider.changePassword') }}">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h5>Đổi mật khẩu</h5>
            </div>
            <div class="modal-body">

                <input type="password" name="old_password" class="form-control mb-2" placeholder="Mật khẩu cũ">
                <input type="password" name="new_password" class="form-control mb-2" placeholder="Mật khẩu mới">
                <input type="password" name="confirm_password" class="form-control" placeholder="Xác nhận mật khẩu">

            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                <button class="btn btn-warning">Đổi</button>
            </div>
        </div>
    </form>
  </div>
</div>

@endsection
