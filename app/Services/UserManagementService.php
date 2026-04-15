<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserManagementService
{
    /**
     * Check if an admin can modify a user.
     *
     * @param User $admin
     * @param User $targetUser
     * @return array{allowed: bool, message: string}
     */
    public static function canModifyUser(User $admin, User $targetUser): array
    {
        // Admin cannot modify themselves
        if ($admin->id === $targetUser->id) {
            return [
                'allowed' => false,
                'message' => 'Bạn không thể thay đổi tài khoản của chính mình qua chức năng này!',
            ];
        }

        // Admin cannot modify other admins
        if ($targetUser->role === 'admin') {
            return [
                'allowed' => false,
                'message' => 'Bạn không có quyền thay đổi tài khoản admin!',
            ];
        }

        return [
            'allowed' => true,
            'message' => '',
        ];
    }

    /**
     * Check if an admin can delete a user.
     *
     * @param User $admin
     * @param User $targetUser
     * @return array{allowed: bool, message: string}
     */
    public static function canDeleteUser(User $admin, User $targetUser): array
    {
        // Cannot delete self
        if ($admin->id === $targetUser->id) {
            Log::warning('Admin attempted to delete their own account', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
            ]);

            return [
                'allowed' => false,
                'message' => 'Bạn không thể xóa chính tài khoản của mình!',
            ];
        }

        // Cannot delete other admins
        if ($targetUser->role === 'admin') {
            Log::warning('Admin attempted to delete another admin', [
                'admin_id' => $admin->id,
                'target_user_id' => $targetUser->id,
            ]);

            return [
                'allowed' => false,
                'message' => 'Bạn không có quyền xóa tài khoản admin!',
            ];
        }

        return [
            'allowed' => true,
            'message' => '',
        ];
    }

    /**
     * Check if an admin can change user status.
     *
     * @param User $admin
     * @param User $targetUser
     * @param string $newStatus
     * @return array{allowed: bool, message: string}
     */
    public static function canChangeUserStatus(User $admin, User $targetUser, string $newStatus): array
    {
        // Cannot change own status to blocked
        if ($admin->id === $targetUser->id && $newStatus === 'blocked') {
            Log::warning('Admin attempted to block their own account', [
                'admin_id' => $admin->id,
            ]);

            return [
                'allowed' => false,
                'message' => 'Bạn không thể khóa chính tài khoản của mình!',
            ];
        }

        // Cannot block admin accounts
        if ($targetUser->role === 'admin' && $newStatus === 'blocked') {
            Log::warning('Admin attempted to block another admin', [
                'admin_id' => $admin->id,
                'target_user_id' => $targetUser->id,
            ]);

            return [
                'allowed' => false,
                'message' => 'Bạn không có quyền khóa tài khoản admin!',
            ];
        }

        return [
            'allowed' => true,
            'message' => '',
        ];
    }

    /**
     * Get user statistics for admin dashboard.
     *
     * @return array
     */
    public static function getUserStatistics(): array
    {
        return [
            'total_users' => User::count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_providers' => User::where('role', 'provider')->count(),
            'total_students' => User::where('role', 'user')->count(),
            'active_users' => User::where('status', 'active')->count(),
            'blocked_users' => User::where('status', 'blocked')->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
        ];
    }

    /**
     * Format user info for logging.
     *
     * @param User $user
     * @return array
     */
    public static function formatUserInfo(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'created_at' => $user->created_at,
        ];
    }
}