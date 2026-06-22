<div class="page" id="laporan-keuangan">
            <div class="page-header">
                <div>
                    <h1 class="page-title">Laporan Keuangan</h1>
                    <p class="page-subtitle">Pantau pemasukan dari transaksi Midtrans yang berhasil</p>
                </div>
                <div style="display: flex; gap: 10px;">
                    <a href="{{ route('dashboard.admin.export.pdf', ['type' => 'revenue']) }}?{{ http_build_query(request()->only(['filter', 'start_date', 'end_date'])) }}" class="btn-primary" style="background: #DC2626;" target="_blank">
                        <i class="fas fa-file-pdf"></i> Export PDF (Pendapatan)
                    </a>
                    <a href="{{ route('dashboard.admin.export.excel', ['type' => 'revenue']) }}?{{ http_build_query(request()->only(['filter', 'start_date', 'end_date'])) }}" class="btn-primary" style="background: #10B981;">
                        <i class="fas fa-file-excel"></i> Export Excel (Pendapatan)
                    </a>
                </div>
            </div>

            <!-- FILTERS -->
            <div style="background: white; border-radius: 12px; padding: 16px 20px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                <form method="GET" id="filterForm" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                    <input type="hidden" name="t" value="keuangan">

                    <!-- Filter Cepat Dropdown -->
                    <select name="filter" onchange="this.form.submit()"
                        style="padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 13px; background: white; font-family: inherit; cursor: pointer; min-width: 140px;">
                        <option value="">Semua Waktu</option>
                        <option value="today" {{ request('filter') === 'today' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="week" {{ request('filter') === 'week' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="month" {{ request('filter') === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="year" {{ request('filter') === 'year' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>

                    <span style="color: #9CA3AF; font-size: 13px; font-weight: 500;">atau</span>

                    <!-- Rentang Tanggal -->
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                        style="padding: 10px 12px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 13px; font-family: inherit;">
                    <span style="color: #9CA3AF; font-size: 13px;">s/d</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                        style="padding: 10px 12px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 13px; font-family: inherit;">

                    <button type="submit" class="btn-primary" style="padding: 10px 20px; font-size: 13px;">
                        <i class="fas fa-filter"></i> Terapkan
                    </button>

                    @if(request('filter') || request('start_date') || request('end_date'))
                        <a href="?#laporan-keuangan" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 16px; background: #FEE2E2; color: #DC2626; border-radius: 8px; text-decoration: none; font-size: 12px; font-weight: 600; border: 1px solid #FECACA;">
                            <i class="fas fa-times"></i> Reset
                        </a>
                    @endif
                </form>
            </div>

            <!-- STAT CARDS -->
            <div class="stats-grid" style="margin-bottom: 30px;">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Pendapatan</h3>
                        <div class="number">Rp {{ number_format($filteredRevenueStats['total'] ?? 0, 0, ',', '.') }}</div>
                        <div class="change" style="color: #10B981;">
                            <i class="fas fa-check"></i> {{ $filteredBookingStats['dibayar'] ?? 0 }} booking berhasil
                        </div>
                    </div>
                </div>
                <div class="stat-card purple">
                    <div class="stat-icon" style="background: #A78BFA;">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Transaksi</h3>
                        <div class="number">{{ $filteredBookingStats['total'] ?? 0 }}</div>
                        <div class="change" style="color: #10B981;">
                            <i class="fas fa-exchange-alt"></i> Semua status
                        </div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom-color: #F472B6;">
                    <div class="stat-icon" style="background: #F472B6;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Booking Pending</h3>
                        <div class="number">{{ $filteredBookingStats['pending'] ?? 0 }}</div>
                        <div class="change" style="color: #78350F;">
                            <i class="fas fa-exclamation-circle"></i> Menunggu pembayaran
                        </div>
                    </div>
                </div>
                <div class="stat-card pink">
                    <div class="stat-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Booking Dibatalkan</h3>
                        <div class="number">{{ $filteredBookingStats['dibatalkan'] ?? 0 }}</div>
                        <div class="change" style="color: #DC2626;">
                            <i class="fas fa-ban"></i> Tidak termasuk pendapatan
                        </div>
                    </div>
                </div>
            </div>

            <!-- TRANSACTION TABLE -->
            <div class="table-container">
                <div class="table-header">
                    <h3 class="table-title">
                        Daftar Transaksi
                        @if(request('filter') === 'today') — Hari Ini
                        @elseif(request('filter') === 'week') — Minggu Ini
                        @elseif(request('filter') === 'month') — Bulan Ini
                        @elseif(request('filter') === 'year') — Tahun Ini
                        @elseif(request('start_date') && request('end_date')) — {{ request('start_date') }} s/d {{ request('end_date') }}
                        @else — Semua Waktu
                        @endif
                    </h3>
                    <div class="table-actions">
                        <a href="{{ route('dashboard.admin.export.pdf', ['type' => 'payments']) }}?{{ http_build_query(request()->only(['filter', 'start_date', 'end_date'])) }}" class="filter-btn" target="_blank" style="text-decoration: none;">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                        <a href="{{ route('dashboard.admin.export.excel', ['type' => 'payments']) }}?{{ http_build_query(request()->only(['filter', 'start_date', 'end_date'])) }}" class="filter-btn" style="text-decoration: none;">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="financeTransactionsTable">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Kamar</th>
                                <th>Order ID</th>
                                <th>Metode</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($filteredBookings as $booking)
                                @php
                                    $isPaid = $booking->status === 'Dibayar';
                                    $isPending = $booking->status === 'Pending';
                                @endphp
                                <tr>
                                    <td>{{ $booking->paid_at?->format('d M Y H:i') ?? $booking->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <strong>{{ $booking->customer_name }}</strong>
                                        <br><small style="color: #999;">{{ $booking->customer_email }}</small>
                                    </td>
                                    <td>{{ $booking->room_name ?? $booking->room?->number ?? '-' }}</td>
                                    <td><code style="font-size: 11px; background: #F5F5F7; padding: 2px 6px; border-radius: 4px;">{{ $booking->midtrans_order_id }}</code></td>
                                    <td>{{ ucfirst($booking->payment_method ?? '-') }}</td>
                                    <td style="font-weight: 700; color: {{ $isPaid ? '#10B981' : ($isPending ? '#F59E0B' : '#DC2626') }};">
                                        Rp {{ number_format(floatval($booking->gross_amount ?: $booking->room_price), 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($isPaid)
                                            <span class="badge success">Berhasil</span>
                                        @elseif ($isPending)
                                            <span class="badge warning">Pending</span>
                                        @elseif ($booking->status === 'Menunggu Pembayaran')
                                            <span class="badge info">Menunggu</span>
                                        @else
                                            <span class="badge danger">{{ $booking->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; color: #999; padding: 30px;">
                                        <i class="fas fa-inbox" style="font-size: 36px; display: block; margin-bottom: 10px; color: #CCC;"></i>
                                        Belum ada data transaksi pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>