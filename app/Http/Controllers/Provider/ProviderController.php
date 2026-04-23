<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ProviderController extends Controller
{
    /**
     * Get provider's students
     */
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

        // Calculate remaining earnings after withdrawals
        $approvedWithdrawals = Withdrawal::where('provider_id', $providerId)
            ->where('status', 'approved')
            ->sum('amount');

        $pendingWithdrawals = Withdrawal::where('provider_id', $providerId)
            ->where('status', 'pending')
            ->sum('amount');

        $remainingEarnings = max(0, $totalEarnings - $approvedWithdrawals - $pendingWithdrawals);

        $monthlyEarnings = Enrollment::whereHas('course', function($query) use ($providerId) {
            $query->where('provider_id', $providerId);
        })
        ->where('payment_status', 'paid')
        ->selectRaw('DATE_FORMAT(enrolled_at, "%Y-%m") as month, SUM(price_at_purchase) as total')
        ->groupBy('month')
        ->orderBy('month', 'desc')
        ->get();

        return view('provider.earnings', compact('earnings', 'totalEarnings', 'remainingEarnings', 'approvedWithdrawals', 'monthlyEarnings', 'year', 'month'));
    }

    public function withdrawals(Request $request)
    {
        $provider = Auth::guard('provider')->user() ?? Auth::user();
        $providerId = $provider->id;

        $totalEarnings = Enrollment::whereHas('course', function ($query) use ($providerId) {
            $query->where('provider_id', $providerId);
        })->where('payment_status', 'paid')->sum('price_at_purchase');

        $approvedWithdrawals = Withdrawal::where('provider_id', $providerId)
            ->where('status', 'approved')
            ->sum('amount');

        $pendingWithdrawals = Withdrawal::where('provider_id', $providerId)
            ->where('status', 'pending')
            ->sum('amount');

        $availableBalance = max(0, $totalEarnings - $approvedWithdrawals - $pendingWithdrawals);

        $withdrawals = Withdrawal::where('provider_id', $providerId)
            ->with('processedBy')
            ->latest('requested_at')
            ->paginate(12);

        return view('provider.withdrawals.index', compact(
            'withdrawals',
            'availableBalance',
            'totalEarnings',
            'approvedWithdrawals',
            'pendingWithdrawals',
            'provider'
        ));
    }

    public function requestWithdrawal(Request $request)
    {
        $provider = Auth::guard('provider')->user() ?? Auth::user();

        if ($provider->status !== 'active') {
            return back()->with('error', 'Bạn chưa được phê duyệt, không thể gửi yêu cầu rút tiền.');
        }

        $totalEarnings = Enrollment::whereHas('course', function ($query) use ($provider) {
            $query->where('provider_id', $provider->id);
        })->where('payment_status', 'paid')->sum('price_at_purchase');

        $approvedWithdrawals = Withdrawal::where('provider_id', $provider->id)
            ->where('status', 'approved')
            ->sum('amount');

        $pendingWithdrawals = Withdrawal::where('provider_id', $provider->id)
            ->where('status', 'pending')
            ->sum('amount');

        $availableBalance = max(0, $totalEarnings - $approvedWithdrawals - $pendingWithdrawals);

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:100000', "max:{$availableBalance}"],
            'bank_account' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'account_holder' => 'required|string|max:255',
        ], [
            'amount.max' => 'Số tiền yêu cầu không được vượt quá số dư khả dụng.',
        ]);

        Withdrawal::create([
            'provider_id' => $provider->id,
            'amount' => $validated['amount'],
            'bank_account' => $validated['bank_account'],
            'bank_name' => $validated['bank_name'],
            'account_holder' => $validated['account_holder'],
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        return back()->with('success', 'Yêu cầu rút tiền đã được gửi. Vui lòng chờ admin xử lý.');
    }

    public function updateStudentStatus(Request $request, $courseId, $userId)
    {
        // Cập nhật trực tiếp vào bảng Enrollments cho nhanh và chính xác
        \App\Models\Enrollment::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->update(['status' => $request->status]);

        return back()->with('success', 'Cập nhật trạng thái thành công!');
    }
    /**
     * Get provider's profile
     */
    public function profile()
    {
        $provider = Auth::user();

        return view('provider.profile', compact('provider'));
    }
public function updateProfile(Request $request)
{
    // 1. Lấy thông tin Provider đang đăng nhập
    // Tùy vào cách bạn đặt tên guard, thường là 'provider'
    $provider = auth('provider')->user(); 

    // 2. Kiểm tra dữ liệu đầu vào
    $request->validate([
        'full_name' => 'required|string|max:255',
        'avatar'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Ảnh tối đa 2MB
    ]);

    // 3. Cập nhật họ tên
    $provider->full_name = $request->full_name;

    // 4. Xử lý upload ảnh đại diện
    if ($request->hasFile('avatar')) {
        // Xóa ảnh cũ nếu đã tồn tại để tránh rác server
        if ($provider->avatar) {
            Storage::disk('public')->delete($provider->avatar);
        }

        // Lưu file mới vào thư mục storage/app/public/avatars
        // store() sẽ tự động tạo tên file ngẫu nhiên để tránh trùng lặp
        $path = $request->file('avatar')->store('avatars', 'public');

        // Lưu đường dẫn mới vào database
        $provider->avatar = $path;
    }

    // 5. Lưu lại toàn bộ thay đổi
    $provider->save();

    return back()->with('success', 'Cập nhật thông tin và ảnh đại diện thành công!');
}
public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password',
        ], [
            'new_password.min' => 'Mật khẩu mới phải từ 8 ký tự.',
            'confirm_password.same' => 'Mật khẩu xác nhận không khớp.'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->with('error', 'Mật khẩu cũ không chính xác.');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Đổi mật khẩu thành công!');
    }

}