<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khóa Học Bị Từ Chối</title>
    <link rel="stylesheet" href="{{ asset('css/email.css') }}">
</head>
<body>
    <div class="container">
        <div class="header danger">
            <h1>Khóa Học Bị Từ Chối</h1>
        </div>

        <div class="content">
            <p>Xin chào <strong>{{ $course->provider->full_name ?? $course->provider->username }}</strong>,</p>

            <p>Rất tiếc, khóa học của bạn <strong>"{{ $course->title }}"</strong> đã bị từ chối bởi quản trị viên hệ thống.</p>

            <h3>Lý Do Từ Chối:</h3>
            <div class="reason">
                {!! nl2br(e($reason)) !!}
            </div>

            <h3>Hành Động Tiếp Theo:</h3>
            <ul>
                <li>Cập nhật nội dung khóa học theo gợi ý từ chối</li>
                <li>Tái nộp khóa học để được xem xét lại</li>
                <li>Liên hệ với quản trị viên nếu bạn có thắc mắc</li>
            </ul>

            <p><strong>Thời gian từ chối:</strong> {{ $course->rejected_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}</p>
        </div>

        <div class="footer">
            <p>Cảm ơn bạn đã tin tưởng nền tảng của chúng tôi.</p>
            <p>&copy; 2026 TVD Learning. All rights reserved.</p>
        </div>
    </div>
</body>
</html>