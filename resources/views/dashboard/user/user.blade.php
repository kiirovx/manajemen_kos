<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

        /* TABLE RESPONSIVE */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* MOBILE HEADER & OVERLAY */
        .mobile-header {
            display: none;
            background: white;
            padding: 15px 20px;
            border-bottom: 1px solid #E0E0E0;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .menu-btn {
            background: none;
            border: none;
            font-size: 20px;
            color: #667eea;
            cursor: pointer;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .menu-btn:hover {
            background: #F5F5F7;
        }

        .mobile-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #667eea;
            text-decoration: none;
            font-weight: 700;
            font-size: 18px;
        }

        .mobile-logo i {
            font-size: 20px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1001;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .user-container {
                grid-template-columns: 1fr;
            }

            .user-sidebar {
                position: fixed;
                width: 280px;
                left: 0;
                top: 0;
                height: 100vh;
                z-index: 1002;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            }

            .user-sidebar.show {
                transform: translateX(0);
            }

            .user-main {
                margin-left: 0;
                padding: 20px;
                padding-top: 20px;
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

            .mobile-header {
                display: flex;
            }

            .card-header {
                flex-wrap: wrap;
                gap: 10px;
            }
        }

        @media (max-width: 480px) {
            .user-sidebar {
                width: 280px;
                max-width: 85%;
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
    <!-- MOBILE HEADER -->
    <div class="mobile-header">
        <button class="menu-btn" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="mobile-logo">
            <i class="fas fa-home"></i>
            <span>KosKita</span>
        </div>
        <div style="width: 32px;"></div>
    </div>

    <!-- SIDEBAR BACKDROP -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- SIDEBAR -->
    <div class="user-sidebar">
        <a href="{{ route('home') }}" class="sidebar-header">
            <i class="fas fa-home"></i>
            <span>KosKita</span>
        </a>

        <!-- PROFILE CARD -->
        <div class="user-profile-card">
            <div class="user-avatar">👤</div>
            <h3 id="userNameDisplay">{{ $user->name }}</h3>
            <p id="userRoomDisplay">Kamar {{ $user->tenantProfile?->room?->number ?? '-' }}</p>
            <div class="user-room-info">
                <strong>Status:</strong>
                Aktif
                <br>
                <strong>Sejak:</strong>
                {{ $user->tenantProfile?->lease_start?->format('d M Y') ?? '-' }}
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
            <form action="{{ route('logout') }}" method="POST" onsubmit="localStorage.removeItem('currentUser');">
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
                @include('dashboard.user.dashboard')
        @include('dashboard.user.data-pribadi')
        @include('dashboard.user.pembayaran')
        @include('dashboard.user.notifikasi')
        @include('dashboard.user.maintenance')
        @include('dashboard.user.riwayat')
        @include('dashboard.user.bantuan')
    </div>

    @include('dashboard.user.modals')

    <script>
        // Set currentUser in localStorage from Laravel Auth session
        @if(Auth::check())
            localStorage.setItem('currentUser', JSON.stringify({
                id: {{ Auth::user()->id }},
                name: "{{ Auth::user()->name }}",
                email: "{{ Auth::user()->email }}",
                role: "{{ Auth::user()->role }}",
                loginTime: new Date().toISOString()
            }));
        @endif
    </script>
    <script>
        // ============================================
        // AUTHENTICATION CHECK
        // ============================================
        const APP_ROUTES = {
            login: @json(route('login.page')),
            home: @json(route('home')),
            adminDashboard: @json(route('dashboard.admin')),
        };

        function checkUserAuth() {
            const authData = localStorage.getItem('currentUser');
            if (!authData) {
                window.location.href = APP_ROUTES.login;
                return false;
            }

            const user = JSON.parse(authData);

            if (user.role !== 'user') {
                window.location.href = user.role === 'admin' ? APP_ROUTES.adminDashboard : APP_ROUTES.login;
                return false;
            }

            return user;
        }

        function logoutUser() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                localStorage.removeItem('currentUser');
                localStorage.removeItem('adminAuth');
                window.location.href = APP_ROUTES.login;
            }
        }

        // ============================================
        // SIDEBAR TOGGLE
        // ============================================
        function toggleSidebar() {
            const sidebar = document.querySelector('.user-sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('show');
            if (sidebar.classList.contains('show')) {
                overlay.style.display = 'block';
                setTimeout(() => overlay.classList.add('show'), 10);
            } else {
                overlay.classList.remove('show');
                setTimeout(() => overlay.style.display = 'none', 300);
            }
        }

        // ============================================
        // PAGE NAVIGATION
        // ============================================
        function showUserPage(pageName) {
            const targetPage = document.getElementById(pageName);
            if (!targetPage) return;

            document.querySelectorAll('.page').forEach(page => {
                page.classList.remove('active');
            });

            targetPage.classList.add('active');

            document.querySelectorAll('.user-menu-item').forEach(item => {
                item.classList.remove('active');
            });

            const activeNav = document.querySelector(`.user-menu-item[onclick*="${pageName}"]`);
            if (activeNav) {
                activeNav.classList.add('active');
            }

            // Close sidebar on mobile
            const sidebar = document.querySelector('.user-sidebar');
            if (sidebar.classList.contains('show')) {
                toggleSidebar();
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
            if (window.location.hash !== `#${pageName}`) {
                history.replaceState(null, '', `#${pageName}`);
            }
        }

        // ============================================
        // UTILITY FUNCTIONS
        // ============================================
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 20px;
                border-radius: 8px;
                background: ${type === 'success' ? '#10B981' : '#EF4444'};
                color: white;
                z-index: 9999;
                font-size: 14px;
                font-weight: 600;
                animation: slideIn 0.3s ease-out;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            `;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // ============================================
        // INITIALIZE
        // ============================================
        document.addEventListener('DOMContentLoaded', function() {
            const userData = checkUserAuth();
            if (!userData) return;

            const userNameEl  = document.getElementById('userName');
            const userEmailEl = document.getElementById('userEmail');
            if (userNameEl)  userNameEl.textContent  = userData.name;
            if (userEmailEl) userEmailEl.textContent = userData.email;

            // Add animations style
            const style = document.createElement('style');
            style.textContent = `
                @keyframes slideIn {
                    from { transform: translateX(400px); opacity: 0; }
                    to   { transform: translateX(0);     opacity: 1; }
                }
                @keyframes slideOut {
                    to   { transform: translateX(400px); opacity: 0; }
                }
            `;
            document.head.appendChild(style);

            // Close modals when clicking outside
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) this.classList.remove('show');
                });
            });

            // Close modals with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('.modal.show').forEach(modal => {
                        modal.classList.remove('show');
                    });
                }
            });
        });
    </script>
</body>

</html>