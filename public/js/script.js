// ============================================
// SMOOTH SCROLL & NAVIGATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    // Active navigation link
    const navLinks = document.querySelectorAll('.navbar-menu a');
    const sections = document.querySelectorAll('section');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= sectionTop - 200) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').slice(1) === current) {
                link.classList.add('active');
            }
        });
    });

    // ============================================
    // SEARCH FUNCTIONALITY
    // ============================================
    const searchBox = document.querySelector('.search-box input');
    const searchItems = document.querySelectorAll('.search-item');

    if (searchBox) {
        searchBox.addEventListener('click', function() {
            this.parentElement.classList.add('active');
        });

        searchBox.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            
            searchItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm) || searchTerm === '') {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }

    searchItems.forEach(item => {
        item.addEventListener('click', function() {
            const text = this.querySelector('span').textContent;
            searchBox.value = text;
        });
    });

    // ============================================
    // FORM HANDLING
    // ============================================
    const contactForm = document.querySelector('.contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                nama: document.querySelector('.contact-form input[type="text"]').value,
                email: document.querySelector('.contact-form input[type="email"]').value,
                telepon: document.querySelector('.contact-form input[type="tel"]').value,
                pesan: document.querySelector('.contact-form textarea').value
            };

            // Validasi
            if (!formData.nama || !formData.email || !formData.telepon || !formData.pesan) {
                alert('Mohon lengkapi semua field!');
                return;
            }

            // Simulasi submit
            console.log('Form Data:', formData);
            alert('Terima kasih! Pesan Anda telah dikirim. Kami akan segera menghubungi Anda.');
            contactForm.reset();
        });
    }

    // ============================================
    // BUTTON ANIMATIONS
    // ============================================
    const buttons = document.querySelectorAll('.btn');
    
    buttons.forEach(button => {
        button.addEventListener('mousedown', function(e) {
            const ripple = document.createElement('span');
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.backgroundColor = 'rgba(255, 255, 255, 0.5)';
            ripple.style.width = '20px';
            ripple.style.height = '20px';
            ripple.style.animation = 'ripple 0.6s ease-out';
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            
            ripple.style.width = size + 'px';
            ripple.style.height = size + 'px';
            ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
            ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });

    // ============================================
    // IMAGE LAZY LOADING
    // ============================================
    if ('IntersectionObserver' in window) {
        const images = document.querySelectorAll('img');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.style.animation = 'fadeIn 0.5s ease-in';
                    observer.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }

    // ============================================
    // MOBILE MENU TOGGLE
    // ============================================
    const menuToggle = document.createElement('button');
    menuToggle.className = 'mobile-menu-toggle';
    menuToggle.innerHTML = '<i class="fas fa-bars"></i>';
    menuToggle.style.display = 'none';
    menuToggle.style.background = 'transparent';
    menuToggle.style.border = 'none';
    menuToggle.style.cursor = 'pointer';
    menuToggle.style.fontSize = '20px';
    menuToggle.style.color = 'var(--primary-color)';

    if (window.innerWidth <= 768) {
        document.querySelector('.navbar-menu').style.display = 'none';
    }

    // ============================================
    // SCROLL ANIMATIONS
    // ============================================
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'slideUp 0.6s ease-out forwards';
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.feature-card, .room-product, .facility-item').forEach(el => {
        observer.observe(el);
    });

    // ============================================
    // TOOLTIP FUNCTIONALITY
    // ============================================
    // const facilityItems = document.querySelectorAll('.facility-item');
    
    // facilityItems.forEach(item => {
    //     item.addEventListener('mouseenter', function() {
    //         const tooltip = document.createElement('div');
    //         tooltip.className = 'tooltip';
    //         tooltip.textContent = this.querySelector('span').textContent;
    //         tooltip.style.position = 'absolute';
    //         tooltip.style.backgroundColor = 'rgba(0, 0, 0, 0.8)';
    //         tooltip.style.color = 'white';
    //         tooltip.style.padding = '8px 12px';
    //         tooltip.style.borderRadius = '6px';
    //         tooltip.style.fontSize = '12px';
    //         tooltip.style.whiteSpace = 'nowrap';
    //         tooltip.style.pointerEvents = 'none';
    //         tooltip.style.zIndex = '1000';
            
    //         this.style.position = 'relative';
    //         this.appendChild(tooltip);
            
    //         setTimeout(() => tooltip.remove(), 2000);
    //     });
    // });
});

// ============================================
// ADD CSS ANIMATIONS
// ============================================
const style = document.createElement('style');
style.textContent = `
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    .navbar-menu a.active {
        color: var(--primary-color);
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 5px;
    }

    .btn {
        position: relative;
    }
`;
document.head.appendChild(style);

// ============================================
// UTILITY FUNCTIONS
// ============================================

/**
 * Scroll to element
 */
function scrollToElement(selector) {
    const element = document.querySelector(selector);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
}

/**
 * Format currency to IDR
 */
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

/**
 * Show notification
 */
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px 20px;
        border-radius: 8px;
        background: ${type === 'success' ? '#10B981' : '#EF4444'};
        color: white;
        z-index: 9999;
        animation: slideIn 0.3s ease-out;
        font-size: 14px;
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

/**
 * Validate email
 */
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Validate phone number
 */
function validatePhone(phone) {
    const re = /^(\+62|0)[0-9]{9,12}$/;
    return re.test(phone);
}

// Export functions if using modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        scrollToElement,
        formatCurrency,
        showNotification,
        validateEmail,
        validatePhone
    };
}