<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class HomeController extends Controller
{
    public function index()
    {
        $courses = Course::with('category')
            ->latest()
            ->paginate(8);

        return view('users.home', compact('courses'));
    }
}
