<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $courses = Course::with(['category:id,name', 'chapters.lessons'])
            ->where('status', 'active')
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
            $isEnrolled = Auth::user()->enrollments()->where('course_id', $id)->exists();
        }

        return view('users.course-detail', compact('course', 'isEnrolled'));
    }

    //Xử lý đăng ký khóa học
    public function enroll(Request $request, $id)
    {
        $user = Auth::user();

        // 1. Chặn nếu đã đăng ký rồi
        if ($user->enrollments()->where('course_id', $id)->exists()) {
            return back()->with('error', 'Bạn đã đăng ký khóa học này rồi!');
        }

        try {
            // 2. Tạo bản ghi enrollment
            $user->enrollments()->create([
                'course_id' => $id,
                'payment_status' => 'paid',
                'price_at_purchase' => Course::find($id)->price,
                'enrolled_at' => now(),
            ]);

            // 3. Log thông tin đăng ký (tuỳ chọn)
            // Bạn có thể lưu full_name, email, note vào bảng khác nếu cần

            return back()->with('success', 'Đăng ký thành công! Chúc bạn học tốt.');

        } catch (\Exception $e) {
            return back()->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
        }
    }
        public function search(Request $request)
    {
        $courses = Course::with(['category:id,name', 'chapters.lessons'])
            ->where('status', 'active');

        if ($request->keyword) {
            $courses = $courses->where('title', 'like', '%' . $request->keyword . '%');
        }

        if ($request->category_id) {
            $courses = $courses->where('category_id', $request->category_id);
        }

        $courses = $courses->orderBy('created_at', 'desc')->paginate(8);

        $searchHeading = null;
        $filterHeading = null;

        if ($request->keyword) {
            $searchHeading = 'Kết quả tìm kiếm: "' . $request->keyword . '"';
        }

        if ($request->category_id) {
            $categoryName = Category::find($request->category_id)?->name;
            if ($categoryName) {
                $filterHeading = 'Kết quả lọc: "' . $categoryName . '"';
            }
        }

        return view('users.courses', compact('courses', 'searchHeading', 'filterHeading'));
    }
    public function courses()
{
    $courses = Course::with(['category:id,name', 'chapters.lessons'])
        ->orderBy('created_at', 'desc')
        ->paginate(8); // phân trang 8 khóa học mỗi trang

    return view('users.courses', compact('courses'));
}

    public function about()
    {
        return view('users.about');
    }

    public function contact()
    {
        return view('users.contact');
    }
}