# Sistem Pendaftaran Beasiswa Online
**Universitas Nusantara**

## Struktur Direktori

```
beasiswa/
├── index.php               # Halaman utama - Pilihan Beasiswa + info kuota
├── daftar.php              # Halaman form pendaftaran
├── hasil.php               # Halaman hasil/view pendaftar
├── beasiswa.sql            # Script SQL untuk membuat database & tabel
├── README.md               # Dokumentasi proyek (file ini)
│
├── includes/               # File PHP helper (tidak diakses langsung)
│   ├── config.php          # Konstanta & konfigurasi global (IPK, database)
│   ├── db.php              # Koneksi PDO ke database MySQL (singleton)
│   ├── functions.php       # Kumpulan fungsi/prosedur helper
│   └── icons.php           # Fungsi render SVG icon inline (Heroicons)
│
├── css/
│   └── style.css           # Stylesheet utama aplikasi (custom, tanpa framework)
│
├── img/
│   └── favicon.svg         # Favicon SVG icon graduation (warna putih)
│
└── uploads/                # Folder penyimpanan file berkas yang diupload
    └── (file berkas mahasiswa tersimpan di sini)
```

### Penjelasan Setiap File

| File | Peran |
| `index.php` | Menampilkan 3 pilihan beasiswa, status kuota, progress bar, info IPK |
| `daftar.php` | Form pendaftaran dengan validasi server-side + upload berkas |
| `hasil.php` | Tabel semua pendaftar dengan seluruh kolom + status ajuan |
| `beasiswa.sql` | DDL untuk membuat database `db_beasiswa` dan tabel `pendaftar` |
| `includes/config.php` | Satu-satunya tempat mengubah konfigurasi (DB, IPK, upload, beasiswa) |
| `includes/db.php` | Fungsi `getDB()` — singleton PDO, dipanggil tiap kali butuh query |
| `includes/functions.php` | Semua fungsi bisnis: validasi, CRUD database, upload, sanitasi |
| `includes/icons.php` | Fungsi `icon()` — render SVG Heroicons berdasarkan nama |
| `css/style.css` | Design system lengkap: CSS variables, layout, komponen, responsif |
| `img/favicon.svg` | Icon graduation SVG berwarna putih untuk tab browser |

## Cara Setup & Menjalankan

### Prasyarat
- **XAMPP** versi 7.4+ (sudah include Apache, MySQL, PHP)
- Browser modern (Chrome, Firefox, Edge)
- Text editor (VS Code, Notepad++, dll)

### Langkah 1 — Install & Jalankan XAMPP
1. Download XAMPP dari [https://www.apachefriends.org](https://www.apachefriends.org)
2. Install dan buka **XAMPP Control Panel**
3. Klik **Start** pada **Apache** dan **MySQL**
4. Pastikan status keduanya hijau (Running)

### Langkah 2 — Copy Project
```
Salin folder beasiswa/ ke:
C:\xampp\htdocs\beasiswa\
```
Sehingga struktur menjadi:
```
C:\xampp\htdocs\beasiswa\
├── index.php
├── daftar.php
├── hasil.php
├── beasiswa.sql
└── ...
```

### Langkah 3 — Setup Database
1. Buka browser, akses `http://localhost/phpmyadmin`
2. Klik tab **Import** di menu atas
3. Klik **Choose File** → pilih file `beasiswa.sql`
4. Klik tombol **Go** / **Import**
5. Database `db_beasiswa` dan tabel `pendaftar` otomatis terbuat

### Langkah 4 — Konfigurasi Koneksi Database
Buka `includes/config.php`, sesuaikan jika perlu:
```php
define('DB_HOST', 'localhost');   // biasanya tidak perlu diubah
define('DB_NAME', 'db_beasiswa'); // nama database
define('DB_USER', 'root');        // username MySQL (XAMPP default: root)
define('DB_PASS', '');            // password MySQL (XAMPP default: kosong)
```

### Langkah 5 — Permission Folder Upload
Pastikan folder `uploads/` bisa ditulis. Di Windows dengan XAMPP biasanya sudah otomatis. Jika di Linux/Mac:
```bash
chmod 755 uploads/
```

### Langkah 6 — Buka di Browser
```
http://localhost/beasiswa/
```
## Struktur Database

### Database: `db_beasiswa`
### Tabel: `pendaftar`

| Kolom | Tipe Data | Keterangan |
| `id` | INT UNSIGNED AUTO_INCREMENT | Primary key |
| `kode_daftar` | VARCHAR(40) UNIQUE | ID unik pendaftar format BSW-xxxxxxx |
| `nama` | VARCHAR(150) | Nama lengkap mahasiswa |
| `email` | VARCHAR(150) | Alamat email |
| `hp` | VARCHAR(15) | Nomor HP (10-13 digit) |
| `semester` | TINYINT | Semester aktif (1-8) |
| `ipk` | DECIMAL(3,2) | IPK mahasiswa (0.00-4.00) |
| `pilihan_beasiswa` | VARCHAR(30) | ID beasiswa: akademik / non_akademik / kurang_mampu |
| `nama_beasiswa` | VARCHAR(150) | Nama lengkap beasiswa yang dipilih |
| `berkas` | VARCHAR(200) | Nama file yang diupload ke folder uploads/ |
| `tanggal_daftar` | DATETIME | Timestamp pendaftaran (otomatis) |
| `status_ajuan` | VARCHAR(50) | Default: "Belum Diverifikasi" |

## Library & Teknologi yang Digunakan

### Backend
| Library | Jenis | Kegunaan |
| **PHP 7.4+** | Bahasa pemrograman | Logic server-side, prosedural |
| **PDO** | PHP Extension (built-in) | Koneksi MySQL dengan prepared statement |
| **MySQL** | Database | Penyimpanan data pendaftar |
| `filter_var` | PHP Built-in | Validasi format email |
| `preg_match` | PHP Built-in | Validasi format HP dengan regex |
| `htmlspecialchars` | PHP Built-in | Sanitasi output (XSS prevention) |
| `session_start` | PHP Built-in | Menyimpan IPK simulasi antar request |
| `move_uploaded_file` | PHP Built-in | Memindahkan file upload ke folder tujuan |
| `uniqid` | PHP Built-in | Generate ID unik untuk kode pendaftar & nama file |
| `pathinfo` | PHP Built-in | Mengambil ekstensi file yang diupload |

### Frontend
| Library | Jenis | Kegunaan |
| **HTML5** | Markup | Struktur halaman web |
| **CSS3** | Stylesheet | Tampilan & layout (custom, tanpa framework) |
| **JavaScript Vanilla** | Script | Interaksi UI: file picker, reset form, fokus otomatis |
| **Google Fonts** | CDN Eksternal | Font Playfair Display (heading) + DM Sans (body) |
| **Heroicons** | SVG Inline | Icon vector yang dirender via fungsi `icon()` di `icons.php` |

### Tools & Environment
| Tool | Kegunaan |
| **XAMPP** | Local server (Apache + MySQL + PHP) |
| **phpMyAdmin** | GUI manajemen database MySQL |
| **Browser** | Testing & tampilan aplikasi |

### Tidak Menggunakan
- Framework PHP (Laravel, CodeIgniter, dll)
- Framework CSS (Bootstrap, Tailwind, dll)
- Library JavaScript (jQuery, Vue, React, dll)
- Composer / npm

## Konfigurasi IPK

IPK diambil otomatis dari sistem (simulasi via PHP Session). Setiap refresh halaman fresh, IPK berganti acak antara dua nilai simulasi:

```php
// Di includes/config.php
$_ipk_options = [3.4, 2.9];  // ubah nilai di sini untuk simulasi berbeda
```

### Logika IPK:
- **IPK ≥ 3.0** → Form aktif penuh, kursor otomatis ke pilihan beasiswa
- **IPK < 3.0** → Dropdown beasiswa, upload berkas, dan tombol daftar **dinonaktifkan**
- **Saat form error** → IPK tidak berganti (tersimpan di session), tetap sama sampai berhasil daftar
- **Klik tombol Batal** → Redirect GET ke `daftar.php`, IPK di-random ulang
- **Batas IPK** → Tidak bisa melebihi 4.00 dan tidak bisa di bawah 0.00

## Fitur Aplikasi

| Halaman | Fitur |
| Pilihan Beasiswa | 3 kartu beasiswa, syarat, manfaat, progress bar kuota real-time |
| Daftar Beasiswa | Form registrasi, validasi lengkap, upload berkas, indikator IPK |
| Hasil Pendaftaran | Tabel semua pendaftar, status ajuan, info keterangan |

### Checklist Fitur:
- Validasi email format (nama@domain.com)
- Validasi nomor HP (10-13 digit angka, tanpa spinner up/down)
- Pilihan semester 1-8 via dropdown
- IPK otomatis dari sistem (simulasi session, random tiap refresh)
- Form dinonaktifkan jika IPK di bawah 3.0
- Kursor otomatis ke pilihan beasiswa jika IPK memenuhi syarat
- Upload berkas (PDF, JPG, PNG, ZIP — maks 5MB)
- Status ajuan "Belum Diverifikasi" otomatis tersimpan saat daftar
- Progress bar kuota per jenis beasiswa (real-time dari database)
- Tombol daftar dinonaktifkan & kartu memudar jika kuota penuh
- Favicon SVG graduation icon (warna putih)
- Desain responsif (mobile-friendly)
- Proteksi SQL Injection via PDO Prepared Statement
- Proteksi XSS via htmlspecialchars()

## Jenis Beasiswa

| # | ID | Nama | Kuota | Manfaat |
| 1 | `akademik` | Beasiswa Akademik Prestasi | 20 orang | Bebas SPP 1 semester + uang saku Rp 500.000/bulan |
| 2 | `non_akademik` | Beasiswa Non-Akademik (Bakat & Minat) | 10 orang | Potongan SPP 50% + Rp 1.000.000/semester |
| 3 | `kurang_mampu` | Beasiswa Mahasiswa Berprestasi Kurang Mampu | 15 orang | Bebas SPP penuh + biaya hidup Rp 750.000/bulan |

Semua beasiswa mensyaratkan **IPK minimal 3.0**.