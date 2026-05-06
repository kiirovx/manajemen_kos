// ============================================
// DEMO ACCOUNTS - Admin & User
// ============================================
const DEMO_ACCOUNTS = [
    {
        id: 1,
        email: 'admin@koskita.com',
        password: 'admin123',
        role: 'admin',
        name: 'Admin KosKita',
        redirect: '/admin'
    },
    {
        id: 2,
        email: 'user@koskita.com',
        password: 'user123',
        role: 'user',
        name: 'User Demo',
        redirect: '/user'
    }
];

// ============================================
// UTILITY FUNCTIONS
// ============================================

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function showError(message) {
    const errorEl = document.getElementById('errorMessage');
    errorEl.textContent = message;
    errorEl.classList.add('show');
}

function hideError() {
    const errorEl = document.getElementById('errorMessage');
    errorEl.classList.remove('show');
}

function setLoading(isLoading) {
    const loginBtn = document.getElementById('loginBtn');
    const spinner  = document.getElementById('spinner');
    const btnText  = document.getElementById('btnText');

    if (isLoading) {
        loginBtn.disabled = true;
        spinner.classList.add('show');
        btnText.textContent = 'Sedang masuk...';
    } else {
        loginBtn.disabled = false;
        spinner.classList.remove('show');
        btnText.textContent = 'Masuk';
    }
}

function saveAuthData(account, remember) {
    const authData = {
        id: account.id,
        name: account.name,
        email: account.email,
        role: account.role,
        loginTime: new Date().toISOString()
    };

    // Simpan dengan key universal 'currentUser'
    localStorage.setItem('currentUser', JSON.stringify(authData));

    // Juga simpan key lama agar kompatibel dengan dashboard yang sudah ada
    localStorage.setItem('adminAuth', JSON.stringify(authData));

    if (remember) {
        localStorage.setItem('rememberEmail', account.email);
    } else {
        localStorage.removeItem('rememberEmail');
    }
}

// ============================================
// HANDLE LOGIN - Dipanggil saat form disubmit
// ============================================
function handleLogin(event) {
    event.preventDefault();
    hideError();

    const email    = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const remember = document.getElementById('remember').checked;

    // Validasi kosong
    if (!email || !password) {
        showError('Email dan password tidak boleh kosong.');
        return;
    }

    // Validasi format email
    if (!validateEmail(email)) {
        showError('Format email tidak valid.');
        return;
    }

    // Tampilkan loading
    setLoading(true);

    // Simulasi delay seperti API call
    setTimeout(() => {
        const account = DEMO_ACCOUNTS.find(
            acc => acc.email === email && acc.password === password
        );

        if (account) {
            // Simpan data auth
            saveAuthData(account, remember);

            // Redirect ke dashboard sesuai role
            window.location.href = account.redirect;
        } else {
            showError('Email atau password salah. Coba gunakan akun demo di bawah.');
            setLoading(false);
        }
    }, 800);
}

// ============================================
// LOGOUT FUNCTION (dipakai di dashboard)
// ============================================
function logout() {
    localStorage.removeItem('currentUser');
    localStorage.removeItem('adminAuth');
    window.location.href = '/login';
}

// ============================================
// INITIALIZE
// ============================================
document.addEventListener('DOMContentLoaded', function () {
    const currentUser = localStorage.getItem('currentUser');

    // Jika sudah login, redirect langsung ke dashboard yang sesuai
    if (currentUser) {
        const user = JSON.parse(currentUser);
        if (user.role === 'admin') {
            window.location.href = '/admin';
        } else {
            window.location.href = '/user';
        }
        return;
    }

    // Isi email jika pernah centang "Ingat saya"
    const rememberEmail = localStorage.getItem('rememberEmail');
    if (rememberEmail) {
        document.getElementById('email').value = rememberEmail;
        document.getElementById('remember').checked = true;
    }

    // Focus ke input email
    document.getElementById('email').focus();
});