<!-- BOOKING REVIEW / PERSETUJUAN PENYEWA -->
<div class="page" id="booking-review">
    <div class="page-header">
        <div>
            <h1 class="page-title">Booking Baru</h1>
            <p class="page-subtitle">Review dan setujui penyewa yang sudah melakukan pembayaran</p>
        </div>
    </div>

    @if (session('success'))
        <div style="margin-bottom: 20px; padding: 12px 16px; border-radius: 8px; background: #D1FAE5; color: #065F46; font-size: 13px; font-weight: 600;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="margin-bottom: 20px; padding: 12px 16px; border-radius: 8px; background: #FEE2E2; color: #7F1D1D; font-size: 13px; font-weight: 600;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    @if($tenantsForReview->isEmpty() && $cashBookingsForReview->isEmpty())
        <div class="table-container">
            <div style="padding: 60px 20px; text-align: center;">
                <i class="fas fa-check-circle" style="font-size: 48px; color: #10B981; margin-bottom: 16px; display: block;"></i>
                <h3 style="font-size: 16px; color: #374151; margin-bottom: 8px;">Tidak ada booking yang perlu direview</h3>
                <p style="font-size: 13px; color: #9CA3AF;">Semua booking yang sudah dibayar telah diproses.</p>
            </div>
        </div>
    @else
        <!-- STAT CARDS -->
        <div class="stats-grid" style="margin-bottom: 30px;">
            <div class="stat-card" style="border-bottom-color: #F59E0B;">
                <div class="stat-icon" style="background: #F59E0B;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>Menunggu Persetujuan</h3>
                    <div class="number">{{ $pendingReviewCount }}</div>
                </div>
            </div>
            <div class="stat-card green">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>Booking Dibayar</h3>
                    <div class="number">{{ \App\Models\Booking::where('status', 'Dibayar')->count() }}</div>
                </div>
            </div>
        </div>

        <!-- ========== TENANT PROFILES (Midtrans settlement / cash login user) ========== -->
        @foreach($tenantsForReview as $tenant)
            @php
                $booking = $tenant->booking;
                $bookingNumber = $booking ? 'BK-' . $booking->created_at->format('Ymd') . '-' . str_pad($booking->id, 3, '0', STR_PAD_LEFT) : '-';
                $formattedAmount = 'Rp ' . number_format($booking->gross_amount ?? $booking->room_price ?? 0, 0, ',', '.');
                $paidAt = $booking?->paid_at ? $booking->paid_at->format('d M Y H:i') : '-';
            @endphp
            <div class="table-container" style="margin-bottom: 20px;">
                <div class="table-header" style="background: #FFFBEB; border-bottom: 2px solid #F59E0B;">
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <span class="badge warning">Menunggu Persetujuan</span>
                        <strong style="font-size: 14px; color: #111827;">{{ $booking->customer_name ?? $tenant->user?->name ?? '-' }}</strong>
                        <span style="font-size: 12px; color: #6B7280;">{{ $bookingNumber }}</span>
                        <span class="badge" style="background: #E0E7FF; color: #3730A3;">{{ ucfirst($booking->payment_method ?? 'midtrans') }}</span>
                    </div>
                </div>
                <div style="padding: 20px;">
                    <div class="form-grid-2" style="gap: 16px;">
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Email</div>
                            <div style="font-size: 13px; font-weight: 600; color: #374151;">{{ $booking->customer_email ?? $tenant->user?->email ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Telepon</div>
                            <div style="font-size: 13px; font-weight: 600; color: #374151;">{{ $booking->customer_phone ?? $tenant->phone ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Nomor Kamar</div>
                            <div style="font-size: 13px; font-weight: 600; color: #374151;">{{ $tenant->room?->number ?? $booking->room?->number ?? '-' }} - {{ ucfirst($tenant->room?->type ?? $booking->room?->type ?? '') }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Nama Kamar</div>
                            <div style="font-size: 13px; font-weight: 600; color: #374151;">{{ $booking->room_name ?? $tenant->room?->name ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Nominal Pembayaran</div>
                            <div style="font-size: 13px; font-weight: 600; color: #667eea;">{{ $formattedAmount }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Metode Pembayaran</div>
                            <div style="font-size: 13px; font-weight: 600; color: #374151;">{{ ucfirst($booking->payment_method ?? '-') }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Transaction ID</div>
                            <div style="font-size: 13px; font-weight: 600; color: #374151; word-break: break-all;">{{ $booking->midtrans_transaction_id ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Waktu Pembayaran</div>
                            <div style="font-size: 13px; font-weight: 600; color: #374151;">{{ $paidAt }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Status Booking</div>
                            <span class="badge info">{{ $booking->status ?? '-' }}</span>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Status Midtrans</div>
                            <span class="badge success">{{ $booking->midtrans_transaction_status ?? '-' }}</span>
                        </div>
                    </div>

                    <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap;">
                        <form method="POST" action="{{ route('admin.tenants.approve', $tenant) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-submit" style="background: #10B981;">
                                <i class="fas fa-check"></i> Terima
                            </button>
                        </form>
                        <button class="btn-cancel" onclick="openRejectModal({{ $tenant->id }})">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- ========== CASH BOOKINGS (guest / no login, no TenantProfile) ========== -->
        @foreach($cashBookingsForReview as $cashBooking)
            @php
                $bookingNumber = 'BK-' . $cashBooking->created_at->format('Ymd') . '-' . str_pad($cashBooking->id, 3, '0', STR_PAD_LEFT);
                $formattedAmount = 'Rp ' . number_format($cashBooking->gross_amount ?? $cashBooking->room_price ?? 0, 0, ',', '.');
                $paidAt = $cashBooking->paid_at ? $cashBooking->paid_at->format('d M Y H:i') : '-';
            @endphp
            <div class="table-container" style="margin-bottom: 20px;">
                <div class="table-header" style="background: #FFFBEB; border-bottom: 2px solid #F59E0B;">
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <span class="badge warning">Menunggu Persetujuan</span>
                        <strong style="font-size: 14px; color: #111827;">{{ $cashBooking->customer_name ?? '-' }}</strong>
                        <span style="font-size: 12px; color: #6B7280;">{{ $bookingNumber }}</span>
                        <span class="badge" style="background: #FEF3C7; color: #92400E;">Cash</span>
                    </div>
                </div>
                <div style="padding: 20px;">
                    <div class="form-grid-2" style="gap: 16px;">
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Email</div>
                            <div style="font-size: 13px; font-weight: 600; color: #374151;">{{ $cashBooking->customer_email ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Telepon</div>
                            <div style="font-size: 13px; font-weight: 600; color: #374151;">{{ $cashBooking->customer_phone ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Nomor Kamar</div>
                            <div style="font-size: 13px; font-weight: 600; color: #374151;">{{ $cashBooking->room?->number ?? '-' }} - {{ ucfirst($cashBooking->room?->type ?? '') }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Nama Kamar</div>
                            <div style="font-size: 13px; font-weight: 600; color: #374151;">{{ $cashBooking->room_name ?? '-' }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Nominal Pembayaran</div>
                            <div style="font-size: 13px; font-weight: 600; color: #667eea;">{{ $formattedAmount }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Metode Pembayaran</div>
                            <div style="font-size: 13px; font-weight: 600; color: #374151;">Cash</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Waktu Booking</div>
                            <div style="font-size: 13px; font-weight: 600; color: #374151;">{{ $cashBooking->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <div>
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Status Booking</div>
                            <span class="badge info">{{ $cashBooking->status ?? '-' }}</span>
                        </div>
                        @if($cashBooking->customer_message)
                        <div style="grid-column: 1 / -1;">
                            <div style="font-size: 12px; color: #9CA3AF; margin-bottom: 4px;">Pesan</div>
                            <div style="font-size: 13px; color: #374151;">{{ $cashBooking->customer_message }}</div>
                        </div>
                        @endif
                    </div>

                    <div style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap;">
                        <form method="POST" action="{{ route('admin.bookings.accept-cash', $cashBooking) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-submit" style="background: #10B981;">
                                <i class="fas fa-check"></i> Terima
                            </button>
                        </form>
                        <button class="btn-cancel" onclick="declineCashBooking({{ $cashBooking->id }})">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- MODAL TOLAK PENYEWA -->
<div class="modal modal--edit-room" id="rejectTenantModal">
    <div class="modal-content modal-content--edit-room" style="max-width: 500px;">
        <div class="modal-header modal-header--sticky" style="color: #DC2626;">
            <div>
                <div class="modal-header-title" style="color: #DC2626;">Tolak Penyewa</div>
                <div class="modal-header-subtitle">Berikan alasan penolakan</div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeRejectModal()" title="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="" id="rejectTenantForm">
            @csrf
            <div class="modal-body--scrollable" style="padding: 20px 24px;">
                <div class="form-group">
                    <label>Alasan Penolakan <span class="required-mark">*</span></label>
                    <textarea name="rejection_reason" rows="4" required placeholder="Jelaskan alasan mengapa penyewa ini ditolak..."></textarea>
                </div>
            </div>
            <div class="modal-footer modal-footer--sticky">
                <button type="button" class="btn-cancel" onclick="closeRejectModal()">Batal</button>
                <button type="submit" class="btn-submit" style="background: #DC2626;">
                    <i class="fas fa-times"></i> Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRejectModal(tenantId) {
        document.getElementById('rejectTenantForm').action = "{{ url('/dashboard/admin/tenants') }}/" + tenantId + "/reject";
        document.getElementById('rejectTenantModal').classList.add('show');
    }

    function closeRejectModal() {
        document.getElementById('rejectTenantModal').classList.remove('show');
    }

    async function declineCashBooking(bookingId) {
        if (!confirm('Tolak booking cash ini? Booking akan ditandai sebagai Dibatalkan.')) return;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        try {
            const res = await fetch("{{ url('/dashboard/admin/bookings') }}/" + bookingId + "/cancel", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                alert('Booking cash telah ditolak/dibatalkan.');
                location.reload();
            } else {
                alert(data.message || 'Gagal menolak booking.');
            }
        } catch (e) {
            alert('Terjadi kesalahan.');
        }
    }
</script>