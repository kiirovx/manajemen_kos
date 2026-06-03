<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - KosKita</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
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

        .admin-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: 100vh;
        }

        /* SIDEBAR */
        .sidebar {
            background: white;
            padding: 30px 20px;
            border-right: 1px solid #E0E0E0;
            position: fixed;
            width: 250px;
            height: 100vh;
            overflow-x: hidden;
        }

        .sidebar::-webkit-scrollbar {
            display: none;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
            text-decoration: none;
            color: #667eea;
            font-weight: 700;
            font-size: 16px;
        }

        .sidebar-logo i {
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

        .back-to-website {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid #E0E0E0;
            padding-bottom: 20px;
            transition: all 0.3s ease;
        }

        .back-to-website:hover {
            gap: 12px;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 30px;
        }

        .nav-item {
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

        .nav-item:hover {
            background: #F5F5F7;
            color: #667eea;
        }

        .nav-item.active {
            background: #667eea;
            color: white;
            font-weight: 600;
        }

        .nav-item i {
            width: 20px;
            text-align: center;
        }

        .sidebar-divider {
            height: 1px;
            background: #E0E0E0;
            margin: 20px 0;
        }

        .sidebar-footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 20px;
            border-top: 1px solid #E0E0E0;
            background: white;
            width: 250px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            background: #F5F5F7;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
        }

        .user-details {
            flex: 1;
        }

        .user-details p {
            margin: 0;
            font-size: 12px;
        }

        .user-details p:first-child {
            font-weight: 600;
            color: #333;
            font-size: 13px;
        }

        .user-details p:last-child {
            color: #999;
        }

        .logout-btn {
            display: flex;
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
        }

        .logout-btn:hover {
            background: #FDD;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 250px;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
        }

        .page-subtitle {
            font-size: 14px;
            color: #999;
            margin-top: 5px;
        }

        .btn-primary {
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        /* STAT CARDS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-bottom: 3px solid #667eea;
        }

        .stat-card.green {
            border-bottom-color: #10B981;
        }

        .stat-card.purple {
            border-bottom-color: #A78BFA;
        }

        .stat-card.pink {
            border-bottom-color: #F472B6;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-card:nth-child(1) .stat-icon {
            background: #667eea;
        }

        .stat-card:nth-child(2) .stat-icon {
            background: #10B981;
        }

        .stat-card:nth-child(3) .stat-icon {
            background: #A78BFA;
        }

        .stat-card:nth-child(4) .stat-icon {
            background: #F472B6;
        }

        .stat-content h3 {
            font-size: 12px;
            color: #999;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .stat-content .number {
            font-size: 24px;
            font-weight: 700;
            color: #333;
        }

        .stat-content .change {
            font-size: 12px;
            color: #10B981;
            margin-top: 5px;
        }

        /* CHART CONTAINER */
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .chart-container h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #333;
        }

        /* TABLE */
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid #E0E0E0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-title {
            font-size: 16px;
            font-weight: 600;
            color: #333;
        }

        .table-actions {
            display: flex;
            gap: 10px;
        }

        .search-box {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            font-size: 13px;
            max-width: 400px;
        }

        .filter-btn {
            padding: 10px 15px;
            background: #F5F5F7;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .filter-btn:hover {
            background: #E0E0E0;
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
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #666;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #F0F0F0;
        }

        tbody tr:hover {
            background: #FAFAFA;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }

        .badge.success {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge.warning {
            background: #FEF3C7;
            color: #78350F;
        }

        .badge.danger {
            background: #FEE2E2;
            color: #7F1D1D;
        }

        .badge.info {
            background: #DBEAFE;
            color: #0C2340;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #667eea;
            transition: all 0.3s ease;
        }

        .action-btn:hover {
            color: #5568d3;
            transform: scale(1.2);
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

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn-cancel {
            padding: 10px 20px;
            background: #E0E0E0;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #D0D0D0;
        }

        .btn-submit {
            padding: 10px 20px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background: #5568d3;
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .admin-container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: absolute;
                width: 250px;
                left: 0;
                top: 0;
                height: 100%;
                z-index: 999;
                transform: translateX(-100%);
                transition: all 0.3s ease;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 20px;
            }

            .page-title {
                font-size: 22px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            table {
                font-size: 12px;
            }

            th,
            td {
                padding: 10px;
            }

            .table-header {
                flex-direction: column;
                gap: 15px;
            }

            .search-box {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <div class="sidebar">
        <a href="/index" class="sidebar-logo">
            <i class="fas fa-building"></i>
            <span>KosKita</span>
        </a>



        <div class="nav-menu">
            <button class="nav-item active" onclick="showPage('dashboard')">
                <i class="fas fa-th"></i>
                Dashboard
            </button>
            <button class="nav-item" onclick="showPage('manajemen-kamar')">
                <i class="fas fa-door-open"></i>
                Manajemen Kamar
            </button>
            <button class="nav-item" onclick="showPage('manajemen-penyewa')">
                <i class="fas fa-users"></i>
                Manajemen Penyewa
            </button>
            <button class="nav-item" onclick="showPage('laporan-keuangan')">
                <i class="fas fa-chart-line"></i>
                Laporan Keuangan
            </button>
        </div>

        <div class="sidebar-divider"></div>

        <button class="nav-item" onclick="showPage('pengaturan')">
            <i class="fas fa-cog"></i>
            Pengaturan
        </button>

        <div class="sidebar-footer">
            <div class="user-info" id="userInfo">
                <div class="user-avatar">A</div>
                <div class="user-details">
                    <p id="adminName">Admin User</p>
                    <p id="adminEmail">admin@koskita.com</p>
                </div>
            </div>
            <button class="logout-btn" onclick="logoutAdmin()">
                <i class="fas fa-sign-out-alt"></i>
                Keluar
            </button>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
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
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 30px;">
                <div class="chart-container">
                    <h3>Pendapatan Bulanan</h3>
                    <div id="revenueChart"
                        style="height: 300px; background: linear-gradient(180deg, rgba(102, 126, 234, 0.2) 0%, rgba(102, 126, 234, 0.05) 100%); border-radius: 8px; padding: 20px; display: flex; align-items: flex-end; justify-content: space-around;">
                        <div style="width: 40px; height: 60%; background: #667eea; border-radius: 4px;"></div>
                        <div style="width: 40px; height: 50%; background: #667eea; border-radius: 4px;"></div>
                        <div style="width: 40px; height: 70%; background: #667eea; border-radius: 4px;"></div>
                        <div style="width: 40px; height: 65%; background: #667eea; border-radius: 4px;"></div>
                        <div style="width: 40px; height: 80%; background: #667eea; border-radius: 4px;"></div>
                        <div style="width: 40px; height: 85%; background: #667eea; border-radius: 4px;"></div>
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
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
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

        <!-- MANAJEMEN KAMAR PAGE -->
        <div class="page" id="manajemen-kamar">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Manajemen Kamar</h1>
                    <p class="page-subtitle">Kelola semua kamar kos-kosan Anda</p>
                </div>
                <button class="btn-primary" onclick="openAddRoomModal()">
                    <i class="fas fa-plus"></i>
                    Tambah Kamar
                </button>
            </div>

            <!-- STAT CARDS -->
            <div class="stats-grid" style="margin-bottom: 30px;">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Kamar</h3>
                        <div class="number">48</div>
                    </div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Terisi</h3>
                        <div class="number">42</div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom-color: #A78BFA;">
                    <div class="stat-icon" style="background: #A78BFA;">
                        <i class="fas fa-hammer"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Kosong</h3>
                        <div class="number">4</div>
                    </div>
                </div>
                <div class="stat-card pink">
                    <div class="stat-icon" style="background: #F472B6;">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Maintenance</h3>
                        <div class="number">2</div>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-container">
                <div class="table-header">
                    <h3 class="table-title">Daftar Kamar</h3>
                    <div class="table-actions">
                        <input type="text" class="search-box"
                            placeholder="Cari berdasarkan nomor kamar atau penyewa...">
                        <button class="filter-btn">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No. Kamar</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Penyewa</th>
                            <th>Harga</th>
                            <th>Lantai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>01A</strong></td>
                            <td>Standard</td>
                            <td><span class="badge success">Terisi</span></td>
                            <td>Ahmad Rifai</td>
                            <td>Rp 1.5jt</td>
                            <td>1</td>
                            <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                        </tr>
                        <tr>
                            <td><strong>02A</strong></td>
                            <td>Standard</td>
                            <td><span class="badge success">Terisi</span></td>
                            <td>Siti Nurhaliza</td>
                            <td>Rp 1.5jt</td>
                            <td>1</td>
                            <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                        </tr>
                        <tr>
                            <td><strong>03A</strong></td>
                            <td>Standard</td>
                            <td><span class="badge warning">Maintenance</span></td>
                            <td>-</td>
                            <td>Rp 1.5jt</td>
                            <td>1</td>
                            <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                        </tr>
                        <tr>
                            <td><strong>04A</strong></td>
                            <td>Deluxe</td>
                            <td><span class="badge danger">Kosong</span></td>
                            <td>-</td>
                            <td>Rp 2.2jt</td>
                            <td>1</td>
                            <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                        </tr>
                        <tr>
                            <td><strong>05A</strong></td>
                            <td>Deluxe</td>
                            <td><span class="badge success">Terisi</span></td>
                            <td>Rina Wijaya</td>
                            <td>Rp 2.2jt</td>
                            <td>1</td>
                            <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                        </tr>
                        <tr>
                            <td><strong>06B</strong></td>
                            <td>Premium</td>
                            <td><span class="badge success">Terisi</span></td>
                            <td>Budi Santoso</td>
                            <td>Rp 3.0jt</td>
                            <td>2</td>
                            <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                        </tr>
                        <tr>
                            <td><strong>07B</strong></td>
                            <td>Standard</td>
                            <td><span class="badge danger">Kosong</span></td>
                            <td>-</td>
                            <td>Rp 1.5jt</td>
                            <td>2</td>
                            <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                        </tr>
                        <tr>
                            <td><strong>08B</strong></td>
                            <td>Deluxe</td>
                            <td><span class="badge success">Terisi</span></td>
                            <td>Lisa Permata</td>
                            <td>Rp 2.2jt</td>
                            <td>2</td>
                            <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- MANAJEMEN PENYEWA PAGE -->
        <div class="page" id="manajemen-penyewa">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Manajemen Penyewa</h1>
                    <p class="page-subtitle">Kelola data penyewa kos-kosan Anda</p>
                </div>
                <button class="btn-primary" onclick="openAddTenantModal()">
                    <i class="fas fa-plus"></i>
                    Tambah Penyewa
                </button>
            </div>

            <!-- STAT CARDS -->
            <div class="stats-grid" style="margin-bottom: 30px;">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Penyewa</h3>
                        <div class="number">42</div>
                    </div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon" style="background: #10B981;">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Aktif</h3>
                        <div class="number">38</div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom-color: #F472B6;">
                    <div class="stat-icon" style="background: #F472B6;">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Menunggak</h3>
                        <div class="number">4</div>
                    </div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-icon" style="background: #A78BFA;">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Baru Bulan Ini</h3>
                        <div class="number">5</div>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-container">
                <div class="table-header">
                    <h3 class="table-title">Daftar Penyewa</h3>
                    <div class="table-actions">
                        <input type="text" class="search-box" placeholder="Cari berdasarkan nama atau nomor kamar...">
                        <button class="filter-btn">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Penyewa</th>
                            <th>Kamar</th>
                            <th>Kontak</th>
                            <th>Check-in</th>
                            <th>Pembayaran Terakhir</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="width: 35px; height: 35px; border-radius: 50%; background: #667eea; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                        A</div>
                                    <div>
                                        <p style="margin: 0; font-weight: 600;">Ahmad Rifai</p>
                                        <p style="margin: 3px 0 0 0; font-size: 12px; color: #999;">ahmad@email.com</p>
                                    </div>
                                </div>
                            </td>
                            <td>Kamar 01A</td>
                            <td>0812-3456-7890</td>
                            <td>01 Jan 2024</td>
                            <td>01 Nov 2024</td>
                            <td><span class="badge success">Aktif</span></td>
                            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
                        </tr>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="width: 35px; height: 35px; border-radius: 50%; background: #06B6D4; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                        S</div>
                                    <div>
                                        <p style="margin: 0; font-weight: 600;">Siti Nurhaliza</p>
                                        <p style="margin: 3px 0 0 0; font-size: 12px; color: #999;">siti@email.com</p>
                                    </div>
                                </div>
                            </td>
                            <td>Kamar 02A</td>
                            <td>0813-4567-8901</td>
                            <td>15 Feb 2024</td>
                            <td>01 Nov 2024</td>
                            <td><span class="badge success">Aktif</span></td>
                            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
                        </tr>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="width: 35px; height: 35px; border-radius: 50%; background: #10B981; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                        B</div>
                                    <div>
                                        <p style="margin: 0; font-weight: 600;">Budi Santoso</p>
                                        <p style="margin: 3px 0 0 0; font-size: 12px; color: #999;">budi@email.com</p>
                                    </div>
                                </div>
                            </td>
                            <td>Kamar 06B</td>
                            <td>0814-5678-9012</td>
                            <td>10 Mar 2024</td>
                            <td>28 Oct 2024</td>
                            <td><span class="badge success">Aktif</span></td>
                            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
                        </tr>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="width: 35px; height: 35px; border-radius: 50%; background: #F472B6; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                        R</div>
                                    <div>
                                        <p style="margin: 0; font-weight: 600;">Rina Wijaya</p>
                                        <p style="margin: 3px 0 0 0; font-size: 12px; color: #999;">rina@email.com</p>
                                    </div>
                                </div>
                            </td>
                            <td>Kamar 05A</td>
                            <td>0815-6789-0123</td>
                            <td>20 Apr 2024</td>
                            <td>01 Oct 2024</td>
                            <td><span class="badge danger">Menunggak</span></td>
                            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
                        </tr>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="width: 35px; height: 35px; border-radius: 50%; background: #A78BFA; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                        L</div>
                                    <div>
                                        <p style="margin: 0; font-weight: 600;">Lisa Permata</p>
                                        <p style="margin: 3px 0 0 0; font-size: 12px; color: #999;">lisa@email.com</p>
                                    </div>
                                </div>
                            </td>
                            <td>Kamar 08B</td>
                            <td>0816-7890-1234</td>
                            <td>05 May 2024</td>
                            <td>01 Nov 2024</td>
                            <td><span class="badge success">Aktif</span></td>
                            <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- LAPORAN KEUANGAN PAGE -->
        <div class="page" id="laporan-keuangan">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Laporan Keuangan</h1>
                    <p class="page-subtitle">Pantau pemasukan dan pengeluaran bisnis Anda</p>
                </div>
                <button class="btn-primary">
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
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 30px;">
                <div class="chart-container">
                    <h3>Pemasukan vs Pengeluaran</h3>
                    <div
                        style="height: 300px; display: flex; align-items: flex-end; justify-content: space-around; padding: 20px 0;">
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div
                                style="width: 40px; height: 200px; background: #10B981; border-radius: 4px 4px 0 0; margin-bottom: 10px;">
                            </div>
                            <span style="font-size: 12px; color: #10B981; font-weight: 600;">Jan</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div
                                style="width: 40px; height: 180px; background: #10B981; border-radius: 4px 4px 0 0; margin-bottom: 10px;">
                            </div>
                            <span style="font-size: 12px; color: #10B981; font-weight: 600;">Feb</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div
                                style="width: 40px; height: 190px; background: #10B981; border-radius: 4px 4px 0 0; margin-bottom: 10px;">
                            </div>
                            <span style="font-size: 12px; color: #10B981; font-weight: 600;">Mar</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div
                                style="width: 40px; height: 210px; background: #10B981; border-radius: 4px 4px 0 0; margin-bottom: 10px;">
                            </div>
                            <span style="font-size: 12px; color: #10B981; font-weight: 600;">Apr</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div
                                style="width: 40px; height: 225px; background: #10B981; border-radius: 4px 4px 0 0; margin-bottom: 10px;">
                            </div>
                            <span style="font-size: 12px; color: #10B981; font-weight: 600;">May</span>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: center;">
                            <div
                                style="width: 40px; height: 235px; background: #10B981; border-radius: 4px 4px 0 0; margin-bottom: 10px;">
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

        <!-- PENGATURAN PAGE -->
        <div class="page" id="pengaturan">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Pengaturan</h1>
                    <p class="page-subtitle">Kelola preferensi dan konfigurasi sistem</p>
                </div>
            </div>

            <div class="table-container" style="max-width: 500px;">
                <div style="padding: 30px;">
                    <h3 style="margin-bottom: 20px;">Pengaturan Umum</h3>

                    <div class="form-group">
                        <label>Nama Usaha</label>
                        <input type="text" value="KosKita" readonly>
                    </div>

                    <div class="form-group">
                        <label>Email Admin</label>
                        <input type="email" value="admin@koskita.com" readonly>
                    </div>

                    <div class="form-group">
                        <label>Tema</label>
                        <select>
                            <option selected>Light</option>
                            <option>Dark</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Bahasa</label>
                        <select>
                            <option selected>Bahasa Indonesia</option>
                            <option>English</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 30px;">
                        <button class="btn-submit">Simpan Perubahan</button>
                        <button class="btn-cancel">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODALS -->
    <div class="modal" id="addRoomModal">
        <div class="modal-content">
            <div class="modal-header">Tambah Kamar Baru</div>
            <form onsubmit="handleAddRoom(event)">
                <div class="form-group">
                    <label>Nomor Kamar</label>
                    <input type="text" required>
                </div>
                <div class="form-group">
                    <label>Tipe Kamar</label>
                    <select required>
                        <option value="">Pilih Tipe</option>
                        <option>Standard</option>
                        <option>Deluxe</option>
                        <option>Premium</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Harga/Bulan</label>
                    <input type="number" required>
                </div>
                <div class="form-group">
                    <label>Lantai</label>
                    <input type="number" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeAddRoomModal()">Batal</button>
                    <button type="submit" class="btn-submit">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="addTenantModal">
        <div class="modal-content">
            <div class="modal-header">Tambah Penyewa Baru</div>
            <form onsubmit="handleAddTenant(event)">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" required>
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="tel" required>
                </div>
                <div class="form-group">
                    <label>Kamar</label>
                    <select required>
                        <option value="">Pilih Kamar</option>
                        <option>01A</option>
                        <option>02A</option>
                        <option>05A</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeAddTenantModal()">Batal</button>
                    <button type="submit" class="btn-submit">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    @include('components.chatbot')
    <script src="{{ asset('js/chatbot.js') }}"></script>
    <script src="{{ asset('js/admin.js') }}"></script>
</body>

</html>