<!-- DASHBOARD PAGE -->
        <div class="page active" id="dashboard">
            <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Selamat datang kembali, {{ Auth::user()->name }}!</p>
            </div>

            <!-- INFO CARDS -->
            <div class="dashboard-grid">
                <div class="info-card">
                    <div class="info-card-label">Status Pembayaran</div>
                    <div class="info-card-value" style="color: #10B981;">Lunas</div>
                    <div class="info-card-action">
                        <a href="#" onclick="showUserPage('pembayaran')">Lihat Riwayat →</a>
                    </div>
                </div>

                <div class="info-card orange">
                    <div class="info-card-label">Pembayaran Berikutnya</div>
                    <div class="info-card-value">01 Des</div>
                    <div class="info-card-action">
                        Rp 1.5 Juta
                    </div>
                </div>

                <div class="info-card green">
                    <div class="info-card-label">Sisa Masa Sewa</div>
                    <div class="info-card-value">11 Bulan</div>
                    <div class="info-card-action">
                        Berakhir: 01 Jan 2025
                    </div>
                </div>

                <div class="info-card red">
                    <div class="info-card-label">Maintenance Requests</div>
                    <div class="info-card-value">1</div>
                    <div class="info-card-action">
                        <a href="#" onclick="showUserPage('maintenance')">Lihat Detail →</a>
                    </div>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi Cepat</h3>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                    <button class="btn btn-primary" onclick="openPaymentModal()">
                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                    </button>
                    <button class="btn btn-primary" onclick="openMaintenanceModal()">
                        <i class="fas fa-exclamation-circle"></i> Ajukan Maintenance
                    </button>
                    <button class="btn btn-primary" onclick="showUserPage('data-pribadi')">
                        <i class="fas fa-edit"></i> Ubah Data
                    </button>
                    <button class="btn btn-primary" onclick="downloadInvoice()">
                        <i class="fas fa-download"></i> Unduh Invoice
                    </button>
                </div>
            </div>

            <!-- RECENT NOTIFICATIONS -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Notifikasi Terbaru</h3>
                    <a href="#" onclick="showUserPage('notifikasi')"
                        style="font-size: 12px; color: #667eea; text-decoration: none;">Lihat Semua →</a>
                </div>

                <div class="notification-item success">
                    <div class="notification-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">Pembayaran Berhasil</div>
                        <div class="notification-text">Pembayaran Anda sebesar Rp 1.500.000 untuk bulan November telah
                            diterima</div>
                        <div class="notification-time">2 jam yang lalu</div>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">Pengumuman Maintenance</div>
                        <div class="notification-text">Akan ada pembersihan area umum hari Minggu pukul 10:00</div>
                        <div class="notification-time">1 hari yang lalu</div>
                    </div>
                </div>

                <div class="notification-item warning">
                    <div class="notification-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">Pengingat Pembayaran</div>
                        <div class="notification-text">Pembayaran bulan Desember jatuh tempo pada 01 Desember 2024</div>
                        <div class="notification-time">3 hari yang lalu</div>
                    </div>
                </div>
            </div>
        </div>

        