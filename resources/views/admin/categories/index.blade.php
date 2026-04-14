@extends('admin.layout')

@section('title', 'Quản lý danh mục')
@section('page_title', 'Quản lý danh mục')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách danh mục</h5>
            <a href="/admin/categories/create" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Thêm danh mục mới
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Mô tả</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td><strong>#{{ $category->id }}</strong></td>
                        <td>{{ $category->name }}</td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>{{ Str::limit($category->description, 50) ?? 'N/A' }}</td>
                        <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="/admin/categories/{{ $category->id }}/edit" class="btn btn-sm btn-warning" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Xóa danh mục</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Bạn chắc chắn muốn xóa danh mục <strong>{{ $category->name }}</strong>?</p>
                                    <p class="text-danger small"><i class="fas fa-exclamation-triangle"></i> Hành động này không thể hoàn tác!</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <form method="POST" action="/admin/categories/{{ $category->id }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-inbox text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-2">Không có danh mục nào</p>
                            <a href="/admin/categories/create" class="btn btn-primary btn-sm">Tạo danh mục đầu tiên</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection
