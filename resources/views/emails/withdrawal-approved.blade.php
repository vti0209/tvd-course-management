<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Yêu cầu rút tiền được phê duyệt</title>
</head>
<body>
    <h1>Yêu cầu rút tiền đã được phê duyệt</h1>
    <p>Xin chào {{ $withdrawal->provider->full_name ?? $withdrawal->provider->username }},</p>
    <p>Yêu cầu rút tiền <strong>#{{ $withdrawal->id }}</strong> với số tiền <strong>{{ number_format($withdrawal->amount, 0, '.', ',') }} đ</strong> đã được phê duyệt.</p>
    <p>Thông tin thanh toán:</p>
    <ul>
        <li>Ngân hàng: {{ $withdrawal->bank_name }}</li>
        <li>Tài khoản: {{ $withdrawal->bank_account }}</li>
        <li>Chủ tài khoản: {{ $withdrawal->account_holder }}</li>
    </ul>
    <p>Chúng tôi sẽ tiếp tục xử lý thanh toán và thông báo lại khi hoàn tất.</p>
    <p>Trân trọng,<br>Đội ngũ Gemini Academy</p>
</body>
</html>
