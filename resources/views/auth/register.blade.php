@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<div class="form-container">
    <h2>Đăng ký</h2>

@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

<form method="POST" action="/register">
    @csrf
    <input type="text" name="name" placeholder="Name" value="{{ old('name') }}">
    @error('name') <span class="error">{{ $message }}</span> @enderror
    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
    @error('email') <span class="error">{{ $message }}</span> @enderror
    <input type="password" name="password" placeholder="Password">
    @error('password') <span class="error">{{ $message }}</span> @enderror
    <input type="password" name="password_confirmation" placeholder="Confirm Password">
    @error('password_confirmation') <span class="error">{{ $message }}</span> @enderror
    <button>Register</button>
</form>

<a href="/login">Đã có tài khoản? Đăng nhập</a>
@endsection