<!-- DASHBOARD PAGE -->
<div class="page active" id="dashboard">
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">Selamat datang kembali, {{ $user->name }}!</p>
    </div>

    <!-- INFO CARDS -->
    <div class="dashboard-grid">
        <!-- Penyewaan Saya -->
        <div class="info-card">
            <div class="info-card-label">Penyewaan Saya</div>
            <div class="info-card-value" style="font-size: 18px; color: {{ $isTenantActive ? '#10B981' : '#9CA3AF' }};">
                {{ $isTenantActive ? ($tenantRoom?->number ?? 'Aktif') : 'Tidak Aktif' }}
            </div>
            <div class="info-card-action">
                {{ $isTenantActive ? 'Kamar ' . ($tenantRoom?->number ?? '-') . ' - ' . ucfirst($tenantRoom?->type ?? '') : 'Belum ada penyewaan aktif' }}
            </div>
        </div>

        <!-- Tanggal Masuk -->
        <div class="info-card orange">
            <div class="info-card-label">Tanggal Masuk</div>
            <div class="info-card-value" style="font-size: 18px;">
                {{ $tenant?->lease_start?->format('d M Y') ?? '-' }}
            </div>
            <div class="info-card-action">
                Status: {{ $tenant ? $tenant->status : '-' }}
            </div>
        </div>

        <!-- Tanggal Keluar -->
        <div class="info-card green">
            <div class="info-card-label">Tanggal Keluar</div>
            @php
                $leaseEnd = $tenant?->lease_end;
                $monthsLeft = $leaseEnd ? max(0, (int) floor(now()->diffInMonths($leaseEnd, false))) : 0;
            @endphp
            <div class="info-card-value" style="font-size: 18px;">
                {{ $leaseEnd?->format('d M Y') ?? '-' }}
            </div>
            <div class="info-card-action">
                {{ $leaseEnd ? $monthsLeft . ' bulan tersisa' : '-' }}
            </div>
        </div>

        <!-- Tagihan -->
        <div class="info-card red">
            <div class="info-card-label">Tagihan Saya</div>
            <div class="info-card-value" style="font-size: 18px; color: {{ $currentBill ? '#F97316' : '#10B981' }};">
                {{ $currentBill ? 'Rp ' . number_format($currentBill->amount, 0, ',', '.') : 'Tidak Ada' }}
            </div>
            <div class="info-card-action">
                @if($currentBill)
                    Jatuh Tempo: {{ $currentBill->due_date->format('d M Y') }}
                @else
                    Semua tagihan lunas
                @endif
                <br><a href="javascript:void(0)" onclick="showUserPage('pembayaran')">Lihat Tagihan →</a>
            </div>
        </div>
    </div>

    <!-- QUICK ACTIONS -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Aksi Cepat</h3>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
            <a href="{{ route('booking') }}" style="display: flex; align-items: center; gap: 8px; padding: 10px 16px; background: #667eea; color: white; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer;">
                <i class="fas fa-plus-circle"></i> Booking Kamar
            </a>
            <button class="btn btn-primary" onclick="showUserPage('pembayaran')">
                <i class="fas fa-credit-card"></i> Pembayaran
            </button>
            <button class="btn btn-primary" onclick="openMaintenanceModal()">
                <i class="fas fa-exclamation-circle"></i> Maintenance
            </button>
            <button class="btn btn-primary" onclick="showUserPage('data-pribadi')">
                <i class="fas fa-edit"></i> Ubah Data
            </button>
        </div>
    </div>

    <!-- RECENT BOOKINGS -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Booking Saya</h3>
            <a href="{{ route('dashboard.user.bookings') }}" style="font-size: 12px; color: #667eea; text-decoration: none;">Lihat Semua →</a>
        </div>
        @forelse($recentBookings as $booking)
            @php
                $badgeClass = match($booking->status) {
                    'Dibayar' => 'badge-success',
                    'Pending' => 'badge-warning',
                    'Menunggu Pembayaran' => 'badge-warning',
                    default => 'badge-danger',
                };
                $bookingNumber = 'BK-' . $booking->created_at->format('Ymd') . '-' . str_pad($booking->id, 3, '0', STR_PAD_LEFT);
            @endphp
            <div class="invoice-item" style="margin-bottom: 10px;">
                <div class="invoice-left">
                    <div class="invoice-month">{{ $bookingNumber }} - {{ $booking->room_name }}</div>
                    <div class="invoice-status">{{ $booking->created_at->format('d M Y H:i') }}</div>
                    @if($booking->tenantProfile)
                    <div class="invoice-status" style="margin-top:3px;">Status Penyewa: {{ $booking->tenantProfile->status }}</div>
                    @endif
                </div>
                <div class="invoice-right">
                    <div class="invoice-amount">Rp {{ number_format($booking->room_price, 0, ',', '.') }}</div>
                    <span class="badge {{ $badgeClass }}">{{ $booking->status }}</span>
                </div>
            </div>
        @empty
            <p style="color: #999; font-size: 13px;">Belum ada booking. <a href="{{ route('booking') }}" style="color: #667eea;">Booking sekarang</a></p>
        @endforelse
    </div>

    <!-- RECENT NOTIFICATIONS -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Notifikasi Terbaru</h3>
            <a href="javascript:void(0)" onclick="showUserPage('notifikasi')"
                style="font-size: 12px; color: #667eea; text-decoration: none;">Lihat Semua →</a>
        </div>
        @forelse($recentNotifications as $notification)
        <div class="notification-item {{ $notification->type === 'success' ? 'success' : ($notification->type === 'warning' ? 'warning' : ($notification->type === 'danger' ? 'danger' : '')) }}">
            <div class="notification-icon">
                <i class="fas fa-{{ $notification->type === 'success' ? 'check-circle' : ($notification->type === 'warning' ? 'info-circle' : 'bell') }}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-title">{{ $notification->title }}</div>
                <div class="notification-text">{{ $notification->message }}</div>
                <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
            </div>
        </div>
        @empty
        <p style="color: #999; font-size: 13px;">Belum ada notifikasi.</p>
        @endforelse
    </div>
</div>