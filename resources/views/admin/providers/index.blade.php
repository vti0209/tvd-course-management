@extends('admin.layout')

@section('title', 'Phê duyệt Provider')
@section('page_title', 'Phê duyệt Provider')

@section('content')
<div class="container-fluid">
    <!-- Pending Providers -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark py-3">
            <h5 class="mb-0"><i class="fas fa-hourglass-half"></i> Yêu cầu chờ duyệt ({{ $pendingProviders->total() }})
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Email</th>
                        <th>Tên đầy đủ</th>
                        <th>Hồ sơ</th>
                        <th>Ngày đăng ký</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingProviders as $provider)
                    <tr>
                        <td><strong>#{{ $provider->user->id }}</strong></td>
                        <td>{{ $provider->user->username }}</td>
                        <td>{{ $provider->user->email }}</td>
                        <td>{{ $provider->user->full_name ?? 'N/A' }}</td>
                        <td>
                            @if($provider->cv_file)
                            <a href="{{ asset('storage/' . $provider->cv_file) }}" class="btn btn-sm btn-info"
                                target="_blank">
                                <i class="fas fa-file-download"></i> Tải xuống
                            </a>
                            @else
                            <span class="badge bg-secondary">Không có</span>
                            @endif
                        </td>
                        <td>{{ $provider->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="/admin/providers/{{ $provider->id }}" class="btn btn-sm btn-info"
                                title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                data-bs-target="#approveModal{{ $provider->id }}" title="Phê duyệt">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectModal{{ $provider->id }}" title="Từ chối">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Approve Modal -->
                    <div class="modal fade" id="approveModal{{ $provider->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Phê duyệt yêu cầu Provider</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="/admin/providers/{{ $provider->id }}/approve">
                                    @csrf
                                    <div class="modal-body">
                                        <p>Phê duyệt yêu cầu trở thành Provider của
                                            <strong>{{ $provider->user->username }}</strong>?
                                        </p>
                                        <p class="text-muted small">Email xác nhận sẽ được gửi tới
                                            {{ $provider->user->email }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-success">Phê duyệt</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Reject Modal -->
                    <div class="modal fade" id="rejectModal{{ $provider->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Từ chối yêu cầu Provider</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="/admin/providers/{{ $provider->id }}/reject">
                                    @csrf
                                    <div class="modal-body">
                                        <p>Bạn chắc chắn muốn từ chối yêu cầu của
                                            <strong>{{ $provider->user->username }}</strong>?
                                        </p>
                                        <div class="mb-3">
                                            <label for="reason{{ $provider->id }}" class="form-label">Lý do từ
                                                chối:</label>
                                            <textarea class="form-control" id="reason{{ $provider->id }}" name="reason"
                                                rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-danger">Từ chối</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                            <p class="text-muted mt-2">Không có yêu cầu chờ duyệt</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pendingProviders->total() > 0)
        <div class="card-footer bg-white">
            {{ $pendingProviders->links() }}
        </div>
        @endif
    </div>

    <!-- Pending User-Based Providers -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark py-3">
            <h5 class="mb-0"><i class="fas fa-hourglass-half"></i> Yêu cầu Provider mới ({{ $pendingUserProviders->total() }})
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Email</th>
                        <th>Tên đầy đủ</th>
                        <th>Tài liệu xác minh</th>
                        <th>Ngày đăng ký</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingUserProviders as $user)
                    <tr>
                        <td><strong>#{{ $user->id }}</strong></td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->full_name ?? 'N/A' }}</td>
                        <td>
                            @if($user->provider_info)
                            <a href="{{ asset($user->provider_info) }}" class="btn btn-sm btn-info"
                                target="_blank">
                                <i class="fas fa-file-download"></i> Tải xuống
                            </a>
                            @else
                            <span class="badge bg-secondary">Không có</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                data-bs-target="#approveUserModal{{ $user->id }}" title="Phê duyệt">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectUserModal{{ $user->id }}" title="Từ chối">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Approve User Modal -->
                    <div class="modal fade" id="approveUserModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Phê duyệt yêu cầu Provider</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="/admin/providers/user/{{ $user->id }}/approve">
                                    @csrf
                                    <div class="modal-body">
                                        <p>Phê duyệt yêu cầu trở thành Provider của
                                            <strong>{{ $user->username }}</strong>?
                                        </p>
                                        <p class="text-muted small">Email xác nhận sẽ được gửi tới
                                            {{ $user->email }}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-success">Phê duyệt</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Reject User Modal -->
                    <div class="modal fade" id="rejectUserModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Từ chối yêu cầu Provider</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="/admin/providers/user/{{ $user->id }}/reject">
                                    @csrf
                                    <div class="modal-body">
                                        <p>Bạn chắc chắn muốn từ chối yêu cầu của
                                            <strong>{{ $user->username }}</strong>?
                                        </p>
                                        <div class="mb-3">
                                            <label for="reason{{ $user->id }}" class="form-label">Lý do từ
                                                chối:</label>
                                            <textarea class="form-control" id="reason{{ $user->id }}" name="reason"
                                                rows="3" required></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Hủy</button>
                                        <button type="submit" class="btn btn-danger">Từ chối</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 48px;"></i>
                            <p class="text-muted mt-2">Không có yêu cầu chờ duyệt</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pendingUserProviders->total() > 0)
        <div class="card-footer bg-white">
            {{ $pendingUserProviders->links() }}
        </div>
        @endif
    </div>

    <!-- Approved Providers -->
    <div class="card">
        <div class="card-header bg-success text-white py-3">
            <h5 class="mb-0"><i class="fas fa-check-circle"></i> Provider được phê duyệt
                ({{ $approvedProviders->total() }})</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Email</th>
                        <th>Tên đầy đủ</th>
                        <th>Tài khoản ngân hàng</th>
                        <th>Ngày phê duyệt</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvedProviders as $provider)
                    <tr>
                        <td><strong>#{{ $provider->user->id }}</strong></td>
                        <td>{{ $provider->user->username }}</td>
                        <td>{{ $provider->user->email }}</td>
                        <td>{{ $provider->user->full_name ?? 'N/A' }}</td>
                        <td>{{ $provider->bank_account ?? 'Chưa cập nhật' }}</td>
                        <td>{{ $provider->approved_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="/admin/providers/{{ $provider->id }}" class="btn btn-sm btn-info"
                                title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <p class="text-muted">Chưa có Provider được phê duyệt</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($approvedProviders->total() > 0)
        <div class="card-footer bg-white">
            {{ $approvedProviders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection