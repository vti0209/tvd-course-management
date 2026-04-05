
<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<div class="form-container">
    <h2>Đăng nhập</h2>

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="form-group">
            <input type="email" name="email" placeholder="Email" required>
        </div>

        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>

        <button class="btn-submit">Login</button>
    </form>

    <div class="form-footer">
        <a href="/register">Chưa có tài khoản? Đăng ký</a>
    </div>
</div>