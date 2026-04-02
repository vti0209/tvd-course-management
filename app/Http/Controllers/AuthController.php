<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Hiện trang đăng nhập
    public function showLogin() {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request) {
        $credentials = $request->only('username', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->route('products.index');
        }
        return back()->with('error', 'Sai tài khoản hoặc mật khẩu!');
    }

    // Hiện trang đăng ký
    public function showRegister() {
        return view('auth.register');
    }

    // Xử lý đăng ký
    public function register(Request $request) {
        User::create([
            'username' => $request->username,
            'fullname' => $request->fullname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user' // Mặc định là user
        ]);
        return redirect()->route('login')->with('success', 'Đăng ký thành công!');
    }

    // Đăng xuất
    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
