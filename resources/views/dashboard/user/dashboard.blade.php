<!-- DASHBOARD PAGE -->
        <div class="page active" id="dashboard">
            <div class="page-header">
                <h1 class="page-title">Dashboard</h1>
                <p class="page-subtitle">Selamat datang kembali, {{ $user->name }}!</p>
            </div>

            <!-- INFO CARDS -->
            <div class="dashboard-grid">
                <div class="info-card">
                    <div class="info-card-label">Status Pembayaran</div>
                    <div class="info-card-value" style="color: {{ $currentBill ? '#F97316' : '#10B981' }};">
                        {{ $currentBill ? 'Belum Lunas' : 'Lunas' }}
                    </div>
                    <div class="info-card-action">
                        <a href="javascript:void(0)" onclick="showUserPage('pembayaran')">Lihat Riwayat →</a>
                    </div>
                </div>

                <div class="info-card orange">
                    <div class="info-card-label">Pembayaran Berikutnya</div>
                    <div class="info-card-value">
                        {{ $currentBill ? $currentBill->due_date->format('d M') : '-' }}
                    </div>
                    <div class="info-card-action">
                        {{ $currentBill ? 'Rp ' . number_format($currentBill->amount / 1000000, 1, ',', '.') . ' Juta' : 'Tidak ada tagihan' }}
                    </div>
                </div>

                <div class="info-card green">
                    <div class="info-card-label">Sisa Masa Sewa</div>
                    @php
                        $leaseEnd = $user->tenantProfile?->lease_end;
                        $monthsLeft = $leaseEnd ? max(0, now()->diffInMonths($leaseEnd, false)) : 0;
                    @endphp
                    <div class="info-card-value">{{ $monthsLeft }} Bulan</div>
                    <div class="info-card-action">
                        Berakhir: {{ $leaseEnd?->format('d M Y') ?? '-' }}
                    </div>
                </div>

                <div class="info-card red">
                    <div class="info-card-label">Maintenance Requests</div>
                    <div class="info-card-value">{{ $pendingMaintenanceCount }}</div>
                    <div class="info-card-action">
                        <a href="javascript:void(0)" onclick="showUserPage('maintenance')">Lihat Detail →</a>
                    </div>
                </div>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Aksi Cepat</h3>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                    <button class="btn btn-primary" onclick="openPaymentModal()">
                        <i class="fas fa-credit-card"></i> Bayar Sekarang
                    </button>
                    <button class="btn btn-primary" onclick="openMaintenanceModal()">
                        <i class="fas fa-exclamation-circle"></i> Ajukan Maintenance
                    </button>
                    <button class="btn btn-primary" onclick="showUserPage('data-pribadi')">
                        <i class="fas fa-edit"></i> Ubah Data
                    </button>
                    <button class="btn btn-primary" onclick="downloadInvoice()">
                        <i class="fas fa-download"></i> Unduh Invoice
                    </button>
                </div>
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
