<div class="page" id="inbox">
    <div class="page-header">
        <div>
            <h1 class="page-title">Inbox</h1>
            <p class="page-subtitle">Pesan masuk dari calon penghuni & pengunjung website</p>
        </div>
        <button class="btn-primary" onclick="refreshInbox()">
            <i class="fas fa-sync-alt"></i>
            Refresh
        </button>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h3 class="table-title">Daftar Pesan</h3>
            <div class="table-actions">
                <input type="text" class="search-box" id="inboxSearch"
                    placeholder="Cari berdasarkan nama, email, atau pesan..." onkeyup="filterInboxTable()">
            </div>
        </div>
        <div class="table-responsive">
            <table id="inboxTable">
                <thead>
                    <tr>
                        <th style="width: 40px;"></th>
                        <th>Pengirim</th>
                        <th>Kontak</th>
                        <th>Pesan</th>
                        <th>Tanggal</th>
                        <th style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="inboxTableBody">
                    @forelse ($messages as $msg)
                        @php
                            $initial = strtoupper(substr($msg->name, 0, 1));
                            $isUnread = !$msg->is_read;
                        @endphp
                        <tr class="{{ $isUnread ? '' : '' }}" id="message-row-{{ $msg->id }}"
                            style="{{ $isUnread ? 'font-weight: 600; background: #EEF2FF;' : '' }}">
                            <td>
                                @if ($isUnread)
                                    <span
                                        style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #667eea;"
                                        title="Belum dibaca"></span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 10px;">
                                    <div
                                        style="width: 35px; height: 35px; border-radius: 50%; background: #667eea; color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; flex-shrink: 0;">
                                        {{ $initial }}
                                    </div>
                                    <strong>{{ $msg->name }}</strong>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 12px;">
                                    <div>{{ $msg->email }}</div>
                                    <div style="color: #999;">{{ $msg->phone }}</div>
                                </div>
                            </td>
                            <td>
                                <div style="max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;"
                                    onclick="openMessageDetail({{ $msg->id }})" title="Klik untuk lihat detail">
                                    {{ $msg->message }}
                                </div>
                            </td>
                            <td style="font-size: 12px; color: #999; white-space: nowrap;">
                                {{ $msg->created_at->diffForHumans() }}
                                <br>
                                <small>{{ $msg->created_at->format('d M Y H:i') }}</small>
                            </td>
                            <td>
                                <div style="display: flex; gap: 5px;">
                                    <button class="action-btn" onclick="openMessageDetail({{ $msg->id }})"
                                        title="Lihat detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn" style="color: #EF4444;"
                                        onclick="deleteMessage({{ $msg->id }})" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: #999; padding: 40px;">
                                <i class="fas fa-inbox"
                                    style="font-size: 48px; display: block; margin-bottom: 15px; color: #DDD;"></i>
                                Belum ada pesan masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- MESSAGE DETAIL MODAL -->
    <div class="modal" id="messageDetailModal">
        <div class="modal-content" style="max-width: 600px;">
            <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center;">
                <span>Detail Pesan</span>
                <button class="action-btn" onclick="closeMessageDetail()" style="font-size: 18px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="messageDetailContent">
                <div style="margin-bottom: 15px;">
                    <div style="font-size: 12px; font-weight: 600; color: #999; margin-bottom: 4px;">NAMA</div>
                    <div id="detailName" style="font-size: 16px; font-weight: 600;"></div>
                </div>
                <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <div style="font-size: 12px; font-weight: 600; color: #999; margin-bottom: 4px;">EMAIL</div>
                        <div id="detailEmail" style="font-size: 14px;"></div>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: 12px; font-weight: 600; color: #999; margin-bottom: 4px;">TELEPON</div>
                        <div id="detailPhone" style="font-size: 14px;"></div>
                    </div>
                </div>
                <div style="margin-bottom: 15px;">
                    <div style="font-size: 12px; font-weight: 600; color: #999; margin-bottom: 4px;">PESAN</div>
                    <div id="detailMessage"
                        style="font-size: 14px; line-height: 1.7; background: #F8F9FD; padding: 15px; border-radius: 8px; white-space: pre-wrap;">
                    </div>
                </div>
                <div style="font-size: 12px; color: #999;">
                    Dikirim: <span id="detailDate"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeMessageDetail()">Tutup</button>
                <button class="btn-cancel" style="background: #FEE2E2; color: #7F1D1D;" id="detailDeleteBtn"
                    onclick="deleteMessageFromDetail()">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Store messages data for client-side operations
    const messagesData = @json($messages->keyBy('id'));

    let currentDetailId = null;

    function openMessageDetail(id) {
        const msg = messagesData[id];
        if (!msg) return;

        currentDetailId = id;

        document.getElementById('detailName').textContent = msg.name;
        document.getElementById('detailEmail').textContent = msg.email;
        document.getElementById('detailPhone').textContent = msg.phone;
        document.getElementById('detailMessage').textContent = msg.message;
        document.getElementById('detailDate').textContent = new Date(msg.created_at).toLocaleString('id-ID');

        document.getElementById('messageDetailModal').classList.add('show');

        // Mark as read via API
        fetch(`/dashboard/admin/messages/${id}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById(`message-row-${id}`);
                    if (row) {
                        row.style.fontWeight = '';
                        row.style.background = '';
                        const dot = row.querySelector('td:first-child span');
                        if (dot) dot.style.display = 'none';
                    }
                    updateUnreadBadge();
                }
            });
    }

    function closeMessageDetail() {
        document.getElementById('messageDetailModal').classList.remove('show');
        currentDetailId = null;
    }

    function deleteMessage(id) {
        if (!confirm('Hapus pesan ini?')) return;
        console.log('Deleting message with ID:', id);
        fetch(`/dashboard/admin/messages/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content ?? ''
            }
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById(`message-row-${id}`);
                    if (row) row.remove();
                    updateUnreadBadge();
                    showNotification('Pesan berhasil dihapus.', 'success');

                    // If table empty, show empty state
                    const tbody = document.getElementById('inboxTableBody');
                    if (tbody && tbody.querySelectorAll('tr').length === 0) {
                        tbody.innerHTML = `
                        <tr>
                            <td colspan="6" style="text-align: center; color: #999; padding: 40px;">
                                <i class="fas fa-inbox" style="font-size: 48px; display: block; margin-bottom: 15px; color: #DDD;"></i>
                                Belum ada pesan masuk.
                            </td>
                        </tr>`;
                    }
                }
            });
    }

    function deleteMessageFromDetail() {
        if (currentDetailId) {
            deleteMessage(currentDetailId);
            closeMessageDetail();
        }
    }

    function filterInboxTable() {
        const term = document.getElementById('inboxSearch').value.toLowerCase();
        const rows = document.querySelectorAll('#inboxTableBody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    }

    function refreshInbox() {
        window.location.reload();
    }

    function updateUnreadBadge() {
        const visibleRows = document.querySelectorAll('#inboxTableBody tr');
        let unreadCount = 0;
        visibleRows.forEach(row => {
            const dot = row.querySelector('td:first-child span');
            if (dot && dot.style.display !== 'none' && window.getComputedStyle(dot).display !== 'none') {
                unreadCount++;
            }
        });

        const badge = document.getElementById('unreadBadge');
        if (badge) {
            if (unreadCount > 0) {
                badge.style.display = 'inline';
                badge.textContent = unreadCount;
            } else {
                badge.style.display = 'none';
            }
        }
    }

    // Close modal on outside click
    document.getElementById('messageDetailModal').addEventListener('click', function (e) {
        if (e.target === this) closeMessageDetail();
    });

    // Close modal on Escape
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && document.getElementById('messageDetailModal').classList.contains('show')) {
            closeMessageDetail();
        }
    });
</script>