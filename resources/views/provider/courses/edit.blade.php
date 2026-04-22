@extends('provider.layout')

@section('content')
<div class="course-form-container">
    <div class="form-header mb-4">
        <h2>Chỉnh sửa khóa học: {{ $course->title }}</h2>
        <p>Cập nhật thông tin và cấu trúc chương trình học</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('provider.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data" class="course-form">
        @csrf
        @method('PUT')

        {{-- Section 1: Thông tin cơ bản --}}
        <div class="form-card mb-4" style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-header p-3 border-bottom">
                <h3 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin cơ bản</h3>
            </div>
            <div class="card-body p-4">
                <div class="form-group mb-3">
                    <label class="form-label fw-bold">Tên khóa học <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $course->title) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label fw-bold">Mô tả <span class="text-danger">*</span></label>
                    <textarea name="description" rows="4" class="form-control" required>{{ old('description', $course->description) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Chọn danh mục</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" {{ $course->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Giá (đ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" value="{{ old('price', $course->price) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Thời lượng (giờ)</label>
                        <input type="number" name="duration" class="form-control" value="{{ old('duration', $course->duration) }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Cấu trúc Chương & Bài học --}}
        <div class="form-card mb-4" style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-header d-flex justify-content-between align-items-center p-3 border-bottom">
                <h3 class="mb-0"><i class="fas fa-layer-group"></i> Nội dung chương trình học</h3>
                <button type="button" id="add-chapter" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus-circle"></i> Thêm chương mới
                </button>
            </div>
            <div class="card-body p-4" id="chapters-container">
                @foreach($course->chapters as $cIdx => $chapter)
                    <div class="chapter-item mb-4" id="chapter-{{ $cIdx }}" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; background: #fcfcfc; border-left: 5px solid #06b6d4;">
                        {{-- Hidden ID để biết là update chương cũ --}}
                        <input type="hidden" name="chapters[{{ $cIdx }}][id]" value="{{ $chapter->id }}">
                        
                        <div class="d-flex mb-3 gap-2">
                            <input type="text" name="chapters[{{ $cIdx }}][title]" class="form-control fw-bold" value="{{ $chapter->title }}" placeholder="Tên chương" required>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.chapter-item').remove()">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <div class="lessons-container ms-4" id="lessons-of-{{ $cIdx }}">
                            @foreach($chapter->lessons as $lIdx => $lesson)
                                <div class="lesson-item d-flex gap-2 mb-2 align-items-center bg-white p-2 border rounded shadow-sm">
                                    {{-- Hidden ID để biết là update bài học cũ --}}
                                    <input type="hidden" name="chapters[{{ $cIdx }}][lessons][{{ $lIdx }}][id]" value="{{ $lesson->id }}">
                                    
                                    <span class="text-muted small">{{ $lIdx + 1 }}.</span>
                                    
                                    <input type="text" name="chapters[{{ $cIdx }}][lessons][{{ $lIdx }}][title]" 
                                           class="form-control form-control-sm" value="{{ $lesson->title }}" required style="flex: 2;">
                                    
                                    <div class="d-flex align-items-center" style="flex: 3;">
                                        @if($lesson->lesson_image)
                                            <img src="{{ asset($lesson->lesson_image) }}" width="40" height="30" class="me-2 rounded object-fit-cover">
                                        @endif
                                        <input type="file" name="chapters[{{ $cIdx }}][lessons][{{ $lIdx }}][lesson_image]" 
                                               class="form-control form-control-sm" accept="image/*">
                                    </div>

                                    <button type="button" class="text-danger border-0 bg-transparent" onclick="this.parentElement.remove()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-sm btn-link text-decoration-none mt-2 add-lesson-btn" data-id="{{ $cIdx }}">
                            <i class="fas fa-plus"></i> Thêm bài học mới
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Section 3: Hình ảnh đại diện --}}
        <div class="form-card mb-4" style="background: #fff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
            <div class="card-header p-3 border-bottom">
                <h3 class="mb-0"><i class="fas fa-image"></i> Hình ảnh đại diện</h3>
            </div>
            <div class="card-body p-4 text-center">
                <div class="image-upload-area border rounded p-4" style="cursor: pointer; background: #f8f9fa; border: 2px dashed #dee2e6 !important;" onclick="document.getElementById('thumbnail').click()">
                    <input type="file" id="thumbnail" name="thumbnail" class="d-none" accept="image/*">
                    <div id="uploadPlaceholder" style="{{ $course->thumbnail ? 'display:none' : '' }}">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
                        <p class="mt-2">Chọn ảnh mới để thay đổi</p>
                    </div>
                    <img id="imagePreview" src="{{ asset($course->thumbnail) }}" style="{{ $course->thumbnail ? 'display:block' : 'display:none' }}; max-width: 100%; height: 250px; margin: 0 auto; object-fit: cover; border-radius: 8px;">
                </div>
            </div>
        </div>

        <div class="form-actions text-end mb-5">
            <a href="{{ route('provider.courses.index') }}" class="btn btn-secondary px-4">Quay lại</a>
            <button type="submit" class="btn btn-success px-5">Cập nhật khóa học</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Khởi tạo index dựa trên số lượng chương đang có để tránh trùng ID khi thêm mới
        let chapterIndex = {{ $course->chapters->count() > 0 ? $course->chapters->count() : 0 }};
        const container = document.getElementById('chapters-container');
        const addChapterBtn = document.getElementById('add-chapter');

        // Thêm Chương mới (Logic giống hệt file Create)
        if (addChapterBtn) {
            addChapterBtn.addEventListener('click', function() {
                const html = `
                    <div class="chapter-item mb-4" id="chapter-${chapterIndex}" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; background: #fcfcfc; border-left: 5px solid #06b6d4;">
                        <div class="d-flex mb-3 gap-2">
                            <input type="text" name="chapters[${chapterIndex}][title]" class="form-control fw-bold" placeholder="Tên chương mới" required>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('.chapter-item').remove()">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="lessons-container ms-4" id="lessons-of-${chapterIndex}"></div>
                        <button type="button" class="btn btn-sm btn-link text-decoration-none mt-2 add-lesson-btn" data-id="${chapterIndex}">
                            <i class="fas fa-plus"></i> Thêm bài học mới
                        </button>
                    </div>`;
                container.insertAdjacentHTML('beforeend', html);
                chapterIndex++;
            });
        }

        // Thêm Bài học mới
        document.addEventListener('click', function(e) {
            if (e.target && (e.target.classList.contains('add-lesson-btn') || e.target.parentElement.classList.contains('add-lesson-btn'))) {
                const btn = e.target.classList.contains('add-lesson-btn') ? e.target : e.target.parentElement;
                const cIdx = btn.getAttribute('data-id');
                const lessonContainer = document.getElementById(`lessons-of-${cIdx}`);
                const lIdx = lessonContainer.children.length;
                
                const lessonHtml = `
                    <div class="lesson-item d-flex gap-2 mb-2 align-items-center bg-white p-2 border rounded shadow-sm">
                        <span class="text-muted small">${lIdx + 1}.</span>
                        <input type="text" name="chapters[${cIdx}][lessons][${lIdx}][title]" 
                               class="form-control form-control-sm" placeholder="Tên bài học" required style="flex: 2;">
                        <div class="d-flex align-items-center" style="flex: 3;">
                            <label class="mb-0 me-2 text-nowrap small text-muted"><i class="fas fa-image"></i> Ảnh:</label>
                            <input type="file" name="chapters[${cIdx}][lessons][${lIdx}][lesson_image]" 
                                   class="form-control form-control-sm" accept="image/*">
                        </div>
                        <button type="button" class="text-danger border-0 bg-transparent" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>`;
                lessonContainer.insertAdjacentHTML('beforeend', lessonHtml);
            }
        });

        // Preview ảnh đại diện
        const thumbnailInput = document.getElementById('thumbnail');
        if (thumbnailInput) {
            thumbnailInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const preview = document.getElementById('imagePreview');
                        const placeholder = document.getElementById('uploadPlaceholder');
                        preview.src = event.target.result;
                        preview.style.display = 'block';
                        placeholder.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
@endsection