<!-- MAINTENANCE PAGE -->
        <div class="page" id="maintenance">
            <div class="page-header">
                <h1 class="page-title">Maintenance & Perbaikan</h1>
                <p class="page-subtitle">Ajukan dan kelola permintaan perbaikan kamar Anda</p>
            </div>

            <button class="btn btn-primary" onclick="openMaintenanceModal()" style="margin-bottom: 20px;">
                <i class="fas fa-plus"></i> Ajukan Maintenance Baru
            </button>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Maintenance Requests</h3>
                </div>

                @forelse($user->maintenanceRequests as $maintenance)
                <div class="maintenance-item">
                    <div class="maintenance-header">
                        <div class="maintenance-title">{{ $maintenance->title }}</div>
                        <div class="maintenance-status {{ $maintenance->status === 'completed' ? 'completed' : '' }}">
                            {{ $maintenance->status === 'completed' ? 'Selesai' : 'Pending' }}
                        </div>
                    </div>
                    <div class="maintenance-desc">{{ $maintenance->description }}</div>
                    <div class="maintenance-date">
                        Diajukan: {{ $maintenance->submitted_at->format('d F Y') }}
                        @if($maintenance->completed_at)
                        | Selesai: {{ $maintenance->completed_at->format('d F Y') }}
                        @endif
                    </div>
                </div>
                @empty
                <p style="color: #999; font-size: 13px;">Belum ada permintaan maintenance.</p>
                @endforelse
            </div>
        </div>
