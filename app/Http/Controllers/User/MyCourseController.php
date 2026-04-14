<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class MyCourseController extends Controller
{
    public function index()
    {
        // Lấy danh sách khóa học mà User hiện tại đã đăng ký (qua bảng Enrollments)
        $user = Auth::user();

        // Lấy các khóa học đã đăng ký thông qua quan hệ hasManyThrough với Enrollment
        $myCourses = $user->courses()->paginate(6);

        return view('users.my-courses', compact('myCourses'));
    }
}
