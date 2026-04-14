<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
    public function index()
    {
        $courses = Course::with('category')
            ->paginate(10);
        
        return view('admin.courses.index', [
            'courses' => $courses,
            'categories' => Category::all(),
        ]);
    }

    /**
     * Show the form for creating a new course.
     */
    public function create()
    {
        return view('admin.courses.create', [
            'categories' => Category::all(),
        ]);
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'level' => 'nullable|in:beginner,intermediate,advanced',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $validated['status'] = $request->has('active') ? 'active' : 'pending';

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/courses'), $filename);
            $validated['image'] = 'images/courses/' . $filename;
        }

        $validated['active'] = $request->has('active');

        Course::create($validated);

        return redirect('/admin/courses')
            ->with('success', 'Khóa học được tạo thành công!');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course)
    {
        return view('admin.courses.edit', [
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
            'duration' => 'nullable|numeric|min:0',
            'level' => 'nullable|in:beginner,intermediate,advanced',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'active' => 'nullable|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($course->image && file_exists(public_path($course->image))) {
                unlink(public_path($course->image));
            }
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/courses'), $filename);
            $imagePath = 'images/courses/' . $filename;
            $validated['image'] = $imagePath;
        }

        $validated['active'] = $request->has('active');

        $course->update($validated);

        return redirect('/admin/courses')
            ->with('success', 'Khóa học được cập nhật thành công!');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course)
    {
        // Delete image if exists
        if ($course->image && file_exists(public_path($course->image))) {
            unlink(public_path($course->image));
        }

        $course->delete();

        return redirect('/admin/courses')
            ->with('success', 'Khóa học được xóa thành công!');
    }
}