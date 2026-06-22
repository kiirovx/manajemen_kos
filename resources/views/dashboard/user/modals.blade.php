<!-- MODALS -->
    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <div class="modal-header">Pembayaran Midtrans</div>
            <p style="font-size: 13px; color: #6B7280; margin-bottom: 16px;">Anda akan diarahkan ke halaman pembayaran Midtrans (Transfer Bank, E-Wallet, dll).</p>

            <input type="hidden" id="selectedPaymentId" value="{{ $currentBill ? $currentBill->id : '' }}">

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closePaymentModal()">Batal</button>
                <button type="button" class="btn btn-primary" onclick="handlePaymentSubmit()">
                    <i class="fas fa-credit-card"></i> Bayar via Midtrans
                </button>
            </div>
        </div>
    </div>

    <div class="modal" id="maintenanceModal">
        <div class="modal-content">
            <div class="modal-header">Ajukan Maintenance Baru</div>
            <form onsubmit="handleMaintenance(event)">
                <div class="form-group">
                    <label>Judul Perbaikan</label>
                    <input type="text" name="title" placeholder="Contoh: Pintu rusak, Lampu mati, dll" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category" required>
                        <option value="">Pilih Kategori</option>
                        <option>Kelistrikan</option>
                        <option>Plumbing (Air)</option>
                        <option>Furniture</option>
                        <option>Dinding/Cat</option>
                        <option>Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi Detail</label>
                    <textarea name="description" placeholder="Jelaskan masalah yang Anda alami secara detail" required></textarea>
                </div>

                <div class="form-group">
                    <label>Prioritas</label>
                    <select name="priority" required>
                        <option value="">Pilih Prioritas</option>
                        <option>Rendah</option>
                        <option selected>Normal</option>
                        <option>Tinggi</option>
                        <option>Urgent</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeMaintenanceModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Ajukan Maintenance</button>
                </div>
            </form>
        </div>
    </div>

<script>
    function openPaymentModal() {
        document.getElementById('paymentModal').classList.add('show');
    }

    function closePaymentModal() {
        document.getElementById('paymentModal').classList.remove('show');
    }

    async function handlePaymentSubmit() {
        const paymentId = document.getElementById('selectedPaymentId').value;

        console.log('[KosKita] handlePaymentSubmit() dipanggil. Payment ID:', paymentId);

        if (!paymentId) {
            showNotification('Tidak ada tagihan yang tersedia.', 'error');
            return;
        }

        // Validasi window.snap tersedia SEBELUM fetch
        if (typeof window.snap === 'undefined') {
            console.error('[KosKita] ERROR: window.snap tidak tersedia! Midtrans Snap JS tidak dimuat.');
            showNotification(
                'Layanan pembayaran tidak dapat dimuat. Silakan muat ulang halaman atau hubungi admin.',
                'error'
            );
            return;
        }

        console.log('[KosKita] window.snap tersedia ✓');

        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        try {
            const url = '/payment/bill/create-snap/' + paymentId;
            console.log('[KosKita] Mengirim request ke:', url);
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            });
            const data = await res.json();

            console.log('[KosKita] Response dari server:', { ok: res.ok, status: res.status, data: data });

            if (!res.ok) {
                const errMsg = data.message || 'Server error (HTTP ' + res.status + ')';
                showNotification('Midtrans Error: ' + errMsg, 'error');
                console.error('[KosKita] Midtrans response error:', data);
                return;
            }

            if (data.success && data.snap_token) {
                console.log('[KosKita] Snap token diterima:', data.snap_token.substring(0, 20) + '...');
                closePaymentModal();

                // Verifikasi ulang sebelum memanggil snap.pay()
                if (typeof window.snap !== 'undefined' && typeof window.snap.pay === 'function') {
                    console.log('[KosKita] Memanggil window.snap.pay()...');
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            console.log('[KosKita] Pembayaran berhasil:', result);
                            showNotification('Pembayaran berhasil! Tagihan langsung Lunas.', 'success');
                            setTimeout(() => location.reload(), 2000);
                        },
                        onPending: function(result) {
                            console.log('[KosKita] Pembayaran pending:', result);
                            showNotification('Pembayaran pending. Silakan tunggu.', 'success');
                            setTimeout(() => location.reload(), 2000);
                        },
                        onError: function(result) {
                            console.error('[KosKita] Pembayaran gagal:', result);
                            showNotification('Pembayaran gagal: ' + (result.status_message || 'Silakan coba lagi.'), 'error');
                        },
                        onClose: function() {
                            console.log('[KosKita] Popup pembayaran ditutup oleh user.');
                            showNotification('Popup pembayaran ditutup. Silakan coba lagi jika belum selesai.', 'error');
                        }
                    });
                } else {
                    console.error('[KosKita] ERROR: window.snap.pay bukan fungsi meskipun token diterima!');
                    showNotification('Snap Midtrans tidak tersedia. Muat ulang halaman dan coba lagi.', 'error');
                }
            } else {
                const failMsg = data.message || 'Gagal membuat transaksi. Cek konfigurasi Midtrans (Server Key / Mode).';
                showNotification(failMsg, 'error');
                console.error('[KosKita] Snap token failure:', data);
            }
        } catch (e) {
            showNotification('Gagal terhubung ke server. Periksa koneksi internet Anda.', 'error');
            console.error('[KosKita] Midtrans fetch exception:', e);
        }
    }

    function openMaintenanceModal() {
        document.getElementById('maintenanceModal').classList.add('show');
    }

    function closeMaintenanceModal() {
        document.getElementById('maintenanceModal').classList.remove('show');
    }

    async function handleMaintenance(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('{{ route('dashboard.maintenance.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json();

            if (data.success) {
                closeMaintenanceModal();
                showNotification(data.message, 'success');
                form.reset();
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('Gagal mengajukan maintenance.', 'error');
            }
        } catch (error) {
            showNotification('Terjadi kesalahan saat mengajukan maintenance.', 'error');
        }
    }
</script>