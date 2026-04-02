
---

# TVD - Online Course Management System

## 1. Giới thiệu
**Online Course Management System** là hệ thống quản lý khóa học trực tuyến được xây dựng nhằm tối ưu hóa việc học tập và quản lý đào tạo. Hệ thống cho phép:
* **Người dùng:** Đăng ký, đăng nhập và đăng ký tham gia các khóa học.
* **Quản trị viên:** Quản lý toàn diện khóa học và người dùng (CRUD).
* **Chức năng bổ trợ:** Tìm kiếm, lọc và xem chi tiết lộ trình học tập.
* **Kiến trúc:** Dự án xây dựng theo mô hình **MVC** sử dụng Framework **Laravel**.

---

## 2. Thành viên và phân công

| Thành viên | Nhiệm vụ chính |
| :--- | :--- |
| **Nguyễn Thị Dung** | Thiết kế Database (3NF), Seeding dữ liệu, Xây dựng Dashboard thống kê, CRUD Users & Filter. |
| **Hồ Thị Vãi** | Hệ thống Authentication (Login/Register), Phân quyền Admin/User, Tối ưu thao tác tìm kiếm/lọc. |
| **Hồ Văn Tiết** | Phát triển UI/UX (Home, Profile, My Courses), Trang chi tiết & Đăng ký khóa học, Logic lọc phía Frontend. |

---

## 3. Công nghệ sử dụng
* **Backend:** Laravel (PHP Framework)
* **Frontend:** Blade Template, HTML5, CSS3, JavaScript
* **Database:** MySQL (Quản lý qua phpMyAdmin)
* **ORM:** Eloquent
* **Authentication:** Laravel Auth Scaffolding

---

## 4. Chức năng chính

### 4.1 Người dùng (User/Guest)
* Đăng ký / Đăng nhập / Đăng xuất.
* Xem danh sách khóa học (Phân trang 8 mục/trang).
* Tìm kiếm và lọc khóa học theo danh mục.
* Xem chi tiết nội dung và đăng ký khóa học.
* Quản lý thông tin cá nhân (Profile) và danh sách khóa học đã tham gia.

### 4.2 Quản trị viên (Admin)
* **Dashboard:** Thống kê tổng quan số lượng User và Khóa học.
* **Quản lý khóa học:** CRUD, tìm kiếm, lọc theo danh mục.
* **Quản lý người dùng:** CRUD, tìm kiếm theo tên, lọc danh sách học viên đã đăng ký.

---

## 5. Cấu trúc thư mục dự án

```text
TVD/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                 # Quản trị viên
│   │   │   │   ├── DashboardController.php  # Thống kê tổng quan
│   │   │   │   ├── CourseController.php     # CRUD Khóa học (8 dòng/trang)
│   │   │   │   └── UserController.php       # CRUD Users (Lọc học viên)
│   │   │   ├── Auth/                  # Hệ thống xác thực
│   │   │   │   ├── LoginController.php      
│   │   │   │   └── RegisterController.php   
│   │   │   └── User/                  # Người dùng (Học viên)
│   │   │       ├── HomeController.php       # Trang chủ (8 khóa mới, Slide)
│   │   │       ├── ProfileController.php    # Cập nhật thông tin cá nhân
│   │   │       └── MyCourseController.php   # Khóa học đã đăng ký
│   │   └── Middleware/
│   │       └── AdminRole.php          # Bảo vệ vùng Admin
│   └── Models/                        # Thực thể Database (Chuẩn 3NF)
│       ├── Category.php               # Quan hệ 1-N với Course
│       ├── Course.php                 # Quan hệ N-N với User
│       ├── Lesson.php                 # Bài học thuộc Course
│       └── User.php                   
├── database/
│   ├── factories/                     # Tạo dữ liệu giả mẫu
│   ├── migrations/                    # Cấu trúc 5 bảng Database
│   └── seeders/                       # Seed dữ liệu mẫu (5 dòng/bảng)
├── public/
│   ├── css/
│   │   ├── style.css                  # CSS cho User
│   │   └── admin.css                  # CSS cho Admin
│   ├── js/
│   │   └── slider.js                  # Script xử lý Carousel/Slide
│   └── uploads/                       # Lưu trữ Thumbnail & Avatar
├── resources/
│   └── views/
│       ├── admin/                     # Giao diện ADMIN
│       │   ├── courses/               # View quản lý khóa học
│       │   ├── users/                 # View quản lý người dùng
│       │   ├── admin_layouts/         # Layout Master Admin
│       │   └── dashboard.blade.php
│       ├── user/                      # Giao diện USER
│       │   ├── home.blade.php
│       │   ├── profile.blade.php
│       │   └── my-courses.blade.php
│       ├── auth/                      # View Login/Register
│       └── layouts/                   # Layout Master chung
└── routes/
    └── web.php                        # Định nghĩa toàn bộ URLs
```

---

## 6. Cơ sở dữ liệu
Hệ thống được thiết kế tối ưu với 5 bảng chính:
1.  `users`: Lưu trữ thông tin tài khoản và phân quyền.
2.  `categories`: Danh mục các khóa học.
3.  `courses`: Thông tin chi tiết khóa học.
4.  `lessons`: Các bài học cụ thể trong từng khóa.
5.  `course_user`: Bảng trung gian quản lý quan hệ Nhiều-Nhiều (Đăng ký học).

---

## 7. Cài đặt dự án

**Bước 1: Clone project**
```bash
git clone <repository_url>
cd tvd
```

**Bước 2: Cài đặt thư viện**
```bash
composer install
```

**Bước 3: Cấu hình môi trường**
```bash
cp .env.example .env
php artisan key:generate
```

**Bước 4: Cấu hình Database**
Mở file `.env` và chỉnh sửa thông số kết nối:
```env
DB_DATABASE=tvd_database
DB_USERNAME=root
DB_PASSWORD=your_password
```

**Bước 5: Migration và Seed dữ liệu mẫu**
```bash
php artisan migrate --seed
```

**Bước 6: Khởi chạy Server**
```bash
php artisan serve
```

---

## 8. Tài khoản dùng thử

* **Admin:**
    * Email: `admin@example.com`
    * Password: `123456`
* **User:**
    * Email: `user@example.com`
    * Password: `123456`

---

## 9. Hướng phát triển tương lai
* [ ] Tích hợp cổng thanh toán trực tuyến (VNPay/Momo).
* [ ] Hệ thống đánh giá và nhận xét (Rating & Review).
* [ ] Upload và phát video bài học trực tiếp.
* [ ] Xây dựng RESTful API cho ứng dụng Mobile.

---
*© 2024 TVD Team - Built with passion and Laravel.*
