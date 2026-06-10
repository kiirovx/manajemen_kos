<!-- RIWAYAT PAGE -->
        <div class="page" id="riwayat">
            <div class="page-header">
                <h1 class="page-title">Riwayat Aktivitas</h1>
                <p class="page-subtitle">Lihat semua aktivitas dan transaksi Anda</p>
            </div>

            <div class="card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Aktivitas</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->activityLogs as $activity)
                            <tr>
                                <td>{{ $activity->activity_date->format('d M Y') }}</td>
                                <td>{{ $activity->activity_type }}</td>
                                <td>{{ $activity->description }}</td>
                                <td>
                                    <span class="badge {{ in_array($activity->status, ['Berhasil', 'Selesai']) ? 'badge-success' : 'badge-warning' }}">
                                        {{ $activity->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #999;">Belum ada aktivitas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
