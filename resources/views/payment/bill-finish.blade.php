<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Tagihan - KosKita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: linear-gradient(135deg, #E8EAFF 0%, #F5F5FF 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .finish-container { background: white; border-radius: 16px; padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); max-width: 550px; width: 100%; text-align: center; }
        .finish-icon { font-size: 80px; margin-bottom: 20px; }
        .finish-icon.success { color: #10B981; }
        .finish-icon.warning { color: #F59E0B; }
        .finish-title { font-size: 24px; font-weight: 700; color: #333; margin-bottom: 10px; }
        .finish-subtitle { font-size: 14px; color: #666; margin-bottom: 30px; }
        .detail { background: #F0F0F7; border-radius: 8px; padding: 20px; margin-bottom: 20px; text-align: left; }
        .detail .row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 14px; }
        .detail .label { color: #666; }
        .detail .value { color: #333; font-weight: 500; }
        .detail .total { border-top: 1px solid #DDD; padding-top: 10px; font-weight: 700; color: #667eea; font-size: 16px; }
        .status-badge { display: inline-block; padding: 8px 20px; border-radius: 20px; font-size: 14px; font-weight: 600; margin-bottom: 20px; }
        .status-badge.paid { background: #D1FAE5; color: #065F46; }
        .status-badge.pending { background: #FEF3C7; color: #92400E; }
        .btn { width: 100%; padding: 14px; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; margin-bottom: 12px; text-decoration: none; display: inline-block; }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(102,126,234,0.3); }
        .btn-secondary { background: transparent; color: #667eea; border: 1px solid #667eea; }
        .btn-secondary:hover { background: #667eea; color: white; }
    </style>
</head>
<body>
    <div class="finish-container">
        @php
            $isPaid = $payment->status === 'paid';
            $icon = $isPaid ? 'fa-check-circle success' : 'fa-clock warning';
            $title = $isPaid ? 'Pembayaran Berhasil!' : 'Menunggu Pembayaran';
            $msg = $isPaid ? 'Terima kasih! Pembayaran tagihan Anda telah berhasil.' : 'Pembayaran Anda sedang diproses.';
        @endphp

        <div class="finish-icon {{ $isPaid ? 'success' : 'warning' }}">
            <i class="fas {{ $isPaid ? 'fa-check-circle' : 'fa-clock' }}"></i>
        </div>
        <h1 class="finish-title">{{ $title }}</h1>
        <p class="finish-subtitle">{{ $msg }}</p>
        <span class="status-badge {{ $isPaid ? 'paid' : 'pending' }}">{{ $isPaid ? 'LUNAS' : 'PENDING' }}</span>

        <div class="detail">
            <div class="row"><span class="label">Tagihan</span><span class="value">{{ $payment->period_label }}</span></div>
            <div class="row"><span class="label">Order ID</span><span class="value">{{ $payment->midtrans_order_id ?? '-' }}</span></div>
            <div class="row total"><span>Total</span><span>Rp {{ number_format($payment->amount, 0, ',', '.') }}</span></div>
        </div>

        <a href="{{ route('dashboard.users') }}" class="btn btn-primary">
            <i class="fas fa-tachometer-alt" style="margin-right: 8px;"></i> Kembali ke Dashboard
        </a>
    </div>
</body>
</html>