<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Reset Password - KosKita</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }

        .email-container {
            max-width: 480px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
        }

        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 32px 24px;
            text-align: center;
        }

        .email-header i {
            font-size: 40px;
            margin-bottom: 12px;
        }

        .email-header h1 {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }

        .email-body {
            padding: 32px 24px;
            text-align: center;
        }

        .email-body p {
            font-size: 14px;
            color: #555;
            line-height: 1.6;
            margin-bottom: 24px;
        }

        .otp-code {
            display: inline-block;
            font-size: 36px;
            font-weight: 700;
            color: #667eea;
            letter-spacing: 8px;
            background: #f0f0f7;
            padding: 16px 32px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-family: 'Courier New', monospace;
        }

        .email-body .expiry {
            font-size: 12px;
            color: #999;
            margin-bottom: 8px;
        }

        .email-footer {
            background: #fafafa;
            padding: 20px 24px;
            text-align: center;
        }

        .email-footer p {
            font-size: 12px;
            color: #999;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <div style="font-size: 40px; margin-bottom: 12px;">&#128274;</div>
            <h1>Reset Password KosKita</h1>
        </div>

        <div class="email-body">
            <p>Anda meminta pengaturan ulang password untuk akun KosKita Anda. Gunakan kode OTP berikut untuk melanjutkan:</p>

            <div class="otp-code">{{ $otp }}</div>

            <p class="expiry">Kode OTP berlaku selama <strong>10 menit</strong>.</p>
            <p class="expiry">Jika Anda tidak meminta reset password, abaikan email ini.</p>
        </div>

        <div class="email-footer">
            <p>&copy; {{ date('Y') }} KosKita. Semua hak dilindungi.</p>
        </div>
    </div>
</body>

</html>