<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - KosKita</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #E8EAFF 0%, #F5F5FF 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .payment-container {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            text-align: center;
        }

        .payment-icon {
            font-size: 60px;
            color: #5B5EFF;
            margin-bottom: 20px;
        }

        .payment-title {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .payment-subtitle {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
        }

        .booking-detail {
            background: #F0F0F7;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: left;
        }

        .booking-detail .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .booking-detail .label {
            color: #666;
        }

        .booking-detail .value {
            color: #333;
            font-weight: 500;
        }

        .booking-detail .total {
            border-top: 1px solid #DDD;
            padding-top: 10px;
            font-weight: 700;
            color: #5B5EFF;
            font-size: 16px;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .status-badge.menunggu {
            background: #FEF3C7;
            color: #92400E;
        }

        .status-badge.pending {
            background: #DBEAFE;
            color: #1E40AF;
        }

        .status-badge.dibayar {
            background: #D1FAE5;
            color: #065F46;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #5B5EFF 0%, #4A4DE6 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 12px;
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(91, 94, 255, 0.3);
        }

        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .btn-outline {
            width: 100%;
            padding: 14px;
            background: transparent;
            color: #5B5EFF;
            border: 1px solid #5B5EFF;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline:hover {
            background: #5B5EFF;
            color: white;
        }

        .btn-success {
            width: 100%;
            padding: 14px;
            background: #10B981;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 12px;
        }

        .btn-success:hover {
            background: #059669;
        }

        .payment-info-box {
            background: #FEF3C7;
            border-left: 4px solid #FBBF24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #78350F;
            text-align: left;
            line-height: 1.6;
        }

        .payment-methods-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 10px;
            margin: 20px 0;
        }

        .payment-method-item {
            background: #F0F0F7;
            border-radius: 8px;
            padding: 12px 8px;
            text-align: center;
            font-size: 11px;
            color: #555;
        }

        .payment-method-item i {
            font-size: 20px;
            color: #5B5EFF;
            display: block;
            margin-bottom: 6px;
        }

        .spinner {
            display: none;
            border: 3px solid rgba(255,255,255,0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            animation: spin 0.8s linear infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @media (max-width: 600px) {
            .payment-container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-icon">
            <i class="fas fa-credit-card"></i>
        </div>

        <h1 class="payment-title">Pembayaran Booking</h1>
        <p class="payment-subtitle">Selesaikan pembayaran untuk mengonfirmasi booking Anda</p>

        @php
            $statusClass = match($booking->status) {
                'Menunggu Pembayaran' => 'menunggu',
                'Pending' => 'pending',
                'Dibayar' => 'dibayar',
                default => 'menunggu',
            };
        @endphp

        <span class="status-badge {{ $statusClass }}">{{ $booking->status }}</span>

        <div class="booking-detail">
            <div class="row">
                <span class="label">Booking ID</span>
                <span class="value">#{{ $booking->id }}</span>
            </div>
            <div class="row">
                <span class="label">Nama</span>
                <span class="value">{{ $booking->customer_name }}</span>
            </div>
            <div class="row">
                <span class="label">Kamar</span>
                <span class="value">{{ $booking->room_name }}</span>
            </div>
            <div class="row total">
                <span>Total</span>
                <span>Rp {{ number_format($booking->room_price, 0, ',', '.') }}</span>
            </div>
        </div>

        @if (in_array($booking->status, ['Menunggu Pembayaran', 'Pending']))
            @if ($booking->midtrans_snap_token)
                <div class="payment-methods-grid">
                    <div class="payment-method-item">
                        <i class="fas fa-qrcode"></i>
                        QRIS
                    </div>
                    <div class="payment-method-item">
                        <i class="fas fa-bank"></i>
                        Bank Transfer
                    </div>
                    <div class="payment-method-item">
                        <i class="fas fa-wallet"></i>
                        E-Wallet
                    </div>
                    <div class="payment-method-item">
                        <i class="fas fa-university"></i>
                        Virtual Account
                    </div>
                    <div class="payment-method-item">
                        <i class="fas fa-credit-card"></i>
                        Credit Card
                    </div>
                </div>

                <div class="payment-info-box">
                    <strong>🔒 Pembayaran Aman</strong><br>
                    Pembayaran diproses oleh <strong>Midtrans</strong>. Data Anda dienkripsi dan aman.
                    Pilih metode pembayaran yang tersedia, kemudian selesaikan transaksi.
                </div>

                <button class="btn-success" id="payBtn" onclick="payNow()">
                    <span class="spinner" id="spinner"></span>
                    <span id="btnText"><i class="fas fa-lock" style="margin-right: 8px;"></i> Bayar Sekarang</span>
                </button>

                <a href="{{ route('booking') }}" class="btn-outline">Kembali ke Halaman Booking</a>
            @else
                <div class="payment-info-box">
                    <strong>⚠️ Token pembayaran tidak tersedia.</strong><br>
                    Silakan hubungi admin atau coba booking ulang.
                </div>
                <a href="{{ route('booking') }}" class="btn-outline">Booking Ulang</a>
            @endif
        @elseif ($booking->status === 'Dibayar')
            <div style="font-size: 48px; color: #10B981; margin: 20px 0;">
                <i class="fas fa-check-circle"></i>
            </div>
            <p style="color: #666; margin-bottom: 20px;">Pembayaran Anda telah berhasil dikonfirmasi.</p>
            <a href="{{ route('home') }}" class="btn-primary" style="text-decoration: none;">
                <i class="fas fa-home" style="margin-right: 8px;"></i> Kembali ke Beranda
            </a>
        @else
            <div style="font-size: 48px; color: #DC2626; margin: 20px 0;">
                <i class="fas fa-times-circle"></i>
            </div>
            <p style="color: #666; margin-bottom: 20px;">
                Status booking: <strong>{{ $booking->status }}</strong>
            </p>
            <a href="{{ route('booking') }}" class="btn-primary" style="text-decoration: none;">Booking Ulang</a>
        @endif
    </div>

    @if ($booking->midtrans_snap_token)
    <script>
        function payNow() {
            const btn = document.getElementById('payBtn');
            const spinner = document.getElementById('spinner');
            const btnText = document.getElementById('btnText');

            btn.disabled = true;
            spinner.style.display = 'inline-block';
            btnText.innerHTML = 'Memproses...';

            window.snap.pay(@json($booking->midtrans_snap_token), {
                onSuccess: function(result) {
                    window.location.href = '{{ route("booking.payment.finish", ["id" => $booking->id]) }}';
                },
                onPending: function(result) {
                    window.location.href = '{{ route("booking.payment.finish", ["id" => $booking->id]) }}';
                },
                onError: function(result) {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                    btn.disabled = false;
                    spinner.style.display = 'none';
                    btnText.innerHTML = '<i class="fas fa-lock" style="margin-right: 8px;"></i> Bayar Sekarang';
                },
                onClose: function() {
                    btn.disabled = false;
                    spinner.style.display = 'none';
                    btnText.innerHTML = '<i class="fas fa-lock" style="margin-right: 8px;"></i> Bayar Sekarang';
                }
            });
        }
    </script>
    @endif

    @if (session('error'))
        <script>
            alert(@json(session('error')));
        </script>
    @endif
</body>
</html>