<!-- NOTIFIKASI PAGE -->
        <div class="page" id="notifikasi">
            <div class="page-header">
                <h1 class="page-title">Notifikasi</h1>
                <p class="page-subtitle">Kelola semua notifikasi Anda</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Semua Notifikasi</h3>
                    <button class="btn btn-secondary" style="font-size: 12px;" onclick="markAllNotificationsRead()">
                        Tandai Semua sebagai Dibaca
                    </button>
                </div>

                @forelse($user->notifications as $notification)
                <div class="notification-item {{ $notification->type === 'success' ? 'success' : ($notification->type === 'warning' ? 'warning' : ($notification->type === 'danger' ? 'danger' : '')) }}">
                    <div class="notification-icon">
                        <i class="fas fa-{{ $notification->type === 'success' ? 'check-circle' : ($notification->type === 'warning' ? 'clock' : ($notification->type === 'danger' ? 'exclamation-circle' : 'bell')) }}"></i>
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

<script>
    async function markAllNotificationsRead() {
        try {
            const response = await fetch('{{ route('dashboard.notifications.read-all') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            });
            const data = await response.json();
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            }
        } catch (error) {
            showNotification('Gagal memperbarui notifikasi.', 'error');
        }
    }
</script>
