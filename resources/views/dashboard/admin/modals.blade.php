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

<div class="modal" id="editRoomModal">
    <div class="modal-content">
        <div class="modal-header">Edit Kamar</div>
        <form method="POST" action="" id="editRoomForm">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nomor Kamar</label>
                <input type="text" name="number" required>
            </div>
            <div class="form-group">
                <label>Tipe Kamar</label>
                <select name="type" required>
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
            <div class="form-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="available">Kosong</option>
                    <option value="occupied">Terisi</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditRoomModal()">Batal</button>
                <button type="submit" class="btn-submit">Simpan</button>
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
                <label>Pilih Pengguna Terdaftar</label>
                <select name="user_id" required>
                    <option value="">Pilih Pengguna</option>
                    @foreach ($availableUsers as $availableUser)
                        <option value="{{ $availableUser->id }}">
                            {{ $availableUser->name }} ({{ $availableUser->email }})
                        </option>
                    @endforeach
                </select>
                @if ($availableUsers->isEmpty())
                    <p style="margin: 8px 0 0; font-size: 12px; color: #7F1D1D;">Belum ada pengguna terdaftar yang bisa dijadikan penyewa. Pengguna harus mendaftar dulu.</p>
                @endif
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
                <button type="submit" class="btn-submit" {{ $availableRooms->isEmpty() || $availableUsers->isEmpty() ? 'disabled' : '' }}>Tambah</button>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="editTenantModal">
    <div class="modal-content">
        <div class="modal-header">Edit Penyewa</div>
        <form method="POST" action="" id="editTenantForm">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nama Penyewa</label>
                <input type="text" id="editTenantName" disabled style="background: #F5F5F7;">
            </div>
            <div class="form-group">
                <label>Nomor Telepon</label>
                <input type="tel" name="phone" required>
            </div>
            <div class="form-group">
                <label>Kamar</label>
                <select name="room_id" required id="editTenantRoom">
                    <option value="">Pilih Kamar</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">
                            {{ $room->number }} - {{ ucfirst($room->type) }} - Rp {{ number_format((float) $room->price, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Tanggal Masuk</label>
                <input type="date" name="lease_start" required>
            </div>
            <div class="form-group">
                <label>Tanggal Keluar</label>
                <input type="date" name="lease_end">
            </div>
            <div class="form-group">
                <label>Nomor KTP</label>
                <input type="text" name="identity_number">
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="address">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeEditTenantModal()">Batal</button>
                <button type="submit" class="btn-submit">Simpan</button>
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

    function openEditRoomModal(room) {
        const form = document.getElementById('editRoomForm');
        form.action = "{{ url('/dashboard/admin/rooms') }}/" + room.id;
        form.querySelector('[name="number"]').value = room.number;
        form.querySelector('[name="type"]').value = room.type;
        form.querySelector('[name="price"]').value = parseFloat(room.price);
        form.querySelector('[name="floor"]').value = room.floor;
        form.querySelector('[name="status"]').value = room.status;
        document.getElementById('editRoomModal').classList.add('show');
    }

    function closeEditRoomModal() {
        document.getElementById('editRoomModal').classList.remove('show');
    }

    function closeAddTenantModal() {
        document.getElementById('addTenantModal').classList.remove('show');
    }

    function openEditTenantModal(tenant) {
        const form = document.getElementById('editTenantForm');
        form.action = "{{ url('/dashboard/admin/tenants') }}/" + tenant.id;
        document.getElementById('editTenantName').value = tenant.user_name || '-';
        form.querySelector('[name="phone"]').value = tenant.phone || '';
        form.querySelector('[name="lease_start"]').value = tenant.lease_start || '';
        form.querySelector('[name="lease_end"]').value = tenant.lease_end || '';
        form.querySelector('[name="identity_number"]').value = tenant.identity_number || '';
        form.querySelector('[name="address"]').value = tenant.address || '';
        const roomSelect = document.getElementById('editTenantRoom');
        roomSelect.querySelectorAll('option').forEach(opt => {
            opt.hidden = false;
        });
        let hasCurrentRoom = false;
        roomSelect.querySelectorAll('option').forEach(opt => {
            if (opt.value === String(tenant.room_id)) {
                hasCurrentRoom = true;
            }
        });
        if (!hasCurrentRoom && tenant.room_id) {
            const opt = document.createElement('option');
            opt.value = tenant.room_id;
            opt.textContent = tenant.room_number ? 'Kamar ' + tenant.room_number + ' (saat ini)' : 'Kamar saat ini';
            roomSelect.appendChild(opt);
        }
        roomSelect.value = tenant.room_id || '';
        document.getElementById('editTenantModal').classList.add('show');
    }

    function closeEditTenantModal() {
        document.getElementById('editTenantModal').classList.remove('show');
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
