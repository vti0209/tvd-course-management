<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class ContentModerationController extends Controller
{
    /**
     * Display pending courses for approval.
     */
    public function index()
    {
        $pendingCourses = Course::where('status', 'pending')
            ->with('provider')
            ->paginate(10);

        $allCourses = Course::with('provider')
            ->latest()
            ->paginate(15);

        return view('admin.content-moderation.index', [
            'pendingCourses' => $pendingCourses,
            'allCourses' => $allCourses,
        ]);
    }

    /**
     * Show details of a course for moderation.
     */
    public function show(Course $course)
    {
        return view('admin.content-moderation.show', [
            'course' => $course,
        ]);
    }

    /**
     * Approve a course.
     */
    public function approve(Course $course)
    {
        $course->update([
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Khóa học được phê duyệt thành công!');
    }

    /**
     * Reject a course.
     */
    public function reject(Request $request, Course $course)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $course->update([
            'status' => 'rejected',
        ]);

        // TODO: Send email to provider with rejection reason

        return redirect()->back()->with('success', 'Khóa học bị từ chối! Email thông báo đã được gửi tới nhà cung cấp.');
    }
}
