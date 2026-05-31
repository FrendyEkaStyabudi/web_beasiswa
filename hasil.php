<?php
require_once 'includes/functions.php';
require_once 'includes/icons.php';

$halaman_aktif  = 'hasil';
$semuaPendaftar = bacaDataPendaftar();
$baru_daftar    = isset($_GET['sukses']) && $_GET['sukses'] == '1';
$nama_baru      = bersihkan($_GET['nama'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pendaftaran - <?= NAMA_KAMPUS ?></title>
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

<!-- ========== MAIN CONTENT ========== -->
<main class="main-content">

    <!-- Notifikasi Sukses -->
    <?php if ($baru_daftar && $nama_baru): ?>
    <div class="alert alert-success">
        <span class="alert-icon"><?= icon('check-circle', 'icon-success', 20) ?></span>
        <div>
            <strong>Pendaftaran berhasil!</strong> Selamat <?= $nama_baru ?>, data Anda telah tersimpan.
            Tim kami akan memverifikasi dokumen Anda segera.
        </div>
    </div>
    <?php endif; ?>

    <div class="page-title-wrap">
        <h2 class="page-title">Hasil Pendaftaran Beasiswa</h2>
        <p class="page-desc">
            Total pendaftar: <strong><?= count($semuaPendaftar) ?> mahasiswa</strong>
        </p>
    </div>

    <?php if (empty($semuaPendaftar)): ?>
        <div class="empty-state">
            <div class="empty-icon"><?= icon('inbox', 'empty-svg', 64) ?></div>
            <h3>Belum Ada Pendaftar</h3>
            <p>Belum ada mahasiswa yang mendaftar beasiswa.</p>
            <a href="daftar.php" class="btn-hero">
                Daftar Sekarang <?= icon('arrow-right', '', 17) ?>
            </a>
        </div>

    <?php else: ?>
        <div class="table-wrapper">
            <table class="hasil-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pendaftaran</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>No. HP</th>
                        <th>Semester</th>
                        <th>IPK</th>
                        <th>Pilihan Beasiswa</th>
                        <th>Berkas</th>
                        <th>Tgl. Daftar</th>
                        <th>Status Ajuan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($semuaPendaftar as $no => $p): ?>
                    <tr class="<?= ($no % 2 === 0) ? 'row-even' : 'row-odd' ?>">
                        <td class="td-center"><?= $no + 1 ?></td>
                        <td class="td-id"><?= bersihkan($p['id']) ?></td>
                        <td class="td-bold"><?= bersihkan($p['nama']) ?></td>
                        <td><?= bersihkan($p['email']) ?></td>
                        <td><?= bersihkan($p['hp']) ?></td>
                        <td class="td-center">Sem. <?= (int)$p['semester'] ?></td>
                        <td class="td-center td-bold"><?= formatIPK((float)$p['ipk']) ?></td>
                        <td><?= bersihkan($p['nama_beasiswa']) ?></td>
                        <td class="td-center">
                            <?php if (!empty($p['berkas'])): ?>
                                <span class="badge badge-file">
                                    <?= icon('paperclip', '', 13) ?> Ada
                                </span>
                            <?php else: ?>
                                <span class="badge badge-nofile">&mdash;</span>
                            <?php endif; ?>
                        </td>
                        <td class="td-date"><?= bersihkan($p['tanggal_daftar']) ?></td>
                        <td class="td-center">
                            <span class="badge badge-status">
                                <?= icon('clock', '', 12) ?> <?= bersihkan($p['status_ajuan']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="info-box" style="margin-top:2rem;">
            <div class="info-icon"><?= icon('info', '', 22) ?></div>
            <div class="info-text">
                <strong>Keterangan Status:</strong>
                <span class="badge badge-status"><?= icon('clock', '', 12) ?> Belum Diverifikasi</span>
                = Pendaftaran diterima, menunggu review tim beasiswa. Proses verifikasi memakan waktu 7-14 hari kerja.
            </div>
        </div>
    <?php endif; ?>

    <div style="text-align:center; margin-top: 2rem;">
        <a href="daftar.php" class="btn-back">
            <?= icon('plus', '', 16) ?> Daftarkan Mahasiswa Lain
        </a>
    </div>
</main>

<!-- ========== FOOTER ========== -->
<footer class="site-footer">
    <p>Copyright &copy; <?= date('Y') ?> <?= NAMA_KAMPUS ?>. All rights reserved.</p>
</footer>

</body>
</html>