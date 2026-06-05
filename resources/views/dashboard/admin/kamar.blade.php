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

            <!-- STAT CARDS -->
            <div class="stats-grid" style="margin-bottom: 30px;">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Kamar</h3>
                        <div class="number">48</div>
                    </div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Terisi</h3>
                        <div class="number">42</div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom-color: #A78BFA;">
                    <div class="stat-icon" style="background: #A78BFA;">
                        <i class="fas fa-hammer"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Kosong</h3>
                        <div class="number">4</div>
                    </div>
                </div>
                <div class="stat-card pink">
                    <div class="stat-icon" style="background: #F472B6;">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Maintenance</h3>
                        <div class="number">2</div>
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
                            <tr>
                                <td><strong>01A</strong></td>
                                <td>Standard</td>
                                <td><span class="badge success">Terisi</span></td>
                                <td>Ahmad Rifai</td>
                                <td>Rp 1.5jt</td>
                                <td>1</td>
                                <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                            </tr>
                            <tr>
                                <td><strong>02A</strong></td>
                                <td>Standard</td>
                                <td><span class="badge success">Terisi</span></td>
                                <td>Siti Nurhaliza</td>
                                <td>Rp 1.5jt</td>
                                <td>1</td>
                                <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                            </tr>
                            <tr>
                                <td><strong>03A</strong></td>
                                <td>Standard</td>
                                <td><span class="badge warning">Maintenance</span></td>
                                <td>-</td>
                                <td>Rp 1.5jt</td>
                                <td>1</td>
                                <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                            </tr>
                            <tr>
                                <td><strong>04A</strong></td>
                                <td>Deluxe</td>
                                <td><span class="badge danger">Kosong</span></td>
                                <td>-</td>
                                <td>Rp 2.2jt</td>
                                <td>1</td>
                                <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                            </tr>
                            <tr>
                                <td><strong>05A</strong></td>
                                <td>Deluxe</td>
                                <td><span class="badge success">Terisi</span></td>
                                <td>Rina Wijaya</td>
                                <td>Rp 2.2jt</td>
                                <td>1</td>
                                <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                            </tr>
                            <tr>
                                <td><strong>06B</strong></td>
                                <td>Premium</td>
                                <td><span class="badge success">Terisi</span></td>
                                <td>Budi Santoso</td>
                                <td>Rp 3.0jt</td>
                                <td>2</td>
                                <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                            </tr>
                            <tr>
                                <td><strong>07B</strong></td>
                                <td>Standard</td>
                                <td><span class="badge danger">Kosong</span></td>
                                <td>-</td>
                                <td>Rp 1.5jt</td>
                                <td>2</td>
                                <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                            </tr>
                            <tr>
                                <td><strong>08B</strong></td>
                                <td>Deluxe</td>
                                <td><span class="badge success">Terisi</span></td>
                                <td>Lisa Permata</td>
                                <td>Rp 2.2jt</td>
                                <td>2</td>
                                <td><button class="action-btn" title="Edit"><i class="fas fa-edit"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
