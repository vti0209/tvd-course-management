<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    /**
     * Get provider's students
     */
    public function students(Request $request)
    {
        $providerId = Auth::user()->id;
        $search = $request->input('search');
        $learningStatus = $request->input('learning_status');

        $enrollments = Enrollment::whereHas('course', function($query) use ($providerId) {
            $query->where('provider_id', $providerId);
        })
        ->when($search, function($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('full_name', 'LIKE', "%{$search}%")->orWhere('username', 'LIKE', "%{$search}%");
                })->orWhereHas('course', function($c) use ($search) {
                    $c->where('title', 'LIKE', "%{$search}%");
                });
            });
        })
        ->when($learningStatus, function($query, $learningStatus) {
            return $query->where('status', $learningStatus);
        })
        ->with(['user', 'course'])
        ->latest('enrolled_at')
        ->paginate(10)
        ->withQueryString();

        return view('provider.students', compact('enrollments'));
    }

    /**
     * Get provider's earnings
     */
    public function earnings(Request $request)
    {
        $providerId = Auth::user()->id;
        $year = $request->input('year', date('Y'));
        $month = $request->input('month');

        $query = Enrollment::whereHas('course', function($query) use ($providerId) {
            $query->where('provider_id', $providerId);
        })
        ->where('payment_status', 'paid')
        ->whereYear('enrolled_at', $year);

        if ($month) {
            $query->whereMonth('enrolled_at', $month);
        }

        $earnings = $query->with('course')
            ->latest('enrolled_at')
            ->paginate(15);

        // Calculate totals
        $totalEarnings = Enrollment::whereHas('course', function($query) use ($providerId) {
            $query->where('provider_id', $providerId);
        })->where('payment_status', 'paid')->sum('price_at_purchase');

        $monthlyEarnings = Enrollment::whereHas('course', function($query) use ($providerId) {
            $query->where('provider_id', $providerId);
        })
        ->where('payment_status', 'paid')
        ->selectRaw('DATE_FORMAT(enrolled_at, "%Y-%m") as month, SUM(price_at_purchase) as total')
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->get();

        return view('provider.earnings', compact('earnings', 'totalEarnings', 'monthlyEarnings', 'year', 'month'));
    }
    public function updateStudentStatus(Request $request, $courseId, $userId)
    {
        // Cập nhật trực tiếp vào bảng Enrollments cho nhanh và chính xác
        \App\Models\Enrollment::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->update(['status' => $request->status]);

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
    /**
     * Get provider's profile
     */
    public function profile()
    {
        $provider = Auth::user();

        return view('provider.profile', compact('provider'));
    }
}
