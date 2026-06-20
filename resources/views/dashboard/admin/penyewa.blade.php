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
                                        @if ($hasOverdue)
                                            <span class="badge danger">Menunggak</span>
                                        @else
                                            <span class="badge success">Aktif</span>
                                        @endif
                                    </td>
                                    <td>
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
