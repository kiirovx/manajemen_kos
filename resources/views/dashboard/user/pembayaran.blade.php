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

<script>
    // ============================================
    // DOWNLOAD FUNCTIONS
    // ============================================
    function downloadInvoice() {
        showNotification('Invoice sedang diunduh...', 'success');
        console.log('Downloading invoice');
    }
</script>

        