@extends('layouts.master')

@section('title', 'Về chúng tôi - Gemini Academy')

@section('content')
<div class="section-header">
    <hr>
    <h2 class="section-title">Về chúng tôi</h2>
    <div class="title-line"></div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="card-title mb-4 text-center">Chào mừng đến với Gemini Academy</h3>

                    <p class="lead mb-4">
                        Gemini Academy là nền tảng học tập trực tuyến hàng đầu, cung cấp các khóa học chất lượng cao
                        về lập trình, thiết kế và công nghệ thông tin.
                    </p>

                    <h5 class="mb-3">Sứ mệnh của chúng tôi</h5>
                    <p>
                        Chúng tôi cam kết mang đến cho học viên những kiến thức thực tế, cập nhật nhất trong lĩnh vực
                        công nghệ. Với đội ngũ giảng viên giàu kinh nghiệm và phương pháp giảng dạy hiện đại,
                        chúng tôi giúp bạn phát triển kỹ năng và đạt được mục tiêu nghề nghiệp.
                    </p>

                    <h5 class="mb-3">Tại sao chọn Gemini Academy?</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Khóa học được thiết kế bởi chuyên gia</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Học tập linh hoạt, mọi lúc mọi nơi</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Cộng đồng học viên năng động</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Hỗ trợ 24/7 từ đội ngũ kỹ thuật</li>
                    </ul>

                    <div class="text-center mt-4">
                        <a href="{{ route('courses.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-graduation-cap me-2"></i> Khám phá khóa học
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection