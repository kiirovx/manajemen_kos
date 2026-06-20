        <div class="page" id="pengaturan">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Pengaturan</h1>
                    <p class="page-subtitle">Kelola preferensi dan konfigurasi sistem</p>
                </div>
            </div>

            <div class="table-container" style="max-width: 500px;">
                <div style="padding: 30px;">
                    <h3 style="margin-bottom: 20px;">Pengaturan Umum</h3>

                    <div class="form-group">
                        <label>Nama Usaha</label>
                        <input type="text" value="KosKita" readonly>
                    </div>

                    <div class="form-group">
                        <label>Email Admin</label>
                        <input type="email" value="admin@koskita.com" readonly>
                    </div>

                    <div class="form-group">
                        <label>Tema</label>
                        <select>
                            <option selected>Light</option>
                            <option>Dark</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Bahasa</label>
                        <select>
                            <option selected>Bahasa Indonesia</option>
                            <option>English</option>
                        </select>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 30px;">
                        <button class="btn-submit" onclick="saveSettings()">Simpan Perubahan</button>
                        <button class="btn-cancel">Batal</button>
                    </div>
                </div>
            </div>
        </div>

<script>
    // ============================================
    // SAVE SETTINGS
    // ============================================
    function saveSettings() {
        showNotification('Pengaturan berhasil disimpan', 'success');
    }
</script>
