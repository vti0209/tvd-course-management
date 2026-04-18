# 🚀 Content Moderation - Quick Start Guide

## 5 Phút để Bắt Đầu

### Step 1: Chạy Migration (1 min)
```bash
cd "d:\course management\tvd-course-management"
php artisan migrate
```

**Kết quả:** Bảng courses được cập nhật với các cột: `rejection_reason`, `approved_at`, `approved_by`, `rejected_at`

### Step 2: Kiểm tra Routes (1 min)
```bash
php artisan route:list | grep content-moderation
```

**Bạn sẽ thấy 8 routes:**
```
GET|HEAD   /admin/content-moderation
GET|HEAD   /admin/content-moderation/{course}
POST       /admin/content-moderation/{course}/approve
POST       /admin/content-moderation/{course}/reject
POST       /admin/content-moderation/{course}/request-changes
POST       /admin/content-moderation/bulk/approve
GET|HEAD   /admin/content-moderation/export/data
GET|HEAD   /admin/content-moderation/api/statistics
```

### Step 3: Test trong Tinker (1 min)
```bash
php artisan tinker

# Xem các khóa học pending
>>> $courses = App\Models\Course::where('status', 'pending')->get();
>>> $courses->count(); // Số lượng khóa học chờ phê duyệt

# Xem chi tiết một khóa học
>>> $course = $courses->first();
>>> $course->title;
>>> $course->provider->email;
>>> $course->getApprovalStatusLabel(); // ⏳ Chờ phê duyệt
```

### Step 4: Setup Email (1 min)
Kiểm tra file `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=your-email@gmail.com
```

### Step 5: Start Queue Worker (1 min - tùy chọn)
Nếu muốn gửi email async:
```bash
php artisan queue:work
```

---

## 💡 Ví Dụ Sử Dụng

### ✅ Phê duyệt khóa học
```bash
curl -X POST "http://localhost:8000/admin/content-moderation/1/approve" \
  -H "Content-Type: application/json" \
  -d '{"notes": "Nội dung tốt, có thể xuất bản"}'
```

**Response:**
```json
{
  "success": true,
  "message": "✅ Khóa học 'Learn PHP' đã được phê duyệt thành công!"
}
```

**Tự động:**
- ✉️ Email gửi đến nhà cung cấp
- 📝 Hành động được ghi log
- 🕐 `approved_at` được set
- 👤 `approved_by` lưu admin ID

---

### ❌ Từ chối khóa học
```bash
curl -X POST "http://localhost:8000/admin/content-moderation/1/reject" \
  -H "Content-Type: application/json" \
  -d '{"reason": "Video chất lượng thấp, vui lòng quay lại HD"}'
```

**Response:**
```json
{
  "success": true,
  "message": "❌ Khóa học 'Learn PHP' đã bị từ chối"
}
```

**Tự động:**
- ✉️ Email gửi lý do từ chối
- 📝 Hành động được ghi log
- 💾 Lý do lưu trong `rejection_reason`

---

### ✏️ Yêu cầu sửa đổi
```bash
curl -X POST "http://localhost:8000/admin/content-moderation/1/request-changes" \
  -H "Content-Type: application/json" \
  -d '{"reason": "Vui lòng thêm phần thực hành"}'
```

**Khác vs "reject":**
- Khóa học vẫn ở trạng thái `pending`
- Nhà cung cấp có thể sửa & tái nộp
- vs `reject` = khóa học bị từ chối hoàn toàn

---

### 📊 Lọc khóa học
```bash
# Lấy khóa học pending
curl "http://localhost:8000/admin/content-moderation?status=pending"

# Lấy khóa học của 1 nhà cung cấp
curl "http://localhost:8000/admin/content-moderation?provider_id=5"

# Tìm kiếm
curl "http://localhost:8000/admin/content-moderation?search=PHP&status=pending"

# Sắp xếp & phân trang
curl "http://localhost:8000/admin/content-moderation?sort=newest&page=2"
```

---

### 🔁 Phê duyệt hàng loạt
```bash
curl -X POST "http://localhost:8000/admin/content-moderation/bulk/approve" \
  -H "Content-Type: application/json" \
  -d '{"course_ids": [1, 2, 3, 4, 5]}'
```

---

### 📈 Lấy thống kê
```bash
curl "http://localhost:8000/admin/content-moderation/api/statistics"
```

**Response:**
```json
{
  "total": 150,
  "pending": 45,
  "active": 100,
  "rejected": 5
}
```

---

### 📥 Xuất dữ liệu
```bash
curl "http://localhost:8000/admin/content-moderation/export/data?status=pending" \
  | python -m json.tool
```

---

## 🧪 Quy Trình Test

### Test Approval Flow
```php
// 1. Tạo khóa học test
$course = Course::factory()->create([
    'status' => 'pending',
    'provider_id' => 1,
]);

// 2. Lấy admin
$admin = User::where('role', 'admin')->first();

// 3. Sử dụng service
$service = new ContentModerationService();
$service->approveCourse($course, $admin);

// 4. Kiểm tra kết quả
$course->refresh();
assert($course->status === 'active');
assert($course->approved_at !== null);
assert($course->approved_by === $admin->id);
```

---

## 🔍 Xem Logs

### Admin Actions Log
```bash
tail -50 storage/logs/admin_actions.log
```

**Ví dụ:**
```
[2026-04-19 14:30:00] Content Moderation Action
Message: COURSE_APPROVED - Đã phê duyệt khóa học: Learn PHP (ID: 1)
Data: {
  action: "approve",
  course_id: 1,
  admin_id: 1,
  status: "success"
}
```

---

## 🚨 Troubleshooting

### Problem: Email không gửi
```bash
# Check mail config
php artisan tinker
>>> config('mail.driver');  // Should be 'smtp'

# Test gửi email
>>> Mail::raw('Test', function($m) { $m->to('test@example.com'); });
```

### Problem: Migration error
```bash
# Rollback nếu lỗi
php artisan migrate:rollback

# Chạy lại
php artisan migrate
```

### Problem: Route not found
```bash
# Clear route cache
php artisan route:clear

# Re-cache
php artisan route:cache
```

---

## 📚 Tài Liệu Chi Tiết

Xem file đầy đủ:
- `BACKEND_CONTENT_MODERATION.md` - API Documentation (100+ lines)
- `CONTENT_MODERATION_CHECKLIST.md` - Implementation Details (200+ lines)

---

## ✨ Features Summary

| Feature | Status | Notes |
|---------|--------|-------|
| List courses | ✅ | With filtering & pagination |
| View details | ✅ | Full course info |
| Approve course | ✅ | Auto email & logging |
| Reject course | ✅ | With detailed reason |
| Request changes | ✅ | Alternative to reject |
| Bulk approve | ✅ | Multiple at once |
| Export data | ✅ | JSON format |
| Statistics | ✅ | Dashboard metrics |
| Email notifications | ✅ | Queued for performance |
| Audit logging | ✅ | All actions tracked |
| Authorization | ✅ | Admin only |
| Validation | ✅ | Form requests |
| Transactions | ✅ | Database consistency |
| Error handling | ✅ | Try-catch & logging |

---

## 🎯 Next Steps

1. **Create Frontend Views**
   - Moderation dashboard
   - Course detail view
   - Approve/reject forms

2. **Add Tests**
   - Unit tests for service
   - Feature tests for endpoints
   - API tests with Postman

3. **Monitor in Production**
   - Track approval times
   - Monitor email delivery
   - Log queue failures

4. **Optimize**
   - Add caching for stats
   - Index database columns
   - Implement search optimization

---

## 💬 Support

Questions? Check:
1. This guide (Quick Start)
2. `BACKEND_CONTENT_MODERATION.md` (Full API)
3. `CONTENT_MODERATION_CHECKLIST.md` (Implementation)
4. Storage logs (`storage/logs/laravel.log`)

---

**Ready to go!** 🚀

Run `php artisan migrate` and start approving courses.
