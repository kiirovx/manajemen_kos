<!-- STEP 2: ISI DATA DIRI -->
<div class="form-section" id="step2">
    <h2 class="page-title">Booking Sekarang</h2>
    <p class="page-subtitle">Isi Form dibawah ini untuk Booking Kamar</p>

    <div class="selected-room-display" id="selectedRoomDisplay"></div>

    <form class="booking-form" onsubmit="nextStep(2); return false;">
        <div class="form-group">
            <label for="nama">Nama Lengkap</label>
            <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" required>
        </div>

        <div class="form-group">
            <label for="email">Alamat Email</label>
            <input type="email" id="email" name="email" placeholder="nama@email.com" required>
        </div>

        <div class="form-group">
            <label for="telepon">Nomor Telepon</label>
            <input type="tel" id="telepon" name="telepon" placeholder="+62 812-3456-7890" required>
        </div>

        <div class="form-group">
            <label for="pesan">Pesan</label>
            <textarea id="pesan" name="pesan" placeholder="Tulis pesan Anda di sini..."></textarea>
        </div>

        <div class="button-group">
            <button type="button" class="btn btn-back" onclick="previousStep(2)">
                <i class="fas fa-arrow-left" style="margin-right: 8px;"></i> Kembali
            </button>
            <button type="submit" class="btn btn-next">
                Lanjutkan <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
            </button>
        </div>
    </form>
</div>
