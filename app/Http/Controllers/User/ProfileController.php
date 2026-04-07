<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    // Hiển thị thông tin cá nhân
    public function index()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    // Hiển thị form chỉnh sửa
    public function edit()
    {
        $user = Auth::user();
        return view('user.editprofile', compact('user'));
    }

    // Xử lý cập nhật
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ], [
            'name.required' => 'Họ tên không được để trống.',
            'avatar.image' => 'File tải lên phải là hình ảnh.',
            'avatar.max' => 'Ảnh không được vượt quá 2MB.'
        ]);

        $user->name = $request->name;

        // Xử lý Upload ảnh đại diện
        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu không phải ảnh mặc định
            if ($user->avatar && $user->avatar != 'default-avatar.png') {
                $oldPath = public_path('uploads/' . $user->avatar);
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }

            // Lưu ảnh mới
            $imageName = time() . '_' . $user->id . '.' . $request->avatar->extension();
            $request->avatar->move(public_path('uploads'), $imageName);
            $user->avatar = $imageName;
        }

        $user->save();

        return redirect()->route('user.profile')->with('success', 'Cập nhật thông tin thành công!');
    }
}
