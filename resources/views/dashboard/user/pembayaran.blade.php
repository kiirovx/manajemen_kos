<!-- PEMBAYARAN PAGE -->
        <div class="page" id="pembayaran">
            <div class="page-header">
                <h1 class="page-title">Pembayaran</h1>
                <p class="page-subtitle">Kelola tagihan dan riwayat pembayaran Anda</p>
            </div>

            @if($currentBill)
            <!-- CURRENT BILLING -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tagihan Saat Ini</h3>
                </div>

                <div style="background: #F5F5F7; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                        <span style="font-size: 14px; color: #666;">{{ $currentBill->period_label }}</span>
                        <span style="font-weight: 700; color: #333; font-size: 18px;">Rp {{ number_format($currentBill->amount, 0, ',', '.') }}</span>
                    </div>
                    <div style="font-size: 12px; color: #999; margin-bottom: 15px;">
                        Jatuh tempo: {{ $currentBill->due_date->format('d F Y') }}
                    </div>
                    <button class="btn btn-primary" onclick="openPaymentModal()" style="width: 100%;">
                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                    </button>
                </div>
            </div>
            @endif

            <!-- PAYMENT HISTORY -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Pembayaran</h3>
                </div>

                @forelse($paymentHistory as $payment)
                <div class="invoice-item">
                    <div class="invoice-left">
                        <div class="invoice-month">{{ $payment->period_label }}</div>
                        <div class="invoice-status">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                    </div>
                    <div class="invoice-right">
                        <div style="margin-bottom: 5px;">
                            <span class="badge badge-success">LUNAS</span>
                        </div>
                        <div style="font-size: 12px; color: #999;">
                            Dibayar: {{ $payment->paid_date?->format('d M Y') ?? '-' }}
                        </div>
                    </div>
                </div>
                @empty
                <p style="color: #999; font-size: 13px;">Belum ada riwayat pembayaran.</p>
                @endforelse
            </div>
        </div>

<script>
    function downloadInvoice() {
        showNotification('Invoice sedang diunduh...', 'success');
    }
</script>
