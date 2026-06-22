<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - KosKita</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            max-width: 1000px;
            width: 90%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .login-left {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .login-left-logo {
            font-size: 40px;
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.2);
            width: 100px;
            height: 100px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-left h2 {
            font-size: 28px;
            margin-bottom: 15px;
        }

        .login-left p {
            font-size: 14px;
            opacity: 0.9;
            line-height: 1.6;
        }

        .login-right {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-header {
            margin-bottom: 30px;
        }

        .login-header h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 8px;
        }

        .login-header p {
            font-size: 13px;
            color: #999;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #DDD;
            border-radius: 8px;
            font-size: 20px;
            font-family: 'Courier New', monospace;
            transition: all 0.3s ease;
            text-align: center;
            letter-spacing: 6px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .login-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .login-btn:disabled {
            opacity: 0.8;
            cursor: not-allowed;
        }

        .btn-outline {
            width: 100%;
            padding: 13px;
            background: transparent;
            color: #667eea;
            border: 1px solid #667eea;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-outline:hover:not(:disabled) {
            background: #667eea;
            color: white;
        }

        .btn-outline:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .signup-text {
            text-align: center;
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
        }

        .signup-text a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .signup-text a:hover {
            text-decoration: underline;
        }

        /* SPINNER */
        .loading {
            display: none;
        }

        .loading.show {
            display: inline-block;
        }

        .spinner {
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .resend-text {
            text-align: center;
            font-size: 13px;
            color: #666;
            margin-bottom: 20px;
        }

        .resend-text a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .resend-text a:hover {
            text-decoration: underline;
        }

        .timer {
            text-align: center;
            font-size: 13px;
            color: #999;
            margin-bottom: 12px;
        }

        /* RESEND SPINNER */
        .spinner-outline {
            border: 3px solid rgba(102, 126, 234, 0.2);
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 0.8s linear infinite;
        }

        /* BACK TO HOME */
        .back-to-home {
            position: absolute;
            top: 20px;
            left: 20px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #555;
            text-decoration: none;
            font-size: 13px;
            transition: gap 0.2s ease;
            background: white;
            padding: 8px 14px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .back-to-home:hover {
            gap: 12px;
            color: #667eea;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .login-container {
                grid-template-columns: 1fr;
                max-width: 100%;
                border-radius: 12px;
            }

            .login-left {
                display: none;
            }

            .login-right {
                padding: 40px 25px;
            }
        }
    </style>
</head>

<body>
    <!-- BACK TO HOME -->
    <a href="{{ route('forgot-password.page') }}" class="back-to-home">
        <i class="fas fa-arrow-left"></i>
        Kembali
    </a>

    <!-- CONTAINER -->
    <div class="login-container">

        <!-- LEFT SIDE -->
        <div class="login-left">
            <div class="login-left-logo">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h2>Verifikasi OTP</h2>
            <p>Masukkan kode OTP 6 digit yang telah dikirim ke email Anda untuk melanjutkan reset password.</p>
        </div>

        <!-- RIGHT SIDE -->
        <div class="login-right">
            <div class="login-header">
                <h1>Kode Verifikasi</h1>
                <p>Kode telah dikirim ke <strong>{{ session('reset_email') }}</strong></p>
            </div>

            <!-- FORM -->
            <form id="otpForm" method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <div class="form-group">
                    <label for="otp">Kode OTP</label>
                    <input type="text" id="otp" name="otp" placeholder="000000" required maxlength="6" minlength="6"
                        pattern="[0-9]{6}" inputmode="numeric" autocomplete="one-time-code" value="{{ old('otp') }}">
                </div>

                <button type="submit" class="login-btn" id="verifyBtn">
                    <span class="spinner loading" id="spinnerVerify"></span>
                    <span id="btnTextVerify">Verifikasi OTP</span>
                </button>
            </form>

            <!-- RESEND OTP -->
            <div id="timerContainer" class="timer">
                <span>Kirim ulang kode dalam <span id="countdown">00:00</span></span>
            </div>

            <form id="resendForm" method="POST" action="{{ route('otp.resend') }}" style="display: none;">
                @csrf
                <button type="submit" class="btn-outline" id="resendBtn">
                    <span class="spinner-outline loading" id="spinnerResend"></span>
                    <span id="btnTextResend"><i class="fas fa-redo-alt"></i> Kirim Ulang OTP</span>
                </button>
            </form>

            <p class="signup-text">
                Ingat password Anda? <a href="{{ route('login.page') }}">Masuk di sini</a>
            </p>
        </div>

    </div>

    <!-- Countdown Timer -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Set countdown from session expiry (in seconds)
            let remainingSeconds = {{ session('otp_expiry', 0) > time() ? session('otp_expiry') - time() : 0 }};
            const countdownEl = document.getElementById('countdown');
            const timerContainer = document.getElementById('timerContainer');
            const resendForm = document.getElementById('resendForm');
            const otpInput = document.getElementById('otp');
            const verifyForm = document.getElementById('otpForm');

            // Auto-focus OTP input
            otpInput.focus();

            // Only allow digits and max 6 chars
            otpInput.addEventListener('input', function () {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
            });

            // Verify form submit
            verifyForm.addEventListener('submit', function () {
                const btn = document.getElementById('verifyBtn');
                const spinner = document.getElementById('spinnerVerify');
                const btnText = document.getElementById('btnTextVerify');
                btn.disabled = true;
                spinner.classList.add('show');
                btnText.textContent = 'Memverifikasi...';
            });

            function updateCountdown() {
                if (remainingSeconds <= 0) {
                    timerContainer.style.display = 'none';
                    resendForm.style.display = 'block';
                    return;
                }
                const minutes = Math.floor(remainingSeconds / 60);
                const seconds = remainingSeconds % 60;
                countdownEl.textContent =
                    String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
                remainingSeconds--;
                setTimeout(updateCountdown, 1000);
            }

            updateCountdown();

            // Resend form submit
            resendForm.addEventListener('submit', function () {
                const btn = document.getElementById('resendBtn');
                const spinner = document.getElementById('spinnerResend');
                const btnText = document.getElementById('btnTextResend');
                btn.disabled = true;
                spinner.classList.add('show');
                btnText.innerHTML = '<span style="margin-left:8px;">Mengirim ulang...</span>';
            });
        });
    </script>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: '{{ $errors->first() }}',
                    confirmButtonText: 'Tutup'
                });
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: @json(session('success')),
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif
</body>

</html>