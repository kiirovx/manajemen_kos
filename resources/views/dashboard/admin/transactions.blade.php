<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Transaksi - Admin KosKita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #F5F5F7; color: #333; }
        .container { max-width: 1300px; margin: 0 auto; padding: 40px 20px; }
        .btn-back { display: inline-flex; align-items: center; gap: 6px; color: #667eea; text-decoration: none; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
        .btn-back:hover { text-decoration: underline; }
        .page-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px; }
        .page-header h1 { font-size: 26px; font-weight: 700; }
        .filter-bar { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 20px; align-items: center; }
        .filter-bar a, .filter-bar span { padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid #DDD; background: white; color: #666; transition: all 0.2s; }
        .filter-bar a:hover, .filter-bar span.active { background: #667eea; color: white; border-color: #667eea; }
        .filter-bar input { padding: 8px 12px; border: 1px solid #DDD; border-radius: 8px; font-size: 13px; }
        .filter-bar button { padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; background: #667eea; color: white; border: none; cursor: pointer; }
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px; }
        .stat-card { background: white; border-radius: 12px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-bottom: 3px solid #667eea; }
        .stat-card .label { font-size: 12px; color: #999; margin-bottom: 5px; }
        .stat-card .value { font-size: 22px; font-weight: 700; color: #333; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        thead { background: #667eea; color: white; }
        th, td { padding: 12px 15px; text-align: left; font-size: 13px; }
        th { font-weight: 600; }
        tr:nth-child(even) { background: #F9F9FB; }
        tr:hover { background: #F0F0F7; }
        .badge { display: inline-block; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; }
        .badge.paid { background: #D1FAE5; color: #065F46; }
        .badge.pending { background: #FEF3C7; color: #78350F; }
        .badge.failed { background: #FEE2E2; color: #7F1D1D; }
        .badge.waiting { background: #DBEAFE; color: #1E40AF; }
        .badge.cancelled { background: #F3F4F6; color: #6B7280; }
        .empty-state { text-align: center; padding: 60px; background: white; border-radius: 12px; color: #999; }
        .export-bar { display: flex; gap: 10px; margin-bottom: 15px; }
        .btn-export { padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none; color: white; transition: all 0.2s; }
        .btn-export.pdf { background: #DC2626; }
        .btn-export.pdf:hover { background: #B91C1C; }
        .btn-export.excel { background: #059669; }
        .btn-export.excel:hover { background: #047857; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('dashboard.admin') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
        <div class="page-header">
            <h1><i class="fas fa-exchange-alt" style="color: #667eea; margin-right: 10px;"></i>Transaksi Pembayaran</h1>
            <div class="export-bar">
                <a href="{{ route('dashboard.admin.export.pdf', ['type' => 'payments', 'filter' => $filter, 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn-export pdf" target="_blank">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('dashboard.admin.export.excel', ['type' => 'payments', 'filter' => $filter, 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn-export excel">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
        </div>

        <form class="filter-bar" method="GET" action="{{ route('dashboard.admin.transactions') }}">
            <span class="{{ $filter === 'all' ? 'active' : '' }}"><a href="?filter=all">Semua</a></span>
            <span class="{{ $filter === 'today' ? 'active' : '' }}"><a href="?filter=today">Hari Ini</a></span>
            <span class="{{ $filter === 'week' ? 'active' : '' }}"><a href="?filter=week">Minggu Ini</a></span>
            <span class="{{ $filter === 'month' ? 'active' : '' }}"><a href="?filter=month">Bulan Ini</a></span>
            <span class="{{ $filter === 'year' ? 'active' : '' }}"><a href="?filter=year">Tahun Ini</a></span>
            <input type="date" name="start_date" value="{{ $startDate }}" placeholder="Dari">
            <input type="date" name="end_date" value="{{ $endDate }}" placeholder="Sampai">
            <button type="submit">Filter</button>
        </form>

        <div class="stats-row">
            <div class="stat-card">
                <div class="label">Total Transaksi</div>
                <div class="value">{{ $totalTransactions }}</div>
            </div>
            <div class="stat-card" style="border-bottom-color: #10B981;">
                <div class="label">Total Pendapatan</div>
                <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
        </div>

        @if($transactions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Penyewa</th>
                    <th>No. Booking</th>
                    <th>Nama Kamar</th>
                    <th>Metode</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th>Transaction ID</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $t)
                @php
                    $badge = match($t->status) {
                        'Dibayar' => 'paid',
                        'Pending' => 'pending',
                        'Menunggu Pembayaran' => 'waiting',
                        'Dibatalkan' => 'cancelled',
                        default => 'failed',
                    };
                @endphp
                <tr>
                    <td>{{ $t->created_at->format('d M Y, H:i') }}</td>
                    <td>{{ $t->customer_name }}</td>
                    <td>#{{ $t->id }}</td>
                    <td>{{ $t->room_name }}</td>
                    <td>{{ $t->payment_method ?? '-' }}</td>
                    <td>Rp {{ number_format($t->gross_amount ?? $t->room_price, 0, ',', '.') }}</td>
                    <td><span class="badge {{ $badge }}">{{ $t->status }}</span></td>
                    <td style="font-size: 11px;">{{ $t->midtrans_transaction_id ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <div class="empty-state">
            <i class="fas fa-inbox" style="font-size: 48px; color: #DDD; margin-bottom: 15px; display: block;"></i>
            <h3>Tidak ada transaksi</h3>
            <p style="margin-top: 5px;">Belum ada transaksi pembayaran yang tercatat.</p>
        </div>
        @endif
    </div>
</body>
</html>