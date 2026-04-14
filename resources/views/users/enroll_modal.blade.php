<!-- BƯỚC 1: Nhập thông tin cơ bản -->
<div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="enrollModalLabel">
                    <i class="bi bi-clipboard-check me-2 text-primary"></i>Đăng ký khóa học - Bước 1/2
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <form id="enrollForm">
                    {{-- Thông tin khóa học --}}
                    <div class="alert alert-info border-0 mb-4" style="background-color: #e7f3ff;">
                        <h6 class="fw-bold text-info mb-2">
                            <i class="bi bi-book me-2"></i>Thông tin khóa học
                        </h6>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-1 fw-bold">{{ $course->title }}</p>
                                <small class="text-muted">{{ $course->category->name ?? 'Khóa học' }}</small>
                            </div>
                            <div class="text-end">
                                <span class="h5 fw-bold text-danger mb-0" id="coursePrice">{{ number_format($course->price) }}</span>
                                <span class="text-danger">₫</span>
                            </div>
                        </div>
                        <input type="hidden" id="courseId" value="{{ $course->id }}">
                        <input type="hidden" id="coursePriceValue" value="{{ $course->price }}">
                    </div>

                    {{-- Họ tên --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">HỌ TÊN HỌC VIÊN *</label>
                        <input type="text" id="fullName" class="form-control" 
                               value="{{ Auth::check() ? Auth::user()->full_name : '' }}" 
                               placeholder="Nhập họ tên" required>
                        <small class="text-danger" id="fullNameError" style="display: none;"></small>
                    </div>

                    {{-- Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">EMAIL LIÊN HỆ *</label>
                        <input type="email" id="email" class="form-control" 
                               value="{{ Auth::check() ? Auth::user()->email : '' }}" 
                               placeholder="Nhập email" required>
                        <small class="text-danger" id="emailError" style="display: none;"></small>
                    </div>

                    {{-- Ghi chú --}}
                    <div class="form-floating mb-0">
                        <textarea id="note" class="form-control" placeholder="Ghi chú" style="height: 100px"></textarea>
                        <label for="note"><i class="bi bi-chat-left-text me-2"></i>Ghi chú thêm (không bắt buộc)</label>
                    </div>
                </form>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary px-4 fw-bold" onclick="enrollStep1Next()">
                    Tiếp tục <i class="bi bi-arrow-right ms-2"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- BƯỚC 2: Xác nhận thanh toán hoặc miễn phí -->
<div class="modal fade" id="enrollStep2Modal" tabindex="-1" aria-labelledby="enrollStep2Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="enrollStep2Label">
                    <i class="bi bi-check-circle me-2 text-success"></i>Xác nhận đăng ký - Bước 2/2
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                {{-- Nếu MIỄN PHÍ --}}
                <div id="freeContent" style="display: none;">
                    <div class="alert alert-success border-0 text-center py-4" style="background-color: #d4edda;">
                        <i class="bi bi-check-circle display-4 text-success mb-3"></i>
                        <h5 class="fw-bold text-success mt-2">Khóa học 0₫</h5>
                        <p class="text-muted mb-0">Bạn không cần thanh toán. Nhấn "Xác nhận đăng ký" để bắt đầu học ngay!</p>
                    </div>
                </div>

                {{-- Nếu CÓ PHÍ --}}
                <div id="paidContent" style="display: none;">
                    <div class="payment-info mb-4 p-4" style="background-color: #fff3cd; border-radius: 8px; border-left: 4px solid #ff9800;">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="bi bi-credit-card me-2"></i>Xác nhận đơn hàng
                        </h6>
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Tên khóa học:</small>
                                <p class="fw-bold" id="step2CourseName"></p>
                            </div>
                            <div class="col-6 text-end">
                                <small class="text-muted">Số tiền:</small>
                                <p class="fw-bold text-danger h5" id="step2CoursePrice"></p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <h6 class="fw-bold mb-3">Thông tin thanh toán</h6>
                        <div class="qr-payment p-4" style="background-color: #f8f9fa; border-radius: 8px; border: 2px dashed #ccc;">
                            <img src="https://cdn-icons-png.flaticon.com/512/2103/2103633.png" alt="QR Code" style="width: 150px; height: 150px;" class="mb-3">
                            <p class="small text-muted mb-2">Hoặc chuyển khoản đến:</p>
                            <p class="fw-bold small mb-1">Ngân hàng: MB Bank</p>
                            <p class="fw-bold small mb-2">Số tài khoản: 123456789</p>
                            <small class="text-success d-block">✓ Mặc định: Thanh toán thành công (tự động kích hoạt)</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light px-4" onclick="enrollStep2Back()">
                    <i class="bi bi-arrow-left me-2"></i>Quay lại
                </button>
                <button type="button" class="btn btn-success px-4 fw-bold" onclick="enrollConfirmSubmit()">
                    <i class="bi bi-check-circle me-2"></i>Xác nhận đăng ký
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let enrollData = {};

    function enrollStep1Next() {
        // Validate
        const fullName = document.getElementById('fullName').value.trim();
        const email = document.getElementById('email').value.trim();
        
        if (!fullName) {
            document.getElementById('fullNameError').textContent = 'Vui lòng nhập họ tên';
            document.getElementById('fullNameError').style.display = 'block';
            return;
        }
        document.getElementById('fullNameError').style.display = 'none';

        if (!email || !email.includes('@')) {
            document.getElementById('emailError').textContent = 'Vui lòng nhập email hợp lệ';
            document.getElementById('emailError').style.display = 'block';
            return;
        }
        document.getElementById('emailError').style.display = 'none';

        // Lưu dữ liệu
        enrollData = {
            courseId: document.getElementById('courseId').value,
            fullName: fullName,
            email: email,
            note: document.getElementById('note').value.trim(),
            price: parseFloat(document.getElementById('coursePriceValue').value)
        };

        // Hiển thị bước 2
        const courseName = '{{ $course->title }}';
        const coursePrice = document.getElementById('coursePrice').textContent;
        
        document.getElementById('step2CourseName').textContent = courseName;
        document.getElementById('step2CoursePrice').textContent = coursePrice + ' ₫';

        // Nếu giá = 0 → hiển thị miễn phí
        if (enrollData.price === 0) {
            document.getElementById('freeContent').style.display = 'block';
            document.getElementById('paidContent').style.display = 'none';
        } else {
            document.getElementById('freeContent').style.display = 'none';
            document.getElementById('paidContent').style.display = 'block';
        }

        // Chuyển modal
        bootstrap.Modal.getInstance(document.getElementById('enrollModal')).hide();
        const step2Modal = new bootstrap.Modal(document.getElementById('enrollStep2Modal'));
        step2Modal.show();
    }

    function enrollStep2Back() {
        bootstrap.Modal.getInstance(document.getElementById('enrollStep2Modal')).hide();
        const enrollModal = new bootstrap.Modal(document.getElementById('enrollModal'));
        enrollModal.show();
    }

    function enrollConfirmSubmit() {
        // Gửi form để đăng ký
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("course.enroll", $course->id) }}';
        form.innerHTML = `
            @csrf
            <input type="hidden" name="full_name" value="${enrollData.fullName}">
            <input type="hidden" name="email" value="${enrollData.email}">
            <input type="hidden" name="note" value="${enrollData.note}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
</script>
