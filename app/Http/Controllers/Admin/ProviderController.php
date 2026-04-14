<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProviderController extends Controller
{
    /**
     * Display pending provider requests.
     */
    public function index()
    {
        $pendingProviders = Provider::where('approved_at', null)
            ->with('user')
            ->paginate(15);

        $approvedProviders = Provider::whereNotNull('approved_at')
            ->with('user')
            ->paginate(15);

        return view('admin.providers.index', [
            'pendingProviders' => $pendingProviders,
            'approvedProviders' => $approvedProviders,
        ]);
    }

    /**
     * Show details of a provider request.
     */
    public function show(Provider $provider)
    {
        return view('admin.providers.show', [
            'provider' => $provider,
        ]);
    }

    /**
     * Approve a provider request.
     */
    public function approve(Request $request, Provider $provider)
    {
        // Update provider status
        $provider->update([
            'approved_at' => now(),
        ]);

        // Send approval email
        try {
            Mail::send('emails.provider-approved', [
                'user' => $provider->user,
                'email' => $provider->user->email,
                'password' => $request->input('password', 'default_password_123'),
            ], function ($message) use ($provider) {
                $message->to($provider->user->email)
                        ->subject('Yêu cầu cung cấp khóa học của bạn được phê duyệt!');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Phê duyệt thành công nhưng gửi email thất bại!');
        }

        return redirect()->route('admin.providers.index')->with('success', 'Yêu cầu Provider được phê duyệt thành công! Email thông báo đã được gửi.');
    }

    /**
     * Reject a provider request.
     */
    public function reject(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        // Send rejection email
        try {
            Mail::send('emails.provider-rejected', [
                'user' => $provider->user,
                'reason' => $validated['reason'],
            ], function ($message) use ($provider) {
                $message->to($provider->user->email)
                        ->subject('Yêu cầu cung cấp khóa học của bạn bị từ chối');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Từ chối không thành công!');
        }

        // Delete the provider request
        $provider->delete();

        return redirect()->route('admin.providers.index')->with('success', 'Yêu cầu Provider bị từ chối! Email thông báo đã được gửi.');
    }
}
