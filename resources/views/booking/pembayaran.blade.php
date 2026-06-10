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

<script>
    // ============================================
    // PAYMENT METHOD SELECTION
    // ============================================
    function selectPayment(method, element) {
        document.querySelectorAll('.payment-method').forEach(pm => {
            pm.classList.remove('selected');
        });

        element.classList.add('selected');
        bookingData.paymentMethod = method;
    }

    // ============================================
    // PROCESS PAYMENT
    // ============================================
    function processPayment() {
        if (!bookingData.paymentMethod) {
            showNotification('Silakan pilih metode pembayaran', 'error');
            return;
        }

        showLoadingModal();

        setTimeout(() => {
            hideLoadingModal();
            showSuccessModal();
        }, 2000);
    }
</script>
