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
        $courses = Course::select('id', 'category_id', 'title', 'price', 'thumbnail', 'status')
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
    public function enroll(Request $request, $id)
    {
        $user = Auth::user();

        // 1. Chặn nếu đã đăng ký rồi
        if ($user->courses()->where('course_id', $id)->exists()) {
            return back()->with('error', 'Bạn đã đăng ký khóa học này rồi!');
        }

        try {
            // 2. Ghi danh và copy thông tin từ bảng users sang bảng course_user
            $user->courses()->attach($id, [
                'full_name'   => $user->name,   // Lấy từ User
                'email'       => $user->email,  // Lấy từ User
                'note'        => $request->note, // Lấy từ Form Modal
                'status'      => 'active',
                'enrolled_at' => now(),
            ]);

            return back()->with('success', 'Đăng ký thành công! Chúc bạn học tốt.');

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }
        public function search(Request $request)
    {
        $courses = Course::with('category:id,name');

        if ($request->keyword) {
            $courses = $courses->where('title', 'like', '%' . $request->keyword . '%');
        }

        if ($request->category_id) {
            $courses = $courses->where('category_id', $request->category_id);
        }

        $courses = $courses->orderBy('created_at', 'desc')->paginate(8);

        return view('users.home', compact('courses'));
    }
    public function courses()
{
    $courses = Course::with('category:id,name')
        ->orderBy('created_at', 'desc')
        ->paginate(8); // phân trang 8 khóa học mỗi trang

    return view('users.courses', compact('courses'));
}
}
