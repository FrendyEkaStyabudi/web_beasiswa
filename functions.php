<?php
// Urutan load: config dulu, baru db
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

// ============================================================
// FUNGSI: Validasi format email
// ============================================================
function validasiEmail(string $email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// ============================================================
// FUNGSI: Validasi nomor HP (hanya angka, 10-13 digit)
// ============================================================
function validasiHP(string $hp): bool {
    return (bool) preg_match('/^[0-9]{10,13}$/', $hp);
}

// ============================================================
// FUNGSI: Validasi semester (1 s/d MAX_SEMESTER)
// ============================================================
function validasiSemester($semester): bool {
    $sem = (int)$semester;
    return $sem >= 1 && $sem <= MAX_SEMESTER;
}

// ============================================================
// FUNGSI: Membaca semua data pendaftar dari database
// ============================================================
function bacaDataPendaftar(): array {
    try {
        $pdo  = getDB();
        $stmt = $pdo->query('SELECT * FROM pendaftar ORDER BY tanggal_daftar DESC');
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log('bacaDataPendaftar error: ' . $e->getMessage());
        return [];
    }
}

// ============================================================
// FUNGSI: Menyimpan data pendaftar baru ke database
// ============================================================
function simpanDataPendaftar(array $dataBaru): bool {
    try {
        $pdo = getDB();

        $sql = 'INSERT INTO pendaftar
                    (kode_daftar, nama, email, hp, semester, ipk,
                     pilihan_beasiswa, nama_beasiswa, berkas, status_ajuan)
                VALUES
                    (:kode_daftar, :nama, :email, :hp, :semester, :ipk,
                     :pilihan_beasiswa, :nama_beasiswa, :berkas, :status_ajuan)';

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            ':kode_daftar'      => uniqid('BSW-', true),
            ':nama'             => $dataBaru['nama'],
            ':email'            => $dataBaru['email'],
            ':hp'               => $dataBaru['hp'],
            ':semester'         => $dataBaru['semester'],
            ':ipk'              => $dataBaru['ipk'],
            ':pilihan_beasiswa' => $dataBaru['pilihan_beasiswa'],
            ':nama_beasiswa'    => $dataBaru['nama_beasiswa'],
            ':berkas'           => $dataBaru['berkas'] ?? '',
            ':status_ajuan'     => STATUS_AJUAN_DEFAULT,
        ]);

    } catch (PDOException $e) {
        error_log('simpanDataPendaftar error: ' . $e->getMessage());
        return false;
    }
}

// ============================================================
// FUNGSI: Upload file berkas syarat
// ============================================================
function uploadBerkas(array $file): array {
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['sukses' => false, 'nama_file' => '', 'pesan' => 'Berkas syarat wajib diupload.'];
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['sukses' => false, 'nama_file' => '', 'pesan' => 'Terjadi kesalahan saat upload file.'];
    }
    if ($file['size'] > MAX_FILE_SIZE) {
        return ['sukses' => false, 'nama_file' => '', 'pesan' => 'Ukuran file melebihi batas maksimal (5MB).'];
    }

    $ekstensi = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ekstensi, ALLOWED_EXTENSIONS)) {
        return [
            'sukses'    => false,
            'nama_file' => '',
            'pesan'     => 'Format file tidak didukung. Gunakan: ' . implode(', ', ALLOWED_EXTENSIONS),
        ];
    }

    $namaFileBaru = uniqid('berkas_') . '.' . $ekstensi;
    $tujuan       = UPLOAD_DIR . $namaFileBaru;

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    if (move_uploaded_file($file['tmp_name'], $tujuan)) {
        return ['sukses' => true, 'nama_file' => $namaFileBaru, 'pesan' => 'File berhasil diupload.'];
    }

    return ['sukses' => false, 'nama_file' => '', 'pesan' => 'Gagal menyimpan file ke server.'];
}

// ============================================================
// FUNGSI: Sanitasi input string (XSS prevention)
// ============================================================
function bersihkan(string $input): string {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// ============================================================
// FUNGSI: Mendapatkan nama beasiswa berdasarkan ID
// ============================================================
function getNamaBeasiswa(string $id): string {
    global $DAFTAR_BEASISWA;
    foreach ($DAFTAR_BEASISWA as $b) {
        if ($b['id'] === $id) return $b['nama'];
    }
    return 'Tidak diketahui';
}

// ============================================================
// FUNGSI: Format IPK menjadi 2 desimal
// ============================================================
function formatIPK(float $ipk): string {
    return number_format($ipk, 2);
}

// ============================================================
// FUNGSI: Hitung jumlah pendaftar per kategori beasiswa
// Input  : string $id_beasiswa
// Output : int jumlah pendaftar
// ============================================================
function hitungPendaftarBeasiswa(string $id_beasiswa): int {
    try {
        $pdo  = getDB();
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM pendaftar WHERE pilihan_beasiswa = :id');
        $stmt->execute([':id' => $id_beasiswa]);
        return (int) $stmt->fetchColumn();
    } catch (PDOException $e) {
        error_log('hitungPendaftarBeasiswa error: ' . $e->getMessage());
        return 0;
    }
}