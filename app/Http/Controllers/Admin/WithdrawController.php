<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class WithdrawController extends Controller
{
    /**
     * Display pending and approved withdrawal requests.
     */
    public function index()
    {
        $pendingWithdrawals = Withdrawal::where('status', 'pending')
            ->with('provider')
            ->latest('requested_at')
            ->paginate(15);

        $approvedWithdrawals = Withdrawal::where('status', 'approved')
            ->with('provider')
            ->latest('processed_at')
            ->paginate(15);

        $totalCoursePayments = Enrollment::where('payment_status', 'paid')
            ->sum('price_at_purchase');

        $totalWithdrawalRequests = Withdrawal::whereIn('status', ['pending', 'approved'])
            ->sum('amount');

        $remainingCourseBalance = max(0, $totalCoursePayments - $totalWithdrawalRequests);

        $adminRevenue = Withdrawal::approved()
            ->selectRaw('SUM(amount * ?) as total', [Withdrawal::FEE_RATE])
            ->value('total') ?: 0;

        return view('admin.withdrawals.index', [
            'pendingWithdrawals' => $pendingWithdrawals,
            'approvedWithdrawals' => $approvedWithdrawals,
            'adminRevenue' => $adminRevenue,
            'totalCoursePayments' => $totalCoursePayments,
            'totalWithdrawalRequests' => $totalWithdrawalRequests,
            'remainingCourseBalance' => $remainingCourseBalance,
        ]);
    }

    /**
     * Show details of a withdrawal request.
     */
    public function show($id)
    {
        $withdrawal = Withdrawal::with(['provider', 'processedBy'])->findOrFail($id);

        return view('admin.withdrawals.show', [
            'withdrawal' => $withdrawal,
        ]);
    }

    /**
     * Approve a withdrawal request.
     */
    public function approve(Request $request, $id)
    {
        $withdrawal = Withdrawal::where('status', 'pending')->findOrFail($id);

        $withdrawal->update([
            'status' => 'approved',
            'processed_by' => Auth::guard('admin')->id() ?? Auth::id(),
            'processed_at' => now(),
            'rejection_reason' => null,
        ]);

        try {
            Mail::send('emails.withdrawal-approved', [
                'withdrawal' => $withdrawal,
            ], function ($message) use ($withdrawal) {
                $message->to($withdrawal->provider->email)
                    ->subject('Yêu cầu rút tiền của bạn đã được phê duyệt');
            });
        } catch (\Exception $e) {
            // Nếu gửi email thất bại thì vẫn tiếp tục xử lý yêu cầu
        }

        return redirect()->back()->with('success', 'Yêu cầu rút tiền đã được phê duyệt thành công!');
    }

    /**
     * Reject a withdrawal request.
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $withdrawal = Withdrawal::where('status', 'pending')->findOrFail($id);

        $withdrawal->update([
            'status' => 'rejected',
            'processed_by' => Auth::guard('admin')->id() ?? Auth::id(),
            'processed_at' => now(),
            'rejection_reason' => $validated['reason'],
        ]);

        try {
            Mail::send('emails.withdrawal-rejected', [
                'withdrawal' => $withdrawal,
                'reason' => $validated['reason'],
            ], function ($message) use ($withdrawal) {
                $message->to($withdrawal->provider->email)
                    ->subject('Yêu cầu rút tiền của bạn đã bị từ chối');
            });
        } catch (\Exception $e) {
            // Không dừng quá trình nếu email thất bại
        }

        return redirect()->back()->with('success', 'Yêu cầu rút tiền đã bị từ chối.');
    }
}
