<!-- MODALS -->
<div class="modal" id="addRoomModal">
    <div class="modal-content">
        <div class="modal-header">Tambah Kamar Baru</div>
        <form method="POST" action="{{ route('admin.rooms.store') }}">
            @csrf
            <div class="form-group">
                <label>Nomor Kamar</label>
                <input type="text" name="number" required>
            </div>
            <div class="form-group">
                <label>Tipe Kamar</label>
                <select name="type" required>
                    <option value="">Pilih Tipe</option>
                    <option value="standard">Standard</option>
                    <option value="deluxe">Deluxe</option>
                    <option value="premium">Premium</option>
                </select>
            </div>
            <div class="form-group">
                <label>Harga/Bulan</label>
                <input type="number" required name="price">
            </div>
            <div class="form-group">
                <label>Lantai</label>
                <input type="number" required name="floor">
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
        <form method="POST" action="{{ route('admin.tenants.store') }}">
            @csrf
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password Awal</label>
                <input type="password" name="password" required minlength="8">
            </div>
            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="tel" name="phone" required>
            </div>
            <div class="form-group">
                <label>Kamar</label>
                <select name="room_id" required>
                    <option value="">Pilih Kamar</option>
                    @foreach ($availableRooms as $room)
                        <option value="{{ $room->id }}">
                            {{ $room->number }} - {{ ucfirst($room->type) }} - Rp {{ number_format((float) $room->price, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
                @if ($availableRooms->isEmpty())
                    <p style="margin: 8px 0 0; font-size: 12px; color: #7F1D1D;">Belum ada kamar kosong. Tambahkan kamar dulu.</p>
                @endif
            </div>
            <div class="form-group">
                <label>Tanggal Masuk</label>
                <input type="date" name="lease_start" required value="{{ now()->toDateString() }}">
            </div>
            <div class="form-group">
                <label>Tanggal Keluar</label>
                <input type="date" name="lease_end">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeAddTenantModal()">Batal</button>
                <button type="submit" class="btn-submit" {{ $availableRooms->isEmpty() ? 'disabled' : '' }}>Tambah</button>
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
