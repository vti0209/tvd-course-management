<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Enrollment;
use App\Models\Course;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. Tổng số khóa học (Lấy từ quan hệ taughtCourses đã tạo ở Bước 1)
        $totalCourses = $user->taughtCourses()->count();

        // 2. Tổng số học sinh
        // Chúng ta lấy ID của tất cả khóa học của ông thầy này, 
        // sau đó đếm số lượng bản ghi trong bảng Enrollment
        $courseIds = $user->taughtCourses()->pluck('id');
        $totalStudents = Enrollment::whereIn('course_id', $courseIds)->count();

        // 3. Tổng doanh thu
        // Giả sử bảng Enrollment của bạn có cột 'total_price'
        $totalRevenue = Enrollment::whereIn('course_id', $courseIds)->sum('total_price');

        return view('provider.dashboard', compact('totalCourses', 'totalStudents', 'totalRevenue'));
    }
}