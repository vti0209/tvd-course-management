@extends('layouts.master')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-sm border-0" style="max-width: 450px; width: 100%;">
        <div class="card-body p-5 text-center">
            <h3 class="mb-3">Quên mật khẩu?</h3>
            <p class="text-muted mb-4">Nhập email của bạn để nhận mật khẩu mới.</p>
            
            @if (session('status'))
                <div class="alert alert-success border-0 small">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger border-0 small">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="text-start">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold">Email đăng ký</label>
                    <input type="email" name="email" class="form-control" placeholder="example@gmail.com" required autofocus>
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary fw-bold py-2">Nhận mật khẩu mới</button>
                </div>
            </form>
            <div class="mt-4">
                <a href="{{ route('login') }}" class="text-decoration-none small text-secondary">← Quay lại đăng nhập</a>
            </div>
        </div>
    </div>
</div>
@endsection