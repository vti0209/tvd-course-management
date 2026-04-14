<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SystemController extends Controller
{
    /**
     * Display system settings.
     */
    public function settings()
    {
        $commission = config('system.commission_rate', 20); // Default 20%
        $settings = [
            'commission_rate' => $commission,
            'min_withdrawal' => config('system.min_withdrawal', 100000),
            'max_withdrawal' => config('system.max_withdrawal', 100000000),
            'bank_name' => config('system.bank_name', 'Vietcombank'),
            'bank_account' => config('system.bank_account', '0123456789'),
        ];

        return view('admin.system.settings', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update system settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'commission_rate' => 'required|numeric|min:0|max:100',
            'min_withdrawal' => 'required|numeric|min:0',
            'max_withdrawal' => 'required|numeric|min:0',
            'bank_name' => 'required|string',
            'bank_account' => 'required|string',
        ]);

        // TODO: Store settings in database or cache
        // For now, we'll just redirect with success message

        return redirect()->back()->with('success', 'Cài đặt hệ thống được cập nhật thành công!');
    }

    /**
     * Display banner management page.
     */
    public function banners()
    {
        // TODO: Fetch banners from database
        $banners = [];

        return view('admin.system.banners', [
            'banners' => $banners,
        ]);
    }

    /**
     * Create a new banner.
     */
    public function createBanner()
    {
        return view('admin.system.create-banner');
    }

    /**
     * Store banner.
     */
    public function storeBanner(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'link' => 'nullable|url',
            'active' => 'nullable|boolean',
        ]);

        // TODO: Handle image upload and store banner

        return redirect()->route('admin.banners')->with('success', 'Banner được tạo thành công!');
    }

    /**
     * Display announcements page.
     */
    public function announcements()
    {
        // TODO: Fetch announcements from database
        $announcements = [];

        return view('admin.system.announcements', [
            'announcements' => $announcements,
        ]);
    }

    /**
     * Create a new announcement.
     */
    public function createAnnouncement()
    {
        return view('admin.system.create-announcement');
    }

    /**
     * Store announcement.
     */
    public function storeAnnouncement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'type' => 'required|in:info,warning,success,error',
        ]);

        // TODO: Store announcement in database

        return redirect()->route('admin.announcements')->with('success', 'Thông báo được tạo thành công!');
    }
}
