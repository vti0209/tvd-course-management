<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
}
