@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<div class="form-container">
    <h2>Đăng ký</h2>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

@if(session('info'))
    <div class="alert alert-info">{{ session('info') }}</div>
@endif

<!-- Registration Type Selection -->
<div class="registration-type mb-4">
    <div class="btn-group w-100" role="group">
        <input type="radio" class="btn-check" name="registration_type" id="user_type" value="user" checked>
        <label class="btn btn-outline-primary" for="user_type">Đăng ký học viên</label>

        <input type="radio" class="btn-check" name="registration_type" id="provider_type" value="provider">
        <label class="btn btn-outline-primary" for="provider_type">Đăng ký nhà cung cấp</label>
    </div>
</div>

<!-- User Registration Form -->
<div id="user_form">
    <form method="POST" action="/register">
        @csrf
        <input type="hidden" name="type" value="user">
        <input type="text" name="name" placeholder="Họ tên" value="{{ old('name') }}" required>
        @error('name') <span class="error">{{ $message }}</span> @enderror
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        @error('email') <span class="error">{{ $message }}</span> @enderror
        <input type="password" name="password" placeholder="Mật khẩu" required>
        @error('password') <span class="error">{{ $message }}</span> @enderror
        <input type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu" required>
        @error('password_confirmation') <span class="error">{{ $message }}</span> @enderror
        <button>Đăng ký học viên</button>
    </form>
</div>

<!-- Provider Registration Form -->
<div id="provider_form" style="display: none;">
    <form method="POST" action="/register" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" value="provider">
        <input type="text" name="name" placeholder="Họ tên" value="{{ old('name') }}" required>
        @error('name') <span class="error">{{ $message }}</span> @enderror
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        @error('email') <span class="error">{{ $message }}</span> @enderror

        <div class="mb-3">
            <label for="provider_info" class="form-label">Tài liệu xác minh (PDF, DOC, DOCX)</label>
            <input type="file" class="form-control" id="provider_info" name="provider_info"
                   accept=".pdf,.doc,.docx" required>
            <small class="form-text text-muted">Upload CV, giấy phép kinh doanh hoặc tài liệu chứng minh năng lực</small>
            @error('provider_info') <span class="error">{{ $message }}</span> @enderror
        </div>

        <button>Đăng ký nhà cung cấp</button>
    </form>
</div>

<a href="/login">Đã có tài khoản? Đăng nhập</a>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userType = document.getElementById('user_type');
    const providerType = document.getElementById('provider_type');
    const userForm = document.getElementById('user_form');
    const providerForm = document.getElementById('provider_form');

    function toggleForms() {
        if (userType.checked) {
            userForm.style.display = 'block';
            providerForm.style.display = 'none';
        } else {
            userForm.style.display = 'none';
            providerForm.style.display = 'block';
        }
    }

    userType.addEventListener('change', toggleForms);
    providerType.addEventListener('change', toggleForms);
});
</script>
@endsection
