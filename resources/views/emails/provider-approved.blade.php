<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu Provider được phê duyệt</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #28a745; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f8f9fa; }
        .credentials { background-color: #fff; padding: 15px; border-left: 4px solid #28a745; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Chúc mừng! Yêu cầu của bạn đã được phê duyệt</h1>
        </div>

        <div class="content">
            <p>Xin chào <strong>{{ $user->full_name ?? $user->username }}</strong>,</p>

            <p>Chúng tôi vui mừng thông báo rằng yêu cầu trở thành Provider trên nền tảng <strong>Gemini Academy</strong> của bạn đã được phê duyệt thành công!</p>

            <p>Bây giờ bạn có thể đăng nhập vào tài khoản của mình và bắt đầu tạo các khóa học tuyệt vời.</p>

            <div class="credentials">
                <h3>Thông tin đăng nhập:</h3>
                <p><strong>Email:</strong> {{ $email }}</p>
                <p><strong>Mật khẩu:</strong> {{ $password }}</p>
                <p style="color: #dc3545; font-weight: bold;">⚠️ Vui lòng đổi mật khẩu ngay sau khi đăng nhập lần đầu!</p>
            </div>

            <p>Để bắt đầu:</p>
            <ol>
                <li>Truy cập <a href="{{ url('/login') }}">trang đăng nhập</a></li>
                <li>Đăng nhập với thông tin ở trên</li>
                <li>Đổi mật khẩu trong phần cài đặt tài khoản</li>
                <li>Bắt đầu tạo khóa học đầu tiên của bạn!</li>
            </ol>

            <p>Nếu bạn có bất kỳ câu hỏi nào, đừng ngần ngại liên hệ với đội ngũ hỗ trợ của chúng tôi.</p>

            <p>Chúc bạn thành công với các khóa học của mình!</p>

            <p>Trân trọng,<br>
            Đội ngũ Gemini Academy</p>
        </div>

        <div class="footer">
            <p>Email này được gửi tự động từ hệ thống Gemini Academy. Vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>
</html>