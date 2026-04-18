<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\ContentModerationService;
use App\Http\Requests\FilterCoursesRequest;
use App\Http\Requests\ApproveCourseRequest;
use App\Http\Requests\RejectCourseRequest;
use App\Traits\LogsAdminActions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class ContentModerationController extends Controller
{
    use LogsAdminActions;

    protected ContentModerationService $moderationService;

    public function __construct(ContentModerationService $moderationService)
    {
        $this->moderationService = $moderationService;
    }

    /**
     * Display pending courses for approval with filtering.
     */
    public function index(FilterCoursesRequest $request)
    {
        try {
            $filters = $request->validated();
            
            // Get statistics
            $stats = $this->moderationService->getStatistics();
            
            // Get courses based on filters
            $courses = $this->moderationService->getCoursesForModeration($filters);
            
            // Get pending courses
            $pendingCourses = $this->moderationService->getPendingCourses(5);

            return view('admin.content-moderation.index', [
                'courses' => $courses,
                'pendingCourses' => $pendingCourses,
                'stats' => $stats,
                'filters' => $filters,
            ]);
        } catch (Exception $e) {
            $this->logAdminError('Error retrieving courses for moderation', ['error' => $e->getMessage()]);
            return back()->with('error', 'Có lỗi khi tải danh sách khóa học.');
        }
    }

    /**
     * Show details of a course for moderation.
     */
    public function show(Course $course)
    {
        try {
            $course->load(['provider', 'category', 'chapters.lessons', 'enrollments', 'approvedBy']);

            return view('admin.content-moderation.show', [
                'course' => $course,
                'canApprove' => $this->moderationService->canApproveCourse($course),
                'canReject' => $this->moderationService->canRejectCourse($course),
            ]);
        } catch (Exception $e) {
            $this->logAdminError('Error showing course details', ['course_id' => $course->id, 'error' => $e->getMessage()]);
            return back()->with('error', 'Có lỗi khi tải chi tiết khóa học.');
        }
    }

    /**
     * Approve a course.
     */
    public function approve(ApproveCourseRequest $request, Course $course)
    {
        try {
            $this->moderationService->approveCourse(
                $course,
                auth()->user(),
                $request->input('notes', '')
            );

            $this->logAdminAction(
                'COURSE_APPROVED',
                "Đã phê duyệt khóa học: {$course->title} (ID: {$course->id})",
                ['course_id' => $course->id, 'course_title' => $course->title]
            );

            return redirect()->back()
                ->with('success', "✅ Khóa học '{$course->title}' đã được phê duyệt thành công! Email thông báo đã gửi đến nhà cung cấp.");
        } catch (Exception $e) {
            $this->logAdminError('Error approving course', [
                'course_id' => $course->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Có lỗi khi phê duyệt khóa học: ' . $e->getMessage());
        }
    }

    /**
     * Reject a course.
     */
    public function reject(RejectCourseRequest $request, Course $course)
    {
        try {
            $reason = $request->validated()['reason'];

            $this->moderationService->rejectCourse(
                $course,
                auth()->user(),
                $reason
            );

            $this->logAdminAction(
                'COURSE_REJECTED',
                "Đã từ chối khóa học: {$course->title} (ID: {$course->id}) | Lý do: {$reason}",
                [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'reason' => $reason
                ]
            );

            return redirect()->back()
                ->with('success', "❌ Khóa học '{$course->title}' đã bị từ chối. Email thông báo với lý do đã gửi đến nhà cung cấp.");
        } catch (Exception $e) {
            $this->logAdminError('Error rejecting course', [
                'course_id' => $course->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Có lỗi khi từ chối khóa học: ' . $e->getMessage());
        }
    }

    /**
     * Request changes on a course (alternative to hard rejection)
     */
    public function requestChanges(RejectCourseRequest $request, Course $course)
    {
        try {
            $reason = $request->validated()['reason'];

            $this->moderationService->requestChanges(
                $course,
                auth()->user(),
                $reason
            );

            $this->logAdminAction(
                'COURSE_CHANGES_REQUESTED',
                "Yêu cầu sửa đổi khóa học: {$course->title} (ID: {$course->id})",
                [
                    'course_id' => $course->id,
                    'course_title' => $course->title,
                    'reason' => $reason
                ]
            );

            return redirect()->back()
                ->with('success', "✏️ Yêu cầu sửa đổi khóa học '{$course->title}' đã gửi đến nhà cung cấp. Họ có thể tái nộp sau khi chỉnh sửa.");
        } catch (Exception $e) {
            $this->logAdminError('Error requesting changes', [
                'course_id' => $course->id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Có lỗi khi yêu cầu sửa đổi: ' . $e->getMessage());
        }
    }

    /**
     * Bulk approve courses
     */
    public function bulkApprove(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_ids' => 'required|array|min:1',
                'course_ids.*' => 'integer|exists:courses,id',
            ]);

            $courseIds = $validated['course_ids'];
            $courses = Course::whereIn('id', $courseIds)->get();
            
            $approved = 0;
            $failed = 0;

            foreach ($courses as $course) {
                try {
                    $this->moderationService->approveCourse($course, auth()->user());
                    $approved++;
                } catch (Exception $e) {
                    $failed++;
                    Log::warning("Failed to approve course {$course->id}: " . $e->getMessage());
                }
            }

            $this->logAdminAction(
                'BULK_COURSE_APPROVED',
                "Phê duyệt hàng loạt $approved khóa học",
                ['approved' => $approved, 'failed' => $failed]
            );

            $message = "✅ Đã phê duyệt $approved khóa học";
            if ($failed > 0) {
                $message .= " ($failed khóa học không thể phê duyệt)";
            }

            return redirect()->back()->with('success', $message);
        } catch (Exception $e) {
            $this->logAdminError('Error in bulk approve', ['error' => $e->getMessage()]);
            return back()->with('error', 'Có lỗi khi phê duyệt hàng loạt: ' . $e->getMessage());
        }
    }

    /**
     * Export courses data for reports
     */
    public function export(FilterCoursesRequest $request)
    {
        try {
            $filters = $request->validated();
            $data = $this->moderationService->exportCoursesData($filters);

            $this->logAdminAction(
                'COURSES_EXPORTED',
                'Xuất dữ liệu khóa học',
                ['total_exported' => count($data)]
            );

            return response()->json([
                'success' => true,
                'data' => $data,
                'count' => count($data),
            ]);
        } catch (Exception $e) {
            $this->logAdminError('Error exporting courses', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi khi xuất dữ liệu: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics
     */
    public function statistics()
    {
        try {
            $stats = $this->moderationService->getStatistics();
            return response()->json($stats);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Có lỗi khi lấy thống kê'
            ], 500);
        }
    }
}