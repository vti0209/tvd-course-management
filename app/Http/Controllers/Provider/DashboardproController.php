<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class DashboardproController extends Controller
{
    /**
     * Hiển thị dashboard cho provider
     */
    public function index()
    {
        $provider = Auth::user();
        
        // Lấy các khóa học của provider
        $courses = Course::where('provider_id', $provider->id)->get();
        
        // Thống kê
        $stats = [
            'total_courses' => $courses->count(),
            'active_courses' => $courses->where('status', 'active')->count(),
            'pending_courses' => $courses->where('status', 'pending')->count(),
            'rejected_courses' => $courses->where('status', 'rejected')->count(),
            'total_students' => Enrollment::whereIn('course_id', $courses->pluck('id'))->distinct('user_id')->count(),
            'total_revenue' => Enrollment::whereIn('course_id', $courses->pluck('id'))
                ->where('payment_status', 'paid')
                ->sum('price_at_purchase'),
        ];
        
        // Lấy enrollments gần đây
        $recent_enrollments = Enrollment::whereIn('course_id', $courses->pluck('id'))
            ->orderBy('enrolled_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('provider.dashboard', [
            'stats' => $stats,
            'courses' => $courses,
            'recent_enrollments' => $recent_enrollments,
        ]);
    }
}
