TVD - Online Course Management System
## 1. Giới thiệu
Online Course Management System là hệ thống quản lý khóa học trực tuyến cho phép:
Người dùng đăng ký, đăng nhập và tham gia đăng ký khóa học.
Quản trị viên quản lý khóa học và người dùng (CRUD)
Tìm kiếm, lọc và xem chi tiết khóa học
Dự án được xây dựng theo mô hình MVC sử dụng Laravel.

## 2. Thành viên và phân công
## Nguyen Thi Dung:
Thiết kế Database (chuẩn 3NF)
Seed dữ liệu mẫu (Factories, Seeders)
Xây dựng Dashboard (thống kê User và Course)
CRUD Users (Admin)
Lọc user đã đăng ký khóa học

## Hồ Thị Vãi:

Xây dựng chức năng Authentication (Login, Register, Logout)
Phân quyền truy cập (User/Admin)
Thực thi các thao tác nhanh cho user, admin (lọc, tìm kiếm)

## Hồ Văn Tiết:

Giao diện người dùng (Header, Footer, Banner)
Trang Home, Profile, My Courses
Trang chi tiết khóa học + đăng ký khóa học
Tìm kiếm và lọc khóa học

## 3. Công nghệ sử dụng
Backend: Laravel
Frontend: HTML, CSS, JavaScript
Database: MySQL, phpMyAdmin
ORM: Eloquent
Authentication: Laravel Auth
## 4. Chức năng chính
# 4.1 Người dùng
Đăng ký tài khoản
Đăng nhập / Đăng xuất
Xem danh sách khóa học (có phân trang)
Xem chi tiết khóa học
Đăng ký khóa học
Quản lý thông tin cá nhân
Xem danh sách khóa học đã đăng ký
# 4.2 Quản trị viên
Dashboard thống kê (User, Course)
Quản lý khóa học (CRUD, tìm kiếm tên, lọc theo danh mục, phân trang)
Quản lý người dùng (CRUD, tìm theo tên, lọc user đã đăng ký khóa học)
## 5. Cấu trúc thư mục
TVD/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                      # Quản trị viên
│   │   │   │   ├── DashboardController.php  # Thống kê tổng số User & Khóa học
│   │   │   │   ├── CourseController.php     # CRUD Khóa học (Lọc danh mục, 8 hàng/trang)
│   │   │   │   └── UserController.php       # CRUD Users (Lọc người đã đăng ký, 8 hàng/trang)
│   │   │   ├── Auth/                       # Hệ thống đăng nhập
│   │   │   │   ├── LoginController.php      
│   │   │   │   └── RegisterController.php   
│   │   │   └── User/                       # Người dùng (Học viên)
│   │   │       ├── HomeController.php       # Trang chủ (8 khóa học mới, Slide 4 ảnh)
│   │   │       ├── ProfileController.php    # Xem và cập nhật Profile
│   │   │       └── MyCourseController.php   # Danh sách khóa học đã đăng ký
│   │   └── Middleware/
│   │       └── AdminRole.php                # Bảo vệ vùng Admin
│   └── Models/                              # Thực thể Database (3NF)
│       ├── Category.php                     # Quan hệ 1-N với Course
│       ├── Course.php                       # Quan hệ N-N với User (via course_user)
│       ├── Lesson.php                       # Bài học thuộc Course
│       └── User.php                         
│
├── database/
|   ├── factories/                          
│   │   ├── CategoryFactory.php              # Fake tên danh mục
│   │   ├── UserFactory.php                  # Fake email, tên, pass
│   │   ├── CourseFactory.php                # Fake tiêu đề, giá, slug
│   │   ├── LessonFactory.php                # Fake bài học
│   │   └── CourseUserFactory.php            # Fake quan hệ đăng ký học
│   ├── migrations/                          # Cấu trúc 5 bảng Database
│   └── seeders/
│       └── DatabaseSeeder.php               # Dữ liệu mẫu (5 dòng/bảng)
│
├── public/
│   ├── css/
│   │   ├── style.css                        # CSS chính cho User/Guest
│   │   └── admin.css                        # CSS riêng cho bảng quản trị
│   ├── js/
│   │   └── slider.js                        # Script chạy 4 ảnh Slide
│   └── uploads/                             # Nơi lưu Thumbnail & Avatar
│
├── resources/
│   └── views/
│       ├── admin/                           # Giao diện ADMIN
│       │   ├── dashboard.blade.php          # View Dashboard tổng quan
│       │   ├── courses/                     # Quản lý khóa học
│       │   │   ├── index.blade.php          # Bảng danh sách (8 hàng, Tìm kiếm, Lọc)
│       │   │   ├── create.blade.php         # Form thêm mới
│       │   │   ├── edit.blade.php           # Form chỉnh sửa
│       │   │   └── add.blade.php            # (Hỗ trợ thêm nhanh)
│       │   ├── users/                       # Quản lý người dùng
│       │   │   ├── index.blade.php          # Bảng danh sách (8 hàng, Lọc đã đăng ký)
│       │   │   ├── create.blade.php         # Form Admin tạo User
│       │   │   ├── edit.blade.php           # Form Admin sửa User
│       │   │   └── add.blade.php            
│       │   └── admin_layouts/               # Layout Admin
│       │       ├── master.blade.php         # Khung chính Admin
│       │       ├── header.blade.php         # Header (Logo, QL Khóa học, QL User, Tên Admin)
│       │       └── footer.blade.php         
│       │
│       ├── user/                            # Giao diện USER/GUEST
│       │   ├── home.blade.php               # Trang chủ (Slide, 8 khóa học, Phân trang)
│       │   ├── profile.blade.php            # Xem thông tin cá nhân
│       │   ├── editprofile.blade.php        # Form tự chỉnh sửa tên/avatar
│       │   |── my-courses.blade.php         # Danh sách khóa học đã mua
|       |   └── formdangkykhoahoc.blade.php  #Form cho người dùng đăng ký khóa học
│       │
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       │
│       └── layouts/                         # Layout chính cho khách/user
│           ├── master.blade.php             
│           ├── header.blade.php             # Header động (Guest/User/Số lượng khóa học)
│           ├── footer.blade.php             # Footer (Chúng tôi, Học tập, Kết nối)
│           └── script.blade.php             
│
└── routes/
    └── web.php                              # Định nghĩa tất cả URL dự án
|...............................................................


## 6. Database

Hệ thống sử dụng 5 bảng chính:
users
categories
courses
lessons
course_user (quan hệ N-N giữa User và Course)

## 7. Cài đặt dự án
Bước 1: Clone project
### git clone <repository_url>
### cd tvd

Bước 2: Cài dependencies
### composer install

Bước 3: Tạo file môi trường
### cp .env.example .env
### php artisan key:generate

Bước 4: Cấu hình database
### Chỉnh file .env:
DB_DATABASE=your_db
DB_USERNAME=root
DB_PASSWORD=

Bước 5: Migration và seed dữ liệu
### php artisan migrate --seed

Bước 6: Chạy server
### php artisan serve

## 8. Tài khoản mẫu

#### Admin:
Email: admin@example.com
Password: 123456

#### User:
Email: user@example.com
Password: 123456
## 9. Hướng phát triển
Thanh toán online
Đánh giá khóa học
Upload video bài học
API cho mobile app
