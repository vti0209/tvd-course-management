@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">

<div class="form-container">
    <h2>Đăng nhập</h2>

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    @if(session('success'))
        <script>alert('{{ session('success') }}');</script>
    @endif

    <form method="POST" action="/login">
        @csrf

        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        @error('email') <span class="error">{{ $message }}</span> @enderror
        <input type="password" name="password" placeholder="Password" required>
        @error('password') <span class="error">{{ $message }}</span> @enderror

        <button class="btn-submit">Login</button>
    </form>

    <div class="form-footer">
    <div style="margin-bottom: 10px;">
        <a href="{{ route('password.request') }}" class="forgot-password">Quên mật khẩu?</a>
    </div>
    
    <a href="/register">Chưa có tài khoản? Đăng ký</a>
</div>
</div>
@endsection