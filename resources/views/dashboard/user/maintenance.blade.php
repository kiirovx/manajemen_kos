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
                @php
                    $statusMap = [
                        'pending' => ['class' => '', 'label' => 'Pending'],
                        'in_progress' => ['class' => '', 'label' => 'Diproses'],
                        'resolved' => ['class' => 'completed', 'label' => 'Selesai'],
                        'completed' => ['class' => 'completed', 'label' => 'Selesai'],
                    ];
                    $statusInfo = $statusMap[$maintenance->status] ?? ['class' => '', 'label' => ucfirst($maintenance->status)];
                @endphp
                <div class="maintenance-item">
                    <div class="maintenance-header">
                        <div class="maintenance-title">{{ $maintenance->title }}</div>
                        <div class="maintenance-status {{ $statusInfo['class'] }}">
                            {{ $statusInfo['label'] }}
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
