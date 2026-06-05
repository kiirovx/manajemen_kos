        <!-- DASHBOARD PAGE -->
        <div class="page active" id="dashboard">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Dashboard</h1>
                    <p class="page-subtitle">Selamat datang kembali! Ini ringkasan bisnis Anda hari ini.</p>
                </div>
                <button class="btn-primary">
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
                        <div class="number">48</div>
                        <div class="change"><i class="fas fa-arrow-up"></i> +2 minggu ini</div>
                    </div>
                </div>

                <div class="stat-card green">
                    <div class="stat-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Kamar Terisi</h3>
                        <div class="number">42</div>
                        <div class="change"><i class="fas fa-arrow-up"></i> 87.5%</div>
                    </div>
                </div>

                <div class="stat-card purple">
                    <div class="stat-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Penyewa</h3>
                        <div class="number">42</div>
                        <div class="change"><i class="fas fa-arrow-up"></i> +5 bulan ini</div>
                    </div>
                </div>

                <div class="stat-card pink">
                    <div class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Pendapatan Bulan Ini</h3>
                        <div class="number">Rp 84jt</div>
                        <div class="change"><i class="fas fa-arrow-up"></i> +12%</div>
                    </div>
                </div>
            </div>

            <!-- CHARTS -->
            <div class="dashboard-charts-grid">
                <div class="chart-container">
                    <h3>Pendapatan Bulanan</h3>
                    <div id="revenueChart"
                        style="height: 300px; background: linear-gradient(180deg, rgba(102, 126, 234, 0.2) 0%, rgba(102, 126, 234, 0.05) 100%); border-radius: 8px; padding: 20px 10px; display: flex; align-items: flex-end; justify-content: space-around; gap: 8px;">
                        <div style="flex: 1; max-width: 40px; height: 60%; background: #667eea; border-radius: 4px;"></div>
                        <div style="flex: 1; max-width: 40px; height: 50%; background: #667eea; border-radius: 4px;"></div>
                        <div style="flex: 1; max-width: 40px; height: 70%; background: #667eea; border-radius: 4px;"></div>
                        <div style="flex: 1; max-width: 40px; height: 65%; background: #667eea; border-radius: 4px;"></div>
                        <div style="flex: 1; max-width: 40px; height: 80%; background: #667eea; border-radius: 4px;"></div>
                        <div style="flex: 1; max-width: 40px; height: 85%; background: #667eea; border-radius: 4px;"></div>
                    </div>
                </div>

                <div class="chart-container">
                    <h3>Tingkat Hunian</h3>
                    <div style="text-align: center; padding: 40px 20px;">
                        <div
                            style="width: 150px; height: 150px; margin: 0 auto; border-radius: 50%; background: conic-gradient(#667eea 0deg 314deg, #E0E0E0 314deg); display: flex; align-items: center; justify-content: center;">
                            <div
                                style="width: 130px; height: 130px; background: white; border-radius: 50%; display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <div style="font-size: 28px; font-weight: 700; color: #667eea;">87.5%</div>
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
                        <div
                            style="padding: 15px 0; border-bottom: 1px solid #E0E0E0; display: flex; align-items: center; gap: 12px;">
                            <div
                                style="width: 40px; height: 40px; border-radius: 50%; background: #D1FAE5; display: flex; align-items: center; justify-content: center; color: #065F46;">
                                <i class="fas fa-check"></i>
                            </div>
                            <div style="flex: 1;">
                                <p style="margin: 0; font-weight: 600; color: #333;">Ahmad Rifai</p>
                                <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">Kamar 12A</p>
                            </div>
                            <p style="margin: 0; font-size: 12px; color: #999;">2 jam lalu</p>
                        </div>
                        <div
                            style="padding: 15px 0; border-bottom: 1px solid #E0E0E0; display: flex; align-items: center; gap: 12px;">
                            <div
                                style="width: 40px; height: 40px; border-radius: 50%; background: #FEF3C7; display: flex; align-items: center; justify-content: center; color: #78350F;">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div style="flex: 1;">
                                <p style="margin: 0; font-weight: 600; color: #333;">Siti Nurhaliza</p>
                                <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">Kamar 08B</p>
                            </div>
                            <p style="margin: 0; font-size: 12px; color: #999;">3 jam lalu</p>
                        </div>
                        <div style="padding: 15px 0; display: flex; align-items: center; gap: 12px;">
                            <div
                                style="width: 40px; height: 40px; border-radius: 50%; background: #FED7AA; display: flex; align-items: center; justify-content: center; color: #92400E;">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <div style="flex: 1;">
                                <p style="margin: 0; font-weight: 600; color: #333;">Budi Santoso</p>
                                <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">Kamar 15C</p>
                            </div>
                            <p style="margin: 0; font-size: 12px; color: #999;">5 jam lalu</p>
                        </div>
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-header">
                        <h3 class="table-title">Pembayaran Mendatang</h3>
                    </div>
                    <div style="padding: 20px;">
                        <div
                            style="padding: 15px 0; border-bottom: 1px solid #E0E0E0; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div
                                    style="width: 40px; height: 40px; border-radius: 50%; background: #DDD6FE; display: flex; align-items: center; justify-content: center; color: #4F46E5; font-weight: 700;">
                                    R</div>
                                <div>
                                    <p style="margin: 0; font-weight: 600; color: #333;">Rina Wijaya</p>
                                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">Kamar 05A</p>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <p style="margin: 0; font-weight: 600; color: #667eea;">Rp 2.2jt</p>
                                <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">25 Nov</p>
                            </div>
                        </div>
                        <div
                            style="padding: 15px 0; border-bottom: 1px solid #E0E0E0; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div
                                    style="width: 40px; height: 40px; border-radius: 50%; background: #DBEAFE; display: flex; align-items: center; justify-content: center; color: #0369A1; font-weight: 700;">
                                    D</div>
                                <div>
                                    <p style="margin: 0; font-weight: 600; color: #333;">Doni Pratama</p>
                                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">Kamar 11B</p>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <p style="margin: 0; font-weight: 600; color: #667eea;">Rp 1.5jt</p>
                                <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">28 Nov</p>
                            </div>
                        </div>
                        <div
                            style="padding: 15px 0; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div
                                    style="width: 40px; height: 40px; border-radius: 50%; background: #E9D5FF; display: flex; align-items: center; justify-content: center; color: #7C3AED; font-weight: 700;">
                                    L</div>
                                <div>
                                    <p style="margin: 0; font-weight: 600; color: #333;">Lisa Permata</p>
                                    <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">Kamar 09C</p>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <p style="margin: 0; font-weight: 600; color: #667eea;">Rp 3.0jt</p>
                                <p style="margin: 5px 0 0 0; font-size: 12px; color: #999;">27 Nov</p>
                            </div>
                        </div>
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
