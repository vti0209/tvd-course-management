<?php

namespace App\Http\Controllers\Admin;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    /**
     * Display pending provider requests.
     */
    public function index(Request $request)
    {
        $search = $request->query('search', '');

        // Get pending providers from users table (new system)
        $pendingUserProviders = User::where('role', 'provider')
            ->where('status', 'pending')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('full_name', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Get approved providers from users table
        $approvedUserProviders = User::where('role', 'provider')
            ->where('status', 'active')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('full_name', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.providers.index', [
            'pendingUserProviders' => $pendingUserProviders,
            'approvedUserProviders' => $approvedUserProviders,
            'search' => $search,
        ]);
    }

    /**
     * Approve a user-based provider request.
     */
    public function approveUser(Request $request, User $user)
    {
        // Update user status to active
        $user->update(['status' => 'active']);

        // Send approval email with login credentials
        try {
            Mail::send('emails.provider-approved', [
                'user' => $user,
                'email' => $user->email,
                'password' => 'Gemini2026!', // Default password set during registration
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Yêu cầu cung cấp khóa học của bạn được phê duyệt!');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Phê duyệt thành công nhưng gửi email thất bại!');
        }

        return redirect()->route('admin.providers.index')->with('success', 'Yêu cầu Provider được phê duyệt thành công! Email thông báo đã được gửi.');
    }

    /**
     * Reject a user-based provider request.
     */
    public function rejectUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        // Send rejection email
        try {
            Mail::send('emails.provider-rejected', [
                'user' => $user,
                'reason' => $validated['reason'],
            ], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Yêu cầu cung cấp khóa học của bạn bị từ chối');
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Từ chối không thành công!');
        }

        // Delete the user
        $user->delete();

        return redirect()->route('admin.providers.index')->with('success', 'Yêu cầu Provider bị từ chối! Email thông báo đã được gửi.');
    }

    public function students(Request $request)
    {
        $providerId = Auth::user()->id;
        $search = $request->input('search');
        $learningStatus = $request->input('learning_status');

        $enrollments = Enrollment::whereHas('course', function($query) use ($providerId) {
            $query->where('provider_id', $providerId);
        })
        ->when($search, function($query, $search) {
            return $query->where(function($q) use ($search) {
                $q->whereHas('user', function($u) use ($search) {
                    $u->where('full_name', 'LIKE', "%{$search}%")->orWhere('username', 'LIKE', "%{$search}%");
                })->orWhereHas('course', function($c) use ($search) {
                    $c->where('title', 'LIKE', "%{$search}%");
                });
            });
        })
        ->when($learningStatus, function($query, $learningStatus) {
            return $query->where('status', $learningStatus);
        })
        ->with(['user', 'course'])
        ->latest('enrolled_at')
        ->paginate(10)
        ->withQueryString();

        return view('provider.students', compact('enrollments'));
    }

    /**
     * Get provider's earnings
     */
    public function earnings(Request $request)
    {
        $providerId = Auth::user()->id;
        $year = $request->input('year', date('Y'));
        $month = $request->input('month');

        $query = Enrollment::whereHas('course', function($query) use ($providerId) {
            $query->where('provider_id', $providerId);
        })
        ->where('payment_status', 'paid')
        ->whereYear('enrolled_at', $year);

        if ($month) {
            $query->whereMonth('enrolled_at', $month);
        }

        $earnings = $query->with('course')
            ->latest('enrolled_at')
            ->paginate(15);

        // Calculate totals
        $totalEarnings = Enrollment::whereHas('course', function($query) use ($providerId) {
            $query->where('provider_id', $providerId);
        })->where('payment_status', 'paid')->sum('price_at_purchase');

        return view('provider.earnings', compact('earnings', 'totalEarnings', 'year', 'month'));
    }

    /**
     * Get provider's profile
     */
    public function profile()
    {
        $provider = Auth::user();
        return view('provider.profile', compact('provider'));
    }
}