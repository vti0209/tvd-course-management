<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    /**
     * Display pending withdrawal requests.
     */
    public function index()
    {
        // TODO: Fetch withdrawal requests from database
        $pendingWithdrawals = [];
        $approvedWithdrawals = [];

        return view('admin.withdrawals.index', [
            'pendingWithdrawals' => $pendingWithdrawals,
            'approvedWithdrawals' => $approvedWithdrawals,
        ]);
    }

    /**
     * Show details of a withdrawal request.
     */
    public function show($id)
    {
        // TODO: Fetch withdrawal details from database
        $withdrawal = null;

        return view('admin.withdrawals.show', [
            'withdrawal' => $withdrawal,
        ]);
    }

    /**
     * Approve a withdrawal request.
     */
    public function approve(Request $request, $id)
    {
        // TODO: Update withdrawal status
        // TODO: Send email confirmation to provider

        return redirect()->back()->with('success', 'Yêu cầu rút tiền được phê duyệt thành công!');
    }

    /**
     * Reject a withdrawal request.
     */
    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        // TODO: Update withdrawal status
        // TODO: Send email rejection to provider

        return redirect()->back()->with('success', 'Yêu cầu rút tiền bị từ chối!');
    }
}
