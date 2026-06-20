<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Kamar - KosKita</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* BOOKING PAGE STYLES */
        .btn-back-clean {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #666;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #E0E0E0;
            background: white;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .btn-back-clean:hover {
            color: #5B5EFF;
            border-color: #5B5EFF;
            background: #F8F9FF;
            transform: translateY(-1px);
            box-shadow: 0 3px 8px rgba(91, 94, 255, 0.1);
        }

        .booking-container {
            min-height: 100vh;
            background: linear-gradient(135deg, #E8EAFF 0%, #F5F5FF 100%);
            padding: 40px 20px;
        }

        .booking-wrapper {
            max-width: 900px;
            margin: 0 auto;
        }

        /* STEP INDICATOR */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 50px;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 0;
            right: 0;
            height: 2px;
            background: #E0E0E0;
            z-index: 0;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 1;
            flex: 1;
        }

        .step-number {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #FFFFFF;
            border: 2px solid #DDD;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #999;
            transition: all 0.3s ease;
        }

        .step-item.active .step-number {
            background: #5B5EFF;
            color: white;
            border-color: #5B5EFF;
        }

        .step-item.completed .step-number {
            background: #10B981;
            color: transparent !important;
            border-color: #10B981;
            font-size: 0 !important;
        }

        .step-item.completed .step-number::after {
            content: '✓';
            color: white !important;
            font-size: 20px !important;
        }

        .step-name {
            font-size: 13px;
            font-weight: 600;
            color: #666;
            text-align: center;
        }

        .step-item.active .step-name {
            color: #5B5EFF;
        }

        /* CONTENT AREA */
        .booking-content {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            min-height: 500px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            color: #5B5EFF;
            text-align: center;
        }

        .page-subtitle {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-bottom: 30px;
        }

        /* ROOM SELECTION */
        .rooms-selection {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .room-selection-card {
            border: 2px solid #E0E0E0;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .room-selection-card:hover {
            border-color: #5B5EFF;
            box-shadow: 0 4px 12px rgba(91, 94, 255, 0.15);
        }

        .room-selection-card.selected {
            border-color: #5B5EFF;
            background: #F0F0F7;
        }

        .room-selection-card.selected::after {
            content: '✓';
            position: absolute;
            top: 10px;
            right: 10px;
            width: 30px;
            height: 30px;
            background: #5B5EFF;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .room-card-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            position: relative;
        }

        .room-card-content {
            padding: 15px;
        }

        .room-card-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #000;
        }

        .room-card-info {
            font-size: 12px;
            color: #666;
            margin-bottom: 3px;
        }

        .room-card-price {
            font-size: 14px;
            color: #5B5EFF;
            font-weight: 600;
            margin-top: 10px;
        }

        .room-badge {
            display: inline-block;
            background: #5B5EFF;
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        /* FORM STYLES */
        .booking-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .form-group label {
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            padding: 12px;
            border: 1px solid #DDD;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #5B5EFF;
            box-shadow: 0 0 0 3px rgba(91, 94, 255, 0.1);
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* SELECTED ROOM DISPLAY */
        .selected-room-display {
            background: #F0F0F7;
            border-left: 4px solid #5B5EFF;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .selected-room-display h3 {
            margin: 0 0 8px 0;
            color: #5B5EFF;
            font-size: 14px;
        }

        .selected-room-display p {
            margin: 0;
            color: #666;
            font-size: 13px;
        }

        /* PAYMENT METHOD */
        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .payment-method {
            border: 2px solid #E0E0E0;
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
        }

        .payment-method:hover {
            border-color: #5B5EFF;
            background: #F0F0F7;
        }

        .payment-method.selected {
            border-color: #5B5EFF;
            background: #5B5EFF;
            color: white;
        }

        .payment-method i {
            font-size: 24px;
            margin-bottom: 8px;
            display: block;
        }

        .payment-method span {
            font-size: 12px;
            font-weight: 600;
        }

        /* SUMMARY */
        .booking-summary {
            background: #F0F0F7;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .summary-row.total {
            border-top: 1px solid #DDD;
            padding-top: 10px;
            font-weight: 600;
            color: #5B5EFF;
            font-size: 16px;
        }

        .summary-label {
            color: #666;
        }

        .summary-value {
            color: #333;
            font-weight: 500;
        }

        /* BUTTONS */
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            justify-content: space-between;
        }

        .button-group .btn {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-back {
            background: #E0E0E0;
            color: #333;
        }

        .btn-back:hover {
            background: #D0D0D0;
        }

        .btn-next {
            background: #5B5EFF;
            color: white;
        }

        .btn-next:hover {
            background: #4A4DE6;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(91, 94, 255, 0.3);
        }

        .btn-pay {
            width: 100%;
            background: #10B981;
            color: white;
            padding: 14px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-pay:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }

        .btn-cancel {
            width: 100%;
            background: #DC2626;
            color: white;
            padding: 14px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            background: #B91C1C;
        }

        /* PAYMENT INFO */
        .payment-info {
            background: #FEF3C7;
            border-left: 4px solid #FBBF24;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #78350F;
        }

        .payment-info a {
            color: #D97706;
            text-decoration: none;
            font-weight: 600;
        }

        /* HIDDEN */
        .hidden {
            display: none;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .booking-wrapper {
                padding: 0 10px;
            }

            .booking-content {
                padding: 25px;
            }

            .step-indicator {
                margin-bottom: 30px;
            }

            .step-number {
                width: 40px;
                height: 40px;
                font-size: 13px;
            }

            .step-name {
                font-size: 11px;
            }

            .rooms-selection {
                grid-template-columns: 1fr;
            }

            .payment-methods {
                grid-template-columns: repeat(2, 1fr);
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <a href="{{ route('home') }}" class="navbar-logo" style="text-decoration: none; color: inherit;">
                    <i class="fas fa-home"></i>
                    <span>KosKita</span>
                </a>
                <div class="navbar-menu">
                    <a href="{{ route('home') }}#beranda">Beranda</a>
                    <a href="{{ route('home') }}#kamar">Kamar</a>
                    <a href="{{ route('home') }}#fasilitas">Fasilitas</a>
                    <a href="{{ route('home') }}#kontak">Kontak</a>
                </div>
                <div class="navbar-actions">
                    <a href="{{ route('home') }}" class="btn-back-clean">
                        <i class="fas fa-chevron-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- BOOKING CONTAINER -->
    <div class="booking-container">
        <div class="booking-wrapper">
            <!-- STEP INDICATOR -->
            <div class="step-indicator">
                <div class="step-item active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-name">Pilih Kamar</div>
                </div>
                <div class="step-item" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-name">Data Diri</div>
                </div>
                <div class="step-item" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-name">Pembayaran</div>
                </div>
            </div>

            <!-- CONTENT AREA -->
            <div class="booking-content">
                @include('booking.pilih-kamar')
                @include('booking.data-diri')
                @include('booking.pembayaran')
            </div>
        </div>
    </div>

    <script>
        // ============================================
        // BOOKING DATA & STATE
        // ============================================
        const bookingData = {
            selectedRoom: null,
            customerName: '',
            customerEmail: '',
            customerPhone: '',
            customerMessage: '',
            paymentMethod: null,
            currentStep: 1
        };

        const rooms = [
            {
                id: 1,
                name: 'Kamar Standard',
                price: 300000,
                priceText: 'Rp 300k',
                size: 'Ukuran 3x4 m',
                capacity: 'Kapasitas 1 orang',
                image: '{{ asset('asset/kamar.png') }}',
                bathroom: 'Kamar Mandi Luar',
                badge: 'New',
                badgeClass: 'room-tag'
            },
            {
                id: 2,
                name: 'Kamar Deluxe',
                price: 550000,
                priceText: 'Rp 550k',
                size: 'Ukuran 4x5 m',
                capacity: 'Kapasitas 1 orang',
                image: '{{ asset('asset/kamar.png') }}',
                bathroom: 'Kamar Mandi Dalam',
                badge: 'Popular',
                badgeClass: 'room-tag featured'
            },
        ];

        // ============================================
        // UPDATE SELECTED ROOM DISPLAY
        // ============================================
        function updateSelectedRoomDisplay() {
            if (!bookingData.selectedRoom) return;

            const room = bookingData.selectedRoom;
            const displayHTML = `
                <h3>Kamar yang Dipilih</h3>
                <p><strong>${room.name}</strong> - ${formatPrice(room.price)}/bulan</p>
            `;

            const display1 = document.getElementById('selectedRoomDisplay');
            if (display1) display1.innerHTML = displayHTML;

            const display2 = document.getElementById('selectedRoomDisplay2');
            if (display2) display2.innerHTML = displayHTML;

            // Update summary
            document.getElementById('summaryRoom').textContent = room.name;
            document.getElementById('summaryPrice').textContent = formatPrice(room.price);
            document.getElementById('summaryTotal').textContent = formatPrice(room.price);
        }

        // ============================================
        // STEP NAVIGATION
        // ============================================
        function nextStep(currentStep) {
            if (currentStep === 1) {
                if (!bookingData.selectedRoom) {
                    showNotification('Silakan pilih kamar terlebih dahulu', 'error');
                    return;
                }
            }

            if (currentStep === 2) {
                const nama = document.getElementById('nama').value.trim();
                const email = document.getElementById('email').value.trim();
                const telepon = document.getElementById('telepon').value.trim();

                if (!nama || !email || !telepon) {
                    showNotification('Mohon lengkapi semua field yang diperlukan', 'error');
                    return;
                }

                if (!validateEmail(email)) {
                    showNotification('Format email tidak valid', 'error');
                    return;
                }

                bookingData.customerName = nama;
                bookingData.customerEmail = email;
                bookingData.customerPhone = telepon;
                bookingData.customerMessage = document.getElementById('pesan').value.trim();

                document.getElementById('displayNama').textContent = nama;
                document.getElementById('displayEmail').textContent = email;
                document.getElementById('displayTelepon').textContent = telepon;
                document.getElementById('displayPesan').textContent = bookingData.customerMessage || '-';
            }

            document.getElementById(`step${currentStep}`).classList.remove('active');
            document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.remove('active');
            document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.add('completed');

            const nextStepNumber = currentStep + 1;
            document.getElementById(`step${nextStepNumber}`).classList.add('active');
            document.querySelector(`.step-item[data-step="${nextStepNumber}"]`).classList.add('active');

            bookingData.currentStep = nextStepNumber;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function previousStep(currentStep) {
            const previousStepNumber = currentStep - 1;

            document.getElementById(`step${currentStep}`).classList.remove('active');
            document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.remove('active');
            document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.remove('completed');

            document.getElementById(`step${previousStepNumber}`).classList.add('active');
            document.querySelector(`.step-item[data-step="${previousStepNumber}"]`).classList.add('active');

            bookingData.currentStep = previousStepNumber;
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // ============================================
        // MODAL FUNCTIONS
        // ============================================
        function showLoadingModal() {
            const modal = document.createElement('div');
            modal.id = 'loadingModal';
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
            `;

            modal.innerHTML = `
                <div style="background: white; border-radius: 12px; padding: 40px; text-align: center; max-width: 400px;">
                    <div style="font-size: 48px; margin-bottom: 20px;">
                        <i class="fas fa-spinner" style="animation: spin 1s linear infinite;"></i>
                    </div>
                    <h3 style="margin-bottom: 10px; color: #333;">Memproses Pembayaran...</h3>
                    <p style="color: #666; font-size: 14px;">Mohon tunggu sebentar</p>
                </div>
            `;

            document.body.appendChild(modal);

            const style = document.createElement('style');
            style.textContent = `
                @keyframes spin {
                    to { transform: rotate(360deg); }
                }
            `;
            document.head.appendChild(style);
        }

        function hideLoadingModal() {
            const modal = document.getElementById('loadingModal');
            if (modal) modal.remove();
        }

        function showSuccessModal() {
            const modal = document.createElement('div');
            modal.id = 'successModal';
            modal.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.6);
                display: flex;
                align-items: center;
                justify-content: center;
                z-index: 10000;
            `;

            const room = bookingData.selectedRoom;

            modal.innerHTML = `
                <div style="background: white; border-radius: 12px; padding: 40px; text-align: center; max-width: 500px;">
                    <div style="font-size: 60px; color: #10B981; margin-bottom: 20px;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2 style="margin-bottom: 10px; color: #333;">Booking Berhasil!</h2>
                    <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
                        Terima kasih telah memesan <strong>${room.name}</strong>
                    </p>
                    
                    <div style="background: #F0F0F7; border-radius: 8px; padding: 15px; margin-bottom: 20px; text-align: left; font-size: 13px;">
                        <p style="margin: 5px 0;"><strong>Nama:</strong> ${bookingData.customerName}</p>
                        <p style="margin: 5px 0;"><strong>Email:</strong> ${bookingData.customerEmail}</p>
                        <p style="margin: 5px 0;"><strong>Telepon:</strong> ${bookingData.customerPhone}</p>
                        <p style="margin: 5px 0;"><strong>Kamar:</strong> ${room.name}</p>
                        <p style="margin: 5px 0;"><strong>Harga:</strong> ${formatPrice(room.price)}/bulan</p>
                        <p style="margin: 5px 0;"><strong>Metode Pembayaran:</strong> ${getPaymentMethodName(bookingData.paymentMethod)}</p>
                    </div>

                    <p style="color: #10B981; font-size: 13px; margin-bottom: 20px;">
                        ✓ Booking Anda telah dikonfirmasi. Cek email untuk detail selengkapnya.
                    </p>

                    <button onclick="goBackHome()" style="
                        background: #5B5EFF;
                        color: white;
                        border: none;
                        padding: 12px 30px;
                        border-radius: 8px;
                        font-weight: 600;
                        cursor: pointer;
                        width: 100%;
                        transition: all 0.3s ease;
                    " onmouseover="this.style.background='#4A4DE6'" onmouseout="this.style.background='#5B5EFF'">
                        Kembali ke Beranda
                    </button>
                </div>
            `;

            document.body.appendChild(modal);
        }

        const HOME_URL = @json(route('home'));

        function goBackHome() {
            window.location.href = HOME_URL;
        }

        function cancelBooking() {
            if (confirm('Apakah Anda yakin ingin membatalkan booking ini?')) {
                window.location.href = HOME_URL;
            }
        }

        // ============================================
        // UTILITY FUNCTIONS
        // ============================================
        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(price);
        }

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function getPaymentMethodName(method) {
            const methods = {
                'cash': 'Cash',
                'transfer': 'Transfer Bank',
                'ewallet': 'E-Wallet',
                'cc': 'Kartu Kredit'
            };
            return methods[method] || '-';
        }

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

        function setupEventListeners() {
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    // Close any modals if any
                }
            });
        }

        // Add animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(400px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOut {
                to { transform: translateX(400px); opacity: 0; }
            }
        `;
        document.head.appendChild(style);

        document.addEventListener('DOMContentLoaded', function() {
            setupEventListeners();
        });
    </script>
</body>
</html>