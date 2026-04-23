@extends('provider.layout')

@section('title', 'Rút tiền')
@section('page_title', 'Yêu cầu rút tiền')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Số dư khả dụng</h5>
                </div>
                <div class="card-body">
                    <h2 class="text-success">{{ number_format($availableBalance, 0, '.', ',') }} đ</h2>
                    <p class="text-muted">Tổng thu nhập: {{ number_format($totalEarnings, 0, '.', ',') }} đ</p>
                    <p class="text-muted">Đã gửi yêu cầu: {{ number_format($pendingWithdrawals, 0, '.', ',') }} đ</p>
                    <p class="text-muted">Đã thanh toán: {{ number_format($approvedWithdrawals, 0, '.', ',') }} đ</p>
                </div>
            </div>
        </div>

        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Gửi yêu cầu rút tiền</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('provider.withdrawals.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="amount" class="form-label">Số tiền</label>
                            <input type="number" id="amount" name="amount" class="form-control" min="100000" max="{{ $availableBalance }}" step="1000" value="{{ old('amount') }}" required>
                        </div>
                        @if($availableBalance < 100000)
                            <div class="alert alert-warning">
                                Số dư khả dụng hiện tại chưa đủ mức rút tối thiểu 100.000 đ.
                            </div>
                        @endif
                        <div class="mb-3">
                            <label for="bank_name" class="form-label">Tên ngân hàng</label>
                            <input type="text" id="bank_name" name="bank_name" class="form-control" value="{{ old('bank_name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="bank_account" class="form-label">Số tài khoản</label>
                            <input type="text" id="bank_account" name="bank_account" class="form-control" value="{{ old('bank_account', optional($provider->providerProfile)->bank_account) }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="account_holder" class="form-label">Chủ tài khoản</label>
                            <input type="text" id="account_holder" name="account_holder" class="form-control" value="{{ old('account_holder', $provider->full_name ?? $provider->username) }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary" @if($availableBalance < 100000) disabled @endif>Gửi yêu cầu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0">Lịch sử yêu cầu rút tiền</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Số tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày yêu cầu</th>
                        <th>Ngày xử lý</th>
                        <th>Ghi chú</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $withdrawal)
                        <tr>
                            <td>{{ $withdrawal->id }}</td>
                            <td>{{ number_format($withdrawal->amount, 0, '.', ',') }} đ</td>
                            <td>
                                <span class="badge bg-{{ $withdrawal->status === 'pending' ? 'warning' : ($withdrawal->status === 'approved' ? 'success' : 'danger') }} text-dark">
                                    {{ ucfirst($withdrawal->status) }}
                                </span>
                            </td>
                            <td>{{ $withdrawal->requested_at->format('d/m/Y') }}</td>
                            <td>{{ $withdrawal->processed_at?->format('d/m/Y') ?? '-' }}</td>
                            <td>{{ $withdrawal->rejection_reason ? \Illuminate\Support\Str::limit($withdrawal->rejection_reason, 60) : '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Bạn chưa gửi yêu cầu rút tiền nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $withdrawals->links() }}
        </div>
    </div>
</div>
@endsection
