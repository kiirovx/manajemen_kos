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
            roomCard.innerHTML = `
                <div class="room-image" style="height: 200px;">
                    <img src="${room.image}" alt="${room.name}">
                    <span class="${room.badgeClass}">${room.badge}</span>
                    <span class="price-tag">${room.priceText}</span>
                </div>
                <h3 style="font-size: 16px; font-weight: 600; padding: 16px 16px 8px; color: var(--dark-text); margin: 0;">${room.name}</h3>
                <div class="room-info" style="display: flex; flex-direction: column; gap: 8px; padding: 0 16px 16px; font-size: 12px; color: var(--light-text);">
                    <span style="display: flex; align-items: center; gap: 8px;"><i class="fas fa-expand" style="color: var(--primary-color);"></i> ${room.size}</span>
                    <span style="display: flex; align-items: center; gap: 8px;"><i class="fas fa-bed" style="color: var(--primary-color);"></i> ${room.capacity}</span>
                    <span style="display: flex; align-items: center; gap: 8px;"><i class="fas fa-bath" style="color: var(--primary-color);"></i> ${room.bathroom}</span>
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
