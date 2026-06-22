<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Booking Saya - KosKita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #F5F5F7; color: #333; }
        .container { max-width: 1100px; margin: 0 auto; padding: 40px 20px; }
        .page-header { margin-bottom: 30px; }
        .page-header h1 { font-size: 28px; font-weight: 700; margin-bottom: 5px; }
        .page-header p { color: #999; font-size: 14px; }
        .btn-back { display: inline-flex; align-items: center; gap: 6px; color: #667eea; text-decoration: none; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
        .btn-back:hover { text-decoration: underline; }
        .card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 15px; }
        .booking-row { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
        .booking-info { flex: 1; min-width: 200px; }
        .booking-info h3 { font-size: 15px; margin-bottom: 4px; }
        .booking-info .meta { font-size: 12px; color: #666; margin-bottom: 2px; }
        .booking-amount { font-size: 16px; font-weight: 700; color: #333; }
        .badge { display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 600; }
        .badge.paid { background: #D1FAE5; color: #065F46; }
        .badge.pending { background: #FEF3C7; color: #78350F; }
        .badge.failed { background: #FEE2E2; color: #7F1D1D; }
        .badge.waiting { background: #DBEAFE; color: #1E40AF; }
        .badge.info { background: #E0E7FF; color: #3730A3; }
        .badge.success { background: #D1FAE5; color: #065F46; }
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none; border: none; cursor: pointer; transition: all 0.3s ease; }
        .btn-primary { background: #667eea; color: white; }
        .btn-primary:hover { background: #5568d3; }
        .btn-success { background: #10B981; color: white; }
        .btn-success:hover { background: #059669; }
        .btn-outline { background: transparent; border: 1px solid #667eea; color: #667eea; }
        .btn-outline:hover { background: #667eea; color: white; }
        .empty-state { text-align: center; padding: 60px 20px; color: #999; }
        .empty-state i { font-size: 48px; margin-bottom: 15px; color: #DDD; }
        .tenant-status-badge { display: inline-block; padding: 3px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; margin-left: 6px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('dashboard.users') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
        <div class="page-header">
            <h1>Booking Saya</h1>
            <p>Riwayat booking kamar Anda</p>
        </div>

        @forelse ($bookings as $booking)
        @php
            $bookingNumber = 'BK-' . $booking->created_at->format('Ymd') . '-' . str_pad($booking->id, 3, '0', STR_PAD_LEFT);
            $badgeClass = match($booking->status) {
                'Dibayar' => 'paid',
                'Pending' => 'pending',
                'Menunggu Pembayaran' => 'waiting',
                default => 'failed',
            };
            $tenantStatus = $booking->tenantProfile?->status;
            $tenantBadge = match($tenantStatus) {
                'Menunggu Persetujuan' => ['class' => 'pending', 'label' => 'Menunggu Persetujuan'],
                'Aktif' => ['class' => 'success', 'label' => 'Aktif'],
                'Keluar' => ['class' => 'info', 'label' => 'Keluar'],
                'Ditolak' => ['class' => 'failed', 'label' => 'Ditolak'],
                default => null,
            };
        @endphp
        <div class="card">
            <div class="booking-row">
                <div class="booking-info">
                    <h3>{{ $bookingNumber }} - {{ $booking->room_name }}</h3>
                    <div class="meta">Booking: {{ $booking->created_at->format('d M Y, H:i') }}</div>
                    <div class="meta">Metode: {{ ucfirst($booking->payment_method ?? '-') }}</div>
                    @if($booking->paid_at)
                    <div class="meta">Dibayar: {{ $booking->paid_at->format('d M Y H:i') }}</div>
                    @endif
                    @if($tenantStatus)
                    <div class="meta">Status Penyewa: <span class="tenant-status-badge" style="background: {{ $tenantBadge['class'] === 'success' ? '#D1FAE5' : ($tenantBadge['class'] === 'pending' ? '#FEF3C7' : ($tenantBadge['class'] === 'failed' ? '#FEE2E2' : '#E0E7FF')) }}; color: {{ $tenantBadge['class'] === 'success' ? '#065F46' : ($tenantBadge['class'] === 'pending' ? '#78350F' : ($tenantBadge['class'] === 'failed' ? '#7F1D1D' : '#3730A3')) }};">{{ $tenantBadge['label'] }}</span></div>
                    @endif
                </div>
                <div style="text-align: right;">
                    <div class="booking-amount">Rp {{ number_format($booking->gross_amount ?? $booking->room_price, 0, ',', '.') }}</div>
                    <span class="badge {{ $badgeClass }}">{{ $booking->status }}</span>
                </div>
                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    @if($booking->status === 'Dibayar')
                        <a href="{{ route('booking.invoice', $booking->id) }}" class="btn btn-primary" target="_blank">
                            <i class="fas fa-print"></i> Cetak Bukti
                        </a>
                    @endif
                    @if(in_array($booking->status, ['Menunggu Pembayaran', 'Pending']))
                        <a href="{{ route('booking.payment.page', $booking->id) }}" class="btn btn-success">
                            <i class="fas fa-credit-card"></i> Bayar
                        </a>
                    @endif
                    <a href="{{ route('dashboard.user.booking.detail', $booking->id) }}" class="btn btn-outline">
                        <i class="fas fa-eye"></i> Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Belum ada booking</h3>
            <p>Silakan lakukan booking melalui halaman booking.</p>
            <br>
            <a href="{{ route('booking') }}" class="btn btn-primary">Booking Sekarang</a>
        </div>
        @endforelse
    </div>
</body>
</html>