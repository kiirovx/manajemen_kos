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
            <div class="payment-method selected" onclick="selectPayment('midtrans', this)">
                <i class="fas fa-credit-card"></i>
                <span>Midtrans</span>
            </div>
            <div class="payment-method" onclick="selectPayment('cash', this)">
                <i class="fas fa-money-bill"></i>
                <span>Cash</span>
            </div>
        </div>
        <p style="font-size: 12px; color: #666; margin-top: 10px;">
            <i class="fas fa-shield-alt" style="color: #5B5EFF;"></i> 
            Pembayaran melalui Midtrans: QRIS, Transfer Bank, E-Wallet, Virtual Account, Kartu Kredit.
        </p>
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
        <button class="btn-pay" onclick="processPayment()" id="btnPayNow">
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
    // PROCESS PAYMENT (Kirim data ke server + Midtrans)
    // ============================================
    async function processPayment() {
        if (!bookingData.paymentMethod) {
            showNotification('Silakan pilih metode pembayaran', 'error');
            return;
        }

        if (!bookingData.selectedRoom) {
            showNotification('Silakan pilih kamar terlebih dahulu', 'error');
            return;
        }

        const btn = document.getElementById('btnPayNow');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 8px;"></i> Memproses...';
        showLoadingModal();

        const requestBody = {
            customer_name: bookingData.customerName,
            customer_email: bookingData.customerEmail,
            customer_phone: bookingData.customerPhone,
            customer_message: bookingData.customerMessage,
            room_name: bookingData.selectedRoom.name,
            room_price: bookingData.selectedRoom.price,
            room_id: bookingData.selectedRoom.id,
            payment_method: bookingData.paymentMethod,
        };

        console.log('Booking request payload:', JSON.stringify(requestBody));

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

            const response = await fetch('{{ route("booking.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(requestBody),
            });

            hideLoadingModal();

            // CRITICAL: Cek HTTP status SEBELUM parse JSON
            if (!response.ok) {
                let errMsg = 'Server error (HTTP ' + response.status + ')';
                try {
                    // Coba parse sebagai JSON terlebih dahulu
                    const errData = await response.json();
                    if (errData.message) errMsg = errData.message;
                    console.error('Server error (JSON):', errData);
                } catch (jsonErr) {
                    // Response bukan JSON (mungkin HTML error page dari Laravel)
                    try {
                        const text = await response.clone().text();
                        console.error('Server response (non-JSON, first 800 chars):', text.substring(0, 800));
                    } catch (textErr) {
                        console.error('Cannot read response body:', textErr);
                    }
                }
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check-circle" style="margin-right: 8px;"></i> Bayar Sekarang';
                showNotification('Gagal: ' + errMsg, 'error');
                return;
            }

            // Response sukses — parse JSON
            const result = await response.json();
            console.log('Booking response:', result);
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle" style="margin-right: 8px;"></i> Bayar Sekarang';

            if (result.success) {
                if (bookingData.paymentMethod === 'midtrans' && result.snap_token) {
                    // Buka Snap popup Midtrans
                    window.snap.pay(result.snap_token, {
                        onSuccess: function(paymentResult) {
                            window.location.href = result.redirect_url;
                        },
                        onPending: function(paymentResult) {
                            window.location.href = result.redirect_url;
                        },
                        onError: function(paymentResult) {
                            showNotification('Pembayaran gagal. Status: ' + (paymentResult.status_message || 'Silakan coba lagi.'), 'error');
                            console.error('Midtrans snap error:', paymentResult);
                        },
                        onClose: function() {
                            showNotification('Popup pembayaran ditutup. Silakan buka kembali untuk melanjutkan.', 'error');
                        },
                    });
                } else {
                    showSuccessModal();
                }
            } else {
                // Server mengembalikan success=false
                showNotification(result.message || 'Gagal memproses booking. Silakan coba lagi.', 'error');
                console.error('Booking failed (success=false):', result);
            }
        } catch (error) {
            hideLoadingModal();
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check-circle" style="margin-right: 8px;"></i> Bayar Sekarang';
            showNotification('Gagal terhubung ke server. Cek koneksi internet Anda.', 'error');
            console.error('Booking fetch exception:', error);
        }
    }
</script>