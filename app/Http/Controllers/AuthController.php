<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // FORM
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    // REGISTER
    public function register(Request $request)
    {
        if ($request->type === 'provider') {
            return $this->registerProvider($request);
        }

        return $this->registerUser($request);
    }

    private function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ], [
            'name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email phải là địa chỉ email hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải ít nhất 6 ký tự.',
            'password_confirmation.required' => 'Xác nhận mật khẩu là bắt buộc.',
            'password_confirmation.same' => 'Mật khẩu xác nhận không khớp.'
        ]);

        // Generate username from email (part before @)
        $username = explode('@', $request->email)[0];

        // Ensure username is unique
        $baseUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        User::create([
            'username' => $username,
            'full_name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'status' => 'active'
        ]);

        return redirect('/login')->with('success', 'Đăng ký thành công!');
    }

    private function registerProvider(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'provider_info' => 'required|file|mimes:pdf,doc,docx|max:5120'
        ], [
            'name.required' => 'Tên là bắt buộc.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email phải là địa chỉ email hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',
            'provider_info.required' => 'Tài liệu xác minh là bắt buộc.',
            'provider_info.file' => 'Tài liệu phải là file.',
            'provider_info.mimes' => 'Tài liệu phải có định dạng PDF, DOC hoặc DOCX.',
            'provider_info.max' => 'Tài liệu không được vượt quá 5MB.'
        ]);

        // Generate username from email (part before @)
        $username = explode('@', $request->email)[0];

        // Ensure username is unique
        $baseUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        // Handle file upload
        $fileName = time() . '_' . $username . '.' . $request->file('provider_info')->getClientOriginalExtension();
        $request->file('provider_info')->move(public_path('uploads/providers'), $fileName);

        // Generate default password
        $defaultPassword = 'Gemini2026!';

        User::create([
            'username' => $username,
            'full_name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($defaultPassword),
            'role' => 'provider',
            'status' => 'pending',
            'provider_info' => 'uploads/providers/' . $fileName
        ]);

        return redirect('/register')->with('info', 'Tài khoản của bạn đang chờ duyệt. Chúng tôi sẽ gửi thông tin đăng nhập qua email sau khi được phê duyệt.');
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email phải là địa chỉ email hợp lệ.',
            'password.required' => 'Mật khẩu là bắt buộc.'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Check if user status is active
            if ($user->status !== 'active') {
                Auth::logout();
                return back()->with('error', 'Tài khoản của bạn chưa được kích hoạt. Vui lòng liên hệ quản trị viên.');
            }

            $request->session()->regenerate();

            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect('/admin/dashboard');
            } elseif ($user->role === 'provider') {
                return redirect('/admin/dashboard'); // Providers go to admin dashboard for now
            }

            return redirect('/trangchu');
        }

        return back()->with('error', 'Sai email hoặc mật khẩu!');
    }

    // LOGOUT
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}