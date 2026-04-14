<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Provider\DashboardController as ProviderDashboardController;
use App\Http\Controllers\Admin\CourseController;

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
Route::middleware(['auth'])->group(function () {
    Route::get('/my-courses', [MyCourseController::class, 'index'])->name('user.my_courses');
});

// course detail & enroll
Route::get('/courses/{id}', [HomeController::class, 'detail'])->name('course.detail');
Route::post('/course/{id}/enroll', [HomeController::class, 'enroll'])->name('course.enroll')->middleware('auth');
Route::get('/search', [HomeController::class, 'search'])->name('courses.search');
Route::get('/courses', [HomeController::class, 'courses'])->name('courses.index');

// Admin routes
Route::prefix('admin')->group(function () {
    // Dashboard
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

// Provider routes
Route::prefix('provider')->middleware(['auth', 'ensure.provider'])->group(function () {
    Route::get('/dashboard', [ProviderDashboardController::class, 'index'])->name('provider.dashboard');
    
    // Placeholder routes - sẽ tạo controllers sau
    Route::get('/courses', fn() => abort(404))->name('provider.courses.index');
    Route::get('/courses/create', fn() => abort(404))->name('provider.courses.create');
    Route::get('/courses/{id}/edit', fn() => abort(404))->name('provider.courses.edit');
    Route::get('/students', fn() => abort(404))->name('provider.students');
    Route::get('/earnings', fn() => abort(404))->name('provider.earnings');
    Route::get('/profile', fn() => abort(404))->name('provider.profile');
});
