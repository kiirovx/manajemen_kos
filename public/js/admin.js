    // ============================================
    // ADMIN DASHBOARD LOGIC
    // ============================================

    // ============================================
    // AUTHENTICATION CHECK
    // ============================================

    function checkAdminAuth() {
        // Cek key 'currentUser' (key utama dari login.js)
        const authData = localStorage.getItem('currentUser');
        if (!authData) {
            window.location.href = '/login';
            return false;
        }

        const user = JSON.parse(authData);

        // Pastikan role-nya admin, bukan user biasa
        if (user.role !== 'admin') {
            window.location.href = '/login';
            return false;
        }

        return user;
    }

    function logoutAdmin() {
        if (confirm('Apakah Anda yakin ingin keluar?')) {
            localStorage.removeItem('currentUser');
            localStorage.removeItem('adminAuth');
            window.location.href = '/login';
        }
    }

    // ============================================
    // PAGE NAVIGATION
    // ============================================

    function showPage(pageName) {
        document.querySelectorAll('.page').forEach(page => {
            page.classList.remove('active');
        });

        document.getElementById(pageName).classList.add('active');

        document.querySelectorAll('.nav-item').forEach(item => {
            item.classList.remove('active');
        });

        event.target.classList.add('active');

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ============================================
    // ROOM MANAGEMENT
    // ============================================

    function openAddRoomModal() {
        document.getElementById('addRoomModal').classList.add('show');
    }

    function closeAddRoomModal() {
        document.getElementById('addRoomModal').classList.remove('show');
    }

    function handleAddRoom(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        
        console.log('Adding room:', Object.fromEntries(formData));
        
        showNotification('Kamar berhasil ditambahkan!', 'success');
        closeAddRoomModal();
        form.reset();
    }

    // ============================================
    // TENANT MANAGEMENT
    // ============================================

    function openAddTenantModal() {
        document.getElementById('addTenantModal').classList.add('show');
    }

    function closeAddTenantModal() {
        document.getElementById('addTenantModal').classList.remove('show');
    }

    function handleAddTenant(event) {
        event.preventDefault();
        
        const form = event.target;
        const formData = new FormData(form);
        
        console.log('Adding tenant:', Object.fromEntries(formData));
        
        showNotification('Penyewa berhasil ditambahkan!', 'success');
        closeAddTenantModal();
        form.reset();
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
        // Check authentication — harus di sini, bukan di luar DOMContentLoaded
        const adminData = checkAdminAuth();
        if (!adminData) return; // Hentikan eksekusi jika tidak terautentikasi

        // Update user info di sidebar
        const adminNameEl  = document.getElementById('adminName');
        const adminEmailEl = document.getElementById('adminEmail');
        if (adminNameEl)  adminNameEl.textContent  = adminData.name;
        if (adminEmailEl) adminEmailEl.textContent = adminData.email;

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

        // Search functionality
        document.querySelectorAll('.search-box').forEach(searchBox => {
            searchBox.addEventListener('keyup', function() {
                console.log('Search:', this.value);
            });
        });
    });

    // ============================================
    // EXPORT FUNCTIONS
    // ============================================

    function exportPDF() {
        showNotification('Mengunduh laporan PDF...', 'success');
    }

    function exportExcel() {
        showNotification('Mengunduh laporan Excel...', 'success');
    }

    // ============================================
    // CHART FUNCTIONS
    // ============================================

    function generateChart(canvasId, type, data) {
        console.log('Generating chart:', type, data);
    }

    // ============================================
    // FILTER FUNCTIONS
    // ============================================

    function applyFilter() {
        console.log('Applying filters...');
        showNotification('Filter diterapkan', 'success');
    }

    // ============================================
    // SAVE SETTINGS
    // ============================================

    document.addEventListener('DOMContentLoaded', function() {
        const saveSettingsBtn = document.querySelector('[onclick*="Simpan"]');
        if (saveSettingsBtn) {
            saveSettingsBtn.addEventListener('click', function() {
                showNotification('Pengaturan berhasil disimpan', 'success');
            });
        }
    });