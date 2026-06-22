<!-- STEP 1: PILIH KAMAR -->
<div class="form-section active" id="step1">
    <h2 class="page-title">Pilihan Kos yang sesuai dengan Lokasi dan Budget Anda</h2>
    <p class="page-subtitle">Temukan kamar yang sesuai dengan kebutuhan dan budget Anda</p>

    <div class="rooms-selection" id="roomsSelection">
        <!-- Room cards akan diisi via JavaScript -->
    </div>

    <div class="button-group">
        <button class="btn btn-next" onclick="nextStep(1)" disabled id="btnNext1">
            Lanjutkan <i class="fas fa-arrow-right" style="margin-left: 8px;"></i>
        </button>
    </div>
</div>

<script>
    // ============================================
    // RENDER ROOMS
    // ============================================
    function renderRooms() {
        const roomsSelection = document.getElementById('roomsSelection');
        if (!roomsSelection) return;
        roomsSelection.innerHTML = '';

        rooms.forEach(room => {
            const roomCard = document.createElement('div');
            roomCard.className = 'room-selection-card';
            roomCard.onclick = () => selectRoom(room, roomCard);

            const typeLabels = { standard: 'Standard', deluxe: 'Deluxe', premium: 'Premium' };
            const typeName = typeLabels[room.type] || room.type;
            const statusClass = room.status_label === 'Tersedia' ? 'room-tag' : (room.status_label === 'Hampir Penuh' ? 'room-tag featured' : 'room-tag');
            const statusText = room.status_label || 'Tersedia';

            roomCard.innerHTML = `
                <div class="room-image" style="height: 200px; position: relative; overflow: hidden;">
                    <img src="${room.photos || '{{ asset('asset/kamar.png') }}'}" alt="${room.number}" style="width:100%;height:100%;object-fit:cover;">
                    <span class="${statusClass}">${statusText}</span>
                    <span class="price-tag">Rp ${Number(room.price).toLocaleString('id-ID')}</span>
                </div>
                <h3 style="font-size: 16px; font-weight: 600; padding: 16px 16px 8px; color: var(--dark-text); margin: 0;">Kamar ${room.number} - ${typeName}</h3>
                <div class="room-info" style="display: flex; flex-direction: column; gap: 8px; padding: 0 16px 16px; font-size: 12px; color: var(--light-text);">
                    <span style="display: flex; align-items: center; gap: 8px;"><i class="fas fa-bed" style="color: var(--primary-color);"></i> Kapasitas ${room.capacity} orang</span>
                    <span style="display: flex; align-items: center; gap: 8px;"><i class="fas fa-layer-group" style="color: var(--primary-color);"></i> Lantai ${room.floor}</span>
                    ${room.facilities ? `<span style="display: flex; align-items: center; gap: 8px;"><i class="fas fa-check-circle" style="color: var(--primary-color);"></i> ${room.facilities}</span>` : ''}
                    ${room.description ? `<span style="display: flex; align-items: center; gap: 8px; font-style: italic;"><i class="fas fa-info-circle" style="color: var(--primary-color);"></i> ${room.description.substring(0, 60)}...</span>` : ''}
                </div>
            `;
            roomsSelection.appendChild(roomCard);
        });
    }

    // ============================================
    // SELECT ROOM
    // ============================================
    function selectRoom(room, cardElement) {
        document.querySelectorAll('.room-selection-card').forEach(card => {
            card.classList.remove('selected');
        });

        cardElement.classList.add('selected');
        bookingData.selectedRoom = room;

        const nextBtn = document.getElementById('btnNext1');
        if (nextBtn) nextBtn.disabled = false;

        updateSelectedRoomDisplay();
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderRooms();
    });
</script>
