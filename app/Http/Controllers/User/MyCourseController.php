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
        // Lấy danh sách khóa học mà User hiện tại đã đăng ký (qua bảng trung gian course_user)
        $user = Auth::user();

        // Giả sử bạn đã định nghĩa quan hệ belongsToMany('Course') trong Model User
        $myCourses = $user->courses()->paginate(6);

        return view('users.my-courses', compact('myCourses'));
    }
}
