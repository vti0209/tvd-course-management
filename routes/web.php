<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/trangchu', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout']);

// profile
use App\Http\Controllers\User\ProfileController;
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('user.profile.update');
});

// my courses
use App\Http\Controllers\User\MyCourseController;
// Đặt trong group middleware auth để bắt buộc đăng nhập mới xem được
Route::middleware(['auth'])->group(function () {
    Route::get('/my-courses', [MyCourseController::class, 'index'])->name('user.my_courses');
});

// course detail & enroll
// Xem chi tiết khóa học (Ai cũng xem được)
Route::get('/courses/{id}', [HomeController::class, 'detail'])->name('course.detail');

// Đăng ký khóa học (Phải qua middleware auth)
// Route xử lý đăng ký khóa học
Route::post('/course/{id}/enroll', [HomeController::class, 'enroll'])->name('course.enroll')->middleware('auth');
Route::get('/search', [HomeController::class, 'search'])->name('courses.search');
// courses
Route::get('/courses', [HomeController::class, 'courses'])->name('courses.index');
// Admin routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Course management routes
    Route::resource('courses', CourseController::class, [
        'names' => [
            'index' => 'admin.courses.index',
            'create' => 'admin.courses.create',
            'store' => 'admin.courses.store',
            'edit' => 'admin.courses.edit',
            'update' => 'admin.courses.update',
            'destroy' => 'admin.courses.destroy',
        ]
    ]);
});
