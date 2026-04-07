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
}
