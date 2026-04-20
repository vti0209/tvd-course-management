@extends('provider.layout')
@section('content')
<div class="container">
    <h2>Chỉnh sửa khóa học: {{ $course->title }}</h2>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <form action="{{ route('provider.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT') {{-- Bắt buộc phải có @method('PUT') khi cập nhật --}}

        <div class="form-group mb-3">
            <label>Tiêu đề khóa học</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $course->title) }}" required>
        </div>
                <div class="form-group mb-3">
            <label for="category_id">Thể loại / Danh mục</label>
            <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                <option value="">-- Chọn thể loại --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" 
                        {{ (old('category_id') ?? $course->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group mb-3">
            <label>Thời lượng (giờ)</label>
            <input type="number" name="duration" class="form-control" value="{{ old('duration', $course->duration) }}">
        </div>

        <div class="form-group mb-3">
            <label>Giá tiền</label>
            <input type="number" name="price" class="form-control" value="{{ old('price', $course->price) }}">
        </div>

        <div class="form-group mb-3">
            <label>Mô tả</label>
            <textarea name="description" class="form-control">{{ old('description', $course->description) }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label>Ảnh đại diện (Thumbnail)</label>
            <br>
            <img src="{{ asset($course->thumbnail) }}" width="150" class="mb-2">
            <input type="file" name="thumbnail" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật khóa học</button>
        <a href="{{ route('provider.courses.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection