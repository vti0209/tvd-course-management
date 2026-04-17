@extends('provider.layout')

@section('content')
<div class="course-form-container">
    <div class="form-header mb-4">
        <h2>Tạo khóa học mới</h2>
        <p>Nhập đầy đủ thông tin và cấu trúc bài học</p>
    </div>

    <form action="{{ route('provider.courses.store') }}" method="POST" enctype="multipart/form-data" class="course-form">
        @csrf

        {{-- Section 1: Thông tin cơ bản --}}
        <div class="form-card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Thông tin cơ bản</h3>
            </div>
            <div class="card-body p-4">
                <div class="form-group mb-3">
                    <label class="form-label">Tên khóa học <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="Nhập tên khóa học" value="{{ old('title') }}" required>
                </div>

                <div class="form-group mb-3">
                    <label class="form-label">Mô tả ngắn <span class="text-danger">*</span></label>
                    <textarea name="description" rows="3" class="form-control" placeholder="Nhập mô tả ngắn về khóa học" required>{{ old('description') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Chọn danh mục</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Giá (đ) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control" placeholder="0 = Miễn phí" value="{{ old('price') }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Thời lượng (giờ)</label>
                        <input type="number" name="duration" class="form-control" placeholder="Ví dụ: 20" value="{{ old('duration') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Cấu trúc Chương & Bài học --}}
        <div class="form-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3><i class="fas fa-layer-group"></i> Nội dung chương trình học</h3>
                <button type="button" id="add-chapter" class="btn-add-item">
                    <i class="fas fa-plus-circle"></i> Thêm chương mới
                </button>
            </div>
            <div class="card-body p-4" id="chapters-container">
                {{-- Chương sẽ render ở đây --}}
            </div>
        </div>

        {{-- Section 3: Hình ảnh đại diện --}}
        <div class="form-card">
            <div class="card-header">
                <h3><i class="fas fa-image"></i> Hình ảnh đại diện</h3>
            </div>
            <div class="card-body p-4 text-center">
                <div class="image-upload-area border rounded p-4" style="cursor: pointer; background: #f8f9fa; border: 2px dashed #dee2e6 !important;" onclick="document.getElementById('thumbnail').click()">
                    <input type="file" id="thumbnail" name="thumbnail" class="d-none" accept="image/*">
                    <div id="uploadPlaceholder">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
                        <p class="mt-2">Kéo thả hình ảnh hoặc <span>chọn từ máy tính</span></p>
                        <small class="text-muted">PNG, JPG tối đa 5MB</small>
                    </div>
                    <img id="imagePreview" src="" style="display:none; max-width: 100%; height: 200px; object-fit: cover; border-radius: 8px;">
                </div>
            </div>
        </div>

        <div class="form-actions text-end mb-5">
            <a href="{{ route('provider.courses.index') }}" class="btn btn-secondary px-4">Hủy</a>
            <button type="submit" class="btn btn-primary px-5">Tạo khóa học ngay</button>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let chapterIndex = 0;
        const container = document.getElementById('chapters-container');
        const addChapterBtn = document.getElementById('add-chapter');

        // Thêm Chương mới
        if (addChapterBtn) {
            addChapterBtn.addEventListener('click', function() {
                chapterIndex++;
                const html = `
                    <div class="chapter-item mb-4" id="chapter-${chapterIndex}" style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; background: #fcfcfc; border-left: 5px solid #06b6d4;">
                        <div class="d-flex mb-3 gap-2">
                            <input type="text" name="chapters[${chapterIndex}][title]" class="form-control fw-bold" placeholder="Tên chương (VD: Chương 1: Cơ bản)" required>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="document.getElementById('chapter-${chapterIndex}').remove()">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="lessons-container ms-4" id="lessons-of-${chapterIndex}"></div>
                        <button type="button" class="btn btn-sm btn-link text-decoration-none mt-2 add-lesson-btn" data-id="${chapterIndex}">
                            <i class="fas fa-plus"></i> Thêm bài học demo
                        </button>
                    </div>`;
                container.insertAdjacentHTML('beforeend', html);
            });
        }

        // Thêm Bài học (Sử dụng input file thay vì link video)
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
                            <label class="mb-0 me-2 text-nowrap small text-muted"><i class="fas fa-image"></i> Ảnh demo:</label>
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

        // Preview ảnh cho ảnh đại diện khóa học
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