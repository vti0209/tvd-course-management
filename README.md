
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
### 4.1 Người dùng
* Đăng ký tài khoản
* Đăng nhập / Đăng xuất
* Xem danh sách khóa học (có phân trang)
* Xem chi tiết khóa học
* Đăng ký khóa học
* Quản lý thông tin cá nhân
* Xem danh sách khóa học đã đăng ký

### 4.2 Quản trị viên
* Dashboard thống kê (User, Course)
* Quản lý khóa học (duyệt)
* Quản lý người dùng (block, tìm theo tên, lọc user)

### 4.2 Nhà cung cấp
* Dashboard thống kê
* Quản lý khóa học (CRUD, tìm kiếm tên, lọc theo danh mục, phân trang)
* Quản lý người dùng (CRUD, tìm theo tên, lọc user đã đăng ký khóa học)

## 5. Cấu trúc thư mục
```text
Xem ở nhánh dev 

## 6. Database
Hệ thống sử dụng các bảng chính, ngoài ra sẽ có các bảng phụ:
* `users`
* `categories`
* `courses`
* `lessons`
* `course_user` (quan hệ N-N giữa User và Course)

## 7. Cài đặt dự án
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

## 8. Tài khoản mẫu
#### Admin:
* Email: `admin@example.com`
* Password: `123456`

#### User:
* Email: `user@example.com`
* Password: `123456`

## 9. Hướng phát triển
* Thanh toán online
* Đánh giá khóa học
* Upload video bài học
* API cho mobile app

## 9. Hướng phát triển tương lai
* [ ] Tích hợp cổng thanh toán trực tuyến (VNPay/Momo).
* [ ] Hệ thống đánh giá và nhận xét (Rating & Review).
* [ ] Upload và phát video bài học trực tiếp.
* [ ] Xây dựng RESTful API cho ứng dụng Mobile.

---

*© 2026 TVD Team - Built with passion and Laravel.*
