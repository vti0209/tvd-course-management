<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khóa Học Được Phê Duyệt</title>
    <link rel="stylesheet" href="{{ asset('css/email.css') }}">
</head>
<body>
    <div class="container">
        <div class="header success">
            <h1>Khóa Học Được Phê Duyệt</h1>
        </div>
        
        <div class="content">
            <p>Xin chào <strong>{{ $course->provider->full_name ?? $course->provider->username }}</strong>,</p>
            
            <p>Chúc mừng! Khóa học <strong>"{{ $course->title }}"</strong> của bạn đã được <strong style="color: #28a745;">phê duyệt</strong> bởi quản trị viên hệ thống.</p>
            
            <div class="info-box">
                <h3>Thông Tin Khóa Học</h3>
                <ul>
                    <li><strong>Tên:</strong> {{ $course->title }}</li>
                    <li><strong>Danh mục:</strong> {{ $course->category->name }}</li>
                    <li><strong>Giá:</strong> ₫{{ number_format($course->price, 0, ',', '.') }}</li>
                    <li><strong>Ngày phê duyệt:</strong> {{ $course->approved_at?->format('d/m/Y H:i') ?? now()->format('d/m/Y H:i') }}</li>
                </ul>
            </div>
            
            <h3>Bước Tiếp Theo</h3>
            <ul>
                <li>Khóa học của bạn hiển thị công khai trên nền tảng</li>
                <li>Có sẵn để người học đăng ký</li>
                <li>Sẵn sàng bắt đầu</li>
            </ul>
            
            <h3>Quản Lý Khóa Học</h3>
            <ul>
                <li>Theo dõi số lượng người đăng ký</li>
                <li>Cập nhật nội dung bài học</li>
                <li>Xem chi tiết học viên</li>
                <li>Quản lý tài chính</li>
            </ul>
        </div>
        
        <div class="footer">
            <p>Cảm ơn bạn đã tin tưởng và góp phần phong phú thêm kho tài nguyên học tập của chúng tôi!</p>
            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với đội hỗ trợ của chúng tôi.</p>
            <p>&copy; 2026 TVD Learning. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
