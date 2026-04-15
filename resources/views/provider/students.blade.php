

@extends('provider.layout')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/provider-students.css') }}?v={{ time() }}">
@endsection
@section('content')
<div class="student-management-container">
    <table>
        <thead>
            <tr>
                <th class="col-student">Học viên</th>
                <th class="col-course">Khóa học</th>
                <th class="col-date">Ngày đăng ký</th>
                <th class="col-status">Thanh toán</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollments as $enrollment)
            <tr>
                <td>
                    <strong>{{ $enrollment->user->full_name ?? $enrollment->user->username }}</strong>
                </td>
                <td>
                    <span class="course-name-link">
                        {{ $enrollment->course->title }}
                    </span>
                </td>
                <td>{{ optional($enrollment->enrolled_at)->format('d/m/Y') ?? 'N/A' }}</td>
                <td>
                    <span class="payment-badge {{ $enrollment->payment_status == 'paid' ? 'badge-paid' : 'badge-pending' }}">
                        <i class="fas {{ $enrollment->payment_status == 'paid' ? 'fa-check-circle' : 'fa-clock' }}"></i>
                        {{ $enrollment->payment_status == 'paid' ? 'Đã thanh toán' : 'Chờ xử lý' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection