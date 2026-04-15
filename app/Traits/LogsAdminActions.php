<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait LogsAdminActions
{
    /**
     * Log admin action with context information.
     *
     * @param string $action
     * @param array $context
     * @param string $level
     */
    protected function logAdminAction(string $action, array $context = [], string $level = 'info'): void
    {
        $data = array_merge([
            'admin_id' => auth()->id(),
            'admin_email' => auth()->user()?->email,
            'timestamp' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ], $context);

        Log::$level($action, $data);
    }

    /**
     * Log admin warning action.
     *
     * @param string $action
     * @param array $context
     */
    protected function logAdminWarning(string $action, array $context = []): void
    {
        $this->logAdminAction($action, $context, 'warning');
    }

    /**
     * Log admin error action.
     *
     * @param string $action
     * @param array $context
     */
    protected function logAdminError(string $action, array $context = []): void
    {
        $this->logAdminAction($action, $context, 'error');
    }
}
