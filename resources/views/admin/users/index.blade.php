@extends('admin.layout')

@section('title', 'Quản lý người dùng')
@section('page_title', 'Quản lý người dùng')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách người dùng</h5>
            <div>
                <form method="GET" class="d-flex gap-2">
                    <select name="role" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 150px;">
                        <option value="">-- Tất cả vai trò --</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Người học</option>
                        <option value="provider" {{ request('role') == 'provider' ? 'selected' : '' }}>Provider</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>

                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: 120px;">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Bị khóa</option>
                    </select>

                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Tìm kiếm..." value="{{ request('search') }}" style="width: 200px;">
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-search"></i> Tìm
                    </button>
                </form>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Email</th>
                        <th>Tên đầy đủ</th>
                        <th>Vai trò</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td><strong>#{{ $user->id }}</strong></td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->full_name ?? 'N/A' }}</td>
                        <td>
                            <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : ($user->role === 'provider' ? 'success' : 'info') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                {{ $user->status === 'active' ? 'Hoạt động' : 'Bị khóa' }}
                            </span>
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="/admin/users/{{ $user->id }}" class="btn btn-info" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button type="button" class="btn btn-{{ $user->status === 'active' ? 'danger' : 'warning' }}" data-bs-toggle="modal" data-bs-target="#statusModal{{ $user->id }}" title="Thay đổi trạng thái">
                                    <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }}"></i>
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Status Modal -->
                    <div class="modal fade" id="statusModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Thay đổi trạng thái</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="/admin/users/{{ $user->id }}/status">
                                    @csrf
                                    <div class="modal-body">
                                        <p>Bạn muốn {{ $user->status === 'active' ? 'khóa' : 'mở khóa' }} người dùng <strong>{{ $user->username }}</strong>?</p>
                                        <input type="hidden" name="status" value="{{ $user->status === 'active' ? 'blocked' : 'active' }}">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-danger">Xác nhận</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Modal -->
                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header bg-danger bg-opacity-10">
                                    <h5 class="modal-title">
                                        <i class="fas fa-exclamation-triangle text-danger"></i> Xác nhận xóa
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="/admin/users/{{ $user->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-body">
                                        <p class="mb-3">
                                            Bạn có chắc chắn muốn xóa người dùng <strong>{{ $user->username }}</strong> không?
                                        </p>
                                        <div class="alert alert-danger small">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <strong>Cảnh báo:</strong> Hành động này không thể hoàn tác.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Xóa vĩnh viễn
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-inbox text-muted" style="font-size: 48px;"></i>
                            <p class="text-muted mt-2">Không tìm thấy người dùng nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
