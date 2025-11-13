<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gunung Butak | Reservasi Pendakian</title>
    <link rel="icon" type="image/x-icon" href="assets/images/LOGO_WEB.png">
    
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Supabase Client Library -->
    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
    
    <!-- Custom Native CSS (Memanggil file eksternal) -->
    <link rel="stylesheet" href="assets/css/index.css">
    
    <!-- Include Supabase Configuration (Halaman PHP memanggil file PHP lain) -->
    <?php include 'config/supabase.php'; ?>

</head>
<body class="bg-gray-50">
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header (Fixed Top) -->
        <header class="header">
            <div class="container">
                <div class="header-inner">
                    <div class="logo-group">
                        <img src="assets/images/LOGO_WEB.png" onerror="this.src='https://placehold.co/40x40/ffffff/10b981?text=L'" alt="Logo Gunung Butak" class="logo-img">
                        <h1 class="logo-title">Gunung Butak | Reservasi Pendakian</h1>
                    </div>
                    <div class="auth-controls">
                        <button class="dark-mode-toggle" id="dark-mode-toggle">
                            <i class="fas fa-moon"></i>
                        </button>
                        <div id="auth-container">
                            <a href="simaksi/auth/login.php" id="auth-link" class="auth-button">
                                Login
                            </a>
                        </div>
                        <div id="user-greeting" class="user-greeting">
                            <span id="greeting-text" class="text-sm">Halo,</span>
                            <span id="user-fullname" class="font-semibold text-sm"></span>
                            <a href="#" id="logout-link-header" class="logout-btn-header">
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

    <!-- Hero Section -->
    <section class="hero-section" id="hero">
        <div class="hero-text-container">
            <div style="transition-delay: 0s;" class="hero-content">
                <h1 class="hero-title gradient-text" style="text-shadow: 0 4px 10px rgba(0,0,0,0.5);">GUNUNG BUTAK</h1>
                <p class="hero-subtitle font-light" style="text-shadow: 0 2px 5px rgba(0,0,0,0.5);">
                    Jelajahi keindahan alam dan tantangan ekstrem di puncak tertinggi Jawa Timur
                </p>
            </div>
            
            <div class="hero-buttons" style="transition-delay: 0.2s;">
                <a href="simaksi/auth/login.php" class="hero-btn pulse-button reserve-btn">
                    <i class="fas fa-rocket"></i>Reservasi Sekarang
                </a>
                <a href="#lokasi" class="hero-btn pulse-button location-btn">
                    <i class="fas fa-map-marker-alt"></i>Lokasi Pendakian
                </a>
            </div>
            
            <div class="hero-stats-grid" style="transition-delay: 0.4s;">
                <div class="stat-item card-hover">
                    <i class="fas fa-users stat-icon"></i>
                    <div class="stat-value gradient-text"><span class="counter" data-target="2500">0</span>+</div>
                    <div class="stat-label">Pendaki Tahun Ini</div>
                </div>
                <div class="stat-item card-hover">
                    <i class="fas fa-star stat-icon"></i>
                    <div class="stat-value gradient-text"><span class="counter" data-target="49">0</span>/5</div>
                    <div class="stat-label">Rating Pengalaman</div>
                </div>
                <div class="stat-item card-hover">
                    <i class="fas fa-calendar-check stat-icon"></i>
                    <div class="stat-value gradient-text"><span class="counter" data-target="6">0</span>+</div>
                    <div class="stat-label">Tahun Operasional</div>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="scroll-indicator">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- Pengumuman Section -->
    <section class="section-padding bg-gradient-green-blue" id="pengumuman">
        <div class="container">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-primary mb-4 animated-border">Pengumuman Terbaru</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Informasi penting terkait pendakian Gunung Butak
                </p>
            </div>
            
            <div id="pengumuman-container" class="announcement-card-wrapper card-hover">
                <div class="announcement-card">
                    <div id="pengumuman-content" class="announcement-content">
                        <!-- Konten pengumuman akan dimuat di sini oleh JavaScript dari Supabase -->
                        <div id="pengumuman-loading" class="loading-state">
                            <div class="spinner"></div>
                            <p class="text-gray-600">Memuat pengumuman terbaru...</p>
                        </div>
                        <div id="pengumuman-empty" class="empty-state" style="display: none;">
                            <i class="fas fa-bullhorn"></i>
                            <h3 class="text-2xl font-bold text-gray-700 mb-2">Tidak Ada Pengumuman</h3>
                            <p class="text-gray-600">Belum ada pengumuman aktif saat ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Info Kunci -->
    <section class="section-padding bg-gradient-gray-white" id="info-kunci">
        <div class="container">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-primary mb-6 animated-border">Kenapa Memilih Gunung Butak?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Temukan alasan mengapa Gunung Butak menjadi destinasi pendakian favorit para petualang
                </p>
            </div>
            
            <div class="info-grid-main">
                <!-- Ketinggian -->
                <div class="stat-info-card card-hover">
                    <div class="stat-info-card-header header-green"></div>
                    <div class="card-content-p10">
                        <div class="text-center">
                            <div class="icon-wrapper-24 icon-bg-green shadow-inner">
                                <i class="fas fa-mountain text-4xl elevation-icon"></i>
                            </div>
                            <h3 class="info-main-stat gradient-text text-primary">2,868</h3>
                            <p class="info-secondary-stat">Ketinggian Gunung</p>
                            <div class="progress-bar">
                                <div class="progress-fill progress-fill-elevation"></div>
                            </div>
                            <p class="info-description">Meter di Atas Permukaan Laut</p>
                        </div>
                    </div>
                </div>
                
                <!-- Kesulitan -->
                <div class="stat-info-card card-hover">
                    <div class="stat-info-card-header header-orange"></div>
                    <div class="card-content-p10">
                        <div class="text-center">
                            <div class="icon-wrapper-24 icon-bg-orange shadow-inner">
                                <i class="fas fa-bolt text-4xl difficulty-icon text-orange-600"></i>
                            </div>
                            <h3 class="text-5xl font-bold mb-4 text-orange-600">Sulit</h3>
                            <p class="info-secondary-stat">Tingkat Kesulitan</p>
                            <div class="progress-bar progress-bar-difficulty">
                                <div class="progress-fill progress-fill-difficulty" style="width: 80%;"></div>
                            </div>
                            <p class="info-description">Cocok untuk pendaki berpengalaman</p>
                        </div>
                    </div>
                </div>
                
                <!-- Durasi -->
                <div class="stat-info-card card-hover">
                    <div class="stat-info-card-header header-blue"></div>
                    <div class="card-content-p10">
                        <div class="text-center">
                            <div class="icon-wrapper-24 icon-bg-blue shadow-inner">
                                <i class="fas fa-clock text-4xl duration-icon text-blue-600"></i>
                            </div>
                            <h3 class="info-main-stat gradient-text text-blue-600">3-4</h3>
                            <p class="info-secondary-stat">Durasi Pendakian</p>
                            <p class="info-description">Hari Pendakian Ideal</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Info Cards -->
            <div class="info-grid-secondary">
                <div class="additional-info-card card-hover">
                    <div class="icon-wrapper-20 icon-temp shadow-md">
                        <i class="fas fa-temperature-high text-3xl"></i>
                    </div>
                    <h4 class="additional-info-title">Suhu Rata-rata</h4>
                    <p class="additional-info-stat">15-22°C</p>
                    <p class="additional-info-desc">Nyaman untuk pendakian</p>
                </div>
                
                <div class="additional-info-card card-hover">
                    <div class="icon-wrapper-20 icon-wind shadow-md">
                        <i class="fas fa-wind text-3xl"></i>
                    </div>
                    <h4 class="additional-info-title">Kecepatan Angin</h4>
                    <p class="additional-info-stat">10-30 km/jam</p>
                    <p class="additional-info-desc">Angin segar di puncak</p>
                </div>
                
                <div class="additional-info-card card-hover">
                    <div class="icon-wrapper-20 icon-rain shadow-md">
                        <i class="fas fa-cloud-sun text-3xl"></i>
                    </div>
                    <h4 class="additional-info-title">Curah Hujan</h4>
                    <p class="additional-info-stat">Rendah</p>
                    <p class="additional-info-desc">Kemungkinan hujan rendah (Kemarau)</p>
                </div>
                
                <div class="additional-info-card card-hover">
                    <div class="icon-wrapper-20 icon-veg shadow-md">
                        <i class="fas fa-tree text-3xl"></i>
                    </div>
                    <h4 class="additional-info-title">Vegetasi</h4>
                    <p class="additional-info-stat">4 Zona</p>
                    <p class="additional-info-desc">Hutan-Sabana yang bervariasi</p>
                </div>
            </div>
            
            <!-- Additional Information Section -->
            <div class="security-section card-hover">
                <div class="security-content">
                    <h3 class="security-title">Keamanan dan Fasilitas</h3>
                    <div class="security-grid">
                        <div class="security-item card-hover">
                            <i class="fas fa-shield-alt"></i>
                            <h4 class="text-xl font-bold mb-2">Keamanan Terjamin</h4>
                            <p>Pendakian aman dengan pemandu bersertifikat</p>
                        </div>
                        <div class="security-item card-hover">
                            <i class="fas fa-medkit"></i>
                            <h4 class="text-xl font-bold mb-2">Fasilitas Lengkap</h4>
                            <p>Basecamp lengkap dengan peralatan darurat</p>
                        </div>
                        <div class="security-item card-hover">
                            <i class="fas fa-users"></i>
                            <h4 class="text-xl font-bold mb-2">Komunitas Pendaki</h4>
                            <p>Ikuti komunitas pendakian aktif</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tentang Gunung Butak -->
    <section id="tentang" class="section-padding bg-gradient-gray-white">
        <div class="container">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-primary mb-4 animated-border">Tentang Gunung Butak</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-12 leading-relaxed">
                    Kenali lebih dekat destinasi pendakian terbaik di Jawa Timur
                </p>
            </div>
            
            <div class="max-w-6xl mx-auto">
                <div class="about-grid">
                    <div class="feature-card card-hover">
                        <div class="about-card-wrapper">
                            <div class="about-card-inner">
                                <h3 class="about-title">Sejarah dan Asal Usul</h3>
                                <p class="about-text mb-4">
                                    Gunung Butak adalah destinasi pendakian yang menawarkan keindahan alam yang luar biasa dan pengalaman mendaki yang menantang. Terletak di Jawa Timur, gunung ini memiliki ketinggian 2.868 meter di atas permukaan laut.
                                </p>
                                <p class="about-text">
                                    Dengan berbagai ekosistem yang berbeda dari hutan hingga sabana, Gunung Butak menjadi tempat yang ideal bagi para pendaki yang mencari keindahan alam sekaligus tantangan ekstrem.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="feature-card card-hover">
                        <div class="about-image-wrapper">
                            <img src="assets/images/Pemandangan_Gunung_Butak.jpg" onerror="this.src='https://placehold.co/600x400/10b981/ffffff?text=Pemandangan+Gunung+Butak'" alt="Pemandangan Gunung Butak" class="about-image">
                        </div>
                    </div>
                </div>
                
                <div class="fact-grid">
                    <div class="fact-card-left card-hover">
                        <h3 class="fact-title">Fakta Menarik</h3>
                        <ul class="fact-list">
                            <li class="fact-item">
                                <i class="fas fa-star"></i>
                                <span class="fact-item-text">Nama "Butak" berasal dari bahasa Jawa yang berarti 'telanjang', merujuk pada puncaknya yang tidak berpohon.</span>
                            </li>
                            <li class="fact-item">
                                <i class="fas fa-star"></i>
                                <span class="fact-item-text">Merupakan gunung tertinggi ke-3 di Jawa Timur.</span>
                            </li>
                            <li class="fact-item">
                                <i class="fas fa-star"></i>
                                <span class="fact-item-text">Habitat bagi berbagai spesies langka flora dan fauna.</span>
                            </li>
                            <li class="fact-item">
                                <i class="fas fa-star"></i>
                                <span class="fact-item-text">Lokasi *hunting sunrise* yang legendaris.</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="fact-card-right card-hover">
                        <h3 class="fact-title">Keunggulan Kami</h3>
                        <ul class="fact-list">
                            <li class="fact-item">
                                <i class="fas fa-shield-alt fact-item-accent"></i>
                                <span class="fact-item-text">Sistem reservasi online terintegrasi dan aman.</span>
                            </li>
                            <li class="fact-item">
                                <i class="fas fa-user-friends fact-item-accent"></i>
                                <span class="fact-item-text">Pemandu berpengalaman bersertifikat nasional.</span>
                            </li>
                            <li class="fact-item">
                                <i class="fas fa-home fact-item-accent"></i>
                                <span class="fact-item-text">Basecamp lengkap dengan fasilitas memadai.</span>
                            </li>
                            <li class="fact-item">
                                <i class="fas fa-heart fact-item-accent"></i>
                                <span class="fact-item-text">Komunitas pendaki aktif dan suportif.</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Waktu Terbaik untuk Mendaki -->
    <section class="section-padding bg-gradient-gray-white" id="waktu-terbaik">
        <div class="container">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-primary mb-4 animated-border">Waktu Terbaik untuk Mendaki</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Rencanakan pendakian Anda dengan informasi cuaca dan waktu yang optimal
                </p>
            </div>
            
            <div class="best-time-grid">
                <!-- Card 1 -->
                <div class="time-card dry-season-card card-hover">
                    <div class="time-card-header">
                        <div class="time-icon-wrapper dry-season-icon-bg shadow-md">
                            <i class="fas fa-cloud-sun text-4xl"></i>
                        </div>
                        <h3 class="time-card-title dry-season-title">Musim Kemarau</h3>
                        <p class="time-card-subtitle">April - Oktober</p>
                    </div>
                    <ul class="time-feature-list">
                        <li class="time-feature-item">
                            <div class="time-check-icon-wrapper">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <p><strong>Jalur pendakian stabil</strong> tanpa hambatan genangan air</p>
                            </div>
                        </li>
                        <li class="time-feature-item">
                            <div class="time-check-icon-wrapper">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <p><strong>Visibilitas sangat baik</strong> untuk menikmati pemandangan</p>
                            </div>
                        </li>
                        <li class="time-feature-item">
                            <div class="time-check-icon-wrapper">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <p><strong>Suhu sejuk dan nyaman</strong> untuk pendakian</p>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <!-- Card 2 -->
                <div class="time-card daily-time-card card-hover">
                    <div class="time-card-header">
                        <div class="time-icon-wrapper daily-time-icon-bg shadow-md">
                            <i class="fas fa-clock text-4xl"></i>
                        </div>
                        <h3 class="time-card-title daily-time-title">Waktu Ideal Sehari</h3>
                        <p class="time-card-subtitle">Pendakian Harian</p>
                    </div>
                    <ul class="time-feature-list">
                        <li class="time-feature-item">
                            <div class="time-check-icon-wrapper" style="background: linear-gradient(to right, #FFFBEB, #FEF3C7); color: #D97706;">
                                <i class="fas fa-sun" style="color: #D97706;"></i>
                            </div>
                            <div>
                                <p><strong>Pagi hari (04:00-06:00):</strong> Udara segar dan suhu nyaman</p>
                            </div>
                        </li>
                        <li class="time-feature-item">
                            <div class="time-check-icon-wrapper" style="background: linear-gradient(to right, #FFEDD5, #FEF3C7); color: #EA580C;">
                                <i class="fas fa-sunrise" style="color: #EA580C;"></i>
                            </div>
                            <div>
                                <p><strong>Matahari terbit:</strong> Pemandangan menakjubkan dari puncak</p>
                            </div>
                        </li>
                        <li class="time-feature-item">
                            <div class="time-check-icon-wrapper" style="background: linear-gradient(to right, #F3E8FF, #E9D5FF); color: #9333EA;">
                                <i class="fas fa-moon" style="color: #9333EA;"></i>
                            </div>
                            <div>
                                <p><strong>Malam hari:</strong> Sempurna untuk *stargazing*</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <!-- Weather Forecast Section -->
            <div class="weather-forecast-section card-hover">
                <h3 class="weather-forecast-title">Prakiraan Cuaca Mendatang</h3>
                <p class="weather-forecast-subtitle">Cuaca terkini dan perkiraan 3 hari ke depan untuk Gunung Butak</p>
                
                <div id="weather-forecast" class="mt-8">
                    <div class="weather-forecast-loading">
                        <div class="spinner"></div>
                        <p class="opacity-90">Memuat perkiraan cuaca...</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Peta Lokasi -->
    <section id="lokasi" class="section-padding bg-gradient-gray-white">
        <div class="container">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-primary mb-4 animated-border">Lokasi Gunung Butak</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Temukan jalur pendakian melalui Basecamp Kucur dan rute terbaik menuju puncak
                </p>
            </div>
            
            <div class="location-grid">
                <div class="card-hover">
                    <div class="route-card-wrapper">
                        <div class="route-card-inner">
                            <h3 class="route-title">Rute Akses Menuju Basecamp</h3>
                            <div class="route-list">
                                <div class="route-item card-hover">
                                    <div class="route-icon-wrapper shadow-md">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xl text-gray-800 mb-2">Basecamp Kucur</h4>
                                        <p class="text-gray-600 text-lg">Kabupaten Malang, Jawa Timur</p>
                                    </div>
                                </div>
                                
                                <div class="route-item card-hover">
                                    <div class="route-icon-wrapper shadow-md">
                                        <i class="fas fa-car"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xl text-gray-800 mb-2">Akses Kendaraan</h4>
                                        <p class="text-gray-600 text-lg">Dapat dijangkau dengan mobil atau motor, ketinggian 1.200 mdpl</p>
                                    </div>
                                </div>
                                
                                <div class="route-item card-hover">
                                    <div class="route-icon-wrapper shadow-md">
                                        <i class="fas fa-walking"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xl text-gray-800 mb-2">Jalur Pendakian</h4>
                                        <p class="text-gray-600 text-lg">Jalur utama sepanjang 8 km, perkiraan waktu 12-15 jam</p>
                                    </div>
                                </div>
                                
                                <div class="route-item card-hover">
                                    <div class="route-icon-wrapper shadow-md">
                                        <i class="fas fa-info-circle"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-xl text-gray-800 mb-2">Fasilitas Basecamp</h4>
                                        <p class="text-gray-600 text-lg">Parkir, toilet, tempat istirahat, dan penyewaan peralatan</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="travel-tips">
                                <h4 class="font-bold text-lg text-blue-800 mb-3">Tips Perjalanan:</h4>
                                <ul class="travel-tips-list">
                                    <li>
                                        <i class="fas fa-check-circle"></i>
                                        <span>Waktu tempuh dari Surabaya: 2-3 jam</span>
                                    </li>
                                    <li>
                                        <i class="fas fa-check-circle"></i>
                                        <span>Parkir aman tersedia di basecamp</span>
                                    </li>
                                    <li>
                                        <i class="fas fa-check-circle"></i>
                                        <span>Siapkan peralatan sesuai cuaca</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-hover">
                    <div class="map-wrapper">
                        <div class="map-inner">
                            <div class="map-header"></div>
                            <div class="map-iframe-container">
                                <iframe 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.768447332634!2d112.6028828757616!3d-8.16107007943676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7834511d51c313%3A0x6b7a5446e6a188f1!2sGunung%20Butak!5e0!3m2!1sen!2sid!4v1650000000000!5m2!1sen!2sid" 
                                    width="100%" 
                                    height="500" 
                                    class="map-iframe"
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade"
                                    title="Google Maps Location of Gunung Butak">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Interactive Map Features -->
            <div class="map-features-grid">
                <div class="map-feature-card card-hover">
                    <i class="fas fa-route"></i>
                    <h4 class="text-2xl font-bold text-gray-800 mb-3">Jalur Pendakian</h4>
                    <p class="text-gray-600">Rute terbaik dengan pos-pos peristirahatan</p>
                </div>
                
                <div class="map-feature-card card-hover">
                    <i class="fas fa-exclamation-triangle"></i>
                    <h4 class="text-2xl font-bold text-gray-800 mb-3">Titik Bahaya</h4>
                    <p class="text-gray-600">Area rawan longsor dan medan terjal</p>
                </div>
                
                <div class="map-feature-card card-hover">
                    <i class="fas fa-water"></i>
                    <h4 class="text-2xl font-bold text-gray-800 mb-3">Sumber Air</h4>
                    <p class="text-gray-600">Lokasi sumber air bersih di sepanjang jalur</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimoni Pendaki -->
    <section id="testimoni" class="section-padding bg-gradient-gray-white">
        <div class="container">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-primary mb-4 animated-border">Apa Kata Mereka?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed mb-12">
                    Pendapat para pendaki yang telah menaklukkan puncak Gunung Butak
                </p>
            </div>
            
            <!-- Sliding Testimonials Section -->
            <div class="testimonial-wrapper">
                <div class="testimonial-slider-container">
                    <div id="testimoni-container" class="testimonial-slider">
                        <!-- Testimonials will be loaded here by JS -->
                        <div class="testimonial-card-item">
                            <div class="testimonial-card-inner card-hover">
                                <div class="testimonial-rating">
                                    <div class="stars">★★★★★</div>
                                    <span class="score">5.0</span>
                                </div>
                                <p class="testimonial-quote">"Pendakian yang luar biasa! Jalur Kucur menantang tapi sepadan dengan pemandangan sabana di puncak. Basecamp sangat terorganisir dan bersih."</p>
                                <div class="testimonial-author">
                                    <img src="assets/images/Ahmad_Dhani_Avatar.jpg" onerror="this.src='https://placehold.co/50x50/3B82F6/ffffff?text=AD'" class="testimonial-avatar" alt="Avatar Ahmad Dhani">
                                    <div>
                                        <p class="testimonial-name">Ahmad Dhani</p>
                                        <p class="testimonial-role">Pendaki Berpengalaman</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-card-item">
                            <div class="testimonial-card-inner card-hover">
                                <div class="testimonial-rating">
                                    <div class="stars">★★★★☆</div>
                                    <span class="score">4.0</span>
                                </div>
                                <p class="testimonial-quote">"Keamanannya top. Pemandu sangat informatif. Hanya saja, treknya agak licin setelah hujan. Overall, pengalaman yang tak terlupakan!"</p>
                                <div class="testimonial-author">
                                    <img src="assets/images/Siti_Dewi_Avatar.jpg" onerror="this.src='https://placehold.co/50x50/10B981/ffffff?text=SD'" class="testimonial-avatar" alt="Avatar Siti Dewi">
                                    <div>
                                        <p class="testimonial-name">Siti Dewi</p>
                                        <p class="testimonial-role">Pendaki Pemula</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="testimonial-card-item">
                            <div class="testimonial-card-inner card-hover">
                                <div class="testimonial-rating">
                                    <div class="stars">★★★★★</div>
                                    <span class="score">5.0</span>
                                </div>
                                <p class="testimonial-quote">"Sabana Butak di pagi hari adalah yang terbaik! Sunrise dan lautan awan yang sempurna. Sistem registrasi onlinenya juga sangat mudah digunakan."</p>
                                <div class="testimonial-author">
                                    <img src="assets/images/Rizky_Dirgantara_Avatar.jpg" onerror="this.src='https://placehold.co/50x50/F59E0B/ffffff?text=RD'" class="testimonial-avatar" alt="Avatar Rizky Dirgantara">
                                    <div>
                                        <p class="testimonial-name">Rizky Dirgantara</p>
                                        <p class="testimonial-role">Petualang</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Arrows -->
                <button id="prev-testimonial" class="slider-nav-btn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="next-testimonial" class="slider-nav-btn">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Pagination Dots -->
                <div id="testimonial-dots" class="pagination-dots"></div>
            </div>
            
            <!-- Stats Section -->
            <div class="stats-footer card-hover">
                <div class="stats-footer-grid">
                    <div class="text-center card-hover">
                        <div class="stats-footer-item-value" id="total-pendaki-rating"><span class="counter" data-target="98">0</span>%</div>
                        <p class="stats-footer-item-label">Kepuasan Pendaki</p>
                    </div>
                    <div class="text-center card-hover">
                        <div class="stats-footer-item-value" id="avg-rating"><span class="counter-float" data-target="4.9">0</span>/5</div>
                        <p class="stats-footer-item-label">Rating Rata-rata</p>
                    </div>
                    <div class="text-center card-hover">
                        <div class="stats-footer-item-value" id="total-komentar"><span class="counter" data-target="5000">0</span>+</div>
                        <p class="stats-footer-item-label">Total Ulasan</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Poster Slider Section -->
    <section id="poster-slider" class="section-padding bg-gradient-gray-white">
        <div class="container">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-primary mb-4 animated-border">Poster Promosi</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed mb-12">
                    Temukan informasi terbaru dan promosi menarik dari Gunung Butak
                </p>
            </div>
            
            <!-- Sliding Posters Section -->
            <div class="poster-slider-wrapper">
                <div class="poster-slider-container">
                    <div id="poster-container" class="poster-slider">
                        <!-- Poster items will be dynamically loaded -->
                        <div class="poster-card-item">
                            <div class="poster-card-inner card-hover">
                                <img src="assets/images/Poster_Promo.jpg" onerror="this.src='https://placehold.co/600x800/22c55e/ffffff?text=PROMO+HUT+RI'" alt="Poster Promo" class="poster-img">
                                <h4 class="poster-title">Diskon 17% Tiket Pendakian</h4>
                                <p class="poster-desc">Berlaku selama bulan Agustus! Pesan sekarang.</p>
                            </div>
                        </div>
                        <div class="poster-card-item">
                            <div class="poster-card-inner card-hover">
                                <img src="assets/images/Poster_Event.jpg" onerror="this.src='https://placehold.co/600x800/3B82F6/ffffff?text=EVENT+CLEAN+UP'" alt="Poster Event" class="poster-img">
                                <h4 class="poster-title" style="color: var(--accent);">Aksi Bersih Gunung Butak</h4>
                                <p class="poster-desc">Gabung dengan kami di akhir pekan ini!</p>
                            </div>
                        </div>
                        <div class="poster-card-item">
                            <div class="poster-card-inner card-hover">
                                <img src="assets/images/Poster_Info_Cuaca.jpg" onerror="this.src='https://placehold.co/600x800/F59E0B/ffffff?text=INFO+CUACA'" alt="Poster Info" class="poster-img">
                                <h4 class="poster-title" style="color: var(--orange);">Peringatan Cuaca Ekstrem</h4>
                                <p class="poster-desc">Pastikan peralatan anda lengkap sebelum mendaki.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Arrows -->
                <button id="prev-poster" class="slider-nav-btn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="next-poster" class="slider-nav-btn">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <!-- Pagination Dots -->
                <div id="poster-dots" class="pagination-dots"></div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Logo dan Deskripsi -->
                <div>
                    <div class="footer-logo-group">
                        <i class="fas fa-mountain"></i>
                        <h3 class="text-2xl font-bold">Gunung Butak</h3>
                    </div>
                    <p class="footer-description">
                        Platform reservasi pendakian Gunung Butak melalui jalur Kucur. 
                        Membantu pendaki untuk merencanakan dan menikmati petualangan alam mereka dengan aman dan menyenangkan.
                    </p>
                    <div class="social-links">
                        <a href="#" class="social-badge">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="social-badge">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="social-badge">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-badge">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Navigasi -->
                <div>
                    <h4 class="footer-title">Navigasi</h4>
                    <ul class="footer-nav-list">
                        <li><a href="#hero" class="nav-link">Beranda</a></li>
                        <li><a href="#tentang" class="nav-link">Tentang Kami</a></li>
                        <li><a href="#lokasi" class="nav-link">Jalur Pendakian</a></li>
                        <li><a href="#poster-slider" class="nav-link">Galeri</a></li>
                        <li><a href="simaksi/auth/login.php" class="nav-link">Login/Daftar</a></li>
                    </ul>
                </div>
                
                <!-- Jalur Pendakian -->
                <div>
                    <h4 class="footer-title">Data Gunung</h4>
                    <ul class="footer-data-list">
                        <li>
                            <i class="fas fa-mountain"></i>
                            <span>Jalur Utama: Kucur</span>
                        </li>
                        <li>
                            <i class="fas fa-bolt"></i>
                            <span>Tingkat Kesulitan: Sulit</span>
                        </li>
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Durasi: 3-4 Hari</span>
                        </li>
                        <li>
                            <i class="fas fa-ruler-vertical"></i>
                            <span>Ketinggian: 2.868 mdpl</span>
                        </li>
                        <li>
                            <i class="fas fa-thermometer-half"></i>
                            <span>Suhu: 15-22°C</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Kontak Kami -->
                <div>
                    <h4 class="footer-title">Kontak Kami</h4>
                    <ul class="footer-contact-list">
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span class="break-all">info@gunungbutak.com</span>
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span>+62 812 3456 7890</span>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Basecamp Kucur, Gunung Butak, Jawa Timur</span>
                        </li>
                        <li>
                            <i class="fas fa-comments"></i>
                            <span>Live Chat 24/7</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; 2025 Gunung Butak Reservasi Pendakian. All rights reserved.</p>
        </div>
    </footer>

    <!-- Modal Verifikasi Token (Tetap dipertahankan untuk potensi penggunaan JS) -->
    <div id="verifikasi-modal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <i class="fas fa-key"></i>
                <h3 class="modal-title">Verifikasi Email</h3>
                <p class="modal-subtitle">Masukkan kode verifikasi yang dikirimkan ke email Anda</p>
                
                <div id="verifikasi-error-message" class="modal-message modal-message-error" style="display: none;"></div>
                <div id="verifikasi-success-message" class="modal-message modal-message-success" style="display: none;"></div>
                
                <div class="mb-6">
                    <input type="text" id="verification-token" class="modal-input" placeholder="XXXXXX" maxlength="6">
                </div>
                
                <div class="modal-actions">
                    <button type="button" id="cancel-verifikasi" class="modal-btn cancel-btn">
                        Batal
                    </button>
                    <button type="button" id="submit-verifikasi" class="modal-btn submit-modal-btn">
                        Verifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript (Fungsionalitas disatukan dan disederhanakan) -->
    <script>
        // --- Globals & Setup ---
        const appId = typeof __app_id !== 'undefined' ? __app_id : 'default-app-id';
        let isUserLoggedIn = false; // Mock state
        let currentUserName = "Pendaki Hebat";

        // --- Utility Functions (Simplified for External Auth) ---

        function updateAuthState(loggedIn) {
            isUserLoggedIn = loggedIn;
            const authLink = document.getElementById('auth-link');
            const userGreeting = document.getElementById('user-greeting');
            const userFullnameEl = document.getElementById('user-fullname');

            if (isUserLoggedIn) {
                if (authLink) authLink.style.display = 'none';
                if (userGreeting) userGreeting.classList.add('show');
                if (userFullnameEl) userFullnameEl.textContent = currentUserName;
            } else {
                if (authLink) authLink.style.display = 'inline-block';
                if (userGreeting) userGreeting.classList.remove('show');
            }
        }

        document.getElementById('logout-link-header')?.addEventListener('click', (e) => {
            e.preventDefault();
            // Dalam aplikasi nyata, ini akan memanggil signOut Firebase dan me-refresh halaman.
            updateAuthState(false);
            console.log("Logout Mocked. User must manually navigate to login page.");
        });
        
        // Modal functions are retained but simplified since Auth logic is gone
        function showModal(show) {
            const modal = document.getElementById('verifikasi-modal');
            if (show) {
                modal.classList.add('show');
            } else {
                modal.classList.remove('show');
            }
        }
        document.getElementById('cancel-verifikasi')?.addEventListener('click', () => showModal(false));

        // --- Supabase Data Loading Functions ---

        /**
         * Mengambil data pengumuman yang aktif dan menampilkannya di halaman.
         */
        async function loadPengumuman() {
            const contentDiv = document.getElementById('pengumuman-content');
            const loadingDiv = document.getElementById('pengumuman-loading');
            const emptyDiv = document.getElementById('pengumuman-empty');
            
            // Tampilkan loading, sembunyikan semua yang lain
            if (contentDiv) contentDiv.innerHTML = loadingDiv.outerHTML;
            if (emptyDiv) emptyDiv.style.display = 'none';

            try {
                // Pastikan klien Supabase sudah terinisialisasi
                if (typeof supabase === 'undefined') {
                    console.error("Supabase client is not initialized. Check config/supabase.php.");
                    if (contentDiv) contentDiv.innerHTML = `<div class="announcement-item" style="border-left: 4px solid var(--red); background-color: #FEE2E2;"><h4 class="announcement-title">Error Koneksi</h4><p class="announcement-text">Gagal memuat pengumuman. Pastikan konfigurasi Supabase di simaksi/config/supabase.php sudah benar.</p></div>`;
                    return;
                }

                const today = new Date().toISOString();

                // Query Supabase: Ambil data dari tabel 'pengumuman'
                // Filter: 'aktif' = TRUE DAN (tanggal_mulai <= hari_ini) DAN (tanggal_akhir >= hari_ini)
                // Urutkan berdasarkan tanggal terbaru
                const { data, error } = await supabase
                    .from('pengumuman')
                    .select('judul, konten, start_date, end_date')
                    .eq('aktif', true)
                    .lte('start_date', today)
                    .gte('end_date', today)
                    .order('start_date', { ascending: false });

                // Sembunyikan loading
                if (loadingDiv) loadingDiv.style.display = 'none';

                if (error) {
                    console.error("Supabase Error:", error.message);
                    if (contentDiv) contentDiv.innerHTML = `<div class="announcement-item" style="border-left: 4px solid var(--red); background-color: #FEE2E2;"><h4 class="announcement-title">Error Data</h4><p class="announcement-text">Gagal memuat pengumuman: ${error.message}</p></div>`;
                    return;
                }

                if (data && data.length > 0) {
                    const pengumumanHtml = data.map(item => {
                        const startDate = new Date(item.start_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                        const endDate = new Date(item.end_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                        
                        return `
                            <div class="announcement-item">
                                <h4 class="announcement-title" style="font-weight: 600; color: var(--primary); margin-bottom: 0.5rem;">${item.judul}</h4>
                                <p class="announcement-text" style="color: #4B5563;">${item.konten}</p>
                                <span class="announcement-date" style="font-size: 0.875rem; color: #6B7280; display: block; margin-top: 1rem;">
                                    Periode: ${startDate} hingga ${endDate}
                                </span>
                            </div>
                        `;
                    }).join('');

                    if (contentDiv) contentDiv.innerHTML = pengumumanHtml;

                } else {
                    // Tampilkan pesan kosong jika tidak ada data
                    if (contentDiv) contentDiv.innerHTML = emptyDiv.outerHTML;
                    if (emptyDiv) emptyDiv.style.display = 'block';
                }

            } catch (e) {
                console.error("General Error loading announcements:", e);
                if (contentDiv) contentDiv.innerHTML = `<div class="announcement-item" style="border-left: 4px solid var(--red); background-color: #FEE2E2;"><h4 class="announcement-title">Kesalahan Umum</h4><p class="announcement-text">Terjadi kesalahan tak terduga saat memuat data: ${e.message}</p></div>`;
            }
        }
        
        // --- Counter Animation Logic ---
        function animateCounter(element, target, duration) {
            let start = 0;
            const isFloat = target.includes('.');
            const finalValue = parseFloat(target);
            const increment = finalValue / (duration / 16);
            
            const timer = setInterval(() => {
                start += increment;
                if (start >= finalValue) {
                    element.textContent = finalValue.toFixed(isFloat ? 1 : 0);
                    clearInterval(timer);
                } else {
                    element.textContent = start.toFixed(isFloat ? 1 : 0);
                }
            }, 16);
        }

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.querySelectorAll('.counter, .counter-float').forEach(counter => {
                        if (counter.classList.contains('animated')) return;
                        
                        const target = counter.getAttribute('data-target');
                        if (target) {
                            animateCounter(counter, target, 2000);
                            counter.classList.add('animated');
                        }
                    });
                    
                    entry.target.querySelectorAll('.progress-bar').forEach(bar => {
                        const fill = bar.querySelector('.progress-fill');
                        const value = bar.getAttribute('data-value');
                        const max = bar.getAttribute('data-max');
                        const percent = (parseInt(value) / parseInt(max)) * 100;
                        if (fill) fill.style.width = `${percent}%`;
                    });

                    counterObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        document.querySelectorAll('.stat-item, .stat-info-card, .stats-footer').forEach(card => {
            counterObserver.observe(card);
        });

        // --- Slider Logic (Testimonial and Poster) ---

        function initSlider(containerId, prevBtnId, nextBtnId, dotContainerId, cardClass) {
            const container = document.getElementById(containerId);
            const prevBtn = document.getElementById(prevBtnId);
            const nextBtn = document.getElementById(nextBtnId);
            const dotContainer = document.getElementById(dotContainerId);

            if (!container) return;

            let currentIndex = 0;
            let cards;
            let visibleCards = 1;

            function updateSlider() {
                cards = container.querySelectorAll(`.${cardClass}`);
                if (cards.length === 0) return;
                
                if (window.innerWidth >= 768) {
                    visibleCards = 3;
                } else {
                    visibleCards = 1;
                }
                
                const maxIndex = Math.ceil(cards.length / visibleCards) - 1;
                if (currentIndex > maxIndex) currentIndex = maxIndex;
                if (currentIndex < 0) currentIndex = 0;
                
                // Calculate translation percentage based on total cards and visible cards
                const cardWidthPercentage = 100 / cards.length; 
                const translateValue = -(currentIndex * visibleCards * cardWidthPercentage);
                
                container.style.transform = `translateX(${translateValue}%)`;
                
                updateDots(cards.length);
                updateButtons(cards.length, maxIndex);
            }
            
            function updateDots(totalCards) {
                if (!dotContainer) return;
                dotContainer.innerHTML = '';
                const numDots = Math.ceil(totalCards / visibleCards);
                for (let i = 0; i < numDots; i++) {
                    const dot = document.createElement('span');
                    dot.classList.add('dot');
                    if (i === currentIndex) dot.classList.add('active');
                    
                    dot.addEventListener('click', () => {
                        currentIndex = i;
                        updateSlider();
                    });
                    dotContainer.appendChild(dot);
                }
            }

            function updateButtons(totalCards, maxIndex) {
                if (prevBtn) prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
                if (nextBtn) nextBtn.style.opacity = currentIndex >= maxIndex ? '0.5' : '1';
                if (totalCards <= visibleCards) {
                    if (prevBtn) prevBtn.style.display = 'none';
                    if (nextBtn) nextBtn.style.display = 'none';
                    if (dotContainer) dotContainer.style.display = 'none';
                } else {
                    if (prevBtn) prevBtn.style.display = 'block';
                    if (nextBtn) nextBtn.style.display = 'block';
                    if (dotContainer) dotContainer.style.display = 'flex';
                }
            }

            prevBtn?.addEventListener('click', () => {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            });

            nextBtn?.addEventListener('click', () => {
                const maxIndex = Math.ceil(cards.length / visibleCards) - 1;
                if (currentIndex < maxIndex) {
                    currentIndex++;
                    updateSlider();
                }
            });

            window.addEventListener('resize', updateSlider);
            
            setTimeout(updateSlider, 100);
        }

        // --- Dynamic Content & Initial Load ---

        function loadWeatherForecast() {
            const forecastContainer = document.getElementById('weather-forecast');
            if (!forecastContainer) return;

            const weatherData = [
                { day: "Hari Ini", icon: "fas fa-sun", temp: "18°C - 24°C", condition: "Cerah Penuh" },
                { day: "Besok", icon: "fas fa-cloud-sun", temp: "15°C - 22°C", condition: "Cerah Berawan" },
                { day: "Lusa", icon: "fas fa-cloud-rain", temp: "14°C - 20°C", condition: "Hujan Ringan" },
            ];

            const html = `
                <div class="forecast-grid">
                    ${weatherData.map(day => `
                        <div class="forecast-card card-hover text-center">
                            <p class="forecast-card-day">${day.day}</p>
                            <i class="${day.icon} forecast-card-icon"></i>
                            <h4 class="forecast-card-temp">${day.temp}</h4>
                            <p class="forecast-card-condition">${day.condition}</p>
                        </div>
                    `).join('')}
                </div>
            `;
            
            setTimeout(() => {
                forecastContainer.innerHTML = html;
            }, 1500);
        }
        
        function animateOnScroll() {
            const elements = document.querySelectorAll('.card-hover, .section-padding .container > div');
            const observerOptions = { root: null, rootMargin: '0px', threshold: 0.1 };
            
            const observer = new IntersectionObserver((entries, obs) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                        obs.unobserve(entry.target); 
                    }
                });
            }, observerOptions);

            elements.forEach(element => {
                if (!element.closest('.hero-section')) { 
                    element.style.opacity = '0';
                    element.style.transform = 'translateY(40px)';
                    observer.observe(element);
                }
            });
        }
        
        // Parallax Effect
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.hero-section');
            if (parallax) {
                const speed = scrolled * 0.3;
                parallax.style.backgroundPosition = `center calc(50% - ${speed}px)`;
            }
        });

        // Scroll to Anchor Logic
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const hash = this.getAttribute('href');
                if (hash !== '#') { // Ignore links to #
                    e.preventDefault();
                    const target = document.querySelector(hash);
                    if (target) {
                        window.scrollTo({
                            top: target.offsetTop - 80, 
                            behavior: 'smooth'
                        });
                    }
                }
            });
        });
        
        // Navigation Highlighting
        function highlightNavOnScroll() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.nav-link');
            
            let current = '';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= (sectionTop - 150)) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                const linkHref = link.getAttribute('href').replace('#', '');
                link.classList.remove('text-accent');
                if (linkHref === current) {
                    link.classList.add('text-accent');
                }
            });
        }
        
        document.addEventListener('scroll', highlightNavOnScroll);


        document.addEventListener('DOMContentLoaded', () => {
            updateAuthState(false);
            loadWeatherForecast();
            
            // Panggil fungsi untuk memuat pengumuman
            loadPengumuman();

            // Init Sliders
            initSlider('testimoni-container', 'prev-testimonial', 'next-testimonial', 'testimonial-dots', 'testimonial-card-item');
            initSlider('poster-container', 'prev-poster', 'next-poster', 'poster-dots', 'poster-card-item');

            // Hero Animation
            document.querySelectorAll('.hero-content').forEach(el => el.classList.add('show'));

            // Initial scroll check for animations
            setTimeout(animateOnScroll, 100);
            
            // Scroll to top button functionality
            const scrollToTopBtn = document.createElement('div');
            scrollToTopBtn.className = 'scroll-to-top';
            scrollToTopBtn.innerHTML = '<i class="fas fa-arrow-up"></i>';
            document.body.appendChild(scrollToTopBtn);

            scrollToTopBtn.addEventListener('click', function() {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            
            window.addEventListener('scroll', () => {
                scrollToTopBtn.style.display = window.pageYOffset > 300 ? 'flex' : 'none';
            });
            scrollToTopBtn.style.display = 'none'; 
        });
        
    </script>
</body>
</html>