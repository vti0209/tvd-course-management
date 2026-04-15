<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
  public function index()
{
    $providerId = auth()->id();

    // Sửa 'user_id' thành 'provider_id'
    $courses = Course::where('provider_id', $providerId) 
        ->with('category')
        ->withCount('enrollments') // Giữ nguyên để hiện số học viên
        ->paginate(10);
    
    return view('provider.courses.index', [
        'courses' => $courses,
        'categories' => Category::all(),
    ]);
}

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('provider.courses.create', [
            'categories' => Category::all(),
        ]);
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request) {
    // ... validate dữ liệu ...

    $course = new Course();
    $course->fill($request->all());
    $course->provider_id = auth()->id();
    
    // Chốt trạng thái mặc định là chờ duyệt
    $course->status = 'pending'; 
    
    $course->save();

    return redirect()->route('provider.courses.index')
                     ->with('success', 'Khóa học đã được gửi, vui lòng chờ Admin phê duyệt!');
}
    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        return view('provider.courses.edit', [
            'course' => $course,
            'categories' => Category::all(),
        ]);
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'duration' => 'nullable|integer|min:0',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if exists
            if ($course->thumbnail && file_exists(public_path($course->thumbnail))) {
                unlink(public_path($course->thumbnail));
            }
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/courses'), $filename);
            $validated['thumbnail'] = 'images/courses/' . $filename;
        }

        $course->update($validated);

        return redirect('/provider/courses')
            ->with('success', 'Khóa học được cập nhật thành công!');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        // Delete thumbnail if exists
        if ($course->thumbnail && file_exists(public_path($course->thumbnail))) {
            unlink(public_path($course->thumbnail));
        }

        $course->delete();

        return redirect('/provider/courses')
            ->with('success', 'Khóa học được xóa thành công!');
    }
}