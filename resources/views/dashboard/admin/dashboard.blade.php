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
                        <div class="number">Rp {{ number_format($financeStats['income'], 0, ',', '.') }}</div>
                        <div class="change"><i class="fas fa-arrow-up"></i> {{ $financeStats['paid_count'] }} pembayaran</div>
                    </div>
                </div>
            </div>

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
                    <div style="padding: 20px;">
                        @forelse ($recentActivities as $activity)
                            @php
                                $isFinance = $activity->activity_type === 'Pembayaran';
                                $iconBg = $isFinance ? '#FEF3C7' : '#D1FAE5';
                                $iconColor = $isFinance ? '#78350F' : '#065F46';
                                $icon = $isFinance ? 'fa-dollar-sign' : 'fa-wrench';
                                $room = $activity->user?->tenantProfile?->room;
                            @endphp
                            <div style="padding: 15px 0; border-bottom: 1px solid #E0E0E0; display: flex; align-items: center; gap: 12px;">
                                <div
                                    style="width: 40px; height: 40px; border-radius: 50%; background: {{ $iconBg }}; display: flex; align-items: center; justify-content: center; color: {{ $iconColor }};">
                                    <i class="fas {{ $icon }}"></i>
                                </div>
                                <div style="flex: 1;">
                                    <p style="margin: 0; font-weight: 600; color: #333;">{{ $activity->user?->name ?? '-' }}</p>
                                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">{{ $activity->description }}</p>
                                </div>
                                <p style="margin: 0; font-size: 12px; color: #999; white-space: nowrap;">{{ $activity->activity_date?->diffForHumans() ?? '-' }}</p>
                            </div>
                        @empty
                            <p style="color: #999; font-size: 13px;">Belum ada aktivitas.</p>
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
