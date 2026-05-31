<?php
require_once 'includes/functions.php';
require_once 'includes/icons.php';
$halaman_aktif = 'pilihan';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilihan Beasiswa - <?= NAMA_KAMPUS ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="img/favicon.svg">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ========== HEADER ========== -->
<header class="site-header">
    <div class="header-inner">
        <div class="brand">
            <div class="brand-icon"><?= icon('graduation', 'brand-svg', 28) ?></div>
            <div>
                <div class="brand-name"><?= NAMA_KAMPUS ?></div>
                <div class="brand-sub"><?= APP_NAME ?></div>
            </div>
        </div>
        <nav class="main-nav">
            <a href="index.php" class="nav-link <?= $halaman_aktif === 'pilihan' ? 'active' : '' ?>">
                <?= icon('list', 'nav-svg', 17) ?> Pilihan Beasiswa
            </a>
            <a href="daftar.php" class="nav-link <?= $halaman_aktif === 'daftar' ? 'active' : '' ?>">
                <?= icon('pencil', 'nav-svg', 17) ?> Daftar Beasiswa
            </a>
            <a href="hasil.php" class="nav-link <?= $halaman_aktif === 'hasil' ? 'active' : '' ?>">
                <?= icon('chart', 'nav-svg', 17) ?> Hasil Pendaftaran
            </a>
        </nav>
    </div>
</header>

<!-- ========== HERO SECTION ========== -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-badge">Tahun Akademik 2024/2025</div>
        <h1 class="hero-title">Raih Masa Depan<br><span class="accent">Bersama Kami</span></h1>
        <p class="hero-desc">Program beasiswa terbaik untuk mendukung perjalanan akademik Anda. IPK minimal <strong><?= formatIPK(IPK_MINIMAL) ?></strong> untuk dapat mendaftar.</p>
        <a href="daftar.php" class="btn-hero">
            Daftar Sekarang <?= icon('arrow-right', '', 18) ?>
        </a>
    </div>
    <div class="hero-visual">
        <div class="ipk-badge">
            <div class="ipk-label">IPK Anda</div>
            <div class="ipk-value"><?= formatIPK(IPK_MAHASISWA) ?></div>
            <?php if (IPK_MAHASISWA >= IPK_MINIMAL): ?>
                <div class="ipk-status eligible">
                    <?= icon('check-circle', '', 15) ?> Memenuhi Syarat
                </div>
            <?php else: ?>
                <div class="ipk-status not-eligible">
                    <?= icon('x-circle', '', 15) ?> Belum Memenuhi Syarat
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ========== DAFTAR BEASISWA ========== -->
<main class="main-content">
    <div class="section-header">
        <h2 class="section-title">Jenis Beasiswa Tersedia</h2>
        <p class="section-desc">Pilih beasiswa yang sesuai dengan profil dan prestasi Anda</p>
    </div>

    <div class="beasiswa-grid">
        <?php
        $card_icons  = ['trophy', 'star', 'heart'];
        $card_colors = ['card-gold', 'card-blue', 'card-green'];
        foreach ($DAFTAR_BEASISWA as $index => $beasiswa):
            $terisi      = hitungPendaftarBeasiswa($beasiswa['id']);
            $sisa_kuota  = max(0, $beasiswa['kuota'] - $terisi);
            $kuota_habis = $sisa_kuota === 0;
            $persen      = $beasiswa['kuota'] > 0 ? round(($terisi / $beasiswa['kuota']) * 100) : 100;
        ?>
        <div class="beasiswa-card <?= $card_colors[$index % 3] ?> <?= $kuota_habis ? 'card-penuh' : '' ?>">
            <div class="card-header">
                <span class="card-icon"><?= icon($card_icons[$index % 3], 'card-svg', 28) ?></span>
                <div class="card-kuota <?= $kuota_habis ? 'card-kuota-penuh' : '' ?>">
                    <?php if ($kuota_habis): ?>
                        <?= icon('x-circle', '', 14) ?> Kuota Penuh
                    <?php else: ?>
                        <?= icon('users', '', 14) ?> Sisa: <?= $sisa_kuota ?>/<?= $beasiswa['kuota'] ?>
                    <?php endif; ?>
                </div>
            </div>

            <h3 class="card-title"><?= bersihkan($beasiswa['nama']) ?></h3>

            <!-- Progress bar kuota -->
            <div class="kuota-bar-wrap">
                <div class="kuota-bar">
                    <div class="kuota-bar-fill <?= $kuota_habis ? 'kuota-bar-penuh' : '' ?>"
                         style="width: <?= $persen ?>%"></div>
                </div>
                <span class="kuota-bar-label"><?= $terisi ?> dari <?= $beasiswa['kuota'] ?> pendaftar</span>
            </div>

            <div class="card-section">
                <div class="card-label">
                    <?= icon('map-pin', '', 13) ?> Syarat
                </div>
                <p class="card-text"><?= bersihkan($beasiswa['syarat']) ?></p>
            </div>
            <div class="card-section">
                <div class="card-label">
                    <?= icon('gift', '', 13) ?> Manfaat
                </div>
                <p class="card-text highlight"><?= bersihkan($beasiswa['manfaat']) ?></p>
            </div>

            <?php if ($kuota_habis): ?>
                <div class="btn-daftar btn-daftar-penuh">
                    <?= icon('x-circle', '', 16) ?> Kuota Penuh
                </div>
            <?php else: ?>
                <a href="daftar.php?beasiswa=<?= $beasiswa['id'] ?>" class="btn-daftar">
                    Daftar Beasiswa Ini <?= icon('arrow-right', '', 16) ?>
                </a>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Info IPK -->
    <div class="info-box">
        <div class="info-icon"><?= icon('info', '', 22) ?></div>
        <div class="info-text">
            <strong>Ketentuan IPK:</strong> Semua jenis beasiswa mensyaratkan IPK minimal
            <strong><?= formatIPK(IPK_MINIMAL) ?></strong>. IPK Anda saat ini adalah
            <strong><?= formatIPK(IPK_MAHASISWA) ?></strong> —
            <?php if (IPK_MAHASISWA >= IPK_MINIMAL): ?>
                <span class="text-success">Luar biasa! Selamat, kualifikasi Anda sangat pas dan Anda resmi memenuhi syarat untuk mendaftar sekarang!</span>
            <?php else: ?>
                <span class="text-danger">Tetap semangat belajar! Tingkatkan IPK Anda sedikit lagi, kami tunggu pendaftaran Anda selanjutnya.</span>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- ========== FOOTER ========== -->
<footer class="site-footer">
    <p>Copyright &copy; <?= date('Y') ?> <?= NAMA_KAMPUS ?>. All rights reserved.</p>
</footer>

</body>
</html>