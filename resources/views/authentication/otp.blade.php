@extends('layouts.auth')

@section('title', 'OTP Verification')

@section('content')
    <h1 style="font-size: 2rem;">Nhập mã OTP</h1>
    <p class="text-center text-muted" style="margin-top: -20px;">
        Chúng tôi đã gửi mã xác minh đến<br>Ngoc123@gmail.com
    </p>

    <form action="{{-- url('/verify-otp') --}}" method="POST">
        @csrf
        <div class="otp-inputs">
            <input type="text" class="otp-input" name="otp[]" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" name="otp[]" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" name="otp[]" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" name="otp[]" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" name="otp[]" maxlength="1" pattern="[0-9]" required>
            <input type="text" class="otp-input" name="otp[]" maxlength="1" pattern="[0-9]" required>
        </div>

        <p id="countdown-timer" class="text-center text-muted">
            Gửi lại mã sau <span id="timer">30</span> giây
        </p>

        <p id="resend-container" class="text-center" style="display: none;">
            <a href="#" id="resend-link" class="link">Gửi lại mã</a>
        </p>

        <button type="submit" class="btn-primary">Xác minh</button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Lấy các phần tử HTML
            const timerElement = document.getElementById('timer');
            const countdownContainer = document.getElementById('countdown-timer');
            const resendContainer = document.getElementById('resend-container');
            const resendLink = document.getElementById('resend-link');

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

            // Gắn sự kiện click cho link "Gửi lại mã"
            resendLink.addEventListener('click', function(event) {
                event.preventDefault(); // Ngăn link chuyển trang
                
                // Khởi động lại bộ đếm
                startTimer();
            });

            // Bắt đầu bộ đếm lần đầu tiên khi trang tải
            startTimer();
        });
    </script>
@endsection