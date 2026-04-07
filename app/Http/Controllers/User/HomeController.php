<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class HomeController extends Controller
{
    public function index()
    {
        $courses = Course::select('id', 'category_id', 'title', 'price', 'thumbnail', 'duration')
            ->with('category:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        return view('users.home', compact('courses'));
    }
    // Hiển thị chi tiết khóa học
    public function detail($id)
    {
        // Lấy chi tiết khóa học cùng với các bài học (lessons) liên quan
        $course = Course::with(['category', 'lessons'])->findOrFail($id);

        // Kiểm tra xem User đã đăng ký khóa học này chưa
        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Auth::user()->courses()->where('course_id', $id)->exists();
        }

        return view('users.course-detail', compact('course', 'isEnrolled'));
    }

    //Xử lý đăng ký khóa học
    public function enroll($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đăng ký khóa học!');
        }

        $user = Auth::user();

        // Sử dụng syncWithoutDetaching để tránh lỗi chèn trùng dữ liệu (Duplicate entry)
        $user->courses()->syncWithoutDetaching([$id]);

        return redirect()->route('user.my_courses')->with('success', 'Đăng ký khóa học thành công!');
    }
}
