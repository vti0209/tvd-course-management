<div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">

            <form action="{{ route('course.enroll', $course->id) }}" method="POST">
                @csrf

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="enrollModalLabel">Đăng ký khóa học</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body p-4">
                    <div class="course-info-badge mb-4 text-center">
                        <small class="text-uppercase text-muted d-block mb-1">Xác nhận đăng ký học:</small>
                        <h5 class="fw-bold text-primary">{{ $course->title }}</h5>
                    </div>

                    {{-- Hiển thị Tên --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">HỌC VIÊN</label>
                        <input type="text" class="form-control bg-light border-0" value="{{ Auth::check() ? Auth::user()->name : '' }}" readonly>
                    </div>

                    {{-- Hiển thị Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">EMAIL LIÊN HỆ</label>
                        <input type="text" class="form-control bg-light border-0" value="{{ Auth::check() ? Auth::user()->email : '' }}" readonly>
                    </div>

                    {{-- Trường Ghi chú --}}
                    <div class="form-floating mb-0">
                        <textarea name="note" class="form-control" placeholder="Ghi chú" id="floatingNote" style="height: 100px"></textarea>
                        <label for="floatingNote"><i class="bi bi-chat-left-text me-2"></i>Ghi chú thêm (không bắt buộc)</label>
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Xác nhận đăng ký</button>
                </div>

            </form>
        </div>
    </div>
</div>
