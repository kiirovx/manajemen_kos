<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KosKita - Temukan Kos Impianmu</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CHATBOT WIDGET STYLE */
        .chatbot-container {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 9999;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        .chatbot-toggle-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            position: relative;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            outline: none;
        }

        .chatbot-toggle-btn:hover {
            transform: scale(1.08) rotate(5deg);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        .chatbot-toggle-btn:active {
            transform: scale(0.95);
        }

        .chat-close-icon {
            display: none;
        }

        .pulse-ring {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid #667eea;
            left: 0;
            top: 0;
            animation: pulse-wave 2s infinite ease-out;
            pointer-events: none;
            opacity: 0.8;
        }

        @keyframes pulse-wave {
            0% {
                transform: scale(1);
                opacity: 0.8;
            }

            100% {
                transform: scale(1.6);
                opacity: 0;
            }
        }

        .chatbot-window {
            position: fixed;
            bottom: 105px;
            right: 30px;
            width: 370px;
            height: 520px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            border: 1px solid rgba(0, 0, 0, 0.08);
            opacity: 0;
            transform: translateY(20px) scale(0.95);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            pointer-events: none;
        }

        .chatbot-window.show {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .chatbot-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .chatbot-header-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chatbot-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            position: relative;
        }

        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            position: absolute;
            bottom: 0;
            right: 0;
            border: 2px solid white;
        }

        .status-indicator.online {
            background: #10B981;
        }

        .chatbot-title {
            font-size: 15px;
            font-weight: 700;
            margin: 0;
        }

        .chatbot-subtitle {
            font-size: 11px;
            margin: 2px 0 0 0;
            opacity: 0.8;
        }

        .chatbot-close-btn {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            opacity: 0.8;
            font-size: 14px;
            transition: opacity 0.2s;
            padding: 5px;
        }

        .chatbot-close-btn:hover {
            opacity: 1;
        }

        .chatbot-body {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #F8F9FD;
            display: flex;
            flex-direction: column;
            gap: 15px;
            scroll-behavior: smooth;
        }

        .chat-message {
            display: flex;
            flex-direction: column;
            max-width: 85%;
        }

        .chat-message.bot {
            align-self: flex-start;
        }

        .chat-message.user {
            align-self: flex-end;
        }

        .chat-message-content {
    padding: 12px 16px;
    border-radius: 16px;
    font-size: 13.5px;
    line-height: 1.6;
    word-wrap: break-word;
    overflow-wrap: break-word;
}

/* Paragraph */
.chat-message-content p {
    margin: 0 0 10px;
}

.chat-message-content p:last-child {
    margin-bottom: 0;
}

/* Heading */
.chat-message-content h1,
.chat-message-content h2,
.chat-message-content h3,
.chat-message-content h4 {
    margin: 12px 0 8px;
    font-weight: 600;
    line-height: 1.4;
}

.chat-message-content h1 {
    font-size: 18px;
}

.chat-message-content h2 {
    font-size: 16px;
}

.chat-message-content h3 {
    font-size: 15px;
}

/* List */
.chat-message-content ul,
.chat-message-content ol {
    margin: 8px 0;
    padding-left: 20px;
}

.chat-message-content li {
    margin: 4px 0;
}

/* Bold */
.chat-message-content strong {
    font-weight: 600;
}

/* Inline code */
.chat-message-content code {
    background: rgba(0,0,0,.06);
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 12px;
}

/* Code block */
.chat-message-content pre {
    background: #1e1e1e;
    color: #fff;
    padding: 12px;
    border-radius: 10px;
    overflow-x: auto;
    margin: 10px 0;
}

.chat-message-content pre code {
    background: transparent;
    padding: 0;
}

/* Blockquote */
.chat-message-content blockquote {
    border-left: 3px solid #667eea;
    margin: 10px 0;
    padding-left: 12px;
    color: #666;
}

/* Table */
.chat-message-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 10px 0;
}

.chat-message-content th,
.chat-message-content td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

.chat-message-content th {
    background: #f5f5f5;
}

        /* .chat-message-content {
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 13.5px;
            line-height: 1.5;
            word-wrap: break-word;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        } */

        .chat-message.bot .chat-message-content {
            background: white;
            color: #333;
            border-bottom-left-radius: 4px;
        }

        .chat-message.user .chat-message-content {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom-right-radius: 4px;
        }

        .chat-message-time {
            font-size: 10px;
            color: #999;
            margin-top: 4px;
            padding: 0 4px;
        }

        .chat-message.user .chat-message-time {
            align-self: flex-end;
        }

        .chatbot-quick-replies {
            padding: 10px 15px;
            background: #F8F9FD;
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .quick-reply-pill {
            background: white;
            border: 1px solid rgba(102, 126, 234, 0.3);
            color: #667eea;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        }

        .quick-reply-pill:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
            transform: translateY(-1px);
        }

        .chatbot-footer {
            padding: 12px 15px;
            background: white;
            border-top: 1px solid rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chatbot-footer input {
            flex: 1;
            border: 1px solid #E2E8F0;
            padding: 10px 14px;
            border-radius: 24px;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s;
            font-family: inherit;
        }

        .chatbot-footer input:focus {
            border-color: #667eea;
        }

        .chatbot-send-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: transform 0.2s, opacity 0.2s;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }

        .chatbot-send-btn:hover {
            transform: scale(1.05);
            opacity: 0.95;
        }

        .chatbot-send-btn:active {
            transform: scale(0.95);
        }

        @keyframes dot-blink {
            0% {
                opacity: .2;
            }

            20% {
                opacity: 1;
            }

            100% {
                opacity: .2;
            }
        }

        @media (max-width: 480px) {
            .chatbot-window {
                width: calc(100vw - 30px);
                height: calc(100vh - 150px);
                bottom: 95px;
                right: 15px;
            }

            .chatbot-container {
                right: 15px;
                bottom: 15px;
            }
        }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <a href="{{ route('home') }}#beranda" class="navbar-logo"
                    style="text-decoration: none; color: inherit;">
                    <i class="fas fa-home"></i>
                    <span>KosKita</span>
                </a>
                <div class="navbar-menu">
                    <a href="#beranda">Beranda</a>
                    <a href="#kamar">Kamar</a>
                    <a href="#fasilitas">Fasilitas</a>
                    <a href="#kontak">Kontak</a>
                </div>
                <div class="navbar-actions">
                    <a href="{{ route('booking') }}" class="btn btn-primary">Booking Sekarang</a>
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('dashboard.admin') }}" class="btn btn-login">Dashboard Admin</a>
                        @else
                            <a href="{{ route('dashboard.users') }}" class="btn btn-login">Dashboard Saya</a>
                        @endif
                    @else
                        <a href="{{ route('login.page') }}" class="btn btn-login">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <section class="hero" id="beranda">
        <div class="container">
            <div class="hero-content">
                <div class="hero-left">
                    <span class="badge">Kos-kosan Modern & Nyaman Di Purwokerto</span>
                    <h1>KosKita <span class="highlight">Purwokerto</span></h1>
                    <p>Hunian nyaman dengan fasilitas lengkap, lokasi strategis, dan harga terjangkau. Temukan terbaik
                        untuk mahasiswa Purwokerto.</p>
                </div>

                <div class="hero-right">
                    <div class="room-card featured">
                        <img src="{{ asset('asset/dashboard.jpeg') }}" alt="Kamar">
                        <div class="room-price">Mulai dari <br> <strong>Rp 300k</strong></div>
                        <div class="room-rating">
                            <i class="fas fa-star"></i>
                            <span>4.9</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES SECTION -->
    <section class="features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon wifi">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <h3>WiFi Super Cepat</h3>
                    <p>Internet unlimited dengan kecepatan tinggi</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon security">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3>Keamanan 24/7</h3>
                    <p>CCTV dan security guard selama keamanan</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon access">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <h3>Akses 24 Jam</h3>
                    <p>Bebas keluar masuk kapan saja tanpa batas</p>
                </div>


                <div class="feature-card">
                    <div class="feature-icon parking">
                        <i class="fas fa-car"></i>
                    </div>
                    <h3>Parkir Luas</h3>
                    <p>Area parkir ampuh untuk motor dan mobil</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon community">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Dapur Bersama</h3>
                    <p>Dapur lengkap dengan peralatan makan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- KAMAR SECTION -->
    <section class="kamar" id="kamar">
        <div class="container">
            <div class="section-header">
                <h2>Temukan Kos yang sesuai dengan kebutuhan Lokasi dan budget Anda</h2>
            </div>

            <div class="rooms-grid">
                @forelse ($rooms as $room)
                @php
                    $typeLabels = ['standard' => 'Standard', 'deluxe' => 'Deluxe', 'premium' => 'Premium'];
                    $typeDisplay = $typeLabels[$room->type] ?? ucfirst($room->type);
                    $isAlmostFull = $room->status_label === 'Hampir Penuh';
                    $isFull = $room->status_label === 'Penuh';
                    $tagClass = $isAlmostFull ? 'room-tag featured' : 'room-tag';
                    $tagText = $isAlmostFull ? 'Hampir Penuh' : ($isFull ? 'Penuh' : 'Tersedia');
                    if ($isFull) $tagClass = 'room-tag';
                @endphp
                <div class="room-product">
                    <div class="room-image">
                        <img src="{{ $room->photos ?: asset('asset/kamar.png') }}" alt="Kamar {{ $room->number }}">
                        <span class="{{ $tagClass }}">{{ $tagText }}</span>
                        <span class="price-tag">Rp {{ number_format((float) $room->price, 0, ',', '.') }}</span>
                    </div>
                    <h3>{{ $room->name }}</h3>
                    <div class="room-info">
                        <span><i class="fas fa-bed"></i> Kapasitas {{ $room->capacity }} orang</span>
                        <span><i class="fas fa-layer-group"></i> Lantai {{ $room->floor }}</span>
                        @if($room->facilities)
                        <span><i class="fas fa-check-circle"></i> {{ Str::limit($room->facilities, 40) }}</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="room-product" style="grid-column: 1/-1; text-align: center; padding: 40px; color: #999;">
                    <i class="fas fa-door-open" style="font-size: 48px; margin-bottom: 15px; display: block; color: #DDD;"></i>
                    <p>Belum ada kamar tersedia.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- FASILITAS SECTION -->
    <section class="fasilitas" id="fasilitas">
        <div class="container">
            <div class="section-header">
                <h2>Fasilitas Kamar</h2>
                <p>Setiap kamar dilengkapi dengan fasilitas modern</p>
            </div>

            <div class="facilities-grid">
                <div class="facility-item">
                    <i class="fas fa-shower"></i>
                    <span>Kamar Mandi Dalam</span>
                </div>
                <div class="facility-item">
                    <i class="fas fa-couch"></i>
                    <span>Furniture Lengkap</span>
                </div>
                <div class="facility-item">
                    <i class="fas fa-chair"></i>
                    <span>Meja Belajar</span>
                </div>
                <div class="facility-item">
                    <i class="fas fa-parking"></i>
                    <span>Parkiran Luas</span>
                </div>
                <div class="facility-item">
                    <i class="fas fa-snowflake"></i>
                    <span>Kulkas</span>
                </div>
            </div>

            <div class="additional-facilities">
                <div class="facility-box">
                    <h4><i class="fas fa-door-open"></i> Kebersihan Terjaga</h4>
                    <p>Cleaning service rutin setiap minggu</p>
                </div>
                <div class="facility-box">
                    <h4><i class="fas fa-building"></i> Lingkungan Nyaman</h4>
                    <p>Area tenang dan kondusif untuk belajar</p>
                </div>
                <div class="facility-box">
                    <h4><i class="fas fa-leaf"></i> Dekat Kampus</h4>
                    <p>Lokasi dekat dengan kampus</p>
                </div>
            </div>
        </div>
    </section>

    <!-- HUBUNGI KAMI SECTION -->
    <section class="hubungi-kami" id="kontak">
        <div class="container">
            <h2>Hubungi Kami</h2>
            <p>Ada pertanyaan? Kami siap membantu Anda</p>

            <div class="contact-wrapper">
                <div class="contact-left">
                    <div class="contact-info">
                        <h4>Informasi Kontak</h4>

                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <p>Telepon</p>
                                <span>+62 815-7574-1736</span>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <p>Email</p>
                                <span>kelviantofarrell@gmail.com</span>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <p>Alamat</p>
                                <span>Purwokerto Kulon, Kec. Purwokerto Sel., Kabupaten Banyumas.</span>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fab fa-whatsapp"></i>
                            <div>
                                <p>Whatsapp</p>
                                <span>+62 815-7574-1736</span>
                            </div>
                        </div>
                    </div>

                    <div class="location-map">
                        <i class="fas fa-map-pin"></i>
                        <span>Lihat di Peta</span>
                    </div>
                </div>

                <div class="contact-right">
                    <form class="contact-form" id="contactForm" onsubmit="handleContactSubmit(event)">
                        <h4>Kirim Pesan</h4>

                        <div id="contactAlert" style="display: none; margin-bottom: 15px; padding: 12px 16px; border-radius: 8px; font-size: 13px; font-weight: 600;"></div>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" id="contactName" name="name" placeholder="Masukkan nama Anda" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="contactEmail" name="email" placeholder="contoh@email.com" required>
                        </div>

                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="tel" id="contactPhone" name="phone" placeholder="+62 812 5678 9000" required>
                        </div>

                        <div class="form-group">
                            <label>Pesan</label>
                            <textarea id="contactMessage" name="message" placeholder="Tulis pesan Anda di sini..." rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary full-width" id="contactSubmitBtn">
                            <span id="contactSubmitText">Kirim Pesan</span>
                            <span id="contactSubmitSpinner" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i> Mengirim...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 KosKita. Semua hak dilindungi.</p>
        </div>
    </footer>

    <!-- CHATBOT WIDGET -->
    <div class="chatbot-container" id="chatbotWidget">
        <button class="chatbot-toggle-btn" id="chatbotToggleBtn" onclick="toggleChatbot()">
            <i class="fas fa-comments chat-open-icon"></i>
            <i class="fas fa-times chat-close-icon" style="display: none;"></i>
            <span class="pulse-ring"></span>
        </button>

        <div class="chatbot-window" id="chatbotWindow">
            <div class="chatbot-header">
                <div class="chatbot-header-info">
                    <div class="chatbot-avatar">
                        <i class="fas fa-robot"></i>
                        <span class="status-indicator online"></span>
                    </div>
                    <div>
                        <h4 class="chatbot-title">Asisten KosKita</h4>
                        <p class="chatbot-subtitle">Online • Siap membantu</p>
                    </div>
                </div>
                <button class="chatbot-close-btn" onclick="toggleChatbot()">
                    <i class="fas fa-minus"></i>
                </button>
            </div>

            <div class="chatbot-body" id="chatbotBody">
                <div class="chat-message bot">
                    <div class="chat-message-content">
                        Halo! Selamat datang di <strong>KosKita Purwokerto</strong>. Ada yang bisa saya bantu hari ini?
                        😊
                    </div>
                    <span class="chat-message-time">Baru saja</span>
                </div>
            </div>

            <div class="chatbot-quick-replies" id="quickReplies">
                <button class="quick-reply-pill" onclick="sendQuickReply('Berapa harga sewa kamar?')">Harga
                    Kamar</button>
                <button class="quick-reply-pill" onclick="sendQuickReply('Apa saja fasilitas yang tersedia?')">Fasilitas
                    Kos</button>
                <button class="quick-reply-pill" onclick="sendQuickReply('Bagaimana cara memesan/booking?')">Cara
                    Booking</button>
                <button class="quick-reply-pill" onclick="sendQuickReply('Di mana lokasi lengkap KosKita?')">Lokasi &
                    Kontak</button>
            </div>

            <form class="chatbot-footer" id="chatbotForm" onsubmit="handleChatSubmit(event)">
                <input type="text" id="chatbotInput" placeholder="Tulis pesan Anda..." autocomplete="off">
                <button type="submit" class="chatbot-send-btn">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        // ============================================
        // SMOOTH SCROLL & NAVIGATION
        // ============================================
        document.addEventListener('DOMContentLoaded', function () {
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
                searchBox.addEventListener('click', function () {
                    this.parentElement.classList.add('active');
                });

                searchBox.addEventListener('input', function (e) {
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
                item.addEventListener('click', function () {
                    const text = this.querySelector('span').textContent;
                    searchBox.value = text;
                });
            });

            // ============================================
            // CONTACT FORM HANDLER (didefinisikan global)
            // ============================================
            // handleContactSubmit() didefinisikan di bawah dengan fungsi global

            // ============================================
            // BUTTON ANIMATIONS
            // ============================================
            const buttons = document.querySelectorAll('.btn');

            buttons.forEach(button => {
                button.addEventListener('mousedown', function (e) {
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
                const navbarMenu = document.querySelector('.navbar-menu');
                if (navbarMenu) navbarMenu.style.display = 'none';
            }

            // ============================================
            // SCROLL ANIMATIONS
            // ============================================
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -100px 0px'
            };

            const observer = new IntersectionObserver(function (entries) {
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
                from { opacity: 0; }
                to { opacity: 1; }
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
        function scrollToElement(selector) {
            const element = document.querySelector(selector);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }

        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

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

        function validateEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }

        function validatePhone(phone) {
            const re = /^(\+62|0)[0-9]{9,12}$/;
            return re.test(phone);
        }
    </script>
    <script>
        function toggleChatbot() {
            const chatWindow = document.getElementById('chatbotWindow');
            const openIcon = document.querySelector('.chat-open-icon');
            const closeIcon = document.querySelector('.chat-close-icon');

            chatWindow.classList.toggle('show');

            if (chatWindow.classList.contains('show')) {
                openIcon.style.display = 'none';
                closeIcon.style.display = 'block';
                document.getElementById('chatbotInput').focus();
            } else {
                openIcon.style.display = 'block';
                closeIcon.style.display = 'none';
            }
        }

        function appendMessage(text, sender) {
    const body = document.getElementById('chatbotBody');
    const msgDiv = document.createElement('div');

    msgDiv.className = `chat-message ${sender}`;

    const now = new Date();
    const timeStr = now.toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
    });

    const content = sender === 'bot'
        ? marked.parse(text)
        : text;

    msgDiv.innerHTML = `
        <div class="chat-message-content">
            ${content}
        </div>
        <span class="chat-message-time">${timeStr}</span>
    `;

    body.appendChild(msgDiv);
    body.scrollTop = body.scrollHeight;
}

        async function sendBotMessage(text) {
            appendMessage(text, 'user');
            showTypingIndicator();

            try {

                const response = await fetch('{{ route("chatbot.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .content
                    },
                    body: JSON.stringify({
                        message: text
                    })
                });

                const textResponse = await response.text();

                console.log('Status:', response.status);
                console.log('Response Raw:', textResponse);

                removeTypingIndicator();

                let data;

                try {
                    data = JSON.parse(textResponse);
                } catch (e) {
                    appendMessage(textResponse, 'bot');
                    return;
                }

                if (data.reply) {
                    appendMessage(data.reply, 'bot');
                } else {
                    appendMessage('Maaf, tidak ada respon dari server.', 'bot');
                }

            } catch (error) {

                console.error(error);

                removeTypingIndicator();

                appendMessage(
                    'Maaf, terjadi kesalahan. Silakan coba lagi nanti.',
                    'bot'
                );
            }
        }

        function sendQuickReply(text) {
            sendBotMessage(text);
        }

        function handleChatSubmit(event) {
            event.preventDefault();
            const input = document.getElementById('chatbotInput');
            const text = input.value.trim();
            if (!text) return;

            input.value = '';
            sendBotMessage(text);
        }

        function showTypingIndicator() {
            const body = document.getElementById('chatbotBody');
            const typingDiv = document.createElement('div');
            typingDiv.className = 'chat-message bot';
            typingDiv.id = 'typingIndicator';
            typingDiv.innerHTML = `
                <div class="chat-message-content" style="padding: 8px 16px;">
                    <span class="typing-dots">
                        <span style="animation: dot-blink 1.4s infinite both;">•</span>
                        <span style="animation: dot-blink 1.4s infinite both 0.2s;">•</span>
                        <span style="animation: dot-blink 1.4s infinite both 0.4s;">•</span>
                    </span>
                </div>
            `;
            body.appendChild(typingDiv);
            body.scrollTop = body.scrollHeight;
        }

        function removeTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) indicator.remove();
        }
    </script>

    <script>
        // ============================================
        // CONTACT FORM BACKEND HANDLER
        // ============================================
        async function handleContactSubmit(event) {
            event.preventDefault();

            const form = document.getElementById('contactForm');
            const alert = document.getElementById('contactAlert');
            const submitBtn = document.getElementById('contactSubmitBtn');
            const submitText = document.getElementById('contactSubmitText');
            const submitSpinner = document.getElementById('contactSubmitSpinner');

            const name    = document.getElementById('contactName').value.trim();
            const email   = document.getElementById('contactEmail').value.trim();
            const phone   = document.getElementById('contactPhone').value.trim();
            const message = document.getElementById('contactMessage').value.trim();

            if (!name || !email || !phone || !message) {
                alert.style.display = 'block';
                alert.style.background = '#FEE2E2';
                alert.style.color = '#7F1D1D';
                alert.textContent = 'Mohon lengkapi semua field!';
                alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            if (!validateEmail(email)) {
                alert.style.display = 'block';
                alert.style.background = '#FEE2E2';
                alert.style.color = '#7F1D1D';
                alert.textContent = 'Format email tidak valid.';
                alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }

            // Show loading
            submitBtn.disabled = true;
            submitText.style.display = 'none';
            submitSpinner.style.display = 'inline';
            alert.style.display = 'none';

            try {
                const response = await fetch('{{ route("contact.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        phone: phone,
                        message: message
                    })
                });

                const data = await response.json();

                if (data.success) {
                    alert.style.display = 'block';
                    alert.style.background = '#D1FAE5';
                    alert.style.color = '#065F46';
                    alert.textContent = data.message;
                    form.reset();
                } else {
                    alert.style.display = 'block';
                    alert.style.background = '#FEE2E2';
                    alert.style.color = '#7F1D1D';
                    alert.textContent = data.message || 'Gagal mengirim pesan.';
                }
            } catch (error) {
                console.error('Contact form error:', error);
                alert.style.display = 'block';
                alert.style.background = '#FEE2E2';
                alert.style.color = '#7F1D1D';
                alert.textContent = 'Terjadi kesalahan. Silakan coba lagi nanti.';
            } finally {
                submitBtn.disabled = false;
                submitText.style.display = 'inline';
                submitSpinner.style.display = 'none';
                if (alert.style.display === 'block') {
                    alert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        }
    </script>
</body>

</html>