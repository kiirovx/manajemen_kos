<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KosKita - Temukan Kos Impianmu</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <div class="navbar-logo">
                    <i class="fas fa-home"></i>
                    <span>KosKita</span>
                </div>
                <div class="navbar-menu">
                    <a href="#beranda">Beranda</a>
                    <a href="#kamar">Kamar</a>
                    <a href="#fasilitas">Fasilitas</a>
                    <a href="#kontak">Kontak</a>
                </div>
                <div class="navbar-actions">
                    <button class="btn btn-primary" onclick="window.location.href='/booking'">Booking
                        Sekarang</button>
                    <button class="btn btn-login" onclick="window.location.href='/login'">Login</button>

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
                        <img src="{{ asset('asset/utama.jpeg') }}" alt="Kamar">
                        <div class="room-price">Mulai dari <br> <strong>Rp 500k</strong></div>
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
                    <a href="#">Info lebih</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon security">
                        <i class="fas fa-camera"></i>
                    </div>
                    <h3>Keamanan 24/7</h3>
                    <p>CCTV dan security guard selama keamanan</p>
                    <a href="#">Info lebih</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon access">
                        <i class="fas fa-door-open"></i>
                    </div>
                    <h3>Akses 24 Jam</h3>
                    <p>Bebas keluar masuk kapan saja tanpa batas</p>
                    <a href="#">Info lebih</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon laundry">
                        <i class="fas fa-shirt"></i>
                    </div>
                    <h3>Lirik Include</h3>
                    <p>Lirik sudah termasuk dalam harga sewa</p>
                    <a href="#">Info lebih</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon parking">
                        <i class="fas fa-car"></i>
                    </div>
                    <h3>Parkir Luas</h3>
                    <p>Area parkir ampuh untuk motor dan mobil</p>
                    <a href="#">Info lebih</a>
                </div>

                <div class="feature-card">
                    <div class="feature-icon community">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Dapor Bersama</h3>
                    <p>Dapur lengkap dengan peralatan makan</p>
                    <a href="#">Info lebih</a>
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
                <div class="room-product">
                    <div class="room-image">
                        <img src="{{ asset('asset/1.jpeg') }}" alt="Kamar Standard">
                        <span class="room-tag">New</span>
                        <span class="price-tag">Rp 1.5jt</span>
                    </div>
                    <h3>Kamar Standard</h3>
                    <div class="room-info">
                        <span><i class="fas fa-expand"></i> Ukuran 3x4 m</span>
                        <span><i class="fas fa-bed"></i> Kapasitas 1 orang</span>
                        <span><i class="fas fa-bath"></i> Kamar Mandi Dalam</span>
                    </div>
                    <button class="btn btn-primary full-width">Lihat Detail</button>
                </div>

                <div class="room-product">
                    <div class="room-image">
                        <img src="{{ asset('asset/2.jpeg') }}" alt="Kamar Deluxe">
                        <span class="room-tag featured">Popular</span>
                        <span class="price-tag">Rp 2.3jt</span>
                    </div>
                    <h3>Kamar Deluxe</h3>
                    <div class="room-info">
                        <span><i class="fas fa-expand"></i> Ukuran 4x5 m</span>
                        <span><i class="fas fa-bed"></i> Kapasitas 1 orang</span>
                        <span><i class="fas fa-bath"></i> Kamar Mandi Dalam</span>
                    </div>
                    <button class="btn btn-primary full-width">Lihat Detail</button>
                </div>
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
                    <i class="fas fa-tv"></i>
                    <span>AC & TV</span>
                </div>
                <div class="facility-item">
                    <i class="fas fa-shower"></i>
                    <span>Kamar Mandi Dalam</span>
                </div>
                <div class="facility-item">
                    <i class="fas fa-wind"></i>
                    <span>Water Heater</span>
                </div>
                <div class="facility-item">
                    <i class="fas fa-couch"></i>
                    <span>Furniture Lengkap</span>
                </div>
                <div class="facility-item">
                    <i class="fas fa-lightbulb"></i>
                    <span>Mice Booster</span>
                </div>
                <div class="facility-item">
                    <i class="fas fa-ban"></i>
                    <span>Kaukan</span>
                </div>
            </div>

            <div class="additional-facilities">
                <div class="facility-box">
                    <h4><i class="fas fa-door-open"></i> Kebershan Teraga</h4>
                    <p>Cleaning service ruti setiap minggu</p>
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
                                <span>+62-812-3456-7890</span>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <p>Email</p>
                                <span>info@koskita.com</span>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <p>Alamat</p>
                                <span>Jl. Pendidikan No. 123, Jakarta</span>
                            </div>
                        </div>

                        <div class="contact-item">
                            <i class="fab fa-whatsapp"></i>
                            <div>
                                <p>Whatsapp</p>
                                <span>+62-812-3456-7890</span>
                            </div>
                        </div>
                    </div>

                    <div class="location-map">
                        <i class="fas fa-map-pin"></i>
                        <span>Lihat di Peta</span>
                    </div>
                </div>

                <div class="contact-right">
                    <form class="contact-form">
                        <h4>Kirim Pesan</h4>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" placeholder="Masukkan nama Anda">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" placeholder="contoh@email.com">
                        </div>

                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="tel" placeholder="+62 812 5678 9000">
                        </div>

                        <div class="form-group">
                            <label>Pesan</label>
                            <textarea placeholder="Tulis pesan Anda di sini..." rows="4"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary full-width">Kirim Pesan</button>
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

        <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>