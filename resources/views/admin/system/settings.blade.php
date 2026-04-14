@extends('admin.layout')

@section('title', 'Cài đặt hệ thống')
@section('page_title', 'Cài đặt hệ thống')

@section('content')
<div class="container-fluid">
    <!-- Tabs -->
    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                <i class="fas fa-cog"></i> Cài đặt chung
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="commission-tab" data-bs-toggle="tab" data-bs-target="#commission" type="button" role="tab">
                <i class="fas fa-percent"></i> Hoa hồng & Rút tiền
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="banners-tab" data-bs-toggle="tab" data-bs-target="#banners" type="button" role="tab">
                <i class="fas fa-images"></i> Banner
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="announcements-tab" data-bs-toggle="tab" data-bs-target="#announcements" type="button" role="tab">
                <i class="fas fa-bell"></i> Thông báo
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- Settings Tab -->
        <div class="tab-pane fade show active" id="settings" role="tabpanel">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-cog"></i> Cài đặt chung hệ thống</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/admin/system/settings">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="commission_rate" class="form-label">Phần trăm hoa hồng (%)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="commission_rate" name="commission_rate" value="{{ $settings['commission_rate'] }}" min="0" max="100" step="0.01">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="min_withdrawal" class="form-label">Số tiền rút tối thiểu (VND)</label>
                                <input type="number" class="form-control" id="min_withdrawal" name="min_withdrawal" value="{{ $settings['min_withdrawal'] }}" min="0">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="max_withdrawal" class="form-label">Số tiền rút tối đa (VND)</label>
                                <input type="number" class="form-control" id="max_withdrawal" name="max_withdrawal" value="{{ $settings['max_withdrawal'] }}" min="0">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bank_name" class="form-label">Tên ngân hàng</label>
                                <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ $settings['bank_name'] }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="bank_account" class="form-label">Số tài khoản ngân hàng</label>
                                <input type="text" class="form-control" id="bank_account" name="bank_account" value="{{ $settings['bank_account'] }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Lưu cài đặt
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Commission Tab -->
        <div class="tab-pane fade" id="commission" role="tabpanel">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white py-3">
                            <h5 class="mb-0"><i class="fas fa-percent"></i> Hoa hồng</h5>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <p><strong>Hoa hồng hiện tại:</strong> {{ $settings['commission_rate'] }}%</p>
                                <p class="mb-0 small">Mỗi khi nhà cung cấp bán 1 khóa học với giá 100.000đ, hệ thống sẽ giữ {{ $settings['commission_rate'] }}% = {{ ($settings['commission_rate'] / 100) * 100000 }}đ</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="mb-0"><i class="fas fa-money-bill-wave"></i> Rút tiền</h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Tối thiểu:</strong> {{ number_format($settings['min_withdrawal'], 0, ',', '.') }}đ</p>
                            <p><strong>Tối đa:</strong> {{ number_format($settings['max_withdrawal'], 0, ',', '.') }}đ</p>
                            <p class="mb-0 small text-muted"><i class="fas fa-info-circle"></i> Nhà cung cấp chỉ có thể rút tiền trong khoảng này</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banners Tab -->
        <div class="tab-pane fade" id="banners" role="tabpanel">
            <div class="card">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Quản lý Banner</h5>
                    <a href="/admin/system/banners/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Thêm Banner mới
                    </a>
                </div>
                <div class="card-body">
                    <p class="text-muted"><i class="fas fa-info-circle"></i> Chức năng này sẽ được triển khai sớm</p>
                </div>
            </div>
        </div>

        <!-- Announcements Tab -->
        <div class="tab-pane fade" id="announcements" role="tabpanel">
            <div class="card">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Quản lý Thông báo</h5>
                    <a href="/admin/system/announcements/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Thêm Thông báo mới
                    </a>
                </div>
                <div class="card-body">
                    <p class="text-muted"><i class="fas fa-info-circle"></i> Chức năng này sẽ được triển khai sớm</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
