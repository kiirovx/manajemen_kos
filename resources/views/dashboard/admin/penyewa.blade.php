        <div class="page" id="manajemen-penyewa">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Manajemen Penyewa</h1>
                    <p class="page-subtitle">Kelola data penyewa kos-kosan Anda</p>
                </div>
                <button class="btn-primary" onclick="openAddTenantModal()">
                    <i class="fas fa-plus"></i>
                    Tambah Penyewa
                </button>
            </div>

            @if (session('success'))
                <div style="margin-bottom: 20px; padding: 12px 16px; border-radius: 8px; background: #D1FAE5; color: #065F46; font-size: 13px; font-weight: 600;">
                    {{ session('success') }}
                </div>
            @endif

            @if (isset($errors) && $errors->any())
                <div style="margin-bottom: 20px; padding: 12px 16px; border-radius: 8px; background: #FEE2E2; color: #7F1D1D; font-size: 13px;">
                    {{ $errors->first() }}
                </div>
            @endif

                <!-- STAT CARDS -->
                <div class="stats-grid" style="margin-bottom: 30px;">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Penyewa</h3>
                        <div class="number">{{ $tenantStats['total'] }}</div>
                    </div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon" style="background: #10B981;">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Aktif</h3>
                        <div class="number">{{ $tenantStats['active'] }}</div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom-color: #F472B6;">
                    <div class="stat-icon" style="background: #F472B6;">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Menunggak</h3>
                        <div class="number">{{ $tenantStats['overdue'] }}</div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom-color: #A78BFA;">
                    <div class="stat-icon" style="background: #A78BFA;">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Baru Bulan Ini</h3>
                        <div class="number">{{ $tenantStats['new_this_month'] }}</div>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-container">
                <div class="table-header">
                    <h3 class="table-title">Daftar Penyewa</h3>
                    <div class="table-actions">
                        <input type="text" class="search-box" placeholder="Cari berdasarkan nama atau nomor kamar...">
                        <button class="filter-btn">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Penyewa</th>
                                <th>Kamar</th>
                                <th>Kontak</th>
                                <th>Check-in</th>
                                <th>Pembayaran Terakhir</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tenants as $tenant)
                                @php
                                    $payments = $tenant->user?->payments ?? collect();
                                    $lastPaid = $payments->firstWhere('status', 'paid');
                                    $hasOverdue = $payments
                                        ->where('status', 'pending')
                                        ->where('due_date', '<', now()->startOfDay())
                                        ->isNotEmpty();
                                    $initial = strtoupper(substr($tenant->user?->name ?? '?', 0, 1));
                                @endphp
                                <tr>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div
                                                style="width: 35px; height: 35px; border-radius: 50%; background: #667eea; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                                {{ $initial }}</div>
                                            <div>
                                                <p style="margin: 0; font-weight: 600;">{{ $tenant->user?->name ?? '-' }}</p>
                                                <p style="margin: 3px 0 0 0; font-size: 12px; color: #999;">{{ $tenant->user?->email ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $tenant->room ? 'Kamar ' . $tenant->room->number : '-' }}</td>
                                    <td>{{ $tenant->phone ?? '-' }}</td>
                                    <td>{{ $tenant->lease_start?->format('d M Y') ?? '-' }}</td>
                                    <td>{{ $lastPaid?->paid_date?->format('d M Y') ?? '-' }}</td>
                                    <td>
                                        @php
                                            $tenantStatusColors = [
                                                'Menunggu Persetujuan' => ['class' => 'warning', 'label' => 'Menunggu Persetujuan'],
                                                'Aktif' => ['class' => 'success', 'label' => 'Aktif'],
                                                'Keluar' => ['class' => 'info', 'label' => 'Keluar'],
                                                'Nonaktif' => ['class' => 'danger', 'label' => 'Nonaktif'],
                                                'Ditolak' => ['class' => 'danger', 'label' => 'Ditolak'],
                                            ];
                                            $tStatus = $tenantStatusColors[$tenant->status] ?? ['class' => 'info', 'label' => $tenant->status ?? 'Aktif'];
                                        @endphp
                                        <span class="badge {{ $tStatus['class'] }}">{{ $tStatus['label'] }}</span>
                                    </td>
                                    <td style="display: flex; gap: 8px; align-items: center;">
                                        <button class="action-btn" title="Tagihan" style="color: #10B981;" onclick="openTenantBillsModal({{ $tenant->id }})">
                                            <i class="fas fa-file-invoice-dollar"></i>
                                        </button>
                                        <button class="action-btn" title="Edit"
                                            onclick='openEditTenantModal({{ json_encode([
                                                'id' => $tenant->id,
                                                'user_name' => $tenant->user?->name,
                                                'room_id' => $tenant->room_id,
                                                'room_number' => $tenant->room?->number,
                                                'phone' => $tenant->phone,
                                                'lease_start' => $tenant->lease_start?->toDateString(),
                                                'lease_end' => $tenant->lease_end?->toDateString(),
                                                'identity_number' => $tenant->identity_number,
                                                'address' => $tenant->address,
                                            ]) }})'>
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if(in_array($tenant->status, ['Aktif', 'Nonaktif']))
                                        <form method="POST" action="{{ route('admin.tenants.check-out', $tenant) }}" style="display: inline;" onsubmit="return confirm('Check-out penyewa ini? Kamar akan dikembalikan ke status tersedia.')">
                                            @csrf
                                            <button type="submit" class="action-btn" title="Check-out" style="color: #F59E0B;">
                                                <i class="fas fa-sign-out-alt"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #999;">Belum ada data penyewa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

<!-- MODAL TAGIHAN PENYEWA -->
<div class="modal modal--edit-room" id="tenantBillsModal">
    <div class="modal-content modal-content--edit-room" style="max-width: 650px;">
        <div class="modal-header modal-header--sticky">
            <div>
                <div class="modal-header-title">Tagihan Penyewa</div>
                <div class="modal-header-subtitle" id="tenantBillsTitle">-</div>
            </div>
            <button type="button" class="modal-close-btn" onclick="closeTenantBillsModal()" title="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body--scrollable" style="padding: 20px 24px;">
            <!-- Loading -->
            <div id="tenantBillsLoading" style="text-align: center; padding: 20px;">
                <i class="fas fa-spinner fa-spin" style="font-size: 24px; color: #667eea;"></i>
                <p style="font-size: 13px; color: #999; margin-top: 8px;">Memuat data tagihan...</p>
            </div>

            <!-- Daftar Tagihan -->
            <div id="tenantBillsList" style="display: none;"></div>

            <!-- Form Buat Tagihan -->
            <div id="tenantBillsForm" style="display: none; border-top: 1px solid #E5E7EB; margin-top: 20px; padding-top: 20px;">
                <h4 style="font-size: 13px; font-weight: 700; color: #374151; margin-bottom: 12px;">Buat Tagihan Baru</h4>
                <div class="form-grid-2" style="gap: 12px;">
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label>Periode <span class="required-mark">*</span></label>
                        <input type="text" id="newBillPeriod" placeholder="Contoh: Januari 2026">
                    </div>
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label>Nominal <span class="required-mark">*</span></label>
                        <div class="input-price-wrapper">
                            <span class="input-price-prefix">Rp</span>
                            <input type="text" id="newBillAmount" class="input-price" placeholder="100.000" autocomplete="off">
                            <input type="hidden" id="newBillAmountHidden">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 12px;">
                        <label>Jatuh Tempo <span class="required-mark">*</span></label>
                        <input type="date" id="newBillDueDate">
                    </div>
                    <div class="form-group" style="margin-bottom: 12px; display: flex; align-items: flex-end;">
                        <button type="button" class="btn-submit" onclick="createTenantBill()" style="width: 100%;">
                            <i class="fas fa-plus"></i> Buat Tagihan
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer modal-footer--sticky">
            <button type="button" class="btn-cancel" onclick="closeTenantBillsModal()">Tutup</button>
        </div>
    </div>
</div>

<script>
let currentTenantId = null;
let currentTenantUserId = null;

async function openTenantBillsModal(tenantId) {
    currentTenantId = tenantId;
    document.getElementById('tenantBillsModal').classList.add('show');
    document.getElementById('tenantBillsLoading').style.display = 'block';
    document.getElementById('tenantBillsList').style.display = 'none';
    document.getElementById('tenantBillsForm').style.display = 'none';
    document.getElementById('newBillPeriod').value = '';
    document.getElementById('newBillAmount').value = '';
    document.getElementById('newBillAmountHidden').value = '';
    document.getElementById('newBillDueDate').value = '';

    try {
        const res = await fetch("{{ url('/dashboard/admin/tenants') }}/" + tenantId + "/payments");
        const data = await res.json();
        if (!data.success) throw new Error('Gagal memuat data');

        currentTenantUserId = data.tenant.user_id;
        document.getElementById('tenantBillsTitle').textContent = data.tenant.user_name + ' - Kamar ' + data.tenant.room_number;

        const list = document.getElementById('tenantBillsList');
        const payments = data.payments || [];

        if (payments.length === 0) {
            list.innerHTML = '<p style="color: #999; font-size: 13px; text-align: center; padding: 20px 0;">Belum ada tagihan.</p>';
        } else {
            let html = '';
            payments.forEach((p, i) => {
                const isPaid = p.status === 'paid';
                const badgeBg = isPaid ? '#D1FAE5' : (p.status === 'overdue' ? '#FEE2E2' : '#FEF3C7');
                const badgeColor = isPaid ? '#065F46' : (p.status === 'overdue' ? '#991B1B' : '#92400E');
                const badgeLabel = isPaid ? 'LUNAS' : (p.status === 'overdue' ? 'TERLAMBAT' : 'BELUM DIBAYAR');

                html += '<div style="padding: 14px; border: 1px solid #E5E7EB; border-radius: 10px; margin-bottom: 10px; background: ' + (isPaid ? '#FAFBFC' : '#FFFBEB') + ';">';
                html += '<div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;">';
                html += '<strong style="font-size: 13px; color: #111827;">' + p.period_label + '</strong>';
                html += '<span style="display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; background: ' + badgeBg + '; color: ' + badgeColor + ';">' + badgeLabel + '</span>';
                html += '</div>';
                html += '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 4px; font-size: 12px; color: #6B7280;">';
                html += '<div>Nominal: <strong>Rp ' + parseFloat(p.amount).toLocaleString('id-ID') + '</strong></div>';
                html += '<div>Jatuh Tempo: <strong>' + (p.due_date || '-') + '</strong></div>';
                if (p.paid_date) html += '<div>Dibayar: ' + p.paid_date + '</div>';
                if (p.payment_method) html += '<div>Metode: ' + p.payment_method + '</div>';
                html += '</div>';
                if (!isPaid) {
                    html += '<div style="margin-top: 8px;">';
                    html += '<button onclick="markBillAsPaid(' + p.id + ')" style="padding: 5px 12px; background: #10B981; color: #FFF; border: none; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer;"><i class="fas fa-check"></i> Tandai Lunas (Cash)</button>';
                    html += '</div>';
                }
                html += '</div>';
            });
            list.innerHTML = html;
        }

        list.style.display = 'block';
        document.getElementById('tenantBillsForm').style.display = 'block';
    } catch (e) {
        document.getElementById('tenantBillsList').innerHTML = '<p style="color: #DC2626; font-size: 13px;">Gagal memuat data tagihan.</p>';
        document.getElementById('tenantBillsList').style.display = 'block';
    }

    document.getElementById('tenantBillsLoading').style.display = 'none';
}

function closeTenantBillsModal() {
    document.getElementById('tenantBillsModal').classList.remove('show');
}

// Rupiah formatting for bill amount
(function() {
    const billAmountEl = document.getElementById('newBillAmount');
    const billAmountHidden = document.getElementById('newBillAmountHidden');
    if (billAmountEl && billAmountHidden) {
        billAmountEl.addEventListener('input', function() {
            let raw = this.value.replace(/[^0-9]/g, '');
            if (raw === '') { this.value = ''; billAmountHidden.value = ''; return; }
            let num = parseInt(raw, 10);
            if (isNaN(num)) num = 0;
            billAmountHidden.value = num;
            this.value = num.toLocaleString('id-ID');
        });
    }
})();

async function createTenantBill() {
    const period = document.getElementById('newBillPeriod').value.trim();
    const amount = document.getElementById('newBillAmountHidden').value;
    const dueDate = document.getElementById('newBillDueDate').value;

    if (!period || !amount || !dueDate) {
        alert('Harap isi semua field.');
        return;
    }
    if (parseFloat(amount) <= 0) {
        alert('Nominal harus lebih dari 0.');
        return;
    }

    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    try {
        const res = await fetch("{{ url('/dashboard/admin/tenants') }}/" + currentTenantId + "/payments", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json' },
            body: JSON.stringify({ period_label: period, amount: parseFloat(amount), due_date: dueDate }),
        });
        const data = await res.json();
        if (data.success) {
            alert('Tagihan berhasil dibuat.');
            openTenantBillsModal(currentTenantId);
        } else {
            alert(data.message || 'Gagal membuat tagihan.');
        }
    } catch (e) {
        alert('Terjadi kesalahan.');
    }
}

async function markBillAsPaid(paymentId) {
    if (!confirm('Tandai tagihan ini sebagai lunas (cash)?')) return;
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    try {
        const res = await fetch("{{ url('/dashboard/admin/payments') }}/" + paymentId + "/mark-paid", {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json' },
            body: JSON.stringify({ payment_method: 'cash' }),
        });
        const data = await res.json();
        if (data.success) {
            alert('Tagihan berhasil ditandai lunas.');
            openTenantBillsModal(currentTenantId);
        } else {
            alert(data.message || 'Gagal.');
        }
    } catch (e) {
        alert('Terjadi kesalahan.');
    }
}
</script>
