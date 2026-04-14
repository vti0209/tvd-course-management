@extends('admin.layout')

@section('title', 'Quản lý rút tiền')
@section('page_title', 'Quản lý yêu cầu rút tiền')

@section('content')
<div class="container-fluid">
    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab">
                <i class="fas fa-hourglass-half"></i> Chờ phê duyệt
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab">
                <i class="fas fa-check-circle"></i> Đã phê duyệt
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Pending Withdrawals -->
        <div class="tab-pane fade show active" id="pending" role="tabpanel">
            <div class="card">
                <div class="card-header bg-warning text-dark py-3">
                    <h5 class="mb-0">Yêu cầu rút tiền chờ phê duyệt</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nhà cung cấp</th>
                                <th>Số tiền</th>
                                <th>Tài khoản ngân hàng</th>
                                <th>Ngân hàng</th>
                                <th>Ngày yêu cầu</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox text-muted" style="font-size: 48px;"></i>
                                    <p class="text-muted mt-2">Không có yêu cầu rút tiền nào</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Approved Withdrawals -->
        <div class="tab-pane fade" id="approved" role="tabpanel">
            <div class="card">
                <div class="card-header bg-success text-white py-3">
                    <h5 class="mb-0">Yêu cầu rút tiền đã phê duyệt</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nhà cung cấp</th>
                                <th>Số tiền</th>
                                <th>Tài khoản ngân hàng</th>
                                <th>Ngân hàng</th>
                                <th>Ngày phê duyệt</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p class="text-muted">Chưa có yêu cầu rút tiền được phê duyệt</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
