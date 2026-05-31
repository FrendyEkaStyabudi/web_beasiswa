<?php
require_once 'includes/functions.php';
require_once 'includes/icons.php';

$halaman_aktif = 'daftar';
$pesan_error   = [];
$pesan_sukses  = '';

// ============================================================
// PROSES FORM KETIKA SUBMIT (POST)
// ============================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama     = bersihkan($_POST['nama'] ?? '');
    $email    = bersihkan($_POST['email'] ?? '');
    $hp       = bersihkan($_POST['hp'] ?? '');
    $semester = (int)($_POST['semester'] ?? 0);
    $beasiswa = bersihkan($_POST['beasiswa'] ?? '');
    $ipk      = IPK_MAHASISWA;

    if (empty($nama))                          $pesan_error[] = 'Nama tidak boleh kosong.';
    if (empty($email))                         $pesan_error[] = 'Email tidak boleh kosong.';
    elseif (!validasiEmail($email))            $pesan_error[] = 'Format email tidak valid. Contoh: nama@email.com';
    if (empty($hp))                            $pesan_error[] = 'Nomor HP tidak boleh kosong.';
    elseif (!validasiHP($hp))                  $pesan_error[] = 'Nomor HP hanya boleh berisi angka (10-13 digit).';
    if (!validasiSemester($semester))          $pesan_error[] = 'Semester harus dipilih antara 1 sampai ' . MAX_SEMESTER . '.';

    if ($ipk >= IPK_MINIMAL) {
        if (empty($beasiswa)) $pesan_error[] = 'Pilihan beasiswa harus dipilih.';
    } else {
        $pesan_error[] = 'IPK Anda (' . formatIPK($ipk) . ') di bawah minimal (' . formatIPK(IPK_MINIMAL) . '). Tidak dapat mendaftar.';
    }

    $namaFile = '';
    if ($ipk >= IPK_MINIMAL && empty($pesan_error)) {
        $hasilUpload = uploadBerkas($_FILES['berkas'] ?? ['error' => UPLOAD_ERR_NO_FILE]);
        if (!$hasilUpload['sukses']) {
            $pesan_error[] = $hasilUpload['pesan'];
        } else {
            $namaFile = $hasilUpload['nama_file'];
        }
    }

    if (empty($pesan_error)) {
        $dataBaru = [
            'nama'             => $nama,
            'email'            => $email,
            'hp'               => $hp,
            'semester'         => $semester,
            'ipk'              => $ipk,
            'pilihan_beasiswa' => $beasiswa,
            'nama_beasiswa'    => getNamaBeasiswa($beasiswa),
            'berkas'           => $namaFile,
        ];
        if (simpanDataPendaftar($dataBaru)) {
            header('Location: hasil.php?sukses=1&nama=' . urlencode($nama));
            exit();
        } else {
            $pesan_error[] = 'Terjadi kesalahan sistem. Silakan coba lagi.';
        }
    }
}

$beasiswaTerpilih = bersihkan($_GET['beasiswa'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Beasiswa - <?= NAMA_KAMPUS ?></title>
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

<!-- ========== FORM SECTION ========== -->
<main class="main-content">
    <div class="page-title-wrap">
        <h2 class="page-title">Form Pendaftaran Beasiswa</h2>
        <p class="page-desc">Isi data diri Anda dengan lengkap dan benar</p>
    </div>

    <!-- Pesan Error -->
    <?php if (!empty($pesan_error)): ?>
    <div class="alert alert-error">
        <span class="alert-icon"><?= icon('warning', '', 20) ?></span>
        <div>
            <strong>Terdapat kesalahan:</strong>
            <ul>
                <?php foreach ($pesan_error as $err): ?>
                    <li><?= $err ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php endif; ?>

    <!-- Indikator IPK -->
    <div class="ipk-indicator <?= IPK_MAHASISWA >= IPK_MINIMAL ? 'ipk-ok' : 'ipk-fail' ?>">
        <div class="ipk-indicator-left">
            <span class="ipk-indicator-icon">
                <?php if (IPK_MAHASISWA >= IPK_MINIMAL): ?>
                    <?= icon('check-circle', 'icon-success', 26) ?>
                <?php else: ?>
                    <?= icon('x-circle', 'icon-danger', 26) ?>
                <?php endif; ?>
            </span>
            <div>
                <strong>IPK Anda: <?= formatIPK(IPK_MAHASISWA) ?></strong>
                <div class="ipk-indicator-msg">
                    <?php if (IPK_MAHASISWA >= IPK_MINIMAL): ?>
                        IPK memenuhi syarat. Silakan lengkapi form di bawah ini.
                    <?php else: ?>
                        IPK di bawah <?= formatIPK(IPK_MINIMAL) ?>. Beberapa field dinonaktifkan.
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- FORM REGISTRASI -->
    <div class="form-container">
        <div class="form-card">
            <div class="form-card-header">
                <h3><?= icon('academic', '', 20) ?> Registrasi Beasiswa</h3>
            </div>

            <form method="POST" enctype="multipart/form-data" class="reg-form" id="formDaftar">

                <!-- Nama -->
                <div class="form-group">
                    <label for="nama">
                        <?= icon('users', 'label-icon', 15) ?> Masukkan Nama <span class="required">*</span>
                    </label>
                    <input type="text" id="nama" name="nama"
                           value="<?= bersihkan($_POST['nama'] ?? '') ?>"
                           placeholder="Nama lengkap sesuai KTP"
                           class="form-control" required>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">
                        <?= icon('mail', 'label-icon', 15) ?> Masukkan Email <span class="required">*</span>
                    </label>
                    <input type="email" id="email" name="email"
                           value="<?= bersihkan($_POST['email'] ?? '') ?>"
                           placeholder="contoh@email.com"
                           class="form-control" required>
                    <small class="form-hint">Format: nama@domain.com</small>
                </div>

                <!-- Nomor HP -->
                <div class="form-group">
                    <label for="hp">
                        <?= icon('phone', 'label-icon', 15) ?> Nomor HP <span class="required">*</span>
                    </label>
                    <input type="number" id="hp" name="hp"
                           value="<?= bersihkan($_POST['hp'] ?? '') ?>"
                           placeholder="Contoh: 08123456789"
                           class="form-control" required min="0">
                    <small class="form-hint">Hanya angka, 10-13 digit</small>
                </div>

                <!-- Semester -->
                <div class="form-group">
                    <label for="semester">
                        <?= icon('clock', 'label-icon', 15) ?> Semester Saat Ini <span class="required">*</span>
                    </label>
                    <select id="semester" name="semester" class="form-control" required>
                        <option value="">-- Pilih Semester --</option>
                        <?php for ($i = 1; $i <= MAX_SEMESTER; $i++):
                            $selected = (isset($_POST['semester']) && (int)$_POST['semester'] === $i) ? 'selected' : '';
                        ?>
                            <option value="<?= $i ?>" <?= $selected ?>>Semester <?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- IPK (otomatis dari sistem) -->
                <div class="form-group">
                    <label for="ipk">
                        <?= icon('badge-check', 'label-icon', 15) ?> IPK Terakhir
                    </label>
                    <input type="text" id="ipk" name="ipk_display"
                           value="<?= formatIPK(IPK_MAHASISWA) ?>"
                           class="form-control ipk-readonly" readonly>
                    <small class="form-hint">IPK diambil otomatis dari sistem akademik</small>
                </div>

                <!-- Pilihan Beasiswa -->
                <div class="form-group">
                    <label for="beasiswa">
                        <?= icon('graduation', 'label-icon', 15) ?> Pilihan Beasiswa <span class="required">*</span>
                    </label>
                    <select id="beasiswa" name="beasiswa" class="form-control"
                            <?= IPK_MAHASISWA < IPK_MINIMAL ? 'disabled' : '' ?>
                            <?= IPK_MAHASISWA >= IPK_MINIMAL ? 'required' : '' ?>>
                        <option value="">-- Pilih Beasiswa --</option>
                        <?php foreach ($DAFTAR_BEASISWA as $b):
                            $sel = ($beasiswaTerpilih === $b['id'] || (isset($_POST['beasiswa']) && $_POST['beasiswa'] === $b['id'])) ? 'selected' : '';
                        ?>
                            <option value="<?= $b['id'] ?>" <?= $sel ?>><?= bersihkan($b['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (IPK_MAHASISWA < IPK_MINIMAL): ?>
                        <small class="form-hint error-hint">
                            <?= icon('warning', '', 13) ?> Dinonaktifkan karena IPK di bawah minimal
                        </small>
                    <?php endif; ?>
                </div>

                <!-- Upload Berkas -->
                <div class="form-group">
                    <label for="berkas">
                        <?= icon('upload', 'label-icon', 15) ?> Upload Berkas Syarat <span class="required">*</span>
                    </label>
                    <div class="upload-area <?= IPK_MAHASISWA < IPK_MINIMAL ? 'upload-disabled' : '' ?>">
                        <input type="file" id="berkas" name="berkas"
                               class="file-input"
                               accept=".pdf,.jpg,.jpeg,.png,.zip"
                               <?= IPK_MAHASISWA < IPK_MINIMAL ? 'disabled' : '' ?>>
                        <label for="berkas" class="file-label <?= IPK_MAHASISWA < IPK_MINIMAL ? 'disabled' : '' ?>">
                            <?= icon('paperclip', 'file-svg', 18) ?>
                            <span class="file-text" id="fileText">Pilih File (PDF / JPG / ZIP)</span>
                        </label>
                    </div>
                    <small class="form-hint">Format: PDF, JPG, PNG, ZIP &mdash; Maks: 5MB</small>
                </div>

                <!-- Tombol Aksi -->
                <div class="form-actions">
                    <button type="submit" class="btn-submit"
                            <?= IPK_MAHASISWA < IPK_MINIMAL ? 'disabled' : '' ?>>
                        <?= icon('send', '', 18) ?> Daftar Beasiswa
                    </button>
                    <button type="reset" class="btn-batal" onclick="return resetForm()">
                        <?= icon('x-mark', '', 17) ?> Batal
                    </button>
                </div>

            </form>
        </div>
    </div>
</main>

<!-- ========== FOOTER ========== -->
<footer class="site-footer">
    <p>Copyright &copy; <?= date('Y') ?> <?= NAMA_KAMPUS ?>. All rights reserved.</p>
</footer>

<script>
const IPK_MAHASISWA = <?= IPK_MAHASISWA ?>;
const IPK_MINIMAL   = <?= IPK_MINIMAL ?>;

window.addEventListener('DOMContentLoaded', function () {
    if (IPK_MAHASISWA >= IPK_MINIMAL) {
        const selectBeasiswa = document.getElementById('beasiswa');
        if (selectBeasiswa && !selectBeasiswa.value) selectBeasiswa.focus();
    }
});

const inputFile = document.getElementById('berkas');
if (inputFile) {
    inputFile.addEventListener('change', function () {
        document.getElementById('fileText').textContent = this.files[0]
            ? this.files[0].name : 'Pilih File (PDF / JPG / ZIP)';
    });
}

function resetForm() {
    if (confirm('Apakah Anda yakin ingin menghapus semua isian?')) {
        document.getElementById('formDaftar').reset();
        document.getElementById('fileText').textContent = 'Pilih File (PDF / JPG / ZIP)';
        window.location.href = 'daftar.php';
    }
    return false;
}
</script>
</body>
</html>