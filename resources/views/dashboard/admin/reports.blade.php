<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan Keuangan - Admin KosKita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #F5F5F7; color: #333; }
        .container { max-width: 1300px; margin: 0 auto; padding: 40px 20px; }
        .btn-back { display: inline-flex; align-items: center; gap: 6px; color: #667eea; text-decoration: none; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
        .btn-back:hover { text-decoration: underline; }
        .page-header { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 25px; }
        .page-header h1 { font-size: 26px; font-weight: 700; }
        .export-bar { display: flex; gap: 10px; }
        .btn-export { padding: 8px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none; color: white; }
        .btn-export.pdf { background: #DC2626; }
        .btn-export.pdf:hover { background: #B91C1C; }
        .btn-export.excel { background: #059669; }
        .btn-export.excel:hover { background: #047857; }
        .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px; }
        .stat-card { background: white; border-radius: 12px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border-bottom: 3px solid #667eea; }
        .stat-card .label { font-size: 12px; color: #999; margin-bottom: 5px; }
        .stat-card .value { font-size: 22px; font-weight: 700; color: #333; }
        .charts-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 20px; margin-bottom: 20px; }
        .chart-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .chart-card h3 { font-size: 16px; font-weight: 600; margin-bottom: 15px; color: #333; }
        .chart-card canvas { width: 100%; max-height: 300px; }
        @media (max-width: 600px) {
            .charts-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('dashboard.admin') }}" class="btn-back"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
        <div class="page-header">
            <h1><i class="fas fa-chart-bar" style="color: #667eea; margin-right: 10px;"></i>Laporan Keuangan</h1>
            <div class="export-bar">
                <a href="{{ route('dashboard.admin.export.pdf', ['type' => 'revenue']) }}" class="btn-export pdf" target="_blank">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('dashboard.admin.export.excel', ['type' => 'revenue']) }}" class="btn-export excel">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>
        </div>

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

        <div class="charts-grid">
            <div class="chart-card">
                <h3><i class="fas fa-money-bill-wave" style="color: #10B981; margin-right: 8px;"></i>Pendapatan per Bulan</h3>
                <canvas id="revenueChart"></canvas>
            </div>
            <div class="chart-card">
                <h3><i class="fas fa-calendar-check" style="color: #667eea; margin-right: 8px;"></i>Jumlah Booking per Bulan</h3>
                <canvas id="bookingChart"></canvas>
            </div>
            <div class="chart-card" style="grid-column: 1 / -1; max-width: 600px;">
                <h3><i class="fas fa-pie-chart" style="color: #A78BFA; margin-right: 8px;"></i>Status Pembayaran</h3>
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        const revenueData = @json($monthlyRevenue);
        const bookingData = @json($monthlyBookings);
        const statusData = @json($statusDistribution);

        // Revenue Chart
        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: revenueData.map(d => d.month),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: revenueData.map(d => d.revenue),
                    backgroundColor: '#10B981',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => 'Rp ' + (v/1000000).toFixed(1) + 'M' }
                    }
                }
            }
        });

        // Booking Chart
        new Chart(document.getElementById('bookingChart'), {
            type: 'line',
            data: {
                labels: bookingData.map(d => d.month),
                datasets: [{
                    label: 'Jumlah Booking',
                    data: bookingData.map(d => d.count),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102,126,234,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        // Status Chart
        new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: Object.keys(statusData),
                datasets: [{
                    data: Object.values(statusData),
                    backgroundColor: ['#10B981', '#F59E0B', '#3B82F6', '#EF4444', '#6B7280'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 12 } } }
                }
            }
        });
    </script>
</body>
</html>