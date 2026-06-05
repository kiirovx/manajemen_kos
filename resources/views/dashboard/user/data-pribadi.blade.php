<!-- DATA PRIBADI PAGE -->
        <div class="page" id="data-pribadi">
            <div class="page-header">
                <h1 class="page-title">Data Pribadi</h1>
                <p class="page-subtitle">Kelola informasi pribadi Anda</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pribadi</h3>
                </div>

                <form onsubmit="handleSaveProfile(event)">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" value="Ahmad Rifai" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="ahmad@email.com" required>
                        </div>

                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="tel" value="0812-3456-7890" required>
                        </div>

                        <div class="form-group">
                            <label>Nomor Identitas (KTP/SIM)</label>
                            <input type="text" value="1234567890123456" required>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" value="1995-08-15" required>
                        </div>

                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" value="Jakarta" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alamat Asal</label>
                        <textarea required>Jalan Pendidikan No. 123, Jakarta Selatan</textarea>
                    </div>

                    <div class="form-group">
                        <label>Pekerjaan/Institusi</label>
                        <input type="text" value="Mahasiswa - Universitas Indonesia" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Orang Tua/Wali</label>
                        <input type="text" value="Siti Rahmah" required>
                    </div>

                    <div class="form-group">
                        <label>Telepon Orang Tua/Wali</label>
                        <input type="tel" value="0811-2345-6789" required>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                            <i class="fas fa-redo"></i> Reset
                        </button>
                    </div>
                </form>
            </div>

            <!-- JAMINAN KAMAR -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Jaminan Kamar</h3>
                </div>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <td style="font-weight: 600;">Jumlah Jaminan</td>
                            <td>Rp 3.000.000</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Tanggal Diserah</td>
                            <td>01 Januari 2024</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Status</td>
                            <td><span class="badge badge-success">Tersimpan dengan Aman</span></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Catatan</td>
                            <td>Jaminan akan dikembalikan jika tidak ada kerusakan pada saat pindah</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

<script>
    // ============================================
    // PROFILE FORM HANDLERS
    // ============================================
    function handleSaveProfile(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        
        console.log('Saving profile:', Object.fromEntries(formData));
        
        showNotification('Data pribadi berhasil disimpan!', 'success');
    }

    function resetForm() {
        if (confirm('Apakah Anda yakin ingin mereset form?')) {
            const form = document.querySelector('#data-pribadi form');
            if (form) form.reset();
        }
    }
</script>

        