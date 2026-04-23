
---

# TVD - Online Course Management System

## 1. Giới thiệu
Online Course Management System là hệ thống quản lý khóa học trực tuyến cho phép:
* Người dùng đăng ký, đăng nhập và tham gia đăng ký khóa học.
* Quản trị viên quản lý khóa học và người dùng (CRUD).
* Tìm kiếm, lọc và xem chi tiết khóa học.
* Dự án được xây dựng theo mô hình MVC sử dụng Laravel.

## 2. Thành viên và phân công
### Nguyen Thi Dung:
* Thiết kế Database (chuẩn 3NF)
* Seed dữ liệu mẫu (Factories, Seeders)
* Xây dựng Dashboard (thống kê User và Course)
* CRUD Users (Admin)
* Lọc user đã đăng ký khóa học

### Hồ Thị Vãi:
* Xây dựng chức năng Authentication (Login, Register, Logout)
* Phân quyền truy cập (User/Admin)
* Thực thi các thao tác nhanh cho user, admin (lọc, tìm kiếm)

### Hồ Văn Tiết:
* Giao diện người dùng (Header, Footer, Banner)
* Trang Home, Profile, My Courses
* Trang chi tiết khóa học + đăng ký khóa học
* Tìm kiếm và lọc khóa học

## 3. Công nghệ sử dụng
* **Backend:** Laravel
* **Frontend:** HTML, CSS, JavaScript
* **Database:** MySQL, phpMyAdmin
* **ORM:** Eloquent
* **Authentication:** Laravel Auth

## 4. Chức năng chính

### 4.1 Người dùng (User)
* Đăng ký tài khoản mới
* Đăng nhập / Đăng xuất
* Xem danh sách khóa học (có phân trang)
* Tìm kiếm và lọc khóa học theo danh mục
* Xem chi tiết khóa học đầy đủ
* Đăng ký khóa học (miễn phí)
* Quản lý thông tin cá nhân
* Xem danh sách khóa học đã đăng ký

### 4.2 Quản trị viên (Admin)
* Dashboard thống kê (tổng số User, Course, thống kê hoạt động)
* Duyệt và phê duyệt khóa học trước khi công khai
* Quản lý người dùng (tạo, sửa, xóa, block tài khoản)
* Tìm kiếm và lọc người dùng
* Xem lịch sử hoạt động

### 4.3 Nhà cung cấp (Provider)
* Dashboard thống kê (số khóa học, số học viên, tăng trưởng)
* Quản lý khóa học - CRUD đầy đủ (tạo, sửa, xóa, công khai)
* Tìm kiếm tên khóa học
* Lọc khóa học theo danh mục
* Quản lý bài học trong mỗi khóa học
* Xem danh sách học viên đã đăng ký
* Phân trang và tìm kiếm học viên

## 5. Yêu cầu hệ thống
* **PHP:** >= 8.1
* **Composer:** >= 2.0
* **Node.js:** >= 14.0 (cho Vite)
* **MySQL:** >= 5.7
* **Apache/Nginx** với mod_rewrite

## 6. Cấu trúc thư mục chính
```
tvd-course-management/
├── app/                 # Code ứng dụng
│   ├── Http/           # Controllers, Middleware, Requests
│   ├── Models/         # Eloquent Models
│   ├── Mail/           # Email classes
│   └── Services/       # Business logic
├── database/           # Migrations, Seeders, Factories
├── resources/          # Views, CSS, JavaScript
├── routes/             # Web routes
├── public/             # Assets công khai
├── config/             # Cấu hình ứng dụng
└── storage/            # Logs, cache

## 7. Database
Hệ thống sử dụng các bảng chính, ngoài ra sẽ có các bảng phụ:
* `users` - Thông tin người dùng, admin, nhà cung cấp
* `categories` - Phân loại khóa học
* `courses` - Thông tin khóa học
* `chapters` - Chương trong khóa học
* `lessons` - Bài học trong chương
* `enrollments` - Ghi nhận học viên đã đăng ký
* `sessions` - Quản lý phiên đăng nhập

## 8. Cài đặt dự án
**Bước 1: Clone project**
```bash
git clone <repository_url>
cd tvd
```
**Bước 2: Cài dependencies**
```bash
composer install
```
**Bước 3: Tạo file môi trường**
```bash
cp .env.example .env
php artisan key:generate
```
**Bước 4: Cấu hình database**
Chỉnh file `.env`:
```text
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=
```
**Bước 5: Migration và seed dữ liệu**
```bash
php artisan migrate --seed
```
**Bước 6: Chạy server**
```bash
php artisan serve
```

## 9. Tài khoản mẫu
#### Admin:
* Email: `admin@example.com`
* Password: `123456`

#### User:
* Email: `user@example.com`
* Password: `123456`

## 10. Quy trình phê duyệt khóa học
1. **Nhà cung cấp** tạo khóa học và submit
2. **Quản trị viên** kiểm tra thông tin khóa học
3. **Admin** phê duyệt hoặc từ chối
4. **Người dùng** có thể nhìn thấy khóa học đã phê duyệt

## 11. Hướng phát triển tương lai
* [ ] Tích hợp cổng thanh toán trực tuyến (VNPay/Momo)
* [ ] Hệ thống đánh giá và nhận xét (Rating & Review)
* [ ] Upload và phát video bài học trực tiếp
* [ ] Xây dựng RESTful API cho ứng dụng Mobile
* [ ] Hệ thống thông báo email tự động
* [ ] Chứng chỉ hoàn thành khóa học
* [ ] Forum/Đàm thoại giữa giáo viên và học viên

---

*© 2026 TVD Team - Built with passion and Laravel.*
