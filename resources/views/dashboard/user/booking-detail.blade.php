<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Booking #{{ $booking->id }} - KosKita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #F5F5F7; color: #333; padding: 40px 20px; }
        .container { max-width: 700px; margin: 0 auto; }
        .btn-back { display: inline-flex; align-items: center; gap: 6px; color: #667eea; text-decoration: none; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
        .btn-back:hover { text-decoration: underline; }
        .page-header { margin-bottom: 25px; }
        .page-header h1 { font-size: 24px; font-weight: 700; }
        .card { background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 15px; }
        .detail-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #EEE; font-size: 14px; }
        .detail-row .label { color: #666; }
        .detail-row .value { color: #333; font-weight: 500; }
        .badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge.paid { background: #D1FAE5; color: #065F46; }
        .badge.pending { background: #FEF3C7; color: #78350F; }
        .badge.failed { background: #FEE2E2; color: #7F1D1D; }
        .badge.waiting { background: #DBEAFE; color: #1E40AF; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; margin-top: 10px; margin-right: 8px; }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #5568d3; }
        .btn-success { background: #10B981; color: white; }
        .btn-success:hover { background: #059669; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('dashboard.user.bookings') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Booking Saya</a>
        <div class="page-header">
            <h1>Detail Booking #{{ $booking->id }}</h1>
        </div>

        <div class="card">
            @php
                $badgeClass = match($booking->status) {
                    'Dibayar' => 'paid',
                    'Pending' => 'pending',
                    'Menunggu Pembayaran' => 'waiting',
                    default => 'failed',
                };
            @endphp
            <span class="badge {{ $badgeClass }}" style="margin-bottom: 15px;">{{ $booking->status }}</span>

            <div class="detail-row">
                <span class="label">Nama Penyewa</span>
                <span class="value">{{ $booking->customer_name }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Email</span>
                <span class="value">{{ $booking->customer_email }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Telepon</span>
                <span class="value">{{ $booking->customer_phone }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Nama Kamar</span>
                <span class="value">{{ $booking->room_name }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Harga Kamar</span>
                <span class="value">Rp {{ number_format($booking->room_price, 0, ',', '.') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Total Dibayar</span>
                <span class="value">Rp {{ number_format($booking->gross_amount ?? $booking->room_price, 0, ',', '.') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Metode Pembayaran</span>
                <span class="value">{{ $booking->payment_method ?? '-' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Order ID</span>
                <span class="value">{{ $booking->midtrans_order_id ?? '-' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Transaction ID</span>
                <span class="value">{{ $booking->midtrans_transaction_id ?? '-' }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Tanggal Booking</span>
                <span class="value">{{ $booking->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="detail-row">
                <span class="label">Tanggal Pembayaran</span>
                <span class="value">{{ $booking->paid_at ? $booking->paid_at->format('d M Y, H:i') : '-' }}</span>
            </div>
            @if($booking->customer_message)
            <div class="detail-row">
                <span class="label">Pesan</span>
                <span class="value">{{ $booking->customer_message }}</span>
            </div>
            @endif

            <div style="margin-top: 15px;">
                @if($booking->status === 'Dibayar')
                    <a href="{{ route('booking.invoice', $booking->id) }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-print"></i> Cetak Bukti Pembayaran
                    </a>
                @endif
                @if(in_array($booking->status, ['Menunggu Pembayaran', 'Pending']))
                    <a href="{{ route('booking.payment.page', $booking->id) }}" class="btn btn-success">
                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                    </a>
                @endif
            </div>
        </div>
    </div>
</body>
</html>