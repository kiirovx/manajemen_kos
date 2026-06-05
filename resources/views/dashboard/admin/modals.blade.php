    <!-- MODALS -->
    <div class="modal" id="addRoomModal">
        <div class="modal-content">
            <div class="modal-header">Tambah Kamar Baru</div>
            <form onsubmit="handleAddRoom(event)">
                <div class="form-group">
                    <label>Nomor Kamar</label>
                    <input type="text" required>
                </div>
                <div class="form-group">
                    <label>Tipe Kamar</label>
                    <select required>
                        <option value="">Pilih Tipe</option>
                        <option>Standard</option>
                        <option>Deluxe</option>
                        <option>Premium</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Harga/Bulan</label>
                    <input type="number" required>
                </div>
                <div class="form-group">
                    <label>Lantai</label>
                    <input type="number" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeAddRoomModal()">Batal</button>
                    <button type="submit" class="btn-submit">Tambah</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal" id="addTenantModal">
        <div class="modal-content">
            <div class="modal-header">Tambah Penyewa Baru</div>
            <form onsubmit="handleAddTenant(event)">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" required>
                </div>
                <div class="form-group">
                    <label>Nomor Telepon</label>
                    <input type="tel" required>
                </div>
                <div class="form-group">
                    <label>Kamar</label>
                    <select required>
                        <option value="">Pilih Kamar</option>
                        <option>01A</option>
                        <option>02A</option>
                        <option>05A</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeAddTenantModal()">Batal</button>
                    <button type="submit" class="btn-submit">Tambah</button>
                </div>
            </form>
        </div>
    </div>

<script>
    // ============================================
    // ROOM MANAGEMENT MODALS
    // ============================================
    function openAddRoomModal() {
        document.getElementById('addRoomModal').classList.add('show');
    }

    // ============================================
    // TENANT MANAGEMENT MODALS
    // ============================================
    function openAddTenantModal() {
        document.getElementById('addTenantModal').classList.add('show');
    }

    function closeAddRoomModal() {
        document.getElementById('addRoomModal').classList.remove('show');
    }

    function closeAddTenantModal() {
        document.getElementById('addTenantModal').classList.remove('show');
    }

    function handleAddRoom(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        
        console.log('Adding room:', Object.fromEntries(formData));
        
        showNotification('Kamar berhasil ditambahkan!', 'success');
        closeAddRoomModal();
        form.reset();
    }

    function handleAddTenant(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        
        console.log('Adding tenant:', Object.fromEntries(formData));
        
        showNotification('Penyewa berhasil ditambahkan!', 'success');
        closeAddTenantModal();
        form.reset();
    }
</script>
