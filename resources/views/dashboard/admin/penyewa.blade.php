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

            <!-- STAT CARDS -->
            <div class="stats-grid" style="margin-bottom: 30px;">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Total Penyewa</h3>
                        <div class="number">42</div>
                    </div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon" style="background: #10B981;">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Aktif</h3>
                        <div class="number">38</div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom-color: #F472B6;">
                    <div class="stat-icon" style="background: #F472B6;">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Menunggak</h3>
                        <div class="number">4</div>
                    </div>
                </div>
                <div class="stat-card" style="border-bottom-color: #A78BFA;">
                    <div class="stat-icon" style="background: #A78BFA;">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Baru Bulan Ini</h3>
                        <div class="number">5</div>
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
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div
                                            style="width: 35px; height: 35px; border-radius: 50%; background: #667eea; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                            A</div>
                                        <div>
                                            <p style="margin: 0; font-weight: 600;">Ahmad Rifai</p>
                                            <p style="margin: 3px 0 0 0; font-size: 12px; color: #999;">ahmad@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Kamar 01A</td>
                                <td>0812-3456-7890</td>
                                <td>01 Jan 2024</td>
                                <td>01 Nov 2024</td>
                                <td><span class="badge success">Aktif</span></td>
                                <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div
                                            style="width: 35px; height: 35px; border-radius: 50%; background: #06B6D4; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                            S</div>
                                        <div>
                                            <p style="margin: 0; font-weight: 600;">Siti Nurhaliza</p>
                                            <p style="margin: 3px 0 0 0; font-size: 12px; color: #999;">siti@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Kamar 02A</td>
                                <td>0813-4567-8901</td>
                                <td>15 Feb 2024</td>
                                <td>01 Nov 2024</td>
                                <td><span class="badge success">Aktif</span></td>
                                <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div
                                            style="width: 35px; height: 35px; border-radius: 50%; background: #10B981; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                            B</div>
                                        <div>
                                            <p style="margin: 0; font-weight: 600;">Budi Santoso</p>
                                            <p style="margin: 3px 0 0 0; font-size: 12px; color: #999;">budi@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Kamar 06B</td>
                                <td>0814-5678-9012</td>
                                <td>10 Mar 2024</td>
                                <td>28 Oct 2024</td>
                                <td><span class="badge success">Aktif</span></td>
                                <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div
                                            style="width: 35px; height: 35px; border-radius: 50%; background: #F472B6; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                            R</div>
                                        <div>
                                            <p style="margin: 0; font-weight: 600;">Rina Wijaya</p>
                                            <p style="margin: 3px 0 0 0; font-size: 12px; color: #999;">rina@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Kamar 05A</td>
                                <td>0815-6789-0123</td>
                                <td>20 Apr 2024</td>
                                <td>01 Oct 2024</td>
                                <td><span class="badge danger">Menunggak</span></td>
                                <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div
                                            style="width: 35px; height: 35px; border-radius: 50%; background: #A78BFA; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">
                                            L</div>
                                        <div>
                                            <p style="margin: 0; font-weight: 600;">Lisa Permata</p>
                                            <p style="margin: 3px 0 0 0; font-size: 12px; color: #999;">lisa@email.com</p>
                                        </div>
                                    </div>
                                </td>
                                <td>Kamar 08B</td>
                                <td>0816-7890-1234</td>
                                <td>05 May 2024</td>
                                <td>01 Nov 2024</td>
                                <td><span class="badge success">Aktif</span></td>
                                <td><button class="action-btn"><i class="fas fa-ellipsis-v"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
