@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <h2>Chỉnh sửa thông tin cá nhân</h2>
    <hr>
    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Họ và tên</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email (Không thể thay đổi)</label>
            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Ảnh đại diện</label>
            <div class="mb-2">
                <img src="{{ asset($user->avatar ? 'uploads/'.$user->avatar : 'images/default-avatar.png') }}"
                     width="100" class="img-thumbnail">
            </div>
            <input type="file" name="avatar" class="form-control">
            @error('avatar') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        <a href="{{ route('user.profile') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection
