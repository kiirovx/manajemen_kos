<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - KosKita</title>
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

    .remember-forgot {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 13px;
      margin-bottom: 25px;
    }

    .remember-forgot a {
      color: #667eea;
      text-decoration: none;
    }

    .remember-forgot a:hover {
      text-decoration: underline;
    }

    .checkbox-wrapper {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .checkbox-wrapper input {
      width: 16px;
      height: 16px;
      cursor: pointer;
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
    }

    .login-btn:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }

    .login-btn:disabled {
      opacity: 0.8;
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

    /* ERROR MESSAGE */
    .error-message {
      display: none;
      background: #FEE;
      color: #C33;
      padding: 12px;
      border-radius: 8px;
      font-size: 13px;
      margin-bottom: 20px;
      border-left: 4px solid #C33;
    }

    .error-message.show {
      display: block;
      animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-8px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
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

    /* DEMO INFO */
    .demo-info {
      background: #F0F0F7;
      border-left: 4px solid #667eea;
      padding: 15px;
      border-radius: 8px;
      font-size: 12px;
      color: #555;
      line-height: 1.8;
    }

    .demo-info strong {
      display: block;
      color: #333;
      margin-bottom: 6px;
      font-size: 13px;
    }

    .demo-info code {
      background: white;
      padding: 2px 7px;
      border-radius: 4px;
      font-family: monospace;
      color: #667eea;
      font-size: 12px;
    }

    .demo-info .demo-row {
      display: flex;
      align-items: center;
      gap: 6px;
      margin: 3px 0;
    }

    .demo-info .role-badge {
      font-size: 10px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 10px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .demo-info .badge-admin {
      background: #e8eaff;
      color: #667eea;
    }

    .demo-info .badge-user {
      background: #e8f5e9;
      color: #2e7d32;
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
  <a href="{{ route('home') }}" class="back-to-home">
    <i class="fas fa-arrow-left"></i>
    Kembali ke Beranda
  </a>

  <!-- LOGIN CONTAINER -->
  <div class="login-container">

    <!-- LEFT SIDE -->
    <div class="login-left">
      <div class="login-left-logo">
        <i class="fas fa-building"></i>
      </div>
      <h2>KosKita</h2>
      <p>Masuk sebagai Admin atau User untuk mengelola dan memesan kos-kosan dengan mudah.</p>
    </div>

    <!-- RIGHT SIDE -->
    <div class="login-right">
      <div class="login-header">
        <h1>Masuk ke KosKita</h1>
        <p>Gunakan akun Admin atau User Anda</p>
      </div>

      <!-- ERROR MESSAGE -->
      <div class="error-message" id="errorMessage"></div>

      <!-- LOGIN FORM -->
      <!-- PENTING: onsubmit memanggil handleLogin(event) -->

      <form id="registerForm" method="POST" action="{{ route('register.process') }}">
        @csrf
        <div class="form-group">
          <label for="name">Nama</label>
          <input type="text" id="name" name="name" placeholder="Masukkan nama Anda" required autocomplete="name">
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required autocomplete="email">
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Masukkan password" required
            autocomplete="current-password">
        </div>

        <div class="form-group">
          <label for="password_confirmation">Konfirmasi Password</label>
          <input type="password" id="password_confirmation" name="password_confirmation"
            placeholder="Konfirmasi password" required autocomplete="current-password">
        </div>

        <div class="remember-forgot">
          <div class="checkbox-wrapper">
            <input type="checkbox" id="remember" name="remember">
            <label for="remember" style="margin: 0; cursor: pointer;">Ingat saya</label>
          </div>
          <a href="#forgot-password">Lupa password?</a>
        </div>

        <button type="submit" class="login-btn" id="loginBtn">
          <span id="btnText">Register</span>
        </button>

        <p class="signup-text">
          Sudah Punya Akun? <a href="{{ route('login.page', request()->has('redirect_to') ? ['redirect_to' => request('redirect_to')] : []) }}">Masuk di sini</a>
        </p>
      </form>
    </div>

  </div>
  @if ($errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: @json($errors->first())
        });
      });
    </script>
  @endif
  {{--
  <script src="{{ asset('js/login.js') }}"></script> --}}
</body>

</html>