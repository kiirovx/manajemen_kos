<!-- MODALS -->
    <div class="modal" id="paymentModal">
        <div class="modal-content">
            <div class="modal-header">Lakukan Pembayaran</div>
            <form onsubmit="handlePayment(event)">
                <div class="form-group">
                    <label>Bulan Pembayaran</label>
                    <select required>
                        <option value="">Pilih Bulan</option>
                        <option selected>Desember 2024 - Rp 1.500.000</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select required>
                        <option value="">Pilih Metode</option>
                        <option>Transfer Bank</option>
                        <option>E-Wallet</option>
                        <option>Tunai ke Petugas</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="text" value="Rp 1.500.000" readonly>
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
                    <input type="text" placeholder="Contoh: Pintu rusak, Lampu mati, dll" required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select required>
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
                    <textarea placeholder="Jelaskan masalah yang Anda alami secara detail" required></textarea>
                </div>

                <div class="form-group">
                    <label>Prioritas</label>
                    <select required>
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
    // ============================================
    // MODAL FUNCTIONS
    // ============================================
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

    // ============================================
    // FORM SUBMIT HANDLERS
    // ============================================
    function handlePayment(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        
        console.log('Processing payment:', Object.fromEntries(formData));
        
        closePaymentModal();
        showNotification('Pembayaran sedang diproses. Silakan tunggu konfirmasi...', 'success');
    }

    function handleMaintenance(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        
        console.log('Submitting maintenance:', Object.fromEntries(formData));
        
        closeMaintenanceModal();
        showNotification('Maintenance berhasil diajukan! Tim kami akan segera menghubungi Anda.', 'success');
        form.reset();
    }
</script>

    