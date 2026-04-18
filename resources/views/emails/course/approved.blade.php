@component('mail::message')
# ✅ Khóa học của bạn đã được phê duyệt

Xin chúc mừng! Khóa học **{{ $course->title }}** của bạn đã được phê duyệt thành công và sẽ sớm xuất hiện trên nền tảng.

## Thông tin khóa học:
- **Tiêu đề:** {{ $course->title }}
- **Danh mục:** {{ $course->category->name }}
- **Giá:** ₫{{ number_format($course->price, 0, ',', '.') }}
- **Thời lượng:** {{ $course->duration ?? 'Không xác định' }} giờ
- **Ngày phê duyệt:** {{ $course->approved_at->format('d/m/Y H:i') }}

## Bước tiếp theo:
1. Khóa học của bạn hiện đã có thể nhìn thấy cho sinh viên
2. Hãy đảm bảo nội dung khóa học hoàn chỉnh và cập nhật
3. Theo dõi tương tác của sinh viên từ bảng điều khiển nhà cung cấp

Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi.

@component('mail::button', ['url' => route('provider.courses.index')])
Quản lý khóa học
@endcomponent

Cảm ơn bạn đã tin tưởng nền tảng của chúng tôi!

Trân trọng,<br>
{{ config('app.name') }} Team
@endcomponent
