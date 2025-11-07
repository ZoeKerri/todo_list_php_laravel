@extends('layouts.auth')

@section('title', 'OTP Verification')

@section('content')
    <h1 style="font-size: 2rem;">Enter OTP Code</h1>
    <p class="text-center text-muted" style="margin-top: -20px;">
        We've sent a verification code to<br>
        {{ session('reset_email') ?? 'your email' }}
    </p>

    <form action="{{ url('/otp') }}" method="POST">
        @csrf
        <div class="otp-inputs">
            <input type="text" class="otp-input" name="otp[]" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" name="otp[]" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" name="otp[]" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" name="otp[]" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" name="otp[]" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" name="otp[]" maxlength="1" pattern="[0-9]" required>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding: 0; list-style: none;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <p id="countdown-timer" class="text-center text-muted">
            Resend code in <span id="timer">30</span> seconds
        </p>

        <p id="resend-container" class="text-center" style="display: none;">
            <a href="{{ url('/forgot-password') }}" id="resend-link" class="link">Resend OTP</a>
        </p>

        <button type="submit" class="btn-primary">Verify</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy các phần tử HTML
            const timerElement = document.getElementById('timer');
            const countdownContainer = document.getElementById('countdown-timer');
            const resendContainer = document.getElementById('resend-container');
            const resendLink = document.getElementById('resend-link');
            
            // Lấy tất cả các input OTP
            const otpInputs = document.querySelectorAll('.otp-input');

            let seconds = 30;
            let timerInterval = null; // Biến để giữ tham chiếu đến bộ đếm

            // Hàm để bắt đầu hoặc reset bộ đếm
            function startTimer() {
                // Reset giây về 30
                seconds = 30;
                
                // Cập nhật giao diện
                timerElement.textContent = seconds;
                countdownContainer.style.display = 'block'; // Hiển thị đếm ngược
                resendContainer.style.display = 'none';   // Ẩn link "Gửi lại"

                // Xóa bộ đếm cũ nếu đang chạy
                if (timerInterval) {
                    clearInterval(timerInterval);
                }

                // Bắt đầu bộ đếm mới
                timerInterval = setInterval(() => {
                    seconds--; // Giảm 1 giây
                    timerElement.textContent = seconds;

                    // Khi hết giờ
                    if (seconds <= 0) {
                        clearInterval(timerInterval); // Dừng đếm
                        countdownContainer.style.display = 'none'; // Ẩn văn bản đếm
                        resendContainer.style.display = 'block';  // Hiển thị link "Gửi lại"
                    }
                }, 1000); // Lặp lại mỗi 1 giây
            }

            // Xử lý nhập OTP và tự động chuyển sang ô tiếp theo
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    // Chỉ cho phép số
                    if (!/^\d$/.test(e.target.value)) {
                        e.target.value = '';
                        return;
                    }
                    
                    // Tự động chuyển sang ô tiếp theo nếu có giá trị và không phải ô cuối
                    if (e.target.value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                });
                
                // Xử lý sự kiện khi nhấn phím
                input.addEventListener('keydown', function(e) {
                    // Nếu nhấn Backspace và ô hiện tại trống, chuyển về ô trước
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                    // Nếu nhấn Delete, xóa giá trị hiện tại
                    if (e.key === 'Delete') {
                        e.target.value = '';
                    }
                });
                
                // Xử lý sự kiện paste
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text').slice(0, 6);
                    
                    // Điền vào các input theo thứ tự
                    pasteData.split('').forEach((char, i) => {
                        if (index + i < otpInputs.length && /^\d$/.test(char)) {
                            otpInputs[index + i].value = char;
                        }
                    });
                    
                    // Focus vào ô cuối cùng có giá trị
                    if (index + pasteData.length < otpInputs.length) {
                        otpInputs[Math.min(index + pasteData.length, otpInputs.length - 1)].focus();
                    }
                });
            });

            // Bắt đầu bộ đếm lần đầu tiên khi trang tải
            startTimer();
        });
    </script>
@endsection