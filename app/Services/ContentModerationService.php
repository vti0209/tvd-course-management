<?php

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use App\Mail\CourseApprovedMail;
use App\Mail\CourseRejectedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ContentModerationService
{
    /**
     * Get all courses for moderation with filtering
     */
    public function getCoursesForModeration(array $filters = [])
    {
        $query = Course::with(['provider', 'category']);

        // Filter by status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Filter by provider
        if (!empty($filters['provider_id'])) {
            $query->where('provider_id', $filters['provider_id']);
        }

        // Filter by category
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Search by title or description
        if (!empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                  ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        // Sort
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->latest();
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Get pending courses for approval
     */
    public function getPendingCourses(int $perPage = 10)
    {
        return Course::where('status', 'pending')
            ->with(['provider', 'category'])
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get course statistics for dashboard
     */
    public function getStatistics()
    {
        return [
            'total' => Course::count(),
            'pending' => Course::where('status', 'pending')->count(),
            'active' => Course::where('status', 'active')->count(),
            'rejected' => Course::where('status', 'rejected')->count(),
        ];
    }

    /**
     * Check if course can be approved
     */
    public function canApproveCourse(Course $course): bool
    {
        return $course->status === 'pending' && $course->provider->status === 'active';
    }

    /**
     * Check if course can be rejected
     */
    public function canRejectCourse(Course $course): bool
    {
        return $course->status === 'pending';
    }

    /**
     * Approve a course
     */
    public function approveCourse(Course $course, User $admin, string $notes = ''): bool
    {
        if (!$this->canApproveCourse($course)) {
            throw new Exception('Khóa học này không thể được phê duyệt.');
        }

        try {
            DB::beginTransaction();

            // Update course
            $course->update([
                'status' => 'active',
                'approved_at' => now(),
                'approved_by' => $admin->id,
                'rejection_reason' => null,
                'rejected_at' => null,
            ]);

            // Send email notification
            Mail::to($course->provider->email)->send(
                new CourseApprovedMail($course)
            );

            // Log the action
            $this->logModerationAction(
                'approve',
                $course,
                $admin,
                $notes ?: 'Được phê duyệt bởi admin',
                'success'
            );

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error approving course: ' . $e->getMessage(), [
                'course_id' => $course->id,
                'admin_id' => $admin->id,
            ]);
            throw $e;
        }
    }

    /**
     * Reject a course
     */
    public function rejectCourse(Course $course, User $admin, string $reason): bool
    {
        if (!$this->canRejectCourse($course)) {
            throw new Exception('Khóa học này không thể bị từ chối.');
        }

        try {
            DB::beginTransaction();

            // Update course
            $course->update([
                'status' => 'rejected',
                'rejection_reason' => $reason,
                'rejected_at' => now(),
                'approved_at' => null,
                'approved_by' => null,
            ]);

            // Send email notification with reason
            Mail::to($course->provider->email)->send(
                new CourseRejectedMail($course, $reason)
            );

            // Log the action
            $this->logModerationAction(
                'reject',
                $course,
                $admin,
                $reason,
                'success'
            );

            DB::commit();

            return true;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error rejecting course: ' . $e->getMessage(), [
                'course_id' => $course->id,
                'admin_id' => $admin->id,
            ]);
            throw $e;
        }
    }

    /**
     * Request changes on a course (alternative to rejection)
     */
    public function requestChanges(Course $course, User $admin, string $reason): bool
    {
        // This is similar to rejection but indicates the provider can resubmit
        $course->update([
            'status' => 'pending',
            'rejection_reason' => $reason,
        ]);

        Mail::to($course->provider->email)->send(
            new CourseRejectedMail($course, $reason)
        );

        $this->logModerationAction(
            'request_changes',
            $course,
            $admin,
            $reason,
            'success'
        );

        return true;
    }

    /**
     * Resubmit a rejected course (for provider)
     */
    public function resubmitCourse(Course $course): bool
    {
        if ($course->status !== 'rejected') {
            throw new Exception('Chỉ có thể tái nộp khóa học đã bị từ chối.');
        }

        $course->update([
            'status' => 'pending',
            'rejection_reason' => null,
            'rejected_at' => null,
        ]);

        return true;
    }

    /**
     * Log moderation actions
     */
    protected function logModerationAction(
        string $action,
        Course $course,
        User $admin,
        string $details,
        string $status = 'success'
    ): void {
        Log::channel('admin_actions')->info('Content Moderation Action', [
            'action' => $action,
            'course_id' => $course->id,
            'course_title' => $course->title,
            'provider_id' => $course->provider_id,
            'admin_id' => $admin->id,
            'admin_email' => $admin->email,
            'details' => $details,
            'status' => $status,
            'timestamp' => now(),
        ]);
    }

    /**
     * Get courses by provider
     */
    public function getProviderCourses(User $provider)
    {
        if ($provider->role !== 'provider') {
            throw new Exception('Người dùng không phải là nhà cung cấp.');
        }

        return Course::where('provider_id', $provider->id)
            ->with('category')
            ->latest()
            ->get();
    }

    /**
     * Export courses data for reports
     */
    public function exportCoursesData(array $filters = [])
    {
        return $this->getCoursesForModeration($filters)
            ->getCollection()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'provider' => $course->provider->full_name,
                    'category' => $course->category->name,
                    'status' => $course->status,
                    'price' => $course->price,
                    'created_at' => $course->created_at->format('Y-m-d H:i:s'),
                    'approved_at' => $course->approved_at?->format('Y-m-d H:i:s'),
                    'rejection_reason' => $course->rejection_reason,
                ];
            });
    }
}
