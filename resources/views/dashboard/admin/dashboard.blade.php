        <!-- DASHBOARD PAGE -->
        <div class="page active" id="dashboard">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Dashboard</h1>
                    <p class="page-subtitle">Selamat datang kembali! Ini ringkasan bisnis Anda hari ini.</p>
                </div>
                <button class="btn-primary" onclick="window.print()">
                    <i class="fas fa-file-export"></i>
                    Export PDF
                </button>
            </div>

            <!-- STAT CARDS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Kamar</h3>
                        <div class="number">{{ $roomStats['total'] }}</div>
                        <div class="change"><i class="fas fa-arrow-up"></i> +2 minggu ini</div>
                    </div>
                </div>

                <div class="stat-card green">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Kamar Terisi</h3>
                        <div class="number">{{ $roomStats['occupied'] }}</div>
                        <div class="change"><i class="fas fa-arrow-up"></i> {{ $roomStats['occupancy_rate'] }}%</div>
                    </div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Penyewa</h3>
                        <div class="number">{{ $tenantStats['total'] }}</div>
                        <div class="change"><i class="fas fa-arrow-up"></i> +{{ $tenantStats['new_this_month'] }} bulan ini</div>
                    </div>
                </div>

                <div class="stat-card pink">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Pendapatan Bulan Ini</h3>
                        <div class="number">Rp {{ number_format($revenueStats['this_month'], 0, ',', '.') }}</div>
                        <div class="change"><i class="fas fa-arrow-up"></i> {{ $bookingStats['dibayar'] }} booking berhasil</div>
                    </div>
                </div>
            </div>

            <!-- BOOKING STATS + REVENUE RECAP -->
            <div class="stats-grid" style="margin-top: 10px;">
                <div class="stat-card" style="border-bottom-color: #3B82F6;">
                    <div class="stat-icon"><i class="fas fa-shopping-cart"></i></div>
                    <div class="stat-content">
                        <h3>Total Booking</h3>
                        <div class="number">{{ $bookingStats['total'] }}</div>
                        <div class="change">{{ $bookingStats['dibayar'] }} Dibayar | {{ $bookingStats['menunggu_pembayaran'] }} Menunggu</div>
                    </div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-content">
                        <h3>Booking Berhasil</h3>
                        <div class="number">{{ $bookingStats['dibayar'] }}</div>
                        <div class="change">{{ $bookingStats['pending'] }} Pending | {{ $bookingStats['dibatalkan'] }} Dibatalkan</div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom-color: #F59E0B;">
                    <div class="stat-icon"><i class="fas fa-wallet"></i></div>
                    <div class="stat-content">
                        <h3>Pendapatan Hari Ini</h3>
                        <div class="number">Rp {{ number_format($revenueStats['today'], 0, ',', '.') }}</div>
                        <div class="change"><i class="fas fa-calendar-day"></i> Hari ini</div>
                    </div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
                    <div class="stat-content">
                        <h3>Pendapatan Bulan Ini</h3>
                        <div class="number">Rp {{ number_format($revenueStats['this_month'], 0, ',', '.') }}</div>
                        <div class="change"><i class="fas fa-calendar-alt"></i> {{ now()->format('F Y') }}</div>
                    </div>
                </div>
                <div class="stat-card pink">
                    <div class="stat-icon"><i class="fas fa-coins"></i></div>
                    <div class="stat-content">
                        <h3>Total Pendapatan</h3>
                        <div class="number">Rp {{ number_format($revenueStats['total'], 0, ',', '.') }}</div>
                        <div class="change"><i class="fas fa-calendar-check"></i> Semua waktu</div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom-color: #6366F1;">
                    <div class="stat-icon"><i class="fas fa-calendar"></i></div>
                    <div class="stat-content">
                        <h3>Pendapatan Tahun Ini</h3>
                        <div class="number">Rp {{ number_format($revenueStats['this_year'], 0, ',', '.') }}</div>
                        <div class="change"><i class="fas fa-calendar-alt"></i> {{ now()->format('Y') }}</div>
                    </div>
                </div>
            </div>

            <!-- QUICK LINKS -->
            <div style="display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
                <a href="{{ route('dashboard.admin.transactions') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #667eea; color: white; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600;">
                    <i class="fas fa-exchange-alt"></i> Transaksi Pembayaran
                </a>
                <a href="{{ route('dashboard.admin.reports') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; background: #10B981; color: white; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600;">
                    <i class="fas fa-chart-bar"></i> Laporan Keuangan
                </a>
            </div>

            <!-- PAYMENT NOTIFICATIONS -->
            @if($paymentNotifications->count() > 0)
            <div class="table-container" style="margin-bottom: 20px;">
                <div class="table-header">
                    <h3 class="table-title"><i class="fas fa-bell" style="color: #F59E0B;"></i> Notifikasi Pembayaran Terbaru</h3>
                </div>
                <div style="padding: 20px;">
                    @foreach($paymentNotifications as $notif)
                        <div style="padding: 12px 0; {{ $loop->last ? '' : 'border-bottom: 1px solid #E0E0E0;' }}">
                            <p style="margin: 0; font-size: 13px; color: #333;">{{ $notif->message }}</p>
                            <p style="margin: 4px 0 0 0; font-size: 11px; color: #999;">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- CHARTS -->
            <div class="dashboard-charts-grid">
                <div class="chart-container">
                    <h3>Pendapatan Bulanan</h3>
                    <div id="revenueChart"
                        style="height: 300px; background: linear-gradient(180deg, rgba(102, 126, 234, 0.2) 0%, rgba(102, 126, 234, 0.05) 100%); border-radius: 8px; padding: 20px 10px; display: flex; align-items: flex-end; justify-content: space-around; gap: 8px;">
                        @foreach ($monthlyFinance as $month)
                            @php
                                $barHeight = $month['income'] > 0 ? max(8, ($month['income'] / $maxMonthlyAmount) * 240) : 4;
                            @endphp
                            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; gap: 6px; height: 100%;">
                                <div title="Rp {{ number_format($month['income'], 0, ',', '.') }}"
                                    style="width: 100%; max-width: 40px; height: {{ $barHeight }}px; background: #667eea; border-radius: 4px;"></div>
                                <span style="font-size: 10px; color: #666; font-weight: 600;">{{ $month['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="chart-container">
                    <h3>Tingkat Hunian</h3>
                    @php
                        $occupancyRate = $roomStats['occupancy_rate'];
                        $occupancyDeg = round(($occupancyRate / 100) * 360, 2);
                    @endphp
                    <div style="text-align: center; padding: 40px 20px;">
                        <div
                            style="width: 150px; height: 150px; margin: 0 auto; border-radius: 50%; background: conic-gradient(#667eea 0deg {{ $occupancyDeg }}deg, #E0E0E0 {{ $occupancyDeg }}deg); display: flex; align-items: center; justify-content: center;">
                            <div
                                style="width: 130px; height: 130px; background: white; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <div style="font-size: 28px; font-weight: 700; color: #667eea;">{{ $occupancyRate }}%</div>
                                <div style="font-size: 12px; color: #999;">Okupansi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RECENT ACTIVITY -->
            <div class="dashboard-activity-grid">
                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">Aktivitas Terkini</h3>
                    </div>
                    <div style="padding: 20px; max-height: 400px; overflow-y: auto;">
                        @forelse ($recentActivities as $activity)
                            @php
                                $timestamp = $activity['timestamp'];
                                $diffMinutes = $timestamp->diffInMinutes(now());
                                $diffHours = $timestamp->diffInHours(now());
                                $diffDays = $timestamp->diffInDays(now());

                                if ($diffMinutes < 1) {
                                    $timeAgo = 'Baru saja';
                                } elseif ($diffMinutes < 60) {
                                    $timeAgo = $diffMinutes . ' menit lalu';
                                } elseif ($diffHours < 24) {
                                    $timeAgo = $diffHours . ' jam lalu';
                                } elseif ($diffDays === 1) {
                                    $timeAgo = 'Kemarin';
                                } else {
                                    $timeAgo = $diffDays . ' hari lalu';
                                }
                            @endphp
                            <div style="padding: 12px 0; border-bottom: 1px solid #F3F4F6; display: flex; align-items: flex-start; gap: 12px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $activity['icon_bg'] }}; display: flex; align-items: center; justify-content: center; color: {{ $activity['icon_color'] }}; flex-shrink: 0;">
                                    <i class="fas {{ $activity['icon'] }}" style="font-size: 14px;"></i>
                                </div>
                                <div style="flex: 1; min-width: 0;">
                                    <p style="margin: 0; font-weight: 600; font-size: 13px; color: #111827;">{{ $activity['title'] }}</p>
                                    <p style="margin: 3px 0 0 0; font-size: 12px; color: #6B7280; line-height: 1.4;">{{ $activity['description'] }}</p>
                                </div>
                                <span style="font-size: 11px; color: #9CA3AF; white-space: nowrap; flex-shrink: 0;">{{ $timeAgo }}</span>
                            </div>
                        @empty
                            <p style="color: #9CA3AF; font-size: 13px; text-align: center; padding: 20px 0;">Belum ada aktivitas.</p>
                        @endforelse
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">Pembayaran Mendatang</h3>
                    </div>
                    <div style="padding: 20px;">
                        @forelse ($upcomingPayments as $payment)
                            @php
                                $room = $payment->user?->tenantProfile?->room;
                                $initial = strtoupper(substr($payment->user?->name ?? '?', 0, 1));
                                $palette = ['#DDD6FE' => '#4F46E5', '#DBEAFE' => '#0369A1', '#E9D5FF' => '#7C3AED'];
                                $bg = array_keys($palette)[$loop->index % count($palette)];
                                $color = $palette[$bg];
                            @endphp
                            <div
                                style="padding: 15px 0; {{ $loop->last ? '' : 'border-bottom: 1px solid #E0E0E0;' }} display: flex; align-items: center; justify-content: space-between;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div
                                        style="width: 40px; height: 40px; border-radius: 50%; background: {{ $bg }}; display: flex; align-items: center; justify-content: center; color: {{ $color }}; font-weight: 700;">
                                        {{ $initial }}</div>
                                    <div>
                                        <p style="margin: 0; font-weight: 600; color: #333;">{{ $payment->user?->name ?? '-' }}</p>
                                        <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">{{ $room ? 'Kamar ' . $room->number : $payment->period_label }}</p>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <p style="margin: 0; font-weight: 600; color: #667eea;">Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}</p>
                                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">{{ $payment->due_date?->format('d M') ?? '-' }}</p>
                                </div>
                            </div>
                        @empty
                            <p style="color: #999; font-size: 13px;">Tidak ada tagihan mendatang.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

<script>
    // ============================================
    // CHART FUNCTIONS
    // ============================================
    function generateChart(canvasId, type, data) {
        console.log('Generating chart:', type, data);
    }
</script>
