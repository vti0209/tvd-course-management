@extends('admin.layout')

@section('title', 'Chỉnh sửa khóa học')
@section('page_title', 'Chỉnh sửa khóa học')

@section('content')
<div class="course-form-container">
    <div class="form-header">
        <h2>Chỉnh sửa khóa học</h2>
        <p>Cập nhật thông tin chi tiết cho khóa học</p>
    </div>

    <form action="/admin/courses/{{ $course->id }}" method="POST" class="course-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-card">
            <div class="card-header">
                <h3><i class="fas fa-info-circle"></i> Thông tin cơ bản</h3>
            </div>
            <div class="card-body">
                <!-- Tên khóa học -->
                <div class="form-group">
                    <label for="title" class="form-label">Tên khóa học <span class="required">*</span></label>
                    <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                        placeholder="Nhập tên khóa học" value="{{ old('title', $course->title) }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Mô tả ngắn -->
                <div class="form-group">
                    <label for="description" class="form-label">Mô tả ngắn <span class="required">*</span></label>
                    <textarea id="description" name="description" rows="3"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Nhập mô tả ngắn về khóa học"
                        required>{{ old('description', $course->description) }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Danh mục -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="category_id" class="form-label">Danh mục <span class="required">*</span></label>
                        <select id="category_id" name="category_id"
                            class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Chọn danh mục</option>
                            @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Giá -->
                    <div class="form-group">
                        <label for="price" class="form-label">Giá (đ) <span class="required">*</span></label>
                        <input type="number" id="price" name="price"
                            class="form-control @error('price') is-invalid @enderror" placeholder="0"
                            value="{{ old('price', $course->price) }}" required>
                        @error('price')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="card-header">
                <h3><i class="fas fa-clock"></i> Thông tin thời gian</h3>
            </div>
            <div class="card-body">
                <!-- Thời lượng -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="duration" class="form-label">Thời lượng (giờ)</label>
                        <input type="number" id="duration" name="duration"
                            class="form-control @error('duration') is-invalid @enderror" placeholder="0"
                            value="{{ old('duration', $course->duration) }}">
                        @error('duration')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Cấp độ -->
                    <div class="form-group">
                        <label for="level" class="form-label">Cấp độ</label>
                        <select id="level" name="level" class="form-select">
                            <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>
                                Cơ bản</option>
                            <option value="intermediate"
                                {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Trung bình
                            </option>
                            <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>
                                Nâng cao</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="card-header">
                <h3><i class="fas fa-image"></i> Hình ảnh</h3>
            </div>
            <div class="card-body">
                <!-- Hình ảnh -->
                <div class="form-group">
                    <label for="image" class="form-label">Hình ảnh khóa học</label>
                    <div class="image-upload">
                        <input type="file" id="image" name="image" class="image-input" accept="image/*">
                        <div class="upload-placeholder" id="uploadPlaceholder">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Kéo thả hình ảnh hoặc <span>chọn từ máy tính</span></p>
                            <small>PNG, JPG tối đa 5MB</small>
                        </div>
                        @if ($course->image)
                        <img id="imagePreview" src="{{ asset($course->image) }}" class="image-preview">
                        @elseif ($course->thumbnail)
                        <img id="imagePreview" src="{{ asset('images/' . $course->thumbnail) }}" class="image-preview">
                        @else
                        <img id="imagePreview" class="image-preview" style="display: none;">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="card-header">
                <h3><i class="fas fa-toggle-on"></i> Trạng thái</h3>
            </div>
            <div class="card-body">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="active" name="active" value="1"
                        {{ old('active', $course->status) == 'active' ? 'checked' : ($course->status === 'active' ? 'checked' : '') }}>
                    <label class="form-check-label" for="active">
                        Kích hoạt khóa học
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="form-actions">
            <a href="/admin/courses" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Cập nhật
            </button>
        </div>
    </form>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-course-form.css') }}">
@endpush

@push('scripts')
<script>
const imageUpload = document.querySelector('.image-upload');
const imageInput = document.getElementById('image');
const imagePreview = document.getElementById('imagePreview');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');

imageUpload.addEventListener('click', () => imageInput.click());

imageInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
            imagePreview.src = event.target.result;
            imagePreview.style.display = 'block';
            uploadPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});

imageUpload.addEventListener('dragover', (e) => {
    e.preventDefault();
    imageUpload.style.borderColor = '#06b6d4';
    imageUpload.style.background = '#f0f9fc';
});

imageUpload.addEventListener('dragleave', () => {
    imageUpload.style.borderColor = '#e2e8f0';
    imageUpload.style.background = '#f9fbfd';
});

imageUpload.addEventListener('drop', (e) => {
    e.preventDefault();
    imageUpload.style.borderColor = '#e2e8f0';
    imageUpload.style.background = '#f9fbfd';

    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        imageInput.files = e.dataTransfer.files;
        const reader = new FileReader();
        reader.onload = (event) => {
            imagePreview.src = event.target.result;
            imagePreview.style.display = 'block';
            uploadPlaceholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
@endsection