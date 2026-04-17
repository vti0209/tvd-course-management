<!DOCTYPE html>
<html>
<body>
    <h2>Mật khẩu mới của bạn</h2>
    <p>Chào bạn, mật khẩu đăng nhập mới là: <strong style="color: #e74c3c; font-size: 18px;">{{ $newPassword }}</strong></p>
    <p>Hãy dùng mật khẩu này để đăng nhập và nhớ đổi lại mật khẩu ngay nhé.</p>
    <a href="{{ url('/login') }}">Đến trang Đăng nhập</a>
</body>
</html>