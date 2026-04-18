@extends('provider.layout')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-12">
            <h1>Thu nhập</h1>
            <hr>

            <!-- Stats -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title">Tổng thu nhập</h6>
                            <h3 class="text-success">{{ number_format($totalEarnings, 0, '.', ',') }} đ</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Earnings Table -->
            <div class="card">
                <div class="card-header">
                    <h5>Chi tiết thu nhập</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Khóa học</th>
                                <th>Học viên</th>
                                <th>Giá</th>
                                <th>Ngày ghi danh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($earnings as $earning)
                                <tr>
                                    <td>{{ $earning->course->title }}</td>
                                    <td>{{ $earning->user->full_name }}</td>
                                    <td>{{ number_format($earning->price_at_purchase, 0, '.', ',') }} đ</td>
                                    <td>{{ $earning->enrolled_at->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $earnings->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
