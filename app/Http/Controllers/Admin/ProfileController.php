<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    /**
     * Display admin profile
     */
    public function show()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            abort(401, 'Unauthorized');
        }
        
        return view('admin.profile.show', compact('admin'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            abort(401, 'Unauthorized');
        }
        
        return view('admin.profile.edit', compact('admin'));
    }

    /**
     * Update admin profile
     */
    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'full_name.required' => 'Họ tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',
            'avatar.image' => 'File tải lên phải là hình ảnh.',
            'avatar.max' => 'Ảnh không được vượt quá 2MB.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        $admin->full_name = $request->full_name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it exists
            if ($admin->avatar && $admin->avatar != 'default-avatar.png') {
                $oldPath = public_path('uploads/' . $admin->avatar);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Save new avatar
            $imageName = 'admin_' . time() . '_' . $admin->id . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('uploads'), $imageName);
            $admin->avatar = $imageName;
        }

        // Update password if provided
        if ($request->filled('password')) {
            $admin->password = bcrypt($request->password);
        }

        $admin->save();

        return redirect()->route('admin.profile.show')->with('success', 'Cập nhật hồ sơ thành công!');
    }
}
