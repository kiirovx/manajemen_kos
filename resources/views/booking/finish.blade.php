<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran - KosKita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(135deg, #E8EAFF 0%, #F5F5FF 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .finish-container { background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); max-width: 600px; width: 100%; text-align: center; }
        .finish-icon { font-size: 80px; margin-bottom: 20px; }
        .finish-icon.success { color: #10B981; }
        .finish-icon.warning { color: #F59E0B; }
        .finish-icon.error { color: #DC2626; }
        .finish-title { font-size: 24px; font-weight: 700; color: #333; margin-bottom: 10px; }
        .finish-subtitle { font-size: 14px; color: #666; margin-bottom: 30px; }
        .booking-detail { background: #F0F0F7; border-radius: 8px; padding: 20px; margin-bottom: 20px; text-align: left; }
        .booking-detail .row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .booking-detail .label { color: #666; }
        .booking-detail .value { color: #333; font-weight: 500; }
        .booking-detail .total { border-top: 1px solid #DDD; padding-top: 10px; font-weight: 700; color: #667eea; font-size: 16px; }
        .status-badge { display: inline-block; padding: 8px 20px; border-radius: 20px; font-size: 14px; font-weight: 600; margin-bottom: 20px; }
        .status-badge.menunggu { background: #FEF3C7; color: #92400E; }
        .status-badge.pending { background: #DBEAFE; color: #1E40AF; }
        .status-badge.dibayar { background: #D1FAE5; color: #065F46; }
        .status-badge.gagal { background: #FEE2E2; color: #991B1B; }
        .btn-primary { width: 100%; padding: 14px; background: linear-gradient(135deg, #5B5EFF 0%, #4A4DE6 100%); color: white; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; margin-bottom: 12px; text-decoration: none; display: inline-block; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(91,94,255,0.3); }
        .btn-secondary { width: 100%; padding: 14px; background: transparent; color: #5B5EFF; border: 1px solid #5B5EFF; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-block; }
        .btn-secondary:hover { background: #5B5EFF; color: white; }
        @media (max-width: 600px) { .finish-container { padding: 25px; } }
    </style>
</head>
<body>
    <div class="finish-container">
        @php
            $statusIcon = match($booking->status) {
                'Dibayar' => ['icon' => 'fa-check-circle', 'class' => 'success'],
                'Pending' => ['icon' => 'fa-clock', 'class' => 'warning'],
                'Menunggu Pembayaran' => ['icon' => 'fa-clock', 'class' => 'warning'],
                'Gagal' => ['icon' => 'fa-times-circle', 'class' => 'error'],
                'Dibatalkan' => ['icon' => 'fa-ban', 'class' => 'error'],
                'Ditolak' => ['icon' => 'fa-ban', 'class' => 'error'],
                'Kadaluarsa' => ['icon' => 'fa-clock', 'class' => 'error'],
                default => ['icon' => 'fa-clock', 'class' => 'warning'],
            };
            $statusClass = match($booking->status) {
                'Menunggu Pembayaran' => 'menunggu', 'Pending' => 'pending', 'Dibayar' => 'dibayar',
                default => 'gagal',
            };
            $statusTitle = match($booking->status) {
                'Dibayar' => 'Pembayaran Berhasil!', 'Pending' => 'Pembayaran Pending',
                'Menunggu Pembayaran' => 'Menunggu Pembayaran', 'Gagal' => 'Pembayaran Gagal',
                'Dibatalkan' => 'Pembayaran Dibatalkan', default => $booking->status,
            };
            $statusMessage = match($booking->status) {
                'Dibayar' => 'Pembayaran Anda telah berhasil. Admin akan memverifikasi penyewaan Anda.',
                'Pending' => 'Pembayaran Anda sedang diproses. Silakan tunggu konfirmasi.',
                'Menunggu Pembayaran' => 'Silakan selesaikan pembayaran untuk mengonfirmasi booking Anda.',
                'Gagal' => 'Pembayaran Anda gagal. Silakan coba lagi.',
                'Dibatalkan' => 'Booking ini telah dibatalkan.',
                default => 'Status: ' . $booking->status,
            };
        @endphp

        <div class="finish-icon {{ $statusIcon['class'] }}">
            <i class="fas {{ $statusIcon['icon'] }}"></i>
        </div>
        <h1 class="finish-title">{{ $statusTitle }}</h1>
        <p class="finish-subtitle">{{ $statusMessage }}</p>
        <span class="status-badge {{ $statusClass }}">{{ $booking->status }}</span>

        <div class="booking-detail">
            <div class="row"><span class="label">Booking ID</span><span class="value">#{{ $booking->id }}</span></div>
            <div class="row"><span class="label">Order ID</span><span class="value">{{ $booking->midtrans_order_id ?? '-' }}</span></div>
            @if($booking->midtrans_transaction_id)
            <div class="row"><span class="label">Transaction ID</span><span class="value">{{ $booking->midtrans_transaction_id }}</span></div>
            @endif
            <div class="row"><span class="label">Nama</span><span class="value">{{ $booking->customer_name }}</span></div>
            <div class="row"><span class="label">Email</span><span class="value">{{ $booking->customer_email }}</span></div>
            <div class="row"><span class="label">Kamar</span><span class="value">{{ $booking->room_name }}</span></div>
            @if($booking->payment_method)
            <div class="row"><span class="label">Metode</span><span class="value">{{ ucfirst($booking->payment_method) }}</span></div>
            @endif
            @if($booking->paid_at)
            <div class="row"><span class="label">Tanggal Bayar</span><span class="value">{{ $booking->paid_at->format('d M Y, H:i') }}</span></div>
            @endif
            <div class="row total"><span>Total</span><span>Rp {{ number_format($booking->gross_amount ?? $booking->room_price, 0, ',', '.') }}</span></div>
        </div>

        @auth
            @if ($booking->status === 'Dibayar')
                <a href="{{ route('dashboard.user.bookings') }}" class="btn-primary"><i class="fas fa-history" style="margin-right:8px;"></i> Lihat Booking Saya</a>
                <a href="{{ route('dashboard.users') }}" class="btn-secondary"><i class="fas fa-tachometer-alt" style="margin-right:8px;"></i> Dashboard</a>
            @elseif (in_array($booking->status, ['Menunggu Pembayaran', 'Pending']))
                <a href="{{ route('booking.payment.page', ['id' => $booking->id]) }}" class="btn-primary"><i class="fas fa-credit-card" style="margin-right:8px;"></i> Lanjutkan Pembayaran</a>
                <a href="{{ route('dashboard.user.bookings') }}" class="btn-secondary"><i class="fas fa-history" style="margin-right:8px;"></i> Booking Saya</a>
            @else
                <a href="{{ route('booking') }}" class="btn-primary"><i class="fas fa-redo" style="margin-right:8px;"></i> Booking Ulang</a>
                <a href="{{ route('dashboard.users') }}" class="btn-secondary"><i class="fas fa-home" style="margin-right:8px;"></i> Dashboard</a>
            @endif
        @else
            @if ($booking->status === 'Dibayar')
                <a href="{{ route('home') }}" class="btn-primary"><i class="fas fa-home" style="margin-right:8px;"></i> Kembali ke Beranda</a>
            @elseif (in_array($booking->status, ['Menunggu Pembayaran', 'Pending']))
                <a href="{{ route('booking.payment.page', ['id' => $booking->id]) }}" class="btn-primary"><i class="fas fa-credit-card" style="margin-right:8px;"></i> Lanjutkan Pembayaran</a>
                <a href="{{ route('home') }}" class="btn-secondary"><i class="fas fa-home" style="margin-right:8px;"></i> Kembali ke Beranda</a>
            @else
                <a href="{{ route('booking') }}" class="btn-primary"><i class="fas fa-redo" style="margin-right:8px;"></i> Booking Ulang</a>
                <a href="{{ route('home') }}" class="btn-secondary"><i class="fas fa-home" style="margin-right:8px;"></i> Kembali ke Beranda</a>
            @endif
        @endauth
    </div>
</body>
</html>