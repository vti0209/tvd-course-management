@extends('admin.layout')

@section('title', 'Chi tiết rút tiền')
@section('page_title', 'Chi tiết yêu cầu rút tiền')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Yêu cầu rút tiền #{{ $withdrawal->id }}</h5>
                <small class="text-muted">Trạng thái: <strong>{{ ucfirst($withdrawal->status) }}</strong></small>
            </div>
            <a href="{{ route('admin.withdrawals.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h6>Thông tin nhà cung cấp</h6>
                    <p><strong>{{ $withdrawal->provider->full_name ?? $withdrawal->provider->username }}</strong></p>
                    <p>Email: {{ $withdrawal->provider->email }}</p>
                </div>
                <div class="col-md-6">
                    <h6>Thông tin giao dịch</h6>
                    <p>Số tiền yêu cầu: <strong>{{ number_format($withdrawal->amount, 0, '.', ',') }} đ</strong></p>
                    <p>Phí xử lý 20%: <strong>{{ number_format($withdrawal->fee_amount, 0, '.', ',') }} đ</strong></p>
                    <p>Số tiền sau khi trừ phí: <strong>{{ number_format($withdrawal->net_amount, 0, '.', ',') }} đ</strong></p>
                    <p>Ngân hàng: <strong>{{ $withdrawal->bank_name }}</strong></p>
                    <p>Số tài khoản: <strong>{{ $withdrawal->bank_account }}</strong></p>
                    <p>Chủ tài khoản: <strong>{{ $withdrawal->account_holder }}</strong></p>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <p>Ngày yêu cầu: <strong>{{ $withdrawal->requested_at->format('d/m/Y H:i') }}</strong></p>
                    <p>Ngày xử lý: <strong>{{ $withdrawal->processed_at?->format('d/m/Y H:i') ?? 'Chưa xử lý' }}</strong></p>
                    <p>Người xử lý: <strong>{{ $withdrawal->processedBy?->full_name ?? 'N/A' }}</strong></p>
                </div>
                <div class="col-md-6">
                    <p>Kiểu yêu cầu: <strong>{{ ucfirst($withdrawal->status) }}</strong></p>
                    @if($withdrawal->rejection_reason)
                        <p>Lý do từ chối:</p>
                        <div class="alert alert-danger">{{ $withdrawal->rejection_reason }}</div>
                    @endif
                </div>
            </div>

            @if($withdrawal->status === 'pending')
                <div class="d-flex gap-2">
                    <form action="{{ route('admin.withdrawals.approve', $withdrawal->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success">Phê duyệt</button>
                    </form>
                    <button class="btn btn-danger" data-bs-toggle="collapse" data-bs-target="#rejectForm">
                        Từ chối
                    </button>
                </div>
                <div class="collapse mt-3" id="rejectForm">
                    <form action="{{ route('admin.withdrawals.reject', $withdrawal->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="reason" class="form-label">Lý do từ chối</label>
                            <textarea name="reason" id="reason" rows="4" class="form-control" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger">Gửi lý do</button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
