@extends('layouts.master')

@section('content')
<div
    style="background: linear-gradient(135deg, #0f172a, #06b6d4); padding: 4rem 0; text-align: center; color: white; margin-bottom: 3rem;">
    <h1 style="font-size: 3rem; margin-bottom: 0.5rem;">Chào mừng đến Gemini Academy</h1>
    <p style="font-size: 1.3rem; opacity: 0.9;">Học tập từ những khóa học tốt nhất</p>
</div>

<div style="padding: 2rem; max-width: 1200px; margin: 0 auto;">
    <!-- Khóa học mới -->
    <div style="margin-bottom: 3rem;">
        <h2 style="font-size: 2rem; color: #0f172a; margin-bottom: 2rem;">
            <i class="fas fa-star" style="color: #06b6d4;"></i> Khóa học mới nhất
        </h2>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem;">
            @forelse(\App\Models\Course::where('status', 'active')->latest()->take(8)->get() as $course)
            <div
                style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease; display: flex; flex-direction: column;">
                <div style="position: relative; height: 180px; background: linear-gradient(135deg, #06b6d4, #0891b2);">
                    @if($course->thumbnail)
                    <img src="{{ asset('images/' . $course->thumbnail) }}" alt="{{ $course->title }}"
                        style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                    <div
                        style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                        <i class="fas fa-book"></i>
                    </div>
                    @endif
                    <span
                        style="position: absolute; top: 10px; right: 10px; background: #06b6d4; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Mới</span>
                </div>

                <div style="padding: 1.5rem; flex: 1; display: flex; flex-direction: column;">
                    <h3
                        style="font-size: 1.1rem; font-weight: 600; color: #0f172a; margin-bottom: 0.5rem; line-height: 1.4;">
                        {{ $course->title }}</h3>
                    <p style="font-size: 0.9rem; color: #06b6d4; font-weight: 500; margin-bottom: 0.75rem;">
                        {{ $course->category->name ?? 'N/A' }}</p>
                    <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 1rem; flex: 1;">
                        {{ Str::limit($course->description, 80) }}</p>

                    <div
                        style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                        <span
                            style="font-weight: 700; color: #059669; font-size: 1.1rem;">{{ number_format($course->price, 0, ',', '.') }}đ</span>
                        <a href="/courses/{{ $course->id }}"
                            style="background: linear-gradient(135deg, #06b6d4, #0891b2); color: white; padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; transition: all 0.3s ease;">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div style="text-align: center; padding: 2rem; color: #94a3b8; grid-column: 1 / -1;">
                <i class="fas fa-inbox"
                    style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5; display: block;"></i>
                <p>Chưa có khóa học nào</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
