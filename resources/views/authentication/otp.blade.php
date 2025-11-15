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
            const timerElement = document.getElementById('timer');
            const countdownContainer = document.getElementById('countdown-timer');
            const resendContainer = document.getElementById('resend-container');
            const resendLink = document.getElementById('resend-link');
            
            const otpInputs = document.querySelectorAll('.otp-input');

            let seconds = 30;
            let timerInterval = null;

            function startTimer() {
                seconds = 30;
                
                timerElement.textContent = seconds;
                countdownContainer.style.display = 'block';
                resendContainer.style.display = 'none';

                if (timerInterval) {
                    clearInterval(timerInterval);
                }

                timerInterval = setInterval(() => {
                    seconds--;
                    timerElement.textContent = seconds;

                    if (seconds <= 0) {
                        clearInterval(timerInterval);
                        countdownContainer.style.display = 'none';
                        resendContainer.style.display = 'block';
                    }
                }, 1000);
            }

            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    if (!/^\d$/.test(e.target.value)) {
                        e.target.value = '';
                        return;
                    }
                    
                    if (e.target.value && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                });
                
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                    if (e.key === 'Delete') {
                        e.target.value = '';
                    }
                });
                
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pasteData = e.clipboardData.getData('text').slice(0, 6);
                    
                    pasteData.split('').forEach((char, i) => {
                        if (index + i < otpInputs.length && /^\d$/.test(char)) {
                            otpInputs[index + i].value = char;
                        }
                    });
                    
                    if (index + pasteData.length < otpInputs.length) {
                        otpInputs[Math.min(index + pasteData.length, otpInputs.length - 1)].focus();
                    }
                });
            });

            startTimer();
        });
    </script>
@endsection