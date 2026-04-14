<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\HomeController;
// Admin dùng DashboardController
use App\Http\Controllers\Admin\DashboardController; 
// Provider dùng DashboardproController
use App\Http\Controllers\Provider\DashboardproController; 
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\MyCourseController;

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

// ==========================================
// Admin Routes
// ==========================================
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Gọi đúng DashboardController của Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('courses', CourseController::class, [
        'names' => [
            'index'   => 'admin.courses.index',
            'create'  => 'admin.courses.create',
            'store'   => 'admin.courses.store',
            'edit'    => 'admin.courses.edit',
            'update'  => 'admin.courses.update',
            'destroy' => 'admin.courses.destroy',
        ]
    ]);
});

// ==========================================
// Provider Routes
// ==========================================
Route::prefix('provider')->middleware(['auth', 'ensure.provider'])->group(function () {
    // Gọi đúng DashboardproController của Provider
    Route::get('/dashboard', [DashboardproController::class, 'index'])->name('provider.dashboard');
    
    Route::get('/courses', fn() => abort(404))->name('provider.courses.index');
    Route::get('/courses/create', fn() => abort(404))->name('provider.courses.create');
    Route::get('/courses/{id}/edit', fn() => abort(404))->name('provider.courses.edit');
    Route::get('/students', fn() => abort(404))->name('provider.students');
    Route::get('/earnings', fn() => abort(404))->name('provider.earnings');
    Route::get('/profile', fn() => abort(404))->name('provider.profile');
});