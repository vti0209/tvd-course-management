<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Provider;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'user')->count(),
            'total_providers' => User::where('role', 'provider')->count(),
            'pending_providers' => Provider::whereNull('approved_at')->count(),
            'total_courses' => Course::count(),
            'pending_courses' => Course::where('status', 'pending')->count(),
            'active_courses' => Course::where('status', 'active')->count(),
            'rejected_courses' => Course::where('status', 'rejected')->count(),
            'blocked_users' => User::where('status', 'blocked')->count(),
        ];

        return view('admin.dashboard', [
            'stats' => $stats,
        ]);
    }
}