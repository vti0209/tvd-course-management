<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Yêu cầu rút tiền bị từ chối</title>
</head>
<body>
    <h1>Yêu cầu rút tiền bị từ chối</h1>
    <p>Xin chào {{ $withdrawal->provider->full_name ?? $withdrawal->provider->username }},</p>
    <p>Yêu cầu rút tiền <strong>#{{ $withdrawal->id }}</strong> đã bị từ chối.</p>
    <p>Lý do:</p>
    <blockquote>{{ $reason }}</blockquote>
    <p>Nếu bạn cần hỗ trợ thêm, vui lòng liên hệ bộ phận quản trị.</p>
    <p>Trân trọng,<br>Đội ngũ Gemini Academy</p>
</body>
</html>
