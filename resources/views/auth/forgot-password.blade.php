<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - KosKita</title>
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
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
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

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
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
    <a href="{{ route('login.page') }}" class="back-to-home">
        <i class="fas fa-arrow-left"></i>
        Kembali ke Login
    </a>

    <!-- LOGIN CONTAINER -->
    <div class="login-container">

        <!-- LEFT SIDE -->
        <div class="login-left">
            <div class="login-left-logo">
                <i class="fas fa-key"></i>
            </div>
            <h2>Lupa Password</h2>
            <p>Masukkan email terdaftar Anda untuk melanjutkan pengaturan ulang password akun KosKita.</p>
        </div>

        <!-- RIGHT SIDE -->
        <div class="login-right">
            <div class="login-header">
                <h1>Atur Ulang Password</h1>
                <p>Verifikasi alamat email akun Anda</p>
            </div>

            <!-- FORM -->
            <form method="POST" action="{{ route('forgot-password.verify') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Alamat Email</label>
                    <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required value="{{ old('email') }}">
                </div>

                <button type="submit" class="login-btn">
                    <span>Lanjutkan</span>
                    <i class="fas fa-chevron-right"></i>
                </button>

                <p class="signup-text">
                    Ingat password Anda? <a href="{{ route('login.page') }}">Masuk di sini</a>
                </p>
            </form>
        </div>

    </div>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: '{{ $errors->first() }}'
                });
            });
        </script>
    @endif
</body>

</html>
