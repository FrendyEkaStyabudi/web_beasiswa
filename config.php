<?php
// =============================================
// KONSTANTA IPK (Simulasi data dari sistem)
// =============================================
session_start();

$_ipk_options = [3.4, 2.9];

define('IPK_MAHASISWA', $_SESSION['ipk_simulasi']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Form di-submit: pakai IPK yang sudah ada di session
    if (!isset($_SESSION['ipk_simulasi'])) {
        $_SESSION['ipk_simulasi'] = $_ipk_options[array_rand($_ipk_options)];
    }
} else {
    // Fresh load / refresh: random IPK baru
    $_SESSION['ipk_simulasi'] = $_ipk_options[array_rand($_ipk_options)];
}

// =============================================
// KONSTANTA KONFIGURASI APLIKASI
// =============================================
define('NAMA_KAMPUS', 'Universitas Nusantara');
define('APP_NAME', 'Sistem Pendaftaran Beasiswa Online');
define('IPK_MINIMAL', 3.0);
define('MAX_SEMESTER', 8);
define('STATUS_AJUAN_DEFAULT', 'Belum Diverifikasi');

// =============================================
// KONFIGURASI DATABASE MySQL
// =============================================
define('DB_HOST',    'localhost');
define('DB_NAME',    'db_beasiswa');
define('DB_USER',    'root');
define('DB_PASS',    '');
define('DB_CHARSET', 'utf8mb4');

// =============================================
// KONFIGURASI UPLOAD FILE
// =============================================
define('UPLOAD_DIR',         __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE',      5 * 1024 * 1024);
define('ALLOWED_EXTENSIONS', ['pdf', 'jpg', 'jpeg', 'png', 'zip']);

// =============================================
// DAFTAR BEASISWA YANG TERSEDIA
// =============================================
$DAFTAR_BEASISWA = [
    [
        'id'      => 'akademik',
        'nama'    => 'Beasiswa Akademik Prestasi',
        'syarat'  => 'IPK minimal 3.0, aktif kuliah, tidak sedang menerima beasiswa lain',
        'manfaat' => 'Bebas SPP selama 1 semester + uang saku Rp 500.000/bulan',
        'kuota'   => 20,
    ],
    [
        'id'      => 'non_akademik',
        'nama'    => 'Beasiswa Non-Akademik (Bakat & Minat)',
        'syarat'  => 'IPK minimal 3.0, memiliki prestasi di bidang olahraga/seni/organisasi tingkat nasional',
        'manfaat' => 'Potongan SPP 50% + dukungan kegiatan Rp 1.000.000/semester',
        'kuota'   => 10,
    ],
    [
        'id'      => 'kurang_mampu',
        'nama'    => 'Beasiswa Mahasiswa Berprestasi Kurang Mampu',
        'syarat'  => 'IPK minimal 3.0, melampirkan surat keterangan tidak mampu dari kelurahan',
        'manfaat' => 'Bebas SPP penuh + biaya hidup Rp 750.000/bulan',
        'kuota'   => 15,
    ],
];