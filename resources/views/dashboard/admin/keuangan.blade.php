        <div class="page" id="laporan-keuangan">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Laporan Keuangan</h1>
                    <p class="page-subtitle">Pantau pemasukan dan pengeluaran bisnis Anda</p>
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
                        <div class="number">Rp 84jt</div>
                        <div class="change" style="color: #10B981;"><i class="fas fa-arrow-up"></i> +12% dari bulan lalu
                        </div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom-color: #F472B6;">
                    <div class="stat-icon" style="background: #F472B6;">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Pengeluaran</h3>
                        <div class="number">Rp 20jt</div>
                        <div class="change" style="color: #10B981;"><i class="fas fa-arrow-up"></i> +5% dari bulan lalu
                        </div>
                    </div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-icon" style="background: #A78BFA;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Keuntungan Bersih</h3>
                        <div class="number">Rp 64jt</div>
                        <div class="change" style="color: #10B981;"><i class="fas fa-arrow-up"></i> +15% dari bulan lalu
                        </div>
                    </div>
                </div>
            </div>

            <!-- CHARTS -->
            <div class="dashboard-charts-grid">
                <div class="chart-container">
                    <h3>Pemasukan vs Pengeluaran</h3>
                    <div
                        style="height: 300px; display: flex; align-items: flex-end; justify-content: space-around; padding: 20px 10px; gap: 8px;">
                        <div style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 40px;">
                            <div
                                style="width: 100%; height: 200px; background: #10B981; border-radius: 4px 4px 0 0; margin-bottom: 10px;">
                            </div>
                            <span style="font-size: 12px; color: #10B981; font-weight: 600;">Jan</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 40px;">
                            <div
                                style="width: 100%; height: 180px; background: #10B981; border-radius: 4px 4px 0 0; margin-bottom: 10px;">
                            </div>
                            <span style="font-size: 12px; color: #10B981; font-weight: 600;">Feb</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 40px;">
                            <div
                                style="width: 100%; height: 190px; background: #10B981; border-radius: 4px 4px 0 0; margin-bottom: 10px;">
                            </div>
                            <span style="font-size: 12px; color: #10B981; font-weight: 600;">Mar</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 40px;">
                            <div
                                style="width: 100%; height: 210px; background: #10B981; border-radius: 4px 4px 0 0; margin-bottom: 10px;">
                            </div>
                            <span style="font-size: 12px; color: #10B981; font-weight: 600;">Apr</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 40px;">
                            <div
                                style="width: 100%; height: 225px; background: #10B981; border-radius: 4px 4px 0 0; margin-bottom: 10px;">
                            </div>
                            <span style="font-size: 12px; color: #10B981; font-weight: 600;">May</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center; flex: 1; max-width: 40px;">
                            <div
                                style="width: 100%; height: 235px; background: #10B981; border-radius: 4px 4px 0 0; margin-bottom: 10px;">
                            </div>
                            <span style="font-size: 12px; color: #10B981; font-weight: 600;">Jun</span>
                        </div>
                    </div>
                </div>

                <div class="chart-container">
                    <h3>Tren Keuangan</h3>
                    <div style="height: 300px; padding: 20px;">
                        <svg viewBox="0 0 300 250" style="width: 100%; height: 100%;">
                            <polyline points="10,200 50,180 90,160 130,150 170,140 210,120 250,100" fill="none"
                                stroke="#667eea" stroke-width="3" stroke-linecap="round" />
                            <circle cx="10" cy="200" r="4" fill="#667eea" />
                            <circle cx="50" cy="180" r="4" fill="#667eea" />
                            <circle cx="90" cy="160" r="4" fill="#667eea" />
                            <circle cx="130" cy="150" r="4" fill="#667eea" />
                            <circle cx="170" cy="140" r="4" fill="#667eea" />
                            <circle cx="210" cy="120" r="4" fill="#667eea" />
                            <circle cx="250" cy="100" r="4" fill="#667eea" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- TRANSACTION TABLE -->
            <div class="table-container">
                <div class="table-header">
                    <h3 class="table-title">Transaksi Terbaru</h3>
                </div>
                <div class="table-responsive">
                    <table>
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
                            <tr>
                                <td>23 Nov 2024</td>
                                <td>Ahmad Rifai</td>
                                <td>Kamar 01A</td>
                                <td><span class="badge success">Masuk</span></td>
                                <td style="color: #10B981;">+Rp 1.5jt</td>
                                <td><span class="badge success">Lunas</span></td>
                            </tr>
                            <tr>
                                <td>23 Nov 2024</td>
                                <td>Siti Nurhaliza</td>
                                <td>Kamar 02A</td>
                                <td><span class="badge success">Masuk</span></td>
                                <td style="color: #10B981;">+Rp 1.5jt</td>
                                <td><span class="badge success">Lunas</span></td>
                            </tr>
                            <tr>
                                <td>22 Nov 2024</td>
                                <td>Listrik & Air</td>
                                <td>-</td>
                                <td><span class="badge danger">Keluar</span></td>
                                <td style="color: #EF4444;">-Rp 5.0jt</td>
                                <td><span class="badge info">Dibayar</span></td>
                            </tr>
                            <tr>
                                <td>21 Nov 2024</td>
                                <td>Budi Santoso</td>
                                <td>Kamar 06B</td>
                                <td><span class="badge success">Masuk</span></td>
                                <td style="color: #10B981;">+Rp 3.0jt</td>
                                <td><span class="badge success">Lunas</span></td>
                            </tr>
                            <tr>
                                <td>20 Nov 2024</td>
                                <td>Maintenance</td>
                                <td>Kamar 03A</td>
                                <td><span class="badge danger">Keluar</span></td>
                                <td style="color: #EF4444;">-Rp 2.0jt</td>
                                <td><span class="badge info">Dibayar</span></td>
                            </tr>
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
        showNotification('Mengunduh laporan PDF...', 'success');
    }

    function exportExcel() {
        showNotification('Mengunduh laporan Excel...', 'success');
    }

    // ============================================
    // FILTER FUNCTIONS
    // ============================================
    function applyFilter() {
        console.log('Applying filters...');
        showNotification('Filter diterapkan', 'success');
    }
</script>
