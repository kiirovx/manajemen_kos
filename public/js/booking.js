// ============================================
// BOOKING DATA & STATE
// ============================================

const bookingData = {
    selectedRoom: null,
    customerName: '',
    customerEmail: '',
    customerPhone: '',
    customerMessage: '',
    paymentMethod: null,
    currentStep: 1
};

// Data kamar
const rooms = [
    {
        id: 1,
        name: 'Kamar Standard',
        price: 1500000,
        size: '3×4 m',
        capacity: '1 orang',
        image: '/asset/1.jpeg',
        bed: 'Single Bed',
        badge: 'Populer'
    },
    {
        id: 2,
        name: 'Kamar Deluxe',
        price: 2200000,
        size: '4×5 m',
        capacity: '1 orang',
        image: '/asset/2.jpeg',
        bed: 'Queen Bed',
        badge: 'Rekomendasi'
    },
];

// ============================================
// INITIALIZE
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    renderRooms();
    setupEventListeners();
});

// ============================================
// RENDER ROOMS
// ============================================

function renderRooms() {
    const roomsSelection = document.getElementById('roomsSelection');
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
    // Remove previous selection
    document.querySelectorAll('.room-selection-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selection to clicked card
    cardElement.classList.add('selected');

    // Store selected room
    bookingData.selectedRoom = room;

    // Enable next button
    document.getElementById('btnNext1').disabled = false;

    // Update displays
    updateSelectedRoomDisplay();
}

// ============================================
// UPDATE SELECTED ROOM DISPLAY
// ============================================

function updateSelectedRoomDisplay() {
    if (!bookingData.selectedRoom) return;

    const room = bookingData.selectedRoom;
    const displayHTML = `
        <h3>Kamar yang Dipilih</h3>
        <p><strong>${room.name}</strong> - ${formatPrice(room.price)}/bulan</p>
    `;

    const display1 = document.getElementById('selectedRoomDisplay');
    if (display1) display1.innerHTML = displayHTML;

    const display2 = document.getElementById('selectedRoomDisplay2');
    if (display2) display2.innerHTML = displayHTML;

    // Update summary
    document.getElementById('summaryRoom').textContent = room.name;
    document.getElementById('summaryPrice').textContent = formatPrice(room.price);
    document.getElementById('summaryTotal').textContent = formatPrice(room.price);
}

// ============================================
// STEP NAVIGATION
// ============================================

function nextStep(currentStep) {
    // Validate current step
    if (currentStep === 1) {
        if (!bookingData.selectedRoom) {
            showNotification('Silakan pilih kamar terlebih dahulu', 'error');
            return;
        }
    }

    if (currentStep === 2) {
        // Validate form
        const nama = document.getElementById('nama').value.trim();
        const email = document.getElementById('email').value.trim();
        const telepon = document.getElementById('telepon').value.trim();

        if (!nama || !email || !telepon) {
            showNotification('Mohon lengkapi semua field yang diperlukan', 'error');
            return;
        }

        if (!validateEmail(email)) {
            showNotification('Format email tidak valid', 'error');
            return;
        }

        // Store form data
        bookingData.customerName = nama;
        bookingData.customerEmail = email;
        bookingData.customerPhone = telepon;
        bookingData.customerMessage = document.getElementById('pesan').value.trim();

        // Display data on step 3
        document.getElementById('displayNama').textContent = nama;
        document.getElementById('displayEmail').textContent = email;
        document.getElementById('displayTelepon').textContent = telepon;
        document.getElementById('displayPesan').textContent = bookingData.customerMessage || '-';
    }

    // Hide current step
    document.getElementById(`step${currentStep}`).classList.remove('active');

    // Update step indicator
    document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.remove('active');
    document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.add('completed');

    // Show next step
    const nextStepNumber = currentStep + 1;
    document.getElementById(`step${nextStepNumber}`).classList.add('active');
    document.querySelector(`.step-item[data-step="${nextStepNumber}"]`).classList.add('active');

    // Update current step
    bookingData.currentStep = nextStepNumber;

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function previousStep(currentStep) {
    const previousStepNumber = currentStep - 1;

    // Hide current step
    document.getElementById(`step${currentStep}`).classList.remove('active');

    // Update step indicator
    document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.remove('active');
    document.querySelector(`.step-item[data-step="${currentStep}"]`).classList.remove('completed');

    // Show previous step
    document.getElementById(`step${previousStepNumber}`).classList.add('active');
    document.querySelector(`.step-item[data-step="${previousStepNumber}"]`).classList.add('active');

    // Update current step
    bookingData.currentStep = previousStepNumber;

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ============================================
// PAYMENT METHOD SELECTION
// ============================================

function selectPayment(method, element) {
    // Remove previous selection
    document.querySelectorAll('.payment-method').forEach(pm => {
        pm.classList.remove('selected');
    });

    // Add selection
    element.classList.add('selected');
    bookingData.paymentMethod = method;
}

// ============================================
// PROCESS PAYMENT
// ============================================

function processPayment() {
    if (!bookingData.paymentMethod) {
        showNotification('Silakan pilih metode pembayaran', 'error');
        return;
    }

    // Simulate payment processing
    showLoadingModal();

    setTimeout(() => {
        hideLoadingModal();
        showSuccessModal();
    }, 2000);
}

function showLoadingModal() {
    const modal = document.createElement('div');
    modal.id = 'loadingModal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    `;

    modal.innerHTML = `
        <div style="background: white; border-radius: 12px; padding: 40px; text-align: center; max-width: 400px;">
            <div style="font-size: 48px; margin-bottom: 20px;">
                <i class="fas fa-spinner" style="animation: spin 1s linear infinite;"></i>
            </div>
            <h3 style="margin-bottom: 10px; color: #333;">Memproses Pembayaran...</h3>
            <p style="color: #666; font-size: 14px;">Mohon tunggu sebentar</p>
        </div>
    `;

    document.body.appendChild(modal);

    // Add spin animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
}

function hideLoadingModal() {
    const modal = document.getElementById('loadingModal');
    if (modal) modal.remove();
}

function showSuccessModal() {
    const modal = document.createElement('div');
    modal.id = 'successModal';
    modal.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
    `;

    const room = bookingData.selectedRoom;

    modal.innerHTML = `
        <div style="background: white; border-radius: 12px; padding: 40px; text-align: center; max-width: 500px;">
            <div style="font-size: 60px; color: #10B981; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 style="margin-bottom: 10px; color: #333;">Booking Berhasil!</h2>
            <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
                Terima kasih telah memesan <strong>${room.name}</strong>
            </p>
            
            <div style="background: #F0F0F7; border-radius: 8px; padding: 15px; margin-bottom: 20px; text-align: left; font-size: 13px;">
                <p style="margin: 5px 0;"><strong>Nama:</strong> ${bookingData.customerName}</p>
                <p style="margin: 5px 0;"><strong>Email:</strong> ${bookingData.customerEmail}</p>
                <p style="margin: 5px 0;"><strong>Telepon:</strong> ${bookingData.customerPhone}</p>
                <p style="margin: 5px 0;"><strong>Kamar:</strong> ${room.name}</p>
                <p style="margin: 5px 0;"><strong>Harga:</strong> ${formatPrice(room.price)}/bulan</p>
                <p style="margin: 5px 0;"><strong>Metode Pembayaran:</strong> ${getPaymentMethodName(bookingData.paymentMethod)}</p>
            </div>

            <p style="color: #10B981; font-size: 13px; margin-bottom: 20px;">
                ✓ Booking Anda telah dikonfirmasi. Cek email untuk detail selengkapnya.
            </p>

            <button onclick="goBackHome()" style="
                background: #5B5EFF;
                color: white;
                border: none;
                padding: 12px 30px;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                width: 100%;
                transition: all 0.3s ease;
            " onmouseover="this.style.background='#4A4DE6'" onmouseout="this.style.background='#5B5EFF'">
                Kembali ke Beranda
            </button>
        </div>
    `;

    document.body.appendChild(modal);
}

function goBackHome() {
    window.location.href = '/index';
}

// ============================================
// CANCEL BOOKING
// ============================================

function cancelBooking() {
    if (confirm('Apakah Anda yakin ingin membatalkan booking ini?')) {
        window.location.href = 'index.html';
    }
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

function formatPrice(price) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(price);
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function getPaymentMethodName(method) {
    const methods = {
        'cash': 'Cash',
        'transfer': 'Transfer Bank',
        'ewallet': 'E-Wallet',
        'cc': 'Kartu Kredit'
    };
    return methods[method] || '-';
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 20px;
        border-radius: 8px;
        background: ${type === 'success' ? '#10B981' : '#EF4444'};
        color: white;
        z-index: 9999;
        font-size: 14px;
        font-weight: 600;
        animation: slideIn 0.3s ease-out;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    `;
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

function setupEventListeners() {
    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            // Close any modals
        }
    });
}

// Add animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOut {
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);