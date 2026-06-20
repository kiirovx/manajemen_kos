@php
    $profile = $user->tenantProfile;
@endphp
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

                <form id="profileForm" onsubmit="handleSaveProfile(event)">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name }}" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" required>
                        </div>

                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="tel" name="phone" value="{{ $profile?->phone }}" required>
                        </div>

                        <div class="form-group">
                            <label>Nomor Identitas (KTP/SIM)</label>
                            <input type="text" name="identity_number" value="{{ $profile?->identity_number }}" required>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ $profile?->birth_date?->format('Y-m-d') }}" required>
                        </div>

                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <input type="text" name="birth_place" value="{{ $profile?->birth_place }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alamat Asal</label>
                        <textarea name="address" required>{{ $profile?->address }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Pekerjaan/Institusi</label>
                        <input type="text" name="occupation" value="{{ $profile?->occupation }}" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Orang Tua/Wali</label>
                        <input type="text" name="parent_name" value="{{ $profile?->parent_name }}" required>
                    </div>

                    <div class="form-group">
                        <label>Telepon Orang Tua/Wali</label>
                        <input type="tel" name="parent_phone" value="{{ $profile?->parent_phone }}" required>
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
                            <td>Rp {{ number_format($profile?->deposit_amount ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Tanggal Diserah</td>
                            <td>{{ $profile?->deposit_date?->format('d F Y') ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Status</td>
                            <td><span class="badge badge-success">Tersimpan dengan Aman</span></td>
                        </tr>
                        <tr>
                            <td style="font-weight: 600;">Catatan</td>
                            <td>{{ $profile?->deposit_notes ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

<script>
    async function handleSaveProfile(event) {
        event.preventDefault();

        const form = event.target;
        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());

        try {
            const response = await fetch('{{ route('dashboard.profile.update') }}', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json();

            if (data.success) {
                showNotification(data.message, 'success');
                document.getElementById('userNameDisplay').textContent = payload.name;
            } else {
                showNotification('Gagal menyimpan data.', 'error');
            }
        } catch (error) {
            showNotification('Terjadi kesalahan saat menyimpan data.', 'error');
        }
    }

    function resetForm() {
        if (confirm('Apakah Anda yakin ingin mereset form?')) {
            const form = document.getElementById('profileForm');
            if (form) form.reset();
        }
    }
</script>
