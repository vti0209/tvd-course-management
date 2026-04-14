@extends('admin.layout')

@section('title', 'Sửa danh mục')
@section('page_title', 'Chỉnh sửa danh mục')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">Chỉnh sửa danh mục: {{ $category->name }}</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/categories/{{ $category->id }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" required value="{{ old('name', $category->name) }}">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="slug" disabled value="{{ $category->slug }}">
                            <small class="text-muted">Slug được tạo tự động từ tên danh mục</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description"
                                name="description" rows="5">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật danh mục
                            </button>
                            <a href="/admin/categories" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection