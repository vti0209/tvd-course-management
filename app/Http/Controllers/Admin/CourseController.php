<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class CourseController extends Controller
{
    /**
     * Display a listing of the courses.
     */
  public function index(Request $request) // Thêm Request $request vào đây
{
    $providerId = auth()->id();
    
    // Lấy giá trị từ form lọc
    $search = $request->input('search');
    $categoryId = $request->input('category_id');

    $courses = Course::where('provider_id', $providerId) // Dùng provider_id như đã fix
        ->with('category')
        ->withCount('enrollments') // Đếm số học viên để hiện thay cho số 0
        
        // Logic tìm kiếm theo tên khóa học
        ->when($search, function ($query, $search) {
            return $query->where('title', 'LIKE', "%{$search}%");
        })
        
        // Logic lọc theo danh mục
        ->when($categoryId, function ($query, $categoryId) {
            return $query->where('category_id', $categoryId);
        })
        
        ->latest() // Hiện khóa học mới nhất lên đầu
        ->paginate(10)
        ->withQueryString(); // QUAN TRỌNG: Giữ lại thanh tìm kiếm khi bấm chuyển trang

    return view('provider.courses.index', [
        'courses' => $courses,
        'categories' => Category::all(), // Truyền danh sách để hiện ở ô Select
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
public function store(Request $request)
{
    // Sử dụng DB Transaction để đảm bảo nếu lỗi ở bất kỳ bước nào thì dữ liệu sẽ không bị lưu dở dang
    DB::transaction(function () use ($request) {
        
        // --- 1. XỬ LÝ LƯU THUMBNAIL KHÓA HỌC ---
        $thumbnailPath = 'images/courses/default.jpg'; // Ảnh mặc định nếu không upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Lưu trực tiếp vào thư mục public/images/courses như cấu trúc bạn chụp
            $file->move(public_path('images/courses'), $filename);
            $thumbnailPath = 'images/courses/' . $filename;
        }

        // --- 2. TẠO KHÓA HỌC ---
        // Sử dụng Auth::user()->courses()->create sẽ tự động gán provider_id cho bạn
        $course = Auth::user()->createdCourses()->create([
        'category_id' => $request->category_id,
        'title' => $request->title,
        'slug' => Str::slug($request->title) . '-' . time(),
        'description' => $request->description ?? '',
        'price' => $request->price,
        'duration'    => $request->duration,
        'status' => 'pending',
        'thumbnail' => $thumbnailPath,
    ]);

        // --- 3. LƯU CHƯƠNG VÀ BÀI HỌC (DEMO ẢNH) ---
        if ($request->has('chapters')) {
            foreach ($request->chapters as $cIndex => $cData) {
                // Lưu chương
                $chapter = $course->chapters()->create([
                    'title'      => $cData['title'],
                    'sort_order' => $cIndex,
                ]);

                // Lưu bài học trong chương
                if (isset($cData['lessons'])) {
                    foreach ($cData['lessons'] as $lIndex => $lData) {
                        $lessonImagePath = 'images/courses/default-lesson.jpg';

                        // Xử lý lưu FILE ẢNH DEMO cho từng bài học (nếu có)
                        // Lưu ý: Key 'lesson_image' phải khớp với tên trong file create.blade.php
                        if ($request->hasFile("chapters.$cIndex.lessons.$lIndex.lesson_image")) {
                            $lFile = $request->file("chapters.$cIndex.lessons.$lIndex.lesson_image");
                            $lFilename = time() . '_lesson_' . $lFile->getClientOriginalName();
                            $lFile->move(public_path('images/courses'), $lFilename);
                            $lessonImagePath = 'images/courses/' . $lFilename;
                        }

                        // Lưu vào bảng lessons
                        $chapter->lessons()->create([
                            'title'        => $lData['title'],
                            'content_url'  => $lessonImagePath, // Lưu đường dẫn ảnh vào cột content_url
                            'content_type' => 'video',          // Để 'video' để tránh lỗi ENUM database của bạn
                            'sort_order'   => $lIndex,
                        ]);
                    }
                }
            }
        }
    });

    return redirect()->route('provider.courses.index')->with('success', 'Khóa học và nội dung demo đã được lưu thành công!');
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