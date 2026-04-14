@extends('layouts.master')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Thông tin cá nhân</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <img src="{{ $user->avatar ? asset('uploads/'.$user->avatar) : asset('images/default-avatar.png') }}"
                                 class="rounded-circle img-thumbnail shadow-sm"
                                 style="width: 150px; height: 150px; object-fit: cover;"
                                 alt="Avatar">
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Họ và tên:</th>
                                    <td>{{ $user->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <th>Vai trò:</th>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tham gia:</th>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                            <div class="mt-3">
                                <a href="{{ route('user.profile.edit') }}" class="btn btn-warning px-4">
                                    <i class="fas fa-edit"></i> Chỉnh sửa hồ sơ
                                </a>
                                <a href="{{ route('user.my_courses') }}" class="btn btn-outline-primary px-4">
                                    Khóa học của tôi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
