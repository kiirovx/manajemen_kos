        <div class="page" id="laporan-keuangan">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Laporan Keuangan</h1>
                    <p class="page-subtitle">Pantau pemasukan dan tagihan pembayaran kos</p>
                </div>
                <button class="btn-primary" onclick="exportPDF()">
                    <i class="fas fa-download"></i>
                    Export PDF
                </button>
            </div>

            <!-- STAT CARDS -->
            <div class="stats-grid" style="margin-bottom: 30px;">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Pemasukan</h3>
                        <div class="number">Rp {{ number_format($financeStats['income'], 0, ',', '.') }}</div>
                        <div class="change" style="color: #10B981;">
                            <i class="fas fa-check"></i> {{ $financeStats['paid_count'] }} pembayaran lunas
                        </div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom-color: #F472B6;">
                    <div class="stat-icon" style="background: #F472B6;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Piutang Belum Dibayar</h3>
                        <div class="number">Rp {{ number_format($financeStats['pending'], 0, ',', '.') }}</div>
                        <div class="change" style="color: #78350F;">
                            <i class="fas fa-exclamation-circle"></i> Tagihan pending
                        </div>
                    </div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-icon" style="background: #A78BFA;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Saldo Bersih</h3>
                        <div class="number">Rp {{ number_format($financeStats['net'], 0, ',', '.') }}</div>
                        <div class="change" style="color: #10B981;">
                            <i class="fas fa-calculator"></i> Pengeluaran tercatat Rp {{ number_format($financeStats['expense'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS -->
            <div class="dashboard-charts-grid">
                <div class="chart-container">
                    <h3>Pemasukan dan Tagihan per Bulan</h3>
                    <div
                        style="height: 300px; display: flex; align-items: flex-end; justify-content: space-around; padding: 20px 10px; gap: 12px;">
                        @foreach ($monthlyFinance as $month)
                            @php
                                $incomeHeight = $month['income'] > 0 ? max(8, ($month['income'] / $maxMonthlyAmount) * 220) : 4;
                                $pendingHeight = $month['pending'] > 0 ? max(8, ($month['pending'] / $maxMonthlyAmount) * 220) : 4;
                            @endphp
                            <div style="display: flex; flex-direction: column; align-items: center; flex: 1; min-width: 48px;">
                                <div style="height: 235px; display: flex; align-items: flex-end; gap: 4px; width: 100%; justify-content: center;">
                                    <div title="Lunas: Rp {{ number_format($month['income'], 0, ',', '.') }}"
                                        style="width: 14px; height: {{ $incomeHeight }}px; background: #10B981; border-radius: 4px 4px 0 0;">
                                    </div>
                                    <div title="Pending: Rp {{ number_format($month['pending'], 0, ',', '.') }}"
                                        style="width: 14px; height: {{ $pendingHeight }}px; background: #F59E0B; border-radius: 4px 4px 0 0;">
                                    </div>
                                </div>
                                <span style="font-size: 11px; color: #666; font-weight: 600; text-align: center;">{{ $month['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div style="display: flex; gap: 16px; padding: 0 20px 20px; font-size: 12px; color: #666;">
                        <span><span style="display: inline-block; width: 10px; height: 10px; background: #10B981; border-radius: 2px;"></span> Lunas</span>
                        <span><span style="display: inline-block; width: 10px; height: 10px; background: #F59E0B; border-radius: 2px;"></span> Pending</span>
                    </div>
                </div>

                <div class="chart-container">
                    <h3>Ringkasan Pembayaran</h3>
                    <div style="padding: 20px;">
                        <div style="margin-bottom: 18px;">
                            <p style="margin: 0 0 8px; font-size: 12px; color: #666;">Pembayaran lunas</p>
                            <div style="height: 10px; background: #E5E7EB; border-radius: 999px; overflow: hidden;">
                                @php
                                    $totalTracked = max(1, $financeStats['income'] + $financeStats['pending']);
                                    $paidPercent = ($financeStats['income'] / $totalTracked) * 100;
                                @endphp
                                <div style="height: 100%; width: {{ $paidPercent }}%; background: #10B981;"></div>
                            </div>
                            <p style="margin: 8px 0 0; font-size: 13px; font-weight: 700; color: #10B981;">
                                {{ round($paidPercent, 1) }}%
                            </p>
                        </div>
                        <div>
                            <p style="margin: 0 0 8px; font-size: 12px; color: #666;">Tagihan pending</p>
                            <div style="height: 10px; background: #E5E7EB; border-radius: 999px; overflow: hidden;">
                                <div style="height: 100%; width: {{ 100 - $paidPercent }}%; background: #F59E0B;"></div>
                            </div>
                            <p style="margin: 8px 0 0; font-size: 13px; font-weight: 700; color: #F59E0B;">
                                {{ round(100 - $paidPercent, 1) }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TRANSACTION TABLE -->
            <div class="table-container">
                <div class="table-header">
                    <h3 class="table-title">Transaksi Terbaru</h3>
                    <div class="table-actions">
                        <button class="filter-btn" onclick="exportExcel()">
                            <i class="fas fa-file-excel"></i> Export CSV
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="financeTransactionsTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Keterangan</th>
                                <th>Kamar</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentTransactions as $payment)
                                @php
                                    $isPaid = $payment->status === 'paid';
                                    $date = $payment->paid_date ?? $payment->due_date;
                                    $room = $payment->user?->tenantProfile?->room;
                                @endphp
                                <tr>
                                    <td>{{ $date?->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $payment->user?->name ?? '-' }} - {{ $payment->period_label }}</td>
                                    <td>{{ $room ? 'Kamar ' . $room->number : '-' }}</td>
                                    <td>
                                        @if ($isPaid)
                                            <span class="badge success">Masuk</span>
                                        @else
                                            <span class="badge warning">Tagihan</span>
                                        @endif
                                    </td>
                                    <td style="color: {{ $isPaid ? '#10B981' : '#F59E0B' }};">
                                        {{ $isPaid ? '+' : '' }}Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($isPaid)
                                            <span class="badge success">Lunas</span>
                                        @else
                                            <span class="badge warning">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; color: #999;">Belum ada data pembayaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

<script>
    // ============================================
    // EXPORT FUNCTIONS
    // ============================================
    function exportPDF() {
        showNotification('Membuka dialog cetak laporan...', 'success');
        window.print();
    }

    function exportExcel() {
        const rows = Array.from(document.querySelectorAll('#financeTransactionsTable tr'));
        const csv = rows.map(row => {
            return Array.from(row.children)
                .map(cell => `"${cell.textContent.trim().replaceAll('"', '""')}"`)
                .join(',');
        }).join('\n');

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'laporan-keuangan.csv';
        link.click();
        URL.revokeObjectURL(url);
        showNotification('Laporan CSV berhasil dibuat', 'success');
    }

    // ============================================
    // FILTER FUNCTIONS
    // ============================================
    function applyFilter() {
        console.log('Applying filters...');
        showNotification('Filter diterapkan', 'success');
    }
</script>
