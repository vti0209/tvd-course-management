@extends('provider.layout')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h1>Hồ sơ nhà cung cấp</h1>
            <hr>

            <div class="card">
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
                        <input type="text" class="form-control" value="{{ $provider->phone ?? 'Chưa cập nhật' }}"
                            readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <input type="text" class="form-control" value="{{ ucfirst($provider->status) }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection