<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu Provider bị từ chối</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #dc3545; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f8f9fa; }
        .reason { background-color: #fff; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Yêu cầu Provider của bạn đã bị từ chối</h1>
        </div>

        <div class="content">
            <p>Xin chào <strong>{{ $user->full_name ?? $user->username }}</strong>,</p>

            <p>Chúng tôi rất tiếc phải thông báo rằng yêu cầu trở thành Provider trên nền tảng <strong>Gemini Academy</strong> của bạn đã không được phê duyệt.</p>

            <div class="reason">
                <h3>Lý do từ chối:</h3>
                <p>{{ $reason }}</p>
            </div>

            <p>Bạn vẫn có thể:</p>
            <ul>
                <li>Đăng ký tài khoản người dùng thông thường để học các khóa học</li>
                <li>Nộp lại yêu cầu Provider sau khi khắc phục các vấn đề được nêu</li>
                <li>Liên hệ với đội ngũ hỗ trợ để được tư vấn thêm</li>
            </ul>

            <p>Nếu bạn có câu hỏi hoặc cần hỗ trợ thêm, vui lòng liên hệ với chúng tôi qua email hoặc các kênh hỗ trợ khác.</p>

            <p>Cảm ơn bạn đã quan tâm đến Gemini Academy!</p>

            <p>Trân trọng,<br>
            Đội ngũ Gemini Academy</p>
        </div>

        <div class="footer">
            <p>Email này được gửi tự động từ hệ thống Gemini Academy. Vui lòng không trả lời email này.</p>
        </div>
    </div>
</body>
</html>
