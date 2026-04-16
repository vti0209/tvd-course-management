<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash; // Cực kỳ quan trọng để mã hóa pass
use Illuminate\Support\Str;          // Cực kỳ quan trọng để random pass

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Kiểm tra email có tồn tại không
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'Email này không tồn tại trong hệ thống.'
        ]);

        // 1. Tạo mật khẩu mới ngẫu nhiên (8 ký tự)
        $newPassword = Str::random(8);

        // 2. Tìm User và cập nhật mật khẩu mới
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($newPassword); // Mã hóa mật khẩu
        $user->save();

        // 3. Gửi Mail chứa mật khẩu mới
        try {
            Mail::send('emails.forgot-password', ['newPassword' => $newPassword], function($message) use($request){
                $message->to($request->email);
                $message->subject('Mật khẩu mới của bạn - Gemini Academy');
            });

            return back()->with('status', 'Mật khẩu mới đã được gửi vào Email của bạn. Hãy kiểm tra!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Lỗi gửi mail: ' . $e->getMessage()]);
        }
    }
}