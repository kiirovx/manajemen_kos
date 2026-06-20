<!-- MODALS -->
    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <div class="modal-header">Lakukan Pembayaran</div>
            <form onsubmit="handlePayment(event)">
                <div class="form-group">
                    <label>Bulan Pembayaran</label>
                    <select name="payment_id" id="paymentSelect" required>
                        <option value="">Pilih Bulan</option>
                        @if($currentBill)
                        <option value="{{ $currentBill->id }}" selected>
                            {{ $currentBill->period_label }} - Rp {{ number_format($currentBill->amount, 0, ',', '.') }}
                        </option>
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select name="payment_method" required>
                        <option value="">Pilih Metode</option>
                        <option>Transfer Bank</option>
                        <option>E-Wallet</option>
                        <option>Tunai ke Petugas</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="text" id="paymentAmountDisplay" value="{{ $currentBill ? 'Rp ' . number_format($currentBill->amount, 0, ',', '.') : '' }}" readonly>
                </div>

                <div
                    style="background: #FFF5EB; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 12px; color: #78350F;">
                    <i class="fas fa-info-circle"></i> Pastikan Anda melakukan pembayaran sesuai dengan metode yang
                    dipilih
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closePaymentModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Lanjutkan Pembayaran</button>
                </div>
            </form>
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

    function openMaintenanceModal() {
        document.getElementById('maintenanceModal').classList.add('show');
    }

    function closeMaintenanceModal() {
        document.getElementById('maintenanceModal').classList.remove('show');
    }

    async function handlePayment(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('{{ route('dashboard.payment.store') }}', {
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
                closePaymentModal();
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showNotification('Gagal memproses pembayaran.', 'error');
            }
        } catch (error) {
            showNotification('Terjadi kesalahan saat memproses pembayaran.', 'error');
        }
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
