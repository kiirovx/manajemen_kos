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
                <img src="${room.image}" alt="${room.name}" class="room-card-image">
                <div class="room-card-content">
                    <span class="room-badge">${room.badge}</span>
                    <h3 class="room-card-title">${room.name}</h3>
                    <p class="room-card-info"><i class="fas fa-expand" style="color: #5B5EFF; width: 16px;"></i> ${room.size}</p>
                    <p class="room-card-info"><i class="fas fa-user" style="color: #5B5EFF; width: 16px;"></i> Kapasitas: ${room.capacity}</p>
                    <p class="room-card-info"><i class="fas fa-bed" style="color: #5B5EFF; width: 16px;"></i> ${room.bed}</p>
                    <p class="room-card-price">Mulai dari<br><strong>${formatPrice(room.price)}</strong> /bulan</p>
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
