<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_providers' => User::where('role', 'provider')->count(),
            'pending_providers' => User::where('role', 'provider')->where('status', 'pending')->count(),
            'blocked_users' => User::where('status', 'blocked')->count(),
            'total_courses' => Course::count(),
            'pending_courses' => Course::where('status', 'pending')->count(),
            'active_courses' => Course::where('status', 'active')->count(),
            'rejected_courses' => Course::where('status', 'rejected')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}

