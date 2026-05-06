// ============================================
// USER DASHBOARD LOGIC
// ============================================

// ============================================
// AUTHENTICATION CHECK
// ============================================

function checkUserAuth() {
    // Cek key 'currentUser' (key utama dari login.js)
    const authData = localStorage.getItem('currentUser');
    if (!authData) {
        window.location.href = '/login';
        return false;
    }

    const user = JSON.parse(authData);

    // Pastikan role-nya user, bukan admin
    if (user.role !== 'user') {
        window.location.href = '/login';
        return false;
    }

    return user;
}

function logoutUser() {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
        localStorage.removeItem('currentUser');
        localStorage.removeItem('adminAuth');
        window.location.href = '/login';
    }
}

// ============================================
// PAGE NAVIGATION
// ============================================

function showUserPage(pageName) {
    document.querySelectorAll('.page').forEach(page => {
        page.classList.remove('active');
    });

    document.getElementById(pageName).classList.add('active');

    document.querySelectorAll('.user-menu-item').forEach(item => {
        item.classList.remove('active');
    });

    event.target.closest('.user-menu-item').classList.add('active');

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// ============================================
// MODAL FUNCTIONS
// ============================================

function openPaymentModal() {
    document.getElementById('paymentModal').classList.add('show');
}

function closePaymentModal() {
    document.getElementById('paymentModal').classList.remove('show');
}

function openMaintenanceModal() {
    document.getElementById('maintenanceModal').classList.add('show');
}

function closeMaintenanceModal() {
    document.getElementById('maintenanceModal').classList.remove('show');
}

// ============================================
// FORM HANDLERS
// ============================================

function handleSaveProfile(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    console.log('Saving profile:', Object.fromEntries(formData));
    
    showNotification('Data pribadi berhasil disimpan!', 'success');
    form.reset();
}

function handlePayment(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    console.log('Processing payment:', Object.fromEntries(formData));
    
    closePaymentModal();
    showNotification('Pembayaran sedang diproses. Silakan tunggu konfirmasi...', 'success');
}

function handleMaintenance(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    console.log('Submitting maintenance:', Object.fromEntries(formData));
    
    closeMaintenanceModal();
    showNotification('Maintenance berhasil diajukan! Tim kami akan segera menghubungi Anda.', 'success');
}

function resetForm() {
    if (confirm('Apakah Anda yakin ingin mereset form?')) {
        document.querySelector('form').reset();
    }
}

// ============================================
// DOWNLOAD FUNCTIONS
// ============================================

function downloadInvoice() {
    showNotification('Invoice sedang diunduh...', 'success');
    console.log('Downloading invoice');
}

// ============================================
// UTILITY FUNCTIONS
// ============================================

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

// ============================================
// INITIALIZE
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Check authentication — wajib ada di sini
    const userData = checkUserAuth();
    if (!userData) return; // Hentikan eksekusi jika tidak terautentikasi

    // Update info user di halaman jika elemennya ada
    const userNameEl  = document.getElementById('userName');
    const userEmailEl = document.getElementById('userEmail');
    if (userNameEl)  userNameEl.textContent  = userData.name;
    if (userEmailEl) userEmailEl.textContent = userData.email;

    // Add animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to   { transform: translateX(0);     opacity: 1; }
        }
        @keyframes slideOut {
            to   { transform: translateX(400px); opacity: 0; }
        }
    `;
    document.head.appendChild(style);

    // Close modals when clicking outside
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('show');
        });
    });

    // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal.show').forEach(modal => {
                modal.classList.remove('show');
            });
        }
    });
});