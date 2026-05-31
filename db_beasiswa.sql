-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 30 Bulan Mei 2026 pada 09.14
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_beasiswa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftar`
--

CREATE TABLE `pendaftar` (
  `id` int(10) UNSIGNED NOT NULL,
  `kode_daftar` varchar(40) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `hp` varchar(15) NOT NULL,
  `semester` tinyint(4) NOT NULL,
  `ipk` decimal(3,2) NOT NULL,
  `pilihan_beasiswa` varchar(30) NOT NULL,
  `nama_beasiswa` varchar(150) NOT NULL,
  `berkas` varchar(200) NOT NULL DEFAULT '',
  `tanggal_daftar` datetime NOT NULL DEFAULT current_timestamp(),
  `status_ajuan` varchar(50) NOT NULL DEFAULT 'Belum Diverifikasi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pendaftar`
--

INSERT INTO `pendaftar` (`id`, `kode_daftar`, `nama`, `email`, `hp`, `semester`, `ipk`, `pilihan_beasiswa`, `nama_beasiswa`, `berkas`, `tanggal_daftar`, `status_ajuan`) VALUES
(1, 'BSW-6a1a88cfb71ea0.81760937', 'Muhammad Arifin', 'muhammadarifin@gmail.com', '0876656765432', 8, 3.40, 'akademik', 'Beasiswa Akademik Prestasi', 'berkas_6a1a88cfb68f6.pdf', '2026-05-30 13:50:55', 'Belum Diverifikasi'),
(2, 'BSW-6a1a8b0bcf0474.88496769', 'Kholik Hamdan', 'kholikhamdan@gmail.com', '089776534566', 4, 3.40, 'non_akademik', 'Beasiswa Non-Akademik (Bakat & Minat)', 'berkas_6a1a8b0bc8af4.pdf', '2026-05-30 14:00:27', 'Belum Diverifikasi');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `pendaftar`
--
ALTER TABLE `pendaftar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_daftar` (`kode_daftar`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `pendaftar`
--
ALTER TABLE `pendaftar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
