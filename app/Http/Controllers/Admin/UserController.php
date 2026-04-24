<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterUsersRequest;
use App\Http\Requests\UpdateUserStatusRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     *
     * @param FilterUsersRequest $request
     * @return \Illuminate\View\View
     */
    public function index(FilterUsersRequest $request)
    {
        try {
            $validated = $request->validated();

            $query = User::query();

            // Filter by role
            if (!empty($validated['role'])) {
                $query->where('role', $validated['role']);
            }

            // Filter by status
            if (!empty($validated['status'])) {
                $query->where('status', $validated['status']);
            }

            // Search by username, email, or full_name
            if (!empty($validated['search'])) {
                $search = trim($validated['search']);
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('full_name', 'like', "%{$search}%");
                });
            }

            // Get users with pagination
            $users = $query->orderBy('created_at', 'desc')->paginate(15);

            // Log the action
            Log::info('Admin viewed users list', [
                'admin_id' => Auth::user()->id,
                'admin_email' => Auth::user()->email,
                'filters_applied' => count(array_filter($validated)),
                'results_count' => $users->total(),
            ]);

            return view('admin.users.index', [
                'users' => $users,
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching users list', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::user()->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra khi tải danh sách người dùng!');
        }
    }

    /**
     * Show details of a specific user.
     *
     * @param User $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        try {
            \Log::info('Show method called', ['user_id' => $user->id, 'user_username' => $user->username]);

            // Load relationships
            if ($user->isProvider()) {
                // For providers, load created courses
                $user->load('createdCourses');
            } else {
                // For regular users, load enrolled courses
                $user->load('courses');
            }

            // Log the action
            Log::info('Admin viewed user details', [
                'admin_id' => Auth::user()->id,
                'admin_email' => Auth::user()->email,
                'user_id' => $user->id,
                'user_username' => $user->username,
                'user_role' => $user->role,
            ]);

            return view('admin.users.show', [
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in show method', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => $user->id ?? 'unknown',
            ]);

            Log::error('Error displaying user details', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::user()->id,
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('admin.users.index')
                ->with('error', 'Có lỗi xảy ra khi tải thông tin người dùng! Error: ' . $e->getMessage());
        }
    }

    /**
     * Update the status of a user.
     *
     * @param UpdateUserStatusRequest $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(UpdateUserStatusRequest $request, User $user)
    {
        try {
            $validated = $request->validated();

            // Check if user is trying to change their own status to blocked
            if (Auth::user()->id === $user->id && $validated['status'] === 'blocked') {
                Log::warning('Admin attempted to block their own account', [
                    'admin_id' => Auth::user()->id,
                    'admin_email' => Auth::user()->email,
                ]);
                return redirect()->back()
                    ->with('error', 'Bạn không thể khóa chính tài khoản của mình!');
            }

            // Check if trying to change an admin's status
            if ($user->role === 'admin' && $validated['status'] === 'blocked') {
                Log::warning('Admin attempted to block another admin account', [
                    'admin_id' => Auth::user()->id,
                    'admin_email' => Auth::user()->email,
                    'target_user_id' => $user->id,
                    'target_username' => $user->username,
                ]);
                return redirect()->back()
                    ->with('error', 'Không thể khóa tài khoản admin!');
            }

            $oldStatus = $user->status;
            $user->update($validated);

            // Log the action
            Log::info('Admin updated user status', [
                'admin_id' => Auth::user()->id,
                'admin_email' => Auth::user()->email,
                'target_user_id' => $user->id,
                'target_username' => $user->username,
                'target_role' => $user->role,
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
            ]);

            $statusText = $validated['status'] === 'active' ? 'Mở khóa' : 'Khóa';
            return redirect()->back()
                ->with('success', "Đã {$statusText} tài khoản người dùng {$user->username} thành công!");
        } catch (\Exception $e) {
            Log::error('Error updating user status', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::user()->id,
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái người dùng!');
        }
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        try {
            // Prevent deleting the current admin user
            if (Auth::user()->id === $user->id) {
                Log::warning('Admin attempted to delete their own account', [
                    'admin_id' => Auth::user()->id,
                    'admin_email' => Auth::user()->email,
                ]);
                return redirect()->back()
                    ->with('error', 'Bạn không thể xóa chính tài khoản của mình!');
            }

            // Prevent deleting other admin accounts
            if ($user->role === 'admin') {
                Log::warning('Admin attempted to delete another admin account', [
                    'admin_id' => Auth::user()->id,
                    'admin_email' => Auth::user()->email,
                    'target_user_id' => $user->id,
                    'target_username' => $user->username,
                ]);
                return redirect()->back()
                    ->with('error', 'Không thể xóa tài khoản admin!');
            }

            // Store user info for logging before deletion
            $userId = $user->id;
            $username = $user->username;
            $userRole = $user->role;
            $userEmail = $user->email;

            // Use transaction for safe deletion
            DB::transaction(function () use ($user) {
                // Delete related enrollments first
                $user->enrollments()->delete();

                // Delete the user
                $user->delete();
            });

            // Log the action (warning level because deletion is significant)
            Log::warning('Admin deleted user', [
                'admin_id' => Auth::user()->id,
                'admin_email' => Auth::user()->email,
                'deleted_user_id' => $userId,
                'deleted_username' => $username,
                'deleted_user_email' => $userEmail,
                'deleted_user_role' => $userRole,
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', "Đã xóa người dùng {$username} thành công!");
        } catch (\Exception $e) {
            Log::error('Error deleting user', [
                'error' => $e->getMessage(),
                'admin_id' => Auth::user()->id,
                'admin_email' => Auth::user()->email,
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa người dùng. Vui lòng thử lại!');
        }
    }
}
