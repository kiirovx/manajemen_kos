<!-- ADD ROOM MODAL -->
<div class="modal modal--add-room" id="addRoomModal">
    <div class="modal-content modal-content--add-room">
        <div class="modal-header modal-header--sticky">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; font-size: 20px; color: white;">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div>
                    <div class="modal-header-title">Tambah Kamar Baru</div>
                    <div class="modal-header-subtitle">Lengkapi informasi kamar yang akan ditampilkan kepada penyewa</div>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeAddRoomModal()" title="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.rooms.store') }}" id="addRoomForm" enctype="multipart/form-data" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
            @csrf
            <div class="modal-body--scrollable">
                <div class="add-room-grid">
                    <!-- KOLOM KIRI: Informasi Dasar -->
                    <div>
                        <!-- CARD 1: Informasi Kamar -->
                        <div class="form-section-card">
                            <div class="form-section-header">
                                <i class="fas fa-bed"></i>
                                <span>Informasi Kamar</span>
                            </div>
                            <div class="form-group">
                                <label>Nomor Kamar <span class="required-mark">*</span></label>
                                <input type="text" name="number" required placeholder="Contoh: A-101">
                            </div>
                            <div class="form-group">
                                <label>Tipe Kamar <span class="required-mark">*</span></label>
                                <select name="type" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="standard">Standard</option>
                                    <option value="deluxe">Deluxe</option>
                                    <option value="premium">Premium</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Harga/Bulan <span class="required-mark">*</span></label>
                                <div class="input-price-wrapper">
                                    <span class="input-price-prefix">Rp</span>
                                    <input type="text" id="addRoomPrice" required name="price_display" placeholder="Contoh: 1.500.000" class="input-price" autocomplete="off">
                                    <input type="hidden" name="price" id="addRoomPriceHidden">
                                </div>
                            </div>
                            <div class="form-grid-2">
                                <div class="form-group">
                                    <label>Lantai <span class="required-mark">*</span></label>
                                    <input type="number" required name="floor" min="1" value="1" placeholder="1">
                                </div>
                                <div class="form-group">
                                    <label>Kapasitas (orang) <span class="required-mark">*</span></label>
                                    <input type="number" required name="capacity" min="1" value="1" placeholder="2">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Jumlah Slot <span class="required-mark">*</span></label>
                                <input type="number" required name="slots" min="0" value="1" placeholder="1">
                            </div>
                        </div>

                        <!-- CARD 4: Status -->
                        <div class="form-section-card">
                            <div class="form-section-header">
                                <i class="fas fa-toggle-on"></i>
                                <span>Status Kamar</span>
                            </div>
                            <div class="form-group">
                                <label>Status <span class="required-mark">*</span></label>
                                <div class="status-radio-group">
                                    <label class="status-radio-card">
                                        <input type="radio" name="status" value="available" checked>
                                        <span class="status-radio-content">
                                            <i class="fas fa-check-circle" style="color: #10B981;"></i>
                                            <div>
                                                <strong>Tersedia</strong>
                                                <small>Kamar siap disewa</small>
                                            </div>
                                        </span>
                                    </label>
                                    <label class="status-radio-card">
                                        <input type="radio" name="status" value="occupied">
                                        <span class="status-radio-content">
                                            <i class="fas fa-user-check" style="color: #F59E0B;"></i>
                                            <div>
                                                <strong>Terisi</strong>
                                                <small>Sudah ada penyewa</small>
                                            </div>
                                        </span>
                                    </label>
                                    <label class="status-radio-card">
                                        <input type="radio" name="status" value="maintenance">
                                        <span class="status-radio-content">
                                            <i class="fas fa-tools" style="color: #EF4444;"></i>
                                            <div>
                                                <strong>Maintenance</strong>
                                                <small>Dalam perbaikan</small>
                                            </div>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: Detail & Media -->
                    <div>
                        <!-- CARD 2: Detail Kamar -->
                        <div class="form-section-card">
                            <div class="form-section-header">
                                <i class="fas fa-info-circle"></i>
                                <span>Detail Kamar</span>
                            </div>
                            <div class="form-group">
                                <label>Fasilitas</label>
                                <input type="text" name="facilities" placeholder="Contoh: AC, WiFi, Kamar Mandi Dalam, Lemari, Meja Belajar">
                            </div>
                            <div class="form-group">
                                <label>Deskripsi</label>
                                <textarea name="description" rows="5" placeholder="Deskripsi lengkap kamar, kondisi, ukuran, fasilitas tambahan..."></textarea>
                            </div>
                        </div>

                        <!-- CARD 3: Media Kamar -->
                        <div class="form-section-card">
                            <div class="form-section-header">
                                <i class="fas fa-image"></i>
                                <span>Media Kamar</span>
                            </div>
                            <div class="form-group">
                                <label>Upload Gambar <span style="font-weight: 400; color: #999; font-size: 11px;">(JPG, JPEG, PNG, WEBP — max 2MB)</span></label>
                                <div class="photo-upload-area" id="addPhotoUploadArea" style="cursor: pointer; transition: all 0.3s ease;">
                                    <input type="file" name="photo_file" id="addRoomPhotoFile" accept="image/jpeg,image/jpg,image/png,image/webp" style="display: none;">
                                    <div id="addPhotoDropContent" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 30px 20px; text-align: center;">
                                        <i class="fas fa-cloud-upload-alt" style="font-size: 40px; color: #667eea; margin-bottom: 12px;"></i>
                                        <strong style="color: #374151; font-size: 13px;">Drag & Drop atau Klik untuk Upload</strong>
                                        <span style="color: #9CA3AF; font-size: 11px; margin-top: 4px;">JPG, JPEG, PNG, WEBP — Max 2MB</span>
                                    </div>
                                    <div id="addPhotoPreviewArea" style="display: none;">
                                        <img id="addPhotoPreviewImg" src="" alt="Preview" style="max-width: 100%; max-height: 220px; border-radius: 6px; margin-bottom: 10px;">
                                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 10px;">
                                            <div>
                                                <strong id="addPhotoFileName" style="font-size: 12px; color: #374151;">-</strong>
                                                <br><small id="addPhotoFileSize" style="color: #9CA3AF; font-size: 11px;">-</small>
                                            </div>
                                            <button type="button" class="btn-cancel" onclick="removeAddPhoto()" style="padding: 6px 12px; font-size: 11px;">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" style="margin-top: 12px;">
                                <label>Atau URL Gambar</label>
                                <input type="text" name="photos" id="addRoomPhotosUrl" placeholder="https://example.com/kamar.jpg">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer--sticky">
                <button type="button" class="btn-cancel" onclick="closeAddRoomModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-submit" style="padding: 10px 28px;">
                    <i class="fas fa-save"></i> Simpan Kamar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT ROOM MODAL -->
<div class="modal modal--edit-room" id="editRoomModal">
    <div class="modal-content modal-content--edit-room">
        <div class="modal-header modal-header--sticky">
            <div>
                <div class="modal-header-title">Edit Kamar</div>
                <div class="modal-header-subtitle">Perbarui informasi dan detail kamar</div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeEditRoomModal()" title="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="" id="editRoomForm" class="edit-room-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-body--scrollable">
                <!-- SECTION: INFORMASI KAMAR -->
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="fas fa-bed"></i>
                        <span>Informasi Kamar</span>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Nomor Kamar <span class="required-mark">*</span></label>
                            <input type="text" name="number" required placeholder="Contoh: A-101">
                        </div>
                        <div class="form-group">
                            <label>Tipe Kamar <span class="required-mark">*</span></label>
                            <select name="type" required>
                                <option value="">Pilih Tipe</option>
                                <option value="standard">Standard</option>
                                <option value="deluxe">Deluxe</option>
                                <option value="premium">Premium</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Harga/Bulan (Rp) <span class="required-mark">*</span></label>
                            <input type="number" required name="price" min="0" placeholder="Contoh: 1500000">
                        </div>
                        <div class="form-group">
                            <label>Lantai <span class="required-mark">*</span></label>
                            <input type="number" required name="floor" min="1" placeholder="Contoh: 1">
                        </div>
                        <div class="form-group">
                            <label>Kapasitas (orang) <span class="required-mark">*</span></label>
                            <input type="number" required name="capacity" min="1" placeholder="Contoh: 2">
                        </div>
                        <div class="form-group">
                            <label>Slot Tersedia <span class="required-mark">*</span></label>
                            <input type="number" required name="slots" min="0" placeholder="Contoh: 1">
                        </div>
                    </div>
                </div>

                <!-- SECTION: DETAIL KAMAR -->
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="fas fa-info-circle"></i>
                        <span>Detail Kamar</span>
                    </div>
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <textarea name="description" rows="4" placeholder="Deskripsi lengkap kamar, kondisi, ukuran, fasilitas tambahan..."></textarea>
                    </div>
                    <div class="form-group">
                        <label>Fasilitas</label>
                        <input type="text" name="facilities" placeholder="Contoh: AC, WiFi, Kamar Mandi Dalam, Lemari, Meja Belajar">
                    </div>
                </div>

                <!-- SECTION: MEDIA -->
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="fas fa-image"></i>
                        <span>Media</span>
                    </div>
                    <div class="form-group">
                        <label>Upload Gambar <span style="font-weight: 400; color: #999; font-size: 11px;">(JPG, JPEG, PNG, WEBP)</span></label>
                        <div class="photo-upload-area">
                            <input type="file" name="photo_file" id="editRoomPhotoFile" accept="image/jpeg,image/jpg,image/png,image/webp" style="padding: 8px;">
                            <p style="font-size: 11px; color: #999; margin-top: 6px;">Unggah langsung dari perangkat Anda</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Atau URL Gambar</label>
                        <input type="text" name="photos" id="editRoomPhotos" placeholder="https://example.com/kamar.jpg">
                        <p style="font-size: 11px; color: #999; margin-top: 4px;">Masukkan URL gambar eksternal</p>
                    </div>
                    <div class="form-group">
                        <label>Preview Gambar</label>
                        <div class="photo-preview" id="photoPreview" style="border: 2px dashed #D1D5DB; border-radius: 8px; min-height: 150px; display: flex; align-items: center; justify-content: center;">
                            <img id="photoPreviewImg" src="" alt="Preview Foto" style="display: none; max-width: 100%; max-height: 280px; border-radius: 6px;">
                            <div id="photoPreviewPlaceholder" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px; color: #999; font-size: 13px;">
                                <i class="fas fa-image" style="font-size: 48px; margin-bottom: 12px; color: #CCC;"></i>
                                <span>Preview akan muncul di sini</span>
                                <span style="font-size: 11px; margin-top: 4px;">Upload file atau masukkan URL</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION: STATUS -->
                <div class="form-section">
                    <div class="form-section-header">
                        <i class="fas fa-toggle-on"></i>
                        <span>Status</span>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Status Kamar <span class="required-mark">*</span></label>
                            <select name="status" required>
                                <option value="available">Kosong</option>
                                <option value="occupied">Terisi</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer--sticky">
                <button type="button" class="btn-cancel" onclick="closeEditRoomModal()">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE ROOM CONFIRMATION MODAL -->
<div class="modal modal--edit-room" id="deleteRoomModal">
    <div class="modal-content modal-content--edit-room" style="max-width: 650px;">
        <div class="modal-header modal-header--sticky" style="color: #DC2626;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fas fa-exclamation-triangle" style="font-size: 24px;"></i>
                <div>
                    <div class="modal-header-title" style="color: #DC2626;">Hapus Kamar</div>
                    <div class="modal-header-subtitle">Periksa booking terkait sebelum menghapus</div>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeDeleteRoomModal()" title="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body--scrollable" style="padding: 20px 24px;">
            <p style="font-size: 14px; color: #6B7280; margin-bottom: 16px;">
                Anda akan menghapus kamar <strong id="deleteRoomName" style="color: #111827;">-</strong>.
            </p>

            <!-- Loading -->
            <div id="deleteRoomLoading" style="text-align: center; padding: 20px; display: none;">
                <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: #667eea;"></i>
                <p style="font-size: 13px; color: #999; margin-top: 8px;">Memeriksa data booking...</p>
            </div>

            <!-- Tidak ada booking -->
            <div id="deleteRoomNoBookings" style="display: none; padding: 16px; border-radius: 8px; background: #D1FAE5; color: #065F46; font-size: 13px; margin-bottom: 16px;">
                <i class="fas fa-check-circle"></i> Tidak ada booking aktif. Kamar aman untuk dihapus.
            </div>

            <!-- Booking Aktif (tidak bisa hapus) -->
            <div id="deleteRoomActiveWarning" style="display: none; padding: 12px 16px; border-radius: 8px; background: #FEE2E2; color: #991B1B; font-size: 13px; margin-bottom: 16px;">
                <i class="fas fa-exclamation-circle"></i> <strong>Kamar tidak dapat dihapus</strong> karena masih memiliki booking dengan status <strong>Dibayar</strong>.
            </div>

            <!-- DAFTAR BOOKING -->
            <div id="deleteRoomBookingsList" style="display: none; margin-bottom: 16px;">
                <h4 style="font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 10px;">Booking Terkait:</h4>
                <div id="deleteRoomBookingsInner"></div>
            </div>
        </div>

        <form method="POST" action="" id="deleteRoomForm" style="display: none;">
            @csrf
            @method('DELETE')
            <div class="modal-footer modal-footer--sticky" style="display: flex; gap: 10px; justify-content: flex-end; flex-wrap: wrap;">
                <button type="button" class="btn-cancel" onclick="closeDeleteRoomModal()">Batal</button>
                <button type="submit" id="deleteRoomBtn" class="btn-submit" style="background: #DC2626;">
                    <i class="fas fa-trash"></i> Hapus Kamar
                </button>
            </div>
        </form>

        <!-- Footer untuk kasus ada booking aktif -->
        <div id="deleteRoomFooterActions" class="modal-footer modal-footer--sticky" style="display: none; gap: 10px; justify-content: flex-end; flex-wrap: wrap;">
            <button type="button" class="btn-cancel" onclick="closeDeleteRoomModal()">Tutup</button>
        </div>
    </div>
</div>

<!-- TENANT MODALS -->
<div class="modal modal--add-room" id="addTenantModal">
    <div class="modal-content modal-content--add-room">
        <div class="modal-header modal-header--sticky">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; font-size: 20px; color: white;">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <div class="modal-header-title">Tambah Penyewa Baru</div>
                    <div class="modal-header-subtitle">Daftarkan penyewa baru ke kamar yang tersedia</div>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeAddTenantModal()" title="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.tenants.store') }}" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
            @csrf
            <div class="modal-body--scrollable">
                <div class="form-section-card">
                    <div class="form-section-header">
                        <i class="fas fa-user"></i>
                        <span>Data Penyewa</span>
                    </div>
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
                </div>
                <div class="form-section-card">
                    <div class="form-section-header">
                        <i class="fas fa-door-open"></i>
                        <span>Informasi Kamar & Sewa</span>
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
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Tanggal Masuk</label>
                            <input type="date" name="lease_start" required value="{{ now()->toDateString() }}">
                        </div>
                        <div class="form-group">
                            <label>Tanggal Keluar</label>
                            <input type="date" name="lease_end">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer--sticky">
                <button type="button" class="btn-cancel" onclick="closeAddTenantModal()">Batal</button>
                <button type="submit" class="btn-submit" {{ $availableRooms->isEmpty() || $availableUsers->isEmpty() ? 'disabled' : '' }}>Tambah</button>
            </div>
        </form>
    </div>
</div>

<div class="modal modal--add-room" id="editTenantModal">
    <div class="modal-content modal-content--add-room">
        <div class="modal-header modal-header--sticky">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; font-size: 20px; color: white;">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div>
                    <div class="modal-header-title">Edit Penyewa</div>
                    <div class="modal-header-subtitle">Perbarui data penyewa dan informasi sewa</div>
                </div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeEditTenantModal()" title="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="" id="editTenantForm" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
            @csrf
            @method('PUT')
            <div class="modal-body--scrollable">
                <div class="form-section-card">
                    <div class="form-section-header">
                        <i class="fas fa-user"></i>
                        <span>Data Penyewa</span>
                    </div>
                    <div class="form-group">
                        <label>Nama Penyewa</label>
                        <input type="text" id="editTenantName" disabled style="background: #F5F5F7;">
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input type="tel" name="phone" required>
                    </div>
                    <div class="form-group">
                        <label>Nomor KTP</label>
                        <input type="text" name="identity_number" placeholder="Masukkan nomor KTP">
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <input type="text" name="address" placeholder="Alamat lengkap">
                    </div>
                </div>
                <div class="form-section-card">
                    <div class="form-section-header">
                        <i class="fas fa-door-open"></i>
                        <span>Informasi Kamar & Sewa</span>
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
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label>Tanggal Masuk</label>
                            <input type="date" name="lease_start" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Keluar</label>
                            <input type="date" name="lease_end">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer modal-footer--sticky">
                <button type="button" class="btn-cancel" onclick="closeEditTenantModal()">Batal</button>
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ============================================
    // ROOM MANAGEMENT MODALS
    // ============================================
    function openAddRoomModal() {
        // Reset form
        const form = document.getElementById('addRoomForm');
        if (form) form.reset();
        document.getElementById('addRoomPrice').value = '';
        document.getElementById('addRoomPriceHidden').value = '';
        document.getElementById('addRoomPhotosUrl').value = '';
        removeAddPhoto();
        // Reset status radio to available
        const availableRadio = form.querySelector('input[name="status"][value="available"]');
        if (availableRadio) availableRadio.checked = true;
        document.getElementById('addRoomModal').classList.add('show');
    }

    function closeAddRoomModal() {
        document.getElementById('addRoomModal').classList.remove('show');
    }

    // ============================================
    // PRICE FORMATTING (Rupiah)
    // ============================================
    (function() {
        const priceDisplay = document.getElementById('addRoomPrice');
        const priceHidden = document.getElementById('addRoomPriceHidden');

        if (priceDisplay && priceHidden) {
            priceDisplay.addEventListener('input', function() {
                let raw = this.value.replace(/[^0-9]/g, '');
                if (raw === '') {
                    this.value = '';
                    priceHidden.value = '';
                    return;
                }
                let num = parseInt(raw, 10);
                if (isNaN(num)) num = 0;
                priceHidden.value = num;
                this.value = num.toLocaleString('id-ID');
            });

            priceDisplay.addEventListener('blur', function() {
                let raw = this.value.replace(/[^0-9]/g, '');
                if (raw !== '') {
                    let num = parseInt(raw, 10);
                    if (!isNaN(num)) {
                        this.value = num.toLocaleString('id-ID');
                        priceHidden.value = num;
                    }
                }
            });
        }

        // Before form submit, ensure hidden price has value
        const addRoomForm = document.getElementById('addRoomForm');
        if (addRoomForm) {
            addRoomForm.addEventListener('submit', function(e) {
                if (!priceHidden.value || parseInt(priceHidden.value) <= 0) {
                    e.preventDefault();
                    priceDisplay.style.borderColor = '#EF4444';
                    alert('Harap isi harga kamar.');
                    return;
                }
                priceDisplay.style.borderColor = '';
            });
        }
    })();

    // ============================================
    // ADD ROOM PHOTO UPLOAD (Drag & Drop)
    // ============================================
    (function() {
        const uploadArea = document.getElementById('addPhotoUploadArea');
        const fileInput = document.getElementById('addRoomPhotoFile');
        const dropContent = document.getElementById('addPhotoDropContent');
        const previewArea = document.getElementById('addPhotoPreviewArea');
        const previewImg = document.getElementById('addPhotoPreviewImg');
        const fileNameEl = document.getElementById('addPhotoFileName');
        const fileSizeEl = document.getElementById('addPhotoFileSize');
        const urlInput = document.getElementById('addRoomPhotosUrl');

        if (!uploadArea || !fileInput) return;

        // Click to open file dialog
        uploadArea.addEventListener('click', function(e) {
            if (e.target.tagName !== 'BUTTON') {
                fileInput.click();
            }
        });

        // Drag & drop events
        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                uploadArea.style.borderColor = '#667eea';
                uploadArea.style.background = '#EEF2FF';
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, function(e) {
                e.preventDefault();
                e.stopPropagation();
                uploadArea.style.borderColor = '#D1D5DB';
                uploadArea.style.background = '#FAFAFA';
            });
        });

        uploadArea.addEventListener('drop', function(e) {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleAddPhotoFile(files[0]);
            }
        });

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) handleAddPhotoFile(file);
        });

        function handleAddPhotoFile(file) {
            // Validate type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.');
                return;
            }

            // Validate size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar. Maksimal 2MB.');
                return;
            }

            // Preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                dropContent.style.display = 'none';
                previewArea.style.display = 'block';
            };
            reader.readAsDataURL(file);

            fileNameEl.textContent = file.name;
            fileSizeEl.textContent = formatFileSize(file.size);

            // Clear URL input if file selected
            if (urlInput) urlInput.value = '';
        }

        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' B';
            if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            return (bytes / 1048576).toFixed(1) + ' MB';
        }
    })();

    function removeAddPhoto() {
        const dropContent = document.getElementById('addPhotoDropContent');
        const previewArea = document.getElementById('addPhotoPreviewArea');
        const fileInput = document.getElementById('addRoomPhotoFile');
        const urlInput = document.getElementById('addRoomPhotosUrl');
        if (dropContent) dropContent.style.display = 'flex';
        if (previewArea) previewArea.style.display = 'none';
        if (fileInput) fileInput.value = '';
    }

    function openEditRoomModal(room) {
        const form = document.getElementById('editRoomForm');
        form.action = "{{ url('/dashboard/admin/rooms') }}/" + room.id;
        form.querySelector('[name="number"]').value = room.number;
        form.querySelector('[name="type"]').value = room.type;
        form.querySelector('[name="price"]').value = parseFloat(room.price);
        form.querySelector('[name="floor"]').value = room.floor;
        form.querySelector('[name="capacity"]').value = room.capacity || 1;
        form.querySelector('[name="slots"]').value = room.slots ?? room.capacity ?? 1;
        form.querySelector('[name="facilities"]').value = room.facilities || '';
        form.querySelector('[name="description"]').value = room.description || '';
        form.querySelector('[name="photos"]').value = room.photos || '';
        form.querySelector('[name="status"]').value = room.status;
        // Reset file input
        const fileInput = document.getElementById('editRoomPhotoFile');
        if (fileInput) fileInput.value = '';
        // Show preview from existing photos URL
        updatePhotoPreview(room.photos || '');
        document.getElementById('editRoomModal').classList.add('show');
    }

    function closeEditRoomModal() {
        document.getElementById('editRoomModal').classList.remove('show');
    }

    // ============================================
    // DELETE ROOM MODAL
    // ============================================
    let deleteRoomId = null;

    function openDeleteRoomModal(room) {
        deleteRoomId = room.id;
        document.getElementById('deleteRoomName').textContent = room.number + ' - ' + room.type;
        document.getElementById('deleteRoomForm').action = "{{ url('/dashboard/admin/rooms') }}/" + room.id;

        // Reset tampilan
        document.getElementById('deleteRoomForm').style.display = 'none';
        document.getElementById('deleteRoomFooterActions').style.display = 'none';
        document.getElementById('deleteRoomNoBookings').style.display = 'none';
        document.getElementById('deleteRoomActiveWarning').style.display = 'none';
        document.getElementById('deleteRoomBookingsList').style.display = 'none';
        document.getElementById('deleteRoomBookingsInner').innerHTML = '';
        document.getElementById('deleteRoomLoading').style.display = 'block';

        document.getElementById('deleteRoomModal').classList.add('show');

        // Fetch data booking
        fetch("{{ url('/dashboard/admin/rooms') }}/" + room.id + "/bookings-status")
            .then(res => res.json())
            .then(data => {
                document.getElementById('deleteRoomLoading').style.display = 'none';
                const bookings = data.bookings || [];
                const hasActive = data.active_count > 0;

                if (bookings.length === 0) {
                    // Tidak ada booking sama sekali
                    document.getElementById('deleteRoomNoBookings').style.display = 'block';
                    document.getElementById('deleteRoomForm').style.display = 'block';
                } else if (hasActive) {
                    // Ada booking aktif (Dibayar) — tidak bisa hapus
                    document.getElementById('deleteRoomActiveWarning').style.display = 'block';
                    document.getElementById('deleteRoomBookingsList').style.display = 'block';
                    document.getElementById('deleteRoomFooterActions').style.display = 'flex';
                    renderBookingList(bookings, true);
                } else {
                    // Ada booking tapi semuanya non-aktif — bisa hapus
                    document.getElementById('deleteRoomBookingsList').style.display = 'block';
                    document.getElementById('deleteRoomForm').style.display = 'block';
                    renderBookingList(bookings, false);
                }
            })
            .catch(() => {
                document.getElementById('deleteRoomLoading').style.display = 'none';
                document.getElementById('deleteRoomNoBookings').style.display = 'block';
                document.getElementById('deleteRoomForm').style.display = 'block';
            });
    }

    function renderBookingList(bookings, hasActive) {
        const container = document.getElementById('deleteRoomBookingsInner');
        container.innerHTML = '';

        bookings.forEach((b, idx) => {
            const isActive = b.is_active || b.status === 'Dibayar';
            const statusBadgeColors = {
                'Dibayar': { bg: '#D1FAE5', color: '#065F46', label: 'Dibayar' },
                'Menunggu Pembayaran': { bg: '#DBEAFE', color: '#1D4ED8', label: 'Menunggu Pembayaran' },
                'Pending': { bg: '#FEF3C7', color: '#92400E', label: 'Pending' },
                'Dibatalkan': { bg: '#FEE2E2', color: '#991B1B', label: 'Dibatalkan' },
                'Gagal': { bg: '#FEE2E2', color: '#991B1B', label: 'Gagal' },
                'Ditolak': { bg: '#FEE2E2', color: '#991B1B', label: 'Ditolak' },
                'Kadaluarsa': { bg: '#F3F4F6', color: '#6B7280', label: 'Kadaluarsa' },
                'Refund': { bg: '#F3F4F6', color: '#6B7280', label: 'Refund' },
            };
            const badge = statusBadgeColors[b.status] || { bg: '#F3F4F6', color: '#6B7280', label: b.status };

            const formattedAmount = 'Rp ' + (parseFloat(b.gross_amount || b.room_price || 0)).toLocaleString('id-ID');

            const midtransStatus = b.midtrans_transaction_status || '-';
            const midtransLabel = {
                'settlement': 'Settlement',
                'capture': 'Capture',
                'pending': 'Pending',
                'deny': 'Deny',
                'expire': 'Expired',
                'cancel': 'Cancelled',
                'refund': 'Refund',
            }[midtransStatus] || midtransStatus;

            const div = document.createElement('div');
            div.style.cssText = 'padding: 14px; border: 1px solid #E5E7EB; border-radius: 10px; margin-bottom: 10px; background: ' + (isActive ? '#FFF7ED' : '#FAFBFC') + ';';
            div.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px; margin-bottom: 10px;">
                    <strong style="font-size: 13px; color: #111827;">${b.customer_name || '-'}</strong>
                    <span style="display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; background: ${badge.bg}; color: ${badge.color};">${badge.label}</span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 6px; font-size: 12px; color: #6B7280;">
                    <div><i class="fas fa-hashtag" style="width: 16px;"></i> <strong>No:</strong> ${b.booking_number}</div>
                    <div><i class="fas fa-envelope" style="width: 16px;"></i> <strong>Email:</strong> ${b.customer_email || '-'}</div>
                    <div><i class="fas fa-door-open" style="width: 16px;"></i> <strong>Kamar:</strong> ${b.room_name}</div>
                    <div><i class="fas fa-money-bill" style="width: 16px;"></i> <strong>Nominal:</strong> ${formattedAmount}</div>
                    <div><i class="fas fa-calendar" style="width: 16px;"></i> <strong>Tgl Booking:</strong> ${b.created_at || '-'}</div>
                    <div><i class="fas fa-credit-card" style="width: 16px;"></i> <strong>Midtrans:</strong> ${midtransLabel}</div>
                    ${b.paid_at ? '<div><i class="fas fa-check-circle" style="width: 16px;"></i> <strong>Dibayar:</strong> ' + b.paid_at + '</div>' : '<div></div>'}
                    <div><i class="fas fa-tag" style="width: 16px;"></i> <strong>Metode:</strong> ${b.payment_method || '-'}</div>
                </div>
                ${!isActive ? `
                <div style="display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap;">
                    ${(b.status === 'Menunggu Pembayaran' || b.status === 'Pending') ? `
                    <button onclick="cancelBooking(${b.booking_id})" style="padding: 6px 12px; background: #F59E0B; color: #FFF; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-ban"></i> Batalkan
                    </button>` : ''}
                    <button onclick="deleteBooking(${b.booking_id})" style="padding: 6px 12px; background: #EF4444; color: #FFF; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer;">
                        <i class="fas fa-trash"></i> Hapus Booking
                    </button>
                </div>` : ''}
            `;
            container.appendChild(div);
        });
    }

    async function cancelBooking(bookingId) {
        if (!confirm('Batalkan booking ini?')) return;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        try {
            const res = await fetch("{{ url('/dashboard/admin/bookings') }}/" + bookingId + "/cancel", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                alert('Booking berhasil dibatalkan.');
                closeDeleteRoomModal();
                location.reload();
            } else {
                alert(data.message || 'Gagal membatalkan booking.');
            }
        } catch (e) {
            alert('Terjadi kesalahan.');
        }
    }

    async function deleteBooking(bookingId) {
        if (!confirm('Hapus booking ini secara permanen?')) return;
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        try {
            const res = await fetch("{{ url('/dashboard/admin/bookings') }}/" + bookingId, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                alert('Booking berhasil dihapus.');
                closeDeleteRoomModal();
                location.reload();
            } else {
                alert(data.message || 'Gagal menghapus booking.');
            }
        } catch (e) {
            alert('Terjadi kesalahan.');
        }
    }

    function closeDeleteRoomModal() {
        document.getElementById('deleteRoomModal').classList.remove('show');
    }

    // ============================================
    // PHOTO PREVIEW HANDLERS
    // ============================================
    (function() {
        const photoInput = document.getElementById('editRoomPhotos');
        const fileInput = document.getElementById('editRoomPhotoFile');

        if (photoInput) {
            photoInput.addEventListener('input', function() {
                updatePhotoPreview(this.value);
                // Clear file input when URL is entered
                if (this.value && fileInput) fileInput.value = '';
            });
        }

        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        updatePhotoPreview(e.target.result);
                    };
                    reader.readAsDataURL(file);
                    // Clear URL input when file is selected
                    if (photoInput) photoInput.value = '';
                }
            });
        }
    })();

    function updatePhotoPreview(src) {
        const img = document.getElementById('photoPreviewImg');
        const placeholder = document.getElementById('photoPreviewPlaceholder');
        if (!img || !placeholder) return;
        if (src && src.trim()) {
            img.src = src.trim();
            img.style.display = 'block';
            placeholder.style.display = 'none';
        } else {
            img.style.display = 'none';
            img.src = '';
            placeholder.style.display = 'flex';
        }
    }

    // ============================================
    // TENANT MANAGEMENT MODALS
    // ============================================
    function openAddTenantModal() {
        document.getElementById('addTenantModal').classList.add('show');
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
</script>