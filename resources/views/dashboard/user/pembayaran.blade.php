<!-- PEMBAYARAN PAGE -->
<div class="page" id="pembayaran">
    <div class="page-header">
        <h1 class="page-title">Pembayaran</h1>
        <p class="page-subtitle">Kelola tagihan dan riwayat pembayaran Anda</p>
    </div>

    <!-- TAGIHAN AKTIF (belum lunas) -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tagihan Aktif</h3>
        </div>

        @if($currentBill)
        <!-- Current Bill Highlight -->
        <div style="background: #FFF3E0; padding: 20px; border-radius: 8px; margin-bottom: 15px; border-left: 4px solid #F97316;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                <span style="font-size: 14px; color: #666;">{{ $currentBill->period_label }}</span>
                <span style="font-weight: 700; color: #333; font-size: 18px;">Rp {{ number_format($currentBill->amount, 0, ',', '.') }}</span>
            </div>
            <div style="font-size: 12px; color: #999; margin-bottom: 15px;">
                Jatuh tempo: {{ $currentBill->due_date->format('d F Y') }}
            </div>
            <div style="display: flex; gap: 10px;">
                <button class="btn btn-primary" onclick="openPaymentModal()" style="flex: 1;">
                    <i class="fas fa-credit-card"></i> Bayar Sekarang
                </button>
            </div>
        </div>
        @endif

        @forelse($pendingBills as $payment)
        @php
            $isOverdue = $payment->due_date && $payment->due_date->isPast();
            $paymentBadge = $isOverdue 
                ? ['class' => 'badge-danger', 'label' => 'TERLAMBAT']
                : ['class' => 'badge-warning', 'label' => 'BELUM DIBAYAR'];
        @endphp
        <div class="invoice-item">
            <div class="invoice-left">
                <div class="invoice-month">{{ $payment->period_label }}</div>
                <div class="invoice-status">Jatuh tempo: {{ $payment->due_date?->format('d M Y') ?? '-' }}</div>
            </div>
            <div class="invoice-right">
                <div class="invoice-amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                <span class="badge {{ $paymentBadge['class'] }}">{{ $paymentBadge['label'] }}</span>
            </div>
        </div>
        @empty
        @if(!$currentBill)
        <p style="color: #10B981; font-size: 13px;">✓ Semua tagihan sudah lunas. Tidak ada tagihan aktif.</p>
        @endif
        @endforelse
    </div>

    <!-- RIWAYAT PEMBAYARAN TAGIHAN BULANAN -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Pembayaran Tagihan</h3>
        </div>

        @forelse($paymentHistory as $payment)
        <div class="invoice-item">
            <div class="invoice-left">
                <div class="invoice-month">{{ $payment->period_label }}</div>
                <div class="invoice-status">
                    {{ $payment->payment_method ? ucfirst($payment->payment_method) : 'Midtrans' }}
                    @if($payment->midtrans_transaction_id)
                        <br>TXID: {{ $payment->midtrans_transaction_id }}
                    @endif
                </div>
            </div>
            <div class="invoice-right">
                <div class="invoice-amount">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                <span class="badge badge-success">LUNAS</span>
                @if($payment->paid_date)
                <div style="font-size: 11px; color: #999; margin-top: 5px;">{{ $payment->paid_date->format('d M Y H:i') }}</div>
                @endif
            </div>
        </div>
        @empty
        <p style="color: #999; font-size: 13px;">Belum ada riwayat pembayaran tagihan.</p>
        @endforelse
    </div>

    <!-- RIWAYAT BOOKING -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Riwayat Booking</h3>
        </div>

        @forelse($userBookingsForPayment as $booking)
        @php
            $bookingNumber = 'BK-' . $booking->created_at->format('Ymd') . '-' . str_pad($booking->id, 3, '0', STR_PAD_LEFT);
            $payBadge = match($booking->status) {
                'Dibayar' => ['class' => 'badge-success', 'label' => 'Dibayar'],
                'Pending' => ['class' => 'badge-warning', 'label' => 'Pending'],
                'Menunggu Pembayaran' => ['class' => 'badge-warning', 'label' => 'Menunggu'],
                default => ['class' => 'badge-danger', 'label' => $booking->status],
            };
        @endphp
        <div class="invoice-item">
            <div class="invoice-left">
                <div class="invoice-month">{{ $bookingNumber }} - {{ $booking->room_name }}</div>
                <div class="invoice-status" style="margin-top: 3px;">
                    {{ $booking->payment_method ? ucfirst($booking->payment_method) : '-' }}
                    @if($booking->midtrans_transaction_id)
                        <br>TXID: {{ $booking->midtrans_transaction_id }}
                    @endif
                </div>
            </div>
            <div class="invoice-right">
                <div class="invoice-amount">Rp {{ number_format($booking->gross_amount ?? $booking->room_price, 0, ',', '.') }}</div>
                <span class="badge {{ $payBadge['class'] }}">{{ $payBadge['label'] }}</span>
                @if($booking->paid_at)
                <div style="font-size: 11px; color: #999; margin-top: 5px;">{{ $booking->paid_at->format('d M Y H:i') }}</div>
                @endif
            </div>
        </div>
        @empty
        <p style="color: #999; font-size: 13px;">Belum ada riwayat booking.</p>
        @endforelse
    </div>
</div>

<script>
    function downloadInvoice() {
        showNotification('Invoice sedang diunduh...', 'success');
    }
</script>