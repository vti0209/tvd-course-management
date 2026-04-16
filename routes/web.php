<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\HomeController;
// Admin dùng DashboardController
use App\Http\Controllers\Admin\DashboardController;
// Provider dùng DashboardproController
use App\Http\Controllers\Provider\DashboardproController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\MyCourseController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\ContentModerationController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\WithdrawController;

// ==========================================
// Public Routes
// ==========================================
Route::get('/', function () {
    return view('welcome');
});

Route::get('/trangchu', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('courses.search');
Route::get('/courses', [HomeController::class, 'courses'])->name('courses.index');
Route::get('/courses/{id}', [HomeController::class, 'detail'])->name('course.detail');

// ==========================================
// Auth Routes
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// User Routes
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('user.profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::get('/my-courses', [MyCourseController::class, 'index'])->name('user.my_courses');
    Route::post('/course/{id}/enroll', [HomeController::class, 'enroll'])->name('course.enroll');
});

// Provider routes
Route::prefix('provider')->middleware(['auth', 'ensure.provider'])->group(function () {
    
    // Trang Dashboard
    Route::get('/dashboard', [DashboardproController::class, 'index'])->name('provider.dashboard');

    // Quản lý Courses (Tự động tạo route cho index, create, edit, store, update, destroy)
    Route::resource('courses', CourseController::class)->names([
        'index' => 'provider.courses.index',
        'create' => 'provider.courses.create',
        'edit' => 'provider.courses.edit',
        'store' => 'provider.courses.store',
        'update' => 'provider.courses.update',
        'destroy' => 'provider.courses.destroy',
    ]);

    // Các trang khác trỏ về hàm tương ứng trong CourseController (hoặc bạn tạo Controller riêng)
    Route::get('/students', [ProviderController::class, 'students'])->name('provider.students');
    Route::get('/earnings', [ProviderController::class, 'earnings'])->name('provider.earnings');
    Route::get('/profile', [ProviderController::class, 'profile'])->name('provider.profile');
});

// About and Contact pages
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Admin routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
    Route::post('/users/{user}/status', [UserController::class, 'updateStatus'])->name('admin.users.update-status');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::resource('courses', CourseController::class, [
        'names' => [
            'index'   => 'admin.courses.index',
            // 'create'  => 'admin.courses.create',
            'store'   => 'admin.courses.store',
            'edit'    => 'admin.courses.edit',
            'update'  => 'admin.courses.update',
            'destroy' => 'admin.courses.destroy',
        ]
    ]);

    // Provider Management & Approval
    Route::get('/providers', [ProviderController::class, 'index'])->name('admin.providers.index');
    Route::post('/providers/user/{user}/approve', [ProviderController::class, 'approveUser'])->name('admin.providers.approve-user');
    Route::post('/providers/user/{user}/reject', [ProviderController::class, 'rejectUser'])->name('admin.providers.reject-user');

    // Content Moderation
    Route::get('/content-moderation', [ContentModerationController::class, 'index'])->name('admin.content-moderation.index');
    Route::get('/content-moderation/{course}', [ContentModerationController::class, 'show'])->name('admin.content-moderation.show');
    Route::post('/content-moderation/{course}/approve', [ContentModerationController::class, 'approve'])->name('admin.content-moderation.approve');
    Route::post('/content-moderation/{course}/reject', [ContentModerationController::class, 'reject'])->name('admin.content-moderation.reject');

    // Category Management
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

    // System Settings
    Route::get('/system/settings', [SystemController::class, 'settings'])->name('admin.system.settings');
    Route::post('/system/settings', [SystemController::class, 'updateSettings'])->name('admin.system.update-settings');
    Route::get('/system/banners', [SystemController::class, 'banners'])->name('admin.system.banners');
    Route::get('/system/banners/create', [SystemController::class, 'createBanner'])->name('admin.system.create-banner');
    Route::post('/system/banners', [SystemController::class, 'storeBanner'])->name('admin.system.store-banner');
    Route::get('/system/announcements', [SystemController::class, 'announcements'])->name('admin.system.announcements');
    Route::get('/system/announcements/create', [SystemController::class, 'createAnnouncement'])->name('admin.system.create-announcement');
    Route::post('/system/announcements', [SystemController::class, 'storeAnnouncement'])->name('admin.system.store-announcement');

    // Withdrawal Management
    Route::get('/withdrawals', [WithdrawController::class, 'index'])->name('admin.withdrawals.index');
    Route::get('/withdrawals/{id}', [WithdrawController::class, 'show'])->name('admin.withdrawals.show');
    Route::post('/withdrawals/{id}/approve', [WithdrawController::class, 'approve'])->name('admin.withdrawals.approve');
    Route::post('/withdrawals/{id}/reject', [WithdrawController::class, 'reject'])->name('admin.withdrawals.reject');
});