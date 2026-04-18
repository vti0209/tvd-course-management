# 📋 Content Moderation Backend - Hướng dẫn Chi tiết

## 📑 Mục lục
1. [Tổng Quan](#tổng-quan)
2. [Kiến Trúc](#kiến-trúc)
3. [Tính Năng](#tính-năng)
4. [API Endpoints](#api-endpoints)
5. [Database](#database)
6. [Quy Trình Phê Duyệt](#quy-trình-phê-duyệt)
7. [Cách Sử Dụng](#cách-sử-dụng)
8. [Security](#security)
9. [Logging](#logging)

---

## 🎯 Tổng Quan

Hệ thống **Content Moderation** cho phép Admin quản lý và phê duyệt nội dung khóa học trước khi xuất bản. Được xây dựng với:
- ✅ **Service Layer** cho logic phức tạp
- ✅ **Form Requests** cho validation
- ✅ **Database Transactions** cho tính toàn vẹn dữ liệu
- ✅ **Email Notifications** cho nhà cung cấp
- ✅ **Comprehensive Logging** để audit trail
- ✅ **Error Handling** & Exception Management

---

## 🏗️ Kiến Trúc

### File Structure
```
app/
├── Http/
│   ├── Controllers/Admin/
│   │   └── ContentModerationController.php    [1]
│   └── Requests/
│       ├── FilterCoursesRequest.php           [2]
│       ├── ApproveCourseRequest.php           [2]
│       └── RejectCourseRequest.php            [2]
├── Mail/
│   ├── CourseApprovedMail.php                 [3]
│   └── CourseRejectedMail.php                 [3]
├── Models/
│   └── Course.php                             [4]
├── Services/
│   └── ContentModerationService.php           [5]
└── Traits/
    └── LogsAdminActions.php                   [6]

resources/views/emails/course/
├── approved.blade.php                        [7]
└── rejected.blade.php                        [7]

database/migrations/
└── 2026_04_19_add_approval_fields_to_courses.php  [8]

routes/web.php                                [9]
```

### Layer Diagram
```
┌─────────────────────────────┐
│  ContentModerationController │ - HTTP Requests
│         (Controller)         │   - Response
└──────────────┬──────────────┘
               │
┌──────────────▼──────────────┐
│  ContentModerationService    │ - Business Logic
│      (Service Layer)         │ - Validation
└──────────────┬──────────────┘
               │
┌──────────────▼──────────────┐
│    Models & Database         │ - Persistence
│   (Course, User, etc)       │ - Relationships
└──────────────────────────────┘
```

---

## ✨ Tính Năng

### 1. **List & Filter Courses**
- Lọc theo trạng thái (pending, active, rejected)
- Lọc theo nhà cung cấp
- Lọc theo danh mục
- Tìm kiếm theo tiêu đề/mô tả
- Sắp xếp (mới nhất, cũ nhất, theo tên)
- Phân trang

### 2. **View Course Details**
- Xem thông tin chi tiết khóa học
- Xem thông tin nhà cung cấp
- Xem danh sách chương/bài học
- Xem lịch sử phê duyệt

### 3. **Approve Course**
- Phê duyệt khóa học
- Thêm ghi chú (tùy chọn)
- Gửi email thông báo tự động
- Lưu thời gian & admin phê duyệt

### 4. **Reject Course**
- Từ chối khóa học
- Yêu cầu lý do (bắt buộc)
- Gửi email với lý do từ chối
- Cho phép nhà cung cấp tái nộp

### 5. **Request Changes**
- Yêu cầu nhà cung cấp sửa đổi
- Gửi email với ghi chú chi tiết
- Khóa học vẫn ở trạng thái "pending"

### 6. **Bulk Operations**
- Phê duyệt nhiều khóa học cùng lúc
- Kiểm tra từng khóa học riêng biệt

### 7. **Export & Reports**
- Xuất dữ liệu khóa học
- Tạo báo cáo thống kê
- Theo dõi metrics

---

## 🔌 API Endpoints

### Public Routes (Admin Only)

#### 1. List & Filter Courses
```
GET /admin/content-moderation
```

**Query Parameters:**
```json
{
  "status": "pending|active|rejected",
  "provider_id": 1,
  "category_id": 2,
  "search": "course title",
  "sort": "newest|oldest|title",
  "page": 1,
  "per_page": 15
}
```

**Response:**
```json
{
  "courses": {
    "data": [
      {
        "id": 1,
        "title": "Learn PHP",
        "status": "pending",
        "provider": { "id": 1, "full_name": "John" },
        "approved_at": null,
        "rejected_at": null,
        "created_at": "2026-04-19T10:00:00Z"
      }
    ],
    "current_page": 1,
    "total": 150
  },
  "stats": {
    "total": 150,
    "pending": 45,
    "active": 100,
    "rejected": 5
  }
}
```

#### 2. View Course Details
```
GET /admin/content-moderation/{course}
```

**Response:**
- Course information
- Chapters & lessons
- Provider details
- Approval history
- Can approve/reject flags

#### 3. Approve Course
```
POST /admin/content-moderation/{course}/approve
```

**Request Body:**
```json
{
  "notes": "Nội dung tốt" // optional
}
```

**Response:**
```json
{
  "success": true,
  "message": "✅ Khóa học 'Learn PHP' đã được phê duyệt thành công!",
  "course": {
    "id": 1,
    "status": "active",
    "approved_at": "2026-04-19T10:30:00Z",
    "approved_by": 1
  }
}
```

#### 4. Reject Course
```
POST /admin/content-moderation/{course}/reject
```

**Request Body:**
```json
{
  "reason": "Nội dung không đủ chi tiết, vui lòng thêm các ví dụ thực tế"
}
```

**Response:**
```json
{
  "success": true,
  "message": "❌ Khóa học 'Learn PHP' đã bị từ chối",
  "course": {
    "id": 1,
    "status": "rejected",
    "rejection_reason": "...",
    "rejected_at": "2026-04-19T10:30:00Z"
  }
}
```

#### 5. Request Changes
```
POST /admin/content-moderation/{course}/request-changes
```

**Request Body:**
```json
{
  "reason": "Vui lòng cập nhật video chất lượng cao hơn"
}
```

#### 6. Bulk Approve
```
POST /admin/content-moderation/bulk/approve
```

**Request Body:**
```json
{
  "course_ids": [1, 2, 3, 4, 5]
}
```

#### 7. Export Data
```
GET /admin/content-moderation/export/data?status=pending
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Learn PHP",
      "provider": "John Doe",
      "status": "pending",
      "price": 499000,
      "created_at": "2026-04-19 10:00:00"
    }
  ],
  "count": 45
}
```

#### 8. Get Statistics
```
GET /admin/content-moderation/api/statistics
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

## 💾 Database

### Columns Added to `courses` Table

```sql
ALTER TABLE courses ADD (
  rejection_reason TEXT NULLABLE AFTER status,
  approved_at TIMESTAMP NULLABLE AFTER rejection_reason,
  approved_by BIGINT UNSIGNED NULLABLE AFTER approved_at,
  rejected_at TIMESTAMP NULLABLE AFTER approved_by,
  FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### Migration Command
```bash
php artisan migrate
```

---

## 🔄 Quy Trình Phê Duyệt

### Trạng Thái Khóa Học

```
┌──────────┐
│  PENDING │ (Mới tạo bởi nhà cung cấp)
└────┬─────┘
     │
     ├─ [APPROVED] → ┌────────┐
     │               │ ACTIVE │ (Xuất bản)
     │               └────────┘
     │
     └─ [REJECTED] → ┌──────────┐
                     │ REJECTED │ (Từ chối)
                     └──────────┘
                            ↓
                     (Nhà cung cấp sửa đổi)
                            │
                     ┌──────▼──────┐
                     │   PENDING   │ (Tái nộp)
                     └─────────────┘
```

### Validation Rules

#### Khóa học có thể được phê duyệt nếu:
- Status = 'pending'
- Nhà cung cấp status = 'active'
- Khóa học có tiêu đề, mô tả
- Khóa học có ít nhất 1 chương

#### Khóa học có thể bị từ chối nếu:
- Status = 'pending'
- Lý do từ chối ≥ 10 ký tự

---

## 📝 Cách Sử Dụng

### Example 1: Approve Course
```php
// In your controller or service
$course = Course::find(1);
$admin = Auth::user();

try {
    $service = new ContentModerationService();
    $service->approveCourse($course, $admin, 'Nội dung tốt');
    
    // Email đã gửi tự động
    // Logging đã lưu tự động
    
    return response()->json(['success' => true]);
} catch (Exception $e) {
    return response()->json(['error' => $e->getMessage()], 400);
}
```

### Example 2: Filter Courses
```php
$service = new ContentModerationService();

$courses = $service->getCoursesForModeration([
    'status' => 'pending',
    'search' => 'PHP',
    'sort' => 'newest',
    'per_page' => 20,
]);
```

### Example 3: Get Statistics
```php
$service = new ContentModerationService();
$stats = $service->getStatistics();

echo "Pending: " . $stats['pending'];   // 45
echo "Active: " . $stats['active'];     // 100
echo "Rejected: " . $stats['rejected']; // 5
```

### Example 4: Using Model Methods
```php
$course = Course::find(1);

// Check approval status
if ($course->isPending()) {
    echo "Chờ phê duyệt";
}

if ($course->isApproved()) {
    echo "Đã phê duyệt";
    echo "Ngày: " . $course->approved_at;
    echo "Admin: " . $course->approvedBy->email;
}

if ($course->isRejected()) {
    echo "Bị từ chối";
    echo "Lý do: " . $course->rejection_reason;
}

// Display status
echo $course->getApprovalStatusLabel(); // ⏳ Chờ phê duyệt
```

---

## 🔐 Security

### Authorization Middleware
```php
// Routes protected by 'admin' middleware
Route::middleware(['auth:admin', 'admin'])->group(function () {
    // Only admin can access
});
```

### Form Request Validation
```php
// FilterCoursesRequest
public function authorize(): bool {
    return $this->user() && $this->user()->role === 'admin';
}

// ApproveCourseRequest
public function authorize(): bool {
    return $this->user() && $this->user()->role === 'admin';
}
```

### Service Layer Checks
```php
// Check if course can be approved
if (!$this->canApproveCourse($course)) {
    throw new Exception('Không thể phê duyệt khóa học này');
}

// Check if admin exists
if (!Auth::check() || Auth::user()->role !== 'admin') {
    abort(403);
}
```

### Database Transactions
```php
DB::beginTransaction();
try {
    // Update course
    $course->update([...]);
    
    // Send email
    Mail::send(...);
    
    DB::commit();
} catch (Exception $e) {
    DB::rollBack();
    throw $e;
}
```

---

## 📊 Logging

### Action Logging
Tất cả hành động phê duyệt được ghi lại trong channel `admin_actions`:

```
[2026-04-19 14:30:00] Content Moderation Action
Type: info
Message: Content Moderation Action
Data:
  action: approve
  course_id: 1
  course_title: Learn PHP
  provider_id: 5
  admin_id: 1
  admin_email: admin@example.com
  details: Nội dung tốt
  status: success
  timestamp: 2026-04-19T14:30:00Z
```

### Error Logging
```
[2026-04-19 14:35:00] Error approving course
Type: error
Data:
  course_id: 2
  error: Provider is not active
```

### View Logs
```bash
tail -f storage/logs/admin_actions.log
tail -f storage/logs/laravel.log
```

---

## 🚀 Deployment Checklist

- [ ] Chạy migration: `php artisan migrate`
- [ ] Kiểm tra file permissions
- [ ] Cấu hình email (`.env`)
- [ ] Kiểm tra logging config
- [ ] Test approval workflow
- [ ] Test rejection workflow
- [ ] Kiểm tra email sending
- [ ] Test error handling
- [ ] Verify transaction rollback
- [ ] Check authorization

---

## 📚 API Testing dengan Postman

### Setup Collection
```json
{
  "info": {
    "name": "Content Moderation API",
    "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
  },
  "item": [
    {
      "name": "List Courses",
      "request": {
        "method": "GET",
        "url": "{{base_url}}/admin/content-moderation?status=pending"
      }
    },
    {
      "name": "Approve Course",
      "request": {
        "method": "POST",
        "url": "{{base_url}}/admin/content-moderation/1/approve",
        "body": {
          "mode": "raw",
          "raw": "{\"notes\": \"Nội dung tốt\"}"
        }
      }
    },
    {
      "name": "Reject Course",
      "request": {
        "method": "POST",
        "url": "{{base_url}}/admin/content-moderation/1/reject",
        "body": {
          "mode": "raw",
          "raw": "{\"reason\": \"Nội dung không đủ chi tiết\"}"
        }
      }
    }
  ]
}
```

---

## 🐛 Troubleshooting

### Issue: Email not sending
**Solution:**
```bash
# Check mail config
cat config/mail.php

# Send test email
php artisan tinker
>>> Mail::raw('Test', function($m) { $m->to('test@example.com'); });
```

### Issue: Authorization failed
**Solution:**
- Kiểm tra middleware authentication
- Kiểm tra user role = 'admin'
- Kiểm tra form request authorization method

### Issue: Transaction rollback
**Solution:**
```bash
# Check database connectivity
php artisan tinker
>>> DB::connection()->getPDO();

# Check foreign key constraints
PRAGMA foreign_keys = ON;
```

### Issue: Logging not working
**Solution:**
```bash
# Check log file permissions
chmod 777 storage/logs/

# Check laravel.log
tail -f storage/logs/laravel.log
```

---

## 📞 Support

Để báo cáo lỗi hoặc yêu cầu tính năng, vui lòng liên hệ:
- Email: support@example.com
- Documentation: `/docs/content-moderation.md`
- Issues: GitHub Issues

---

**Last Updated:** 2026-04-19
**Version:** 1.0.0
**Status:** ✅ Production Ready
