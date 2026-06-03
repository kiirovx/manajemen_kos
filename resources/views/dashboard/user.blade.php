<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - KosKita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;

        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #F5F5F7;
            color: #333;

        }

        body::-webkit-scrollbar {
            display: none;
            /* Chrome, Edge, Safari */
        }

        .user-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .user-sidebar {
            background: white;
            padding: 25px 20px;
            border-right: 1px solid #E0E0E0;
            position: fixed;
            width: 280px;
            height: 100vh;
            overflow-x: hidden;

        }

        .user-sidebar::-webkit-scrollbar {
            display: none;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
            text-decoration: none;
            color: #667eea;
            font-weight: 700;
            font-size: 16px;
        }

        .sidebar-header i {
            font-size: 24px;
            background: #667eea;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-profile-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            text-align: center;
        }

        .user-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 15px;
            border: 3px solid white;
        }

        .user-profile-card h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .user-profile-card p {
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 15px;
        }

        .user-room-info {
            background: rgba(255, 255, 255, 0.15);
            padding: 12px;
            border-radius: 8px;
            font-size: 12px;
            line-height: 1.6;
        }

        .user-room-info strong {
            display: block;
            margin-bottom: 5px;
        }

        .user-menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 25px;
        }

        .user-menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: #666;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            background: none;
            font-size: 14px;
            width: 100%;
            text-align: left;
            font-family: inherit;
        }

        .user-menu-item:hover {
            background: #F5F5F7;
            color: #667eea;
        }

        .user-menu-item.active {
            background: #667eea;
            color: white;
            font-weight: 600;
        }

        .user-menu-item i {
            width: 20px;
            text-align: center;
        }

        .sidebar-divider {
            height: 1px;
            background: #E0E0E0;
            margin: 20px 0;
        }

        .sidebar-footer {

            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            border-top: 1px solid #E0E0E0;
            background: white;
            width: 280px;
        }

        .logout-btn {
            display: flex;
            position: relative;
            justify-content: center;
            align-items: center;
            gap: 8px;
            width: 100%;
            padding: 10px 15px;
            background: #FEE;
            color: #C33;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-family: inherit;
            right: 22px;
        }

        .logout-btn:hover {
            background: #FDD;
        }

        /* MAIN CONTENT */
        .user-main {
            margin-left: 280px;
            padding: 30px;
        }

        .page {
            display: none;
        }

        .page.active {
            display: block;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .page-subtitle {
            font-size: 14px;
            color: #999;
        }

        /* CARDS */
        .card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #E0E0E0;
        }

        .card-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        /* DASHBOARD CARDS */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-bottom: 3px solid #667eea;
        }

        .info-card.green {
            border-bottom-color: #10B981;
        }

        .info-card.orange {
            border-bottom-color: #F97316;
        }

        .info-card.red {
            border-bottom-color: #EF4444;
        }

        .info-card-label {
            font-size: 12px;
            color: #999;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .info-card-value {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .info-card-action {
            font-size: 12px;
        }

        .info-card-action a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .info-card-action a:hover {
            color: #5568d3;
        }

        /* NOTIFICATION ITEM */
        .notification-item {
            padding: 15px;
            background: #F5F5F7;
            border-radius: 8px;
            margin-bottom: 12px;
            border-left: 4px solid #667eea;
            display: flex;
            gap: 12px;
        }

        .notification-item.warning {
            border-left-color: #F97316;
            background: #FFF5EB;
        }

        .notification-item.danger {
            border-left-color: #EF4444;
            background: #FEE2E2;
        }

        .notification-item.success {
            border-left-color: #10B981;
            background: #D1FAE5;
        }

        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background: white;
            flex-shrink: 0;
        }

        .notification-item.warning .notification-icon {
            color: #F97316;
        }

        .notification-item.danger .notification-icon {
            color: #EF4444;
        }

        .notification-item.success .notification-icon {
            color: #10B981;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 3px;
        }

        .notification-text {
            font-size: 12px;
            color: #666;
            line-height: 1.5;
        }

        .notification-time {
            font-size: 11px;
            color: #999;
            margin-top: 5px;
        }

        /* INVOICE/BILLING */
        .invoice-item {
            padding: 15px;
            background: white;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .invoice-left {
            flex: 1;
        }

        .invoice-month {
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .invoice-status {
            font-size: 12px;
            color: #666;
        }

        .invoice-right {
            text-align: right;
        }

        .invoice-amount {
            font-size: 16px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge.paid {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge.pending {
            background: #FEF3C7;
            color: #78350F;
        }

        .badge.overdue {
            background: #FEE2E2;
            color: #7F1D1D;
        }

        /* MAINTENANCE REQUEST */
        .maintenance-item {
            padding: 15px;
            background: white;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .maintenance-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .maintenance-title {
            font-size: 13px;
            font-weight: 600;
            color: #333;
        }

        .maintenance-status {
            font-size: 11px;
            font-weight: 600;
            padding: 3px 10px;
            border-radius: 12px;
            background: #FEF3C7;
            color: #78350F;
        }

        .maintenance-status.completed {
            background: #D1FAE5;
            color: #065F46;
        }

        .maintenance-desc {
            font-size: 12px;
            color: #666;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .maintenance-date {
            font-size: 11px;
            color: #999;
        }

        /* FORM */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #E0E0E0;
            color: #333;
        }

        .btn-secondary:hover {
            background: #D0D0D0;
        }

        /* MODAL */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #333;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .table-responsive {
            overflow-x: auto;
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        thead {
            background: #F5F5F7;
            border-bottom: 1px solid #E0E0E0;
        }

        th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #666;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #F0F0F0;
        }

        tbody tr:hover {
            background: #FAFAFA;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .user-container {
                grid-template-columns: 1fr;
            }

            .user-sidebar {
                position: absolute;
                width: 250px;
                left: 0;
                top: 0;
                height: 100%;
                z-index: 999;
                transform: translateX(-100%);
                transition: all 0.3s ease;
            }

            .user-sidebar.show {
                transform: translateX(0);
            }

            .user-main {
                margin-left: 0;
                padding: 20px;
            }

            .page-title {
                font-size: 22px;
            }

            .dashboard-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .invoice-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .invoice-right {
                width: 100%;
                text-align: left;
                margin-top: 10px;
            }
        }

        @media (max-width: 480px) {
            .user-sidebar {
                width: 100%;
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .modal-content {
                max-width: 100%;
            }

            .user-main {
                padding: 15px;
            }
        }

        .badge-success {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-warning {
            background: #FEF3C7;
            color: #78350F;
        }

        .badge-danger {
            background: #FEE2E2;
            color: #7F1D1D;
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <div class="user-sidebar">
        <a href="index.html" class="sidebar-header">
            <i class="fas fa-home"></i>
            <span>KosKita</span>
        </a>

        <!-- PROFILE CARD -->
        <div class="user-profile-card">
            <div class="user-avatar">👤</div>
            <h3 id="userNameDisplay">{{ Auth::user()->name }}</h3>
            <p id="userRoomDisplay">Kamar 01A</p>
            <div class="user-room-info">
                <strong>Status:</strong>
                Aktif
                <br>
                <strong>Sejak:</strong>
                01 Jan 2024
            </div>
        </div>

        <!-- MENU -->
        <div class="user-menu">
            <button class="user-menu-item active" onclick="showUserPage('dashboard')">
                <i class="fas fa-th"></i>
                Dashboard
            </button>
            <button class="user-menu-item" onclick="showUserPage('data-pribadi')">
                <i class="fas fa-user"></i>
                Data Pribadi
            </button>
            <button class="user-menu-item" onclick="showUserPage('pembayaran')">
                <i class="fas fa-receipt"></i>
                Pembayaran
            </button>
            <button class="user-menu-item" onclick="showUserPage('notifikasi')">
                <i class="fas fa-bell"></i>
                Notifikasi
            </button>
            <button class="user-menu-item" onclick="showUserPage('maintenance')">
                <i class="fas fa-wrench"></i>
                Maintenance
            </button>
            <button class="user-menu-item" onclick="showUserPage('riwayat')">
                <i class="fas fa-history"></i>
                Riwayat
            </button>
        </div>

        <div class="sidebar-divider"></div>

        <button class="user-menu-item" onclick="showUserPage('bantuan')">
            <i class="fas fa-question-circle"></i>
            Bantuan
        </button>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="user-main">
        <!-- DASHBOARD PAGE -->
        <div class="page active" id="dashboard">
            <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Selamat datang kembali, Ahmad Rifai!</p>
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

        <!-- DATA PRIBADI PAGE -->
        <div class="page" id="data-pribadi">
            <div class="page-header">
                <h1 class="page-title">Data Pribadi</h1>
                <p class="page-subtitle">Kelola informasi pribadi Anda</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pribadi</h3>
                </div>

                <form onsubmit="handleSaveProfile(event)">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" value="Ahmad Rifai" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="ahmad@email.com" required>
                        </div>

                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="tel" value="0812-3456-7890" required>
                        </div>

                        <div class="form-group">
                            <label>Nomor Identitas (KTP/SIM)</label>
                            <input type="text" value="1234567890123456" required>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" value="1995-08-15" required>
                        </div>

                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" value="Jakarta" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alamat Asal</label>
                        <textarea required>Jalan Pendidikan No. 123, Jakarta Selatan</textarea>
                    </div>

                    <div class="form-group">
                        <label>Pekerjaan/Institusi</label>
                        <input type="text" value="Mahasiswa - Universitas Indonesia" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Orang Tua/Wali</label>
                        <input type="text" value="Siti Rahmah" required>
                    </div>

                    <div class="form-group">
                        <label>Telepon Orang Tua/Wali</label>
                        <input type="tel" value="0811-2345-6789" required>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </form>
            </div>

            <!-- JAMINAN KAMAR -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jaminan Kamar</h3>
                </div>
                <table>
                    <tr>
                        <td style="font-weight: 600;">Jumlah Jaminan</td>
                        <td>Rp 3.000.000</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Tanggal Diserah</td>
                        <td>01 Januari 2024</td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Status</td>
                        <td><span class="badge badge-success">Tersimpan dengan Aman</span></td>
                    </tr>
                    <tr>
                        <td style="font-weight: 600;">Catatan</td>
                        <td>Jaminan akan dikembalikan jika tidak ada kerusakan pada saat pindah</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- PEMBAYARAN PAGE -->
        <div class="page" id="pembayaran">
            <div class="page-header">
                <h1 class="page-title">Pembayaran</h1>
                <p class="page-subtitle">Kelola tagihan dan riwayat pembayaran Anda</p>
            </div>

            <!-- CURRENT BILLING -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tagihan Saat Ini</h3>
                </div>

                <div style="background: #F5F5F7; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="font-size: 14px; color: #666;">Desember 2024</span>
                        <span style="font-weight: 700; color: #333; font-size: 18px;">Rp 1.500.000</span>
                    </div>
                    <div style="font-size: 12px; color: #999; margin-bottom: 15px;">
                        Jatuh tempo: 01 Desember 2024
                    </div>
                    <button class="btn btn-primary" onclick="openPaymentModal()" style="width: 100%;">
                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                    </button>
                </div>
            </div>

            <!-- PAYMENT HISTORY -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Pembayaran</h3>
                </div>

                <div class="invoice-item">
                    <div class="invoice-left">
                        <div class="invoice-month">November 2024</div>
                        <div class="invoice-status">Rp 1.500.000</div>
                    </div>
                    <div class="invoice-right">
                        <div style="margin-bottom: 5px;">
                            <span class="badge badge-success">LUNAS</span>
                        </div>
                        <div style="font-size: 12px; color: #999;">
                            Dibayar: 01 Nov 2024
                        </div>
                    </div>
                </div>

                <div class="invoice-item">
                    <div class="invoice-left">
                        <div class="invoice-month">Oktober 2024</div>
                        <div class="invoice-status">Rp 1.500.000</div>
                    </div>
                    <div class="invoice-right">
                        <div style="margin-bottom: 5px;">
                            <span class="badge badge-success">LUNAS</span>
                        </div>
                        <div style="font-size: 12px; color: #999;">
                            Dibayar: 30 Oct 2024
                        </div>
                    </div>
                </div>

                <div class="invoice-item">
                    <div class="invoice-left">
                        <div class="invoice-month">September 2024</div>
                        <div class="invoice-status">Rp 1.500.000</div>
                    </div>
                    <div class="invoice-right">
                        <div style="margin-bottom: 5px;">
                            <span class="badge badge-success">LUNAS</span>
                        </div>
                        <div style="font-size: 12px; color: #999;">
                            Dibayar: 02 Sep 2024
                        </div>
                    </div>
                </div>

                <div class="invoice-item">
                    <div class="invoice-left">
                        <div class="invoice-month">Agustus 2024</div>
                        <div class="invoice-status">Rp 1.500.000</div>
                    </div>
                    <div class="invoice-right">
                        <div style="margin-bottom: 5px;">
                            <span class="badge badge-success">LUNAS</span>
                        </div>
                        <div style="font-size: 12px; color: #999;">
                            Dibayar: 01 Aug 2024
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- NOTIFIKASI PAGE -->
        <div class="page" id="notifikasi">
            <div class="page-header">
                <h1 class="page-title">Notifikasi</h1>
                <p class="page-subtitle">Kelola semua notifikasi Anda</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Semua Notifikasi</h3>
                    <button class="btn btn-secondary" style="font-size: 12px;">
                        Tandai Semua sebagai Dibaca
                    </button>
                </div>

                <div class="notification-item success">
                    <div class="notification-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">Pembayaran Berhasil Dikonfirmasi</div>
                        <div class="notification-text">Pembayaran Anda sebesar Rp 1.500.000 untuk bulan November telah
                            berhasil dikonfirmasi dan diterima oleh sistem</div>
                        <div class="notification-time">2 jam yang lalu</div>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-icon">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">Pengumuman Maintenance Kamar</div>
                        <div class="notification-text">Akan ada pembersihan dan perawatan kamar dan area umum pada hari
                            Minggu tanggal 24 November pukul 10:00-12:00. Mohon tetap berada di kamar atau keluar untuk
                            memudahkan proses maintenance</div>
                        <div class="notification-time">1 hari yang lalu</div>
                    </div>
                </div>

                <div class="notification-item warning">
                    <div class="notification-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">Pengingat Pembayaran Bulan Depan</div>
                        <div class="notification-text">Pembayaran bulan Desember 2024 akan jatuh tempo pada 01 Desember
                            2024. Pastikan melakukan pembayaran sebelum jatuh tempo untuk menghindari denda
                            keterlambatan</div>
                        <div class="notification-time">3 hari yang lalu</div>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">Update Peraturan Kos</div>
                        <div class="notification-text">Telah ada update mengenai peraturan kos terbaru. Mohon baca dan
                            pahami peraturan yang berlaku di kos-kosan kami</div>
                        <div class="notification-time">5 hari yang lalu</div>
                    </div>
                </div>

                <div class="notification-item">
                    <div class="notification-icon">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="notification-content">
                        <div class="notification-title">Verifikasi Data Pribadi Selesai</div>
                        <div class="notification-text">Data pribadi Anda telah berhasil diverifikasi oleh sistem kami.
                            Anda sekarang dapat menggunakan semua fitur dengan lengkap</div>
                        <div class="notification-time">1 minggu yang lalu</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAINTENANCE PAGE -->
        <div class="page" id="maintenance">
            <div class="page-header">
                <h1 class="page-title">Maintenance & Perbaikan</h1>
                <p class="page-subtitle">Ajukan dan kelola permintaan perbaikan kamar Anda</p>
            </div>

            <button class="btn btn-primary" onclick="openMaintenanceModal()" style="margin-bottom: 20px;">
                <i class="fas fa-plus"></i> Ajukan Maintenance Baru
            </button>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Maintenance Requests</h3>
                </div>

                <div class="maintenance-item">
                    <div class="maintenance-header">
                        <div class="maintenance-title">Pintu Kamar Sulit Ditutup</div>
                        <div class="maintenance-status">Pending</div>
                    </div>
                    <div class="maintenance-desc">
                        Pintu kamar saya sulit untuk ditutup dengan rapat. Mohon diperbaiki agar dapat ditutup dengan
                        lancar
                    </div>
                    <div class="maintenance-date">Diajukan: 20 November 2024</div>
                </div>

                <div class="maintenance-item">
                    <div class="maintenance-header">
                        <div class="maintenance-title">Lampu Kamar Mati</div>
                        <div class="maintenance-status completed">Selesai</div>
                    </div>
                    <div class="maintenance-desc">
                        Salah satu lampu di kamar mati dan tidak bisa dinyalakan. Sudah diganti dengan lampu baru
                    </div>
                    <div class="maintenance-date">Diajukan: 15 November 2024 | Selesai: 17 November 2024</div>
                </div>

                <div class="maintenance-item">
                    <div class="maintenance-header">
                        <div class="maintenance-title">Perbaikan Saluran Air Kamar Mandi</div>
                        <div class="maintenance-status completed">Selesai</div>
                    </div>
                    <div class="maintenance-desc">
                        Saluran air kamar mandi tidak lancar. Sudah dibersihkan dan sekarang berfungsi normal
                    </div>
                    <div class="maintenance-date">Diajukan: 10 November 2024 | Selesai: 12 November 2024</div>
                </div>
            </div>
        </div>

        <!-- RIWAYAT PAGE -->
        <div class="page" id="riwayat">
            <div class="page-header">
                <h1 class="page-title">Riwayat Aktivitas</h1>
                <p class="page-subtitle">Lihat semua aktivitas dan transaksi Anda</p>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Aktivitas</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>23 Nov 2024</td>
                                <td>Pembayaran</td>
                                <td>Pembayaran bulan November (Rp 1.500.000)</td>
                                <td><span class="badge badge-success">Berhasil</span></td>
                            </tr>
                            <tr>
                                <td>20 Nov 2024</td>
                                <td>Maintenance</td>
                                <td>Ajukan perbaikan: Pintu sulit ditutup</td>
                                <td><span class="badge badge-warning">Pending</span></td>
                            </tr>
                            <tr>
                                <td>17 Nov 2024</td>
                                <td>Maintenance</td>
                                <td>Perbaikan lampu kamar selesai</td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>15 Nov 2024</td>
                                <td>Maintenance</td>
                                <td>Ajukan perbaikan: Lampu kamar mati</td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>01 Nov 2024</td>
                                <td>Pembayaran</td>
                                <td>Pembayaran bulan Oktober (Rp 1.500.000)</td>
                                <td><span class="badge badge-success">Berhasil</span></td>
                            </tr>
                            <tr>
                                <td>12 Nov 2024</td>
                                <td>Maintenance</td>
                                <td>Perbaikan saluran air selesai</td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>10 Nov 2024</td>
                                <td>Maintenance</td>
                                <td>Ajukan perbaikan: Saluran air tidak lancar</td>
                                <td><span class="badge badge-success">Selesai</span></td>
                            </tr>
                            <tr>
                                <td>01 Oct 2024</td>
                                <td>Pembayaran</td>
                                <td>Pembayaran bulan September (Rp 1.500.000)</td>
                                <td><span class="badge badge-success">Berhasil</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- BANTUAN PAGE -->
        <div class="page" id="bantuan">
            <div class="page-header">
                <h1 class="page-title">Bantuan & FAQ</h1>
                <p class="page-subtitle">Jawaban untuk pertanyaan yang sering diajukan</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pertanyaan yang Sering Diajukan</h3>
                </div>

                <div style="padding: 20px;">
                    <details style="margin-bottom: 15px;">
                        <summary
                            style="cursor: pointer; font-weight: 600; color: #333; padding: 12px; background: #F5F5F7; border-radius: 8px;">
                            Bagaimana cara melakukan pembayaran?
                        </summary>
                        <div style="padding: 15px; margin-top: 10px; background: #FAFAFA; border-radius: 8px;">
                            <p>Anda dapat melakukan pembayaran melalui berbagai metode:</p>
                            <ul style="margin-top: 10px; padding-left: 20px;">
                                <li>Transfer Bank ke rekening yang sudah disediakan</li>
                                <li>E-wallet (GCash, Dana, OVO, dll)</li>
                                <li>Pembayaran tunai ke petugas</li>
                            </ul>
                        </div>
                    </details>

                    <details style="margin-bottom: 15px;">
                        <summary
                            style="cursor: pointer; font-weight: 600; color: #333; padding: 12px; background: #F5F5F7; border-radius: 8px;">
                            Apakah ada denda jika terlambat membayar?
                        </summary>
                        <div style="padding: 15px; margin-top: 10px; background: #FAFAFA; border-radius: 8px;">
                            <p>Ya, ada denda keterlambatan sebesar 5% dari jumlah pembayaran jika pembayaran melebihi
                                tanggal jatuh tempo. Pastikan membayar tepat waktu untuk menghindari denda.</p>
                        </div>
                    </details>

                    <details style="margin-bottom: 15px;">
                        <summary
                            style="cursor: pointer; font-weight: 600; color: #333; padding: 12px; background: #F5F5F7; border-radius: 8px;">
                            Bagaimana cara mengajukan perbaikan kamar?
                        </summary>
                        <div style="padding: 15px; margin-top: 10px; background: #FAFAFA; border-radius: 8px;">
                            <p>Anda dapat mengajukan perbaikan melalui menu "Maintenance" di dashboard. Jelaskan masalah
                                yang Anda alami secara detail, dan tim kami akan segera menangani.</p>
                        </div>
                    </details>

                    <details style="margin-bottom: 15px;">
                        <summary
                            style="cursor: pointer; font-weight: 600; color: #333; padding: 12px; background: #F5F5F7; border-radius: 8px;">
                            Apa yang termasuk dalam harga sewa?
                        </summary>
                        <div style="padding: 15px; margin-top: 10px; background: #FAFAFA; border-radius: 8px;">
                            <p>Harga sewa sudah termasuk:</p>
                            <ul style="margin-top: 10px; padding-left: 20px;">
                                <li>Kamar dengan furniture dasar</li>
                                <li>Akses WiFi gratis</li>
                                <li>Area umum (ruang tamu, dapur bersama)</li>
                                <li>Keamanan 24 jam</li>
                                <li>Listrik dan air</li>
                            </ul>
                        </div>
                    </details>

                    <details style="margin-bottom: 15px;">
                        <summary
                            style="cursor: pointer; font-weight: 600; color: #333; padding: 12px; background: #F5F5F7; border-radius: 8px;">
                            Bagaimana kebijakan check-out?
                        </summary>
                        <div style="padding: 15px; margin-top: 10px; background: #FAFAFA; border-radius: 8px;">
                            <p>Untuk check-out, Anda harus memberikan notifikasi minimal 1 bulan sebelumnya. Pastikan
                                kamar dalam kondisi baik dan jaminan akan dikembalikan jika tidak ada kerusakan.</p>
                        </div>
                    </details>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hubungi Kami</h3>
                </div>

                <div style="padding: 20px;">
                    <div style="margin-bottom: 15px;">
                        <p style="margin: 0; font-weight: 600; margin-bottom: 5px;">
                            <i class="fas fa-phone"></i> Telepon
                        </p>
                        <p style="margin: 0; color: #667eea;">+62-812-3456-7890</p>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <p style="margin: 0; font-weight: 600; margin-bottom: 5px;">
                            <i class="fas fa-envelope"></i> Email
                        </p>
                        <p style="margin: 0; color: #667eea;">support@koskita.com</p>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <p style="margin: 0; font-weight: 600; margin-bottom: 5px;">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </p>
                        <p style="margin: 0; color: #667eea;">+62-812-3456-7890</p>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <p style="margin: 0; font-weight: 600; margin-bottom: 5px;">
                            <i class="fas fa-map-marker-alt"></i> Alamat
                        </p>
                        <p style="margin: 0; color: #666;">Jl. Pendidikan No. 123, Jakarta Selatan 12345</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODALS -->
    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <div class="modal-header">Lakukan Pembayaran</div>
            <form onsubmit="handlePayment(event)">
                <div class="form-group">
                    <label>Bulan Pembayaran</label>
                    <select required>
                        <option value="">Pilih Bulan</option>
                        <option selected>Desember 2024 - Rp 1.500.000</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select required>
                        <option value="">Pilih Metode</option>
                        <option>Transfer Bank</option>
                        <option>E-Wallet</option>
                        <option>Tunai ke Petugas</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="text" value="Rp 1.500.000" readonly>
                </div>

                <div
                    style="background: #FFF5EB; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 12px; color: #78350F;">
                    <i class="fas fa-info-circle"></i> Pastikan Anda melakukan pembayaran sesuai dengan metode yang
                    dipilih
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closePaymentModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Lanjutkan Pembayaran</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="maintenanceModal">
        <div class="modal-content">
            <div class="modal-header">Ajukan Maintenance Baru</div>
            <form onsubmit="handleMaintenance(event)">
                <div class="form-group">
                    <label>Judul Perbaikan</label>
                    <input type="text" placeholder="Contoh: Pintu rusak, Lampu mati, dll" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select required>
                        <option value="">Pilih Kategori</option>
                        <option>Kelistrikan</option>
                        <option>Plumbing (Air)</option>
                        <option>Furniture</option>
                        <option>Dinding/Cat</option>
                        <option>Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi Detail</label>
                    <textarea placeholder="Jelaskan masalah yang Anda alami secara detail" required></textarea>
                </div>

                <div class="form-group">
                    <label>Prioritas</label>
                    <select required>
                        <option value="">Pilih Prioritas</option>
                        <option>Rendah</option>
                        <option selected>Normal</option>
                        <option>Tinggi</option>
                        <option>Urgent</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeMaintenanceModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Ajukan Maintenance</button>
                </div>
            </form>
        </div>
    </div>

    {{--
    <script src="{{ asset('js/user-dashboard.js') }}"></script> --}}
</body>

</html>