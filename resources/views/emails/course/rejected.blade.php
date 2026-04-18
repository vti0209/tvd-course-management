@component('mail::message')
# ❌ Khóa học cần chỉnh sửa

Khóa học **{{ $course->title }}** của bạn cần được chỉnh sửa trước khi có thể được phê duyệt.

## Thông tin khóa học:
- **Tiêu đề:** {{ $course->title }}
- **Danh mục:** {{ $course->category->name }}
- **Trạng thái hiện tại:** Chờ chỉnh sửa

## Lý do từ chối:
```
{{ $reason }}
```

## Hành động cần thực hiện:
1. Đăng nhập vào tài khoản nhà cung cấp
2. Chỉnh sửa khóa học theo lý do được nêu trên
3. Tái nộp khóa học để xem xét

Chúng tôi sẵn sàng phê duyệt khóa học của bạn sau khi bạn thực hiện các thay đổi cần thiết.

@component('mail::button', ['url' => route('provider.courses.index')])
Chỉnh sửa khóa học
@endcomponent

Nếu bạn không đồng ý với phản hồi này hoặc có bất kỳ câu hỏi nào, vui lòng liên hệ với bộ phận hỗ trợ.

Trân trọng,<br>
{{ config('app.name') }} Team
@endcomponent
