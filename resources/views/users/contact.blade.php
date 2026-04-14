@extends('layouts.master')

@section('title', 'Liên hệ - Gemini Academy')

@section('content')
<div class="section-header">
    <hr>
    <h2 class="section-title">Liên hệ với chúng tôi</h2>
    <div class="title-line"></div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="card-title mb-4 text-center">Chúng tôi luôn sẵn sàng hỗ trợ bạn</h3>

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="mb-3">Thông tin liên hệ</h5>
                            <div class="contact-info">
                                <p><i class="fas fa-map-marker-alt text-primary me-2"></i> 99 Tô Hiến Thành, Quận Sơn Trà, TP.Đà Nẵng</p>
                                <p><i class="fas fa-phone text-primary me-2"></i> (+84) 373 000 3223</p>
                                <p><i class="fas fa-envelope text-primary me-2"></i> info@geminiacademy.vn</p>
                                <p><i class="fas fa-clock text-primary me-2"></i> Thứ 2 - Thứ 6: 8:00 - 18:00</p>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="mb-3">Gửi tin nhắn</h5>
                            <form>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Họ tên</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Tin nhắn</label>
                                    <textarea class="form-control" id="message" rows="4" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-paper-plane me-2"></i> Gửi tin nhắn
                                </button>
                            </form>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="text-center">
                        <h5 class="mb-3">Theo dõi chúng tôi</h5>
                        <div class="social-links">
                            <a href="#" class="btn btn-outline-primary me-2"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="btn btn-outline-primary me-2"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="btn btn-outline-primary me-2"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="btn btn-outline-primary"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
