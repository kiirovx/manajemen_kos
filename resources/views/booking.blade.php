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
            color: white;
            border-color: #10B981;
        }

        .step-item.completed .step-number::after {
            content: '✓';
            font-size: 20px;
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
                <div class="navbar-logo">
                    <i class="fas fa-home"></i>
                    <span>KosKita</span>
                </div>
                <div class="navbar-menu">
                    <a href="index.html#beranda">Beranda</a>
                    <a href="index.html#kamar">Kamar</a>
                    <a href="index.html#fasilitas">Fasilitas</a>
                    <a href="index.html#kontak">Kontak</a>
                </div>
                <div class="navbar-actions">
                    <button class="btn btn-primary" onclick="window.history.back()">Kembali</button>
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
                <!-- STEP 1: PILIH KAMAR -->
                <div class="form-section active" id="step1">
                    <h2 class="page-title">Pilihan Kos yang sesuai dengan Lokasi dan Budget Anda</h2>
                    <p class="page-subtitle">Temukan kamar yang sesuai dengan kebutuhan dan budget Anda</p>

                    <div class="rooms-selection" id="roomsSelection">
                        <!-- Room cards akan diisi via JavaScript -->
                    </div>

                    <div class="button-group">
                        <button class="btn btn-next" onclick="nextStep(1)" disabled id="btnNext1">
                            Lanjutkan <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                        </button>
                    </div>
                </div>

                <!-- STEP 2: ISI DATA DIRI -->
                <div class="form-section" id="step2">
                    <h2 class="page-title">Booking Sekarang</h2>
                    <p class="page-subtitle">Isi Form dibawah ini untuk Booking Kamar</p>

                    <div class="selected-room-display" id="selectedRoomDisplay"></div>

                    <form class="booking-form" onsubmit="nextStep(2); return false;">
                        <div class="form-group">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Alamat Email</label>
                            <input type="email" id="email" name="email" placeholder="nama@email.com" required>
                        </div>

                        <div class="form-group">
                            <label for="telepon">Nomor Telepon</label>
                            <input type="tel" id="telepon" name="telepon" placeholder="+62 812-3456-7890" required>
                        </div>

                        <div class="form-group">
                            <label for="pesan">Pesan</label>
                            <textarea id="pesan" name="pesan" placeholder="Tulis pesan Anda di sini..."></textarea>
                        </div>

                        <div class="button-group">
                            <button type="button" class="btn btn-back" onclick="previousStep(2)">
                                <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Kembali
                            </button>
                            <button type="submit" class="btn btn-next">
                                Lanjutkan <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- STEP 3: PEMBAYARAN -->
                <div class="form-section" id="step3">
                    <h2 class="page-title">Pembayaran</h2>

                    <div class="selected-room-display" id="selectedRoomDisplay2"></div>

                    <div style="background: white; border-radius: 8px; padding: 20px; margin-bottom: 20px; border: 1px solid #E0E0E0;">
                        <h3 style="margin-top: 0; color: #333;">Pendaftar Kamar</h3>

                        <div style="font-size: 13px; margin-bottom: 15px;">
                            <p style="margin: 8px 0;"><strong>Nama:</strong> <span id="displayNama"></span></p>
                            <p style="margin: 8px 0;"><strong>Email:</strong> <span id="displayEmail"></span></p>
                            <p style="margin: 8px 0;"><strong>Telepon:</strong> <span id="displayTelepon"></span></p>
                            <p style="margin: 8px 0;"><strong>Pesan:</strong> <span id="displayPesan"></span></p>
                        </div>
                    </div>

                    <div style="background: white; border-radius: 8px; padding: 20px; border: 1px solid #E0E0E0; margin-bottom: 20px;">
                        <h3 style="margin-top: 0; color: #333;">Metode Pembayaran</h3>
                        <div class="payment-methods">
                            <div class="payment-method" onclick="selectPayment('cash', this)">
                                <i class="fas fa-money-bill"></i>
                                <span>Cash</span>
                            </div>
                            <div class="payment-method" onclick="selectPayment('transfer', this)">
                                <i class="fas fa-bank"></i>
                                <span>Transfer Bank</span>
                            </div>
                            <div class="payment-method" onclick="selectPayment('ewallet', this)">
                                <i class="fas fa-wallet"></i>
                                <span>E-Wallet</span>
                            </div>
                            <div class="payment-method" onclick="selectPayment('cc', this)">
                                <i class="fas fa-credit-card"></i>
                                <span>Kartu Kredit</span>
                            </div>
                        </div>
                    </div>

                    <div class="booking-summary">
                        <div class="summary-row">
                            <span class="summary-label">Nama Kamar:</span>
                            <span class="summary-value" id="summaryRoom">-</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Harga/Bulan:</span>
                            <span class="summary-value" id="summaryPrice">-</span>
                        </div>
                        <div class="summary-row total">
                            <span>Total:</span>
                            <span id="summaryTotal">-</span>
                        </div>
                    </div>

                    <div class="payment-info">
                        <strong>ℹ️ Informasi:</strong> Dengan menyelesaikan pembayaran, Anda menyetujui <a href="#">Syarat dan Ketentuan</a> kami.
                    </div>

                    <div class="button-group" style="flex-direction: column; gap: 10px;">
                        <button class="btn-pay" onclick="processPayment()">
                            <i class="fas fa-check-circle" style="margin-right: 8px;"></i> Bayar Sekarang
                        </button>
                        <button class="btn-cancel" onclick="cancelBooking()">Batal</button>
                    </div>

                    <button type="button" class="btn btn-back" onclick="previousStep(3)" style="width: 100%; margin-top: 10px;">
                        <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Kembali
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/booking.js') }}"></script>
</body>
</html>