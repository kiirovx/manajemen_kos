        <div class="page" id="manajemen-kamar">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Manajemen Kamar</h1>
                    <p class="page-subtitle">Kelola semua kamar kos-kosan Anda</p>
                </div>
                <button class="btn-primary" onclick="openAddRoomModal()">
                    <i class="fas fa-plus"></i>
                    Tambah Kamar
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
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Kamar</h3>
                        <div class="number">{{ $roomStats['total'] }}</div>
                    </div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Terisi</h3>
                        <div class="number">{{ $roomStats['occupied'] }}</div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom-color: #A78BFA;">
                    <div class="stat-icon" style="background: #A78BFA;">
                        <i class="fas fa-hammer"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Kosong</h3>
                        <div class="number">{{ $roomStats['available'] }}</div>
                    </div>
                </div>
                <div class="stat-card pink">
                    <div class="stat-icon" style="background: #F472B6;">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Maintenance</h3>
                        <div class="number">{{ $roomStats['maintenance'] }}</div>
                    </div>
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-container">
                <div class="table-header">
                    <h3 class="table-title">Daftar Kamar</h3>
                    <div class="table-actions">
                        <input type="text" class="search-box"
                            placeholder="Cari berdasarkan nomor kamar atau penyewa...">
                        <button class="filter-btn">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>No. Kamar</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Penyewa</th>
                                <th>Harga</th>
                                <th>Lantai</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rooms as $room)
                                @php
                                    $status = [
                                        'occupied' => ['class' => 'success', 'label' => 'Terisi'],
                                        'available' => ['class' => 'danger', 'label' => 'Kosong'],
                                        'maintenance' => ['class' => 'warning', 'label' => 'Maintenance'],
                                    ][$room->status] ?? ['class' => 'info', 'label' => ucfirst($room->status)];
                                @endphp
                                <tr>
                                    <td><strong>{{ $room->number }}</strong></td>
                                    <td>{{ ucfirst($room->type) }}</td>
                                    <td><span class="badge {{ $status['class'] }}">{{ $status['label'] }}</span></td>
                                    <td>{{ $room->tenantProfiles->first()?->user?->name ?? '-' }}</td>
                                    <td>Rp {{ number_format((float) $room->price, 0, ',', '.') }}</td>
                                    <td>{{ $room->floor }}</td>
                                    <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #999;">Belum ada data kamar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
