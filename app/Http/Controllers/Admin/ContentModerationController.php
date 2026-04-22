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
use Illuminate\Support\Facades\Auth; // Đảm bảo có dòng này để tránh lỗi 'Auth not found'
use Exception;
/**
 * @method void logAdminAction(string $action, string $description, array $details = [])
 * @method void logAdminError(string $message, array $context = [])
 */
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

            // Get statistics & data
            $stats = $this->moderationService->getStatistics();
            $courses = $this->moderationService->getCoursesForModeration($filters);
            $pendingCourses = $this->moderationService->getPendingCourses(5);

            return view('admin.content-moderation.index', compact('courses', 'pendingCourses', 'stats', 'filters'));
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
            // Eager loading để tối ưu query
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
        // 1. Thực hiện phê duyệt thông qua Service
        $this->moderationService->approveCourse(
            $course,
            Auth::user(), 
            $request->input('notes') ?? ''
        );

        // 2. Sửa lỗi TypeError tại đây:
        // Đưa chuỗi mô tả vào trong một mảng để khớp với tham số array $context
        $this->logAdminAction(
            'COURSE_APPROVED', 
            [
                'message' => "Đã phê duyệt khóa học: {$course->title} (ID: {$course->id})",
                'course_id' => $course->id, 
                'course_title' => $course->title
            ]
        );

        return redirect()->back()
            ->with('success', "Khóa học '{$course->title}' đã được phê duyệt thành công!");

    } catch (\Exception $e) {
        // Ghi log lỗi nếu có Exception xảy ra
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
   /**
     * Reject a course.
     */
    public function reject(RejectCourseRequest $request, Course $course)
{
    // XÓA DÒNG DD TẠI ĐÂY
    try {
        $reason = $request->validated()['reason'];

        $this->moderationService->rejectCourse(
            $course,
            Auth::user(),
            $reason
        );

        $this->logAdminAction(
            'COURSE_REJECTED',
            [
                'message' => "Đã từ chối khóa học: {$course->title} (ID: {$course->id})",
                'course_id' => $course->id, 
                'reason' => $reason
            ]
        );

        return redirect()->back()
            ->with('success', "Khóa học '{$course->title}' đã bị từ chối.");

    } catch (\Exception $e) {
        // Ghi log lỗi vào file storage/logs/laravel.log để kiểm tra sau
        \Log::error('Reject error: ' . $e->getMessage());

        // Quan trọng: Trả về lỗi để hiển thị lên màn hình
        return back()->with('error', 'Có lỗi khi từ chối: ' . $e->getMessage());
    }
}
    /**
     * Request changes on a course.
     */
    public function requestChanges(RejectCourseRequest $request, Course $course)
    {
        try {
            $reason = $request->validated()['reason'];

            $this->moderationService->requestChanges($course, Auth::user(), $reason);

            // FIX: Đưa string vào mảng ['message' => ...]
            $this->logAdminAction(
                'COURSE_CHANGES_REQUESTED',
                [
                    'message' => "Yêu cầu sửa đổi: {$course->title}",
                    'course_id' => $course->id, 
                    'reason' => $reason
                ]
            );

            return redirect()->back()
                ->with('success', "Đã gửi yêu cầu sửa đổi cho khóa học '{$course->title}'.");
        } catch (\Exception $e) {
            $this->logAdminError('Error requesting changes', [
                'course_id' => $course->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Bulk approve courses.
     */
    public function bulkApprove(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_ids' => 'required|array|min:1',
                'course_ids.*' => 'integer|exists:courses,id',
            ]);

            $courses = Course::whereIn('id', $validated['course_ids'])->get();
            $approved = 0;
            $failed = 0;

            foreach ($courses as $course) {
                try {
                    $this->moderationService->approveCourse($course, Auth::user());
                    $approved++;
                } catch (Exception $e) {
                    $failed++;
                    Log::error("Bulk Approve Fail ID {$course->id}: " . $e->getMessage());
                }
            }

            $this->logAdminAction('BULK_COURSE_APPROVED', "Phê duyệt $approved khóa học", ['count' => $approved]);

            return redirect()->back()->with('success', "Đã phê duyệt $approved khóa học" . ($failed > 0 ? " ($failed lỗi)" : ""));
        } catch (Exception $e) {
            return back()->with('error', 'Lỗi phê duyệt hàng loạt: ' . $e->getMessage());
        }
    }

    /**
     * Export data.
     */
    public function export(FilterCoursesRequest $request)
    {
        try {
            $data = $this->moderationService->exportCoursesData($request->validated());
            return response()->json(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get statistics.
     */
    public function statistics()
    {
        try {
            return response()->json($this->moderationService->getStatistics());
        } catch (Exception $e) {
            return response()->json(['error' => 'Lỗi lấy thống kê'], 500);
        }
    }
}