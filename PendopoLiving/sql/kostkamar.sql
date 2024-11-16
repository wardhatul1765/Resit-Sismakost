-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 16 Nov 2024 pada 05.22
-- Versi server: 8.0.36
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kostkamar`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `idAdmin` int NOT NULL,
  `Email` varchar(45) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `namaAdmin` varchar(255) DEFAULT NULL,
  `reset_token` varchar(6) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`idAdmin`, `Email`, `Password`, `namaAdmin`, `reset_token`, `token_expiry`) VALUES
(1, 'wardhatuljannahfiqyani@gmail.com', 'wardha12', 'Wardha', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `blok`
--

CREATE TABLE `blok` (
  `idBlok` int NOT NULL,
  `namaBlok` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `blok`
--

INSERT INTO `blok` (`idBlok`, `namaBlok`) VALUES
(1, 'A'),
(2, 'B'),
(3, 'C'),
(4, 'D'),
(5, 'E'),
(6, 'F'),
(7, 'G'),
(8, 'H'),
(9, 'I');

-- --------------------------------------------------------

--
-- Struktur dari tabel `fasilitas`
--

CREATE TABLE `fasilitas` (
  `idFasilitas` int NOT NULL,
  `namaFasilitas` text NOT NULL,
  `biayaTambahan` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data untuk tabel `fasilitas`
--

INSERT INTO `fasilitas` (`idFasilitas`, `namaFasilitas`, `biayaTambahan`) VALUES
(1, 'kamar mandi luar', 50000),
(2, 'kamar mandi dalam', 100000),
(3, 'biaya listrik', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kamar`
--

CREATE TABLE `kamar` (
  `idKamar` int NOT NULL,
  `namaKamar` varchar(100) NOT NULL,
  `nomorKamar` varchar(5) NOT NULL,
  `harga` int NOT NULL,
  `status` enum('Tersedia','Kosong','Booking') NOT NULL,
  `foto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `idBlok` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data untuk tabel `kamar`
--

INSERT INTO `kamar` (`idKamar`, `namaKamar`, `nomorKamar`, `harga`, `status`, `foto`, `idBlok`) VALUES
(1, 'KamarA1', 'A1', 300000, 'Tersedia', NULL, 1),
(2, 'KamarA2', 'A2', 300000, 'Tersedia', NULL, 1),
(3, 'KamarA3', 'A3', 300000, 'Tersedia', NULL, 1),
(4, 'KamarA4', 'A4', 300000, 'Tersedia', NULL, 1),
(5, 'KamarA5', 'A5', 300000, 'Tersedia', NULL, 1),
(6, 'KamarA6', 'A6', 300000, 'Tersedia', NULL, 1),
(7, 'KamarA7', 'A7', 300000, 'Tersedia', NULL, 1),
(8, 'KamarA8', 'A8', 300000, 'Tersedia', NULL, 1),
(9, 'KamarA9', 'A9', 300000, 'Tersedia', NULL, 1),
(10, 'KamarA10', 'A10', 300000, 'Tersedia', NULL, 1),
(11, 'KamarA11', 'A11', 300000, 'Tersedia', NULL, 1),
(12, 'KamarA12', 'A12', 300000, 'Tersedia', NULL, 1),
(13, 'KamarB1', 'B1', 300000, 'Tersedia', NULL, 2),
(14, 'KamarB2', 'B2', 300000, 'Tersedia', NULL, 2),
(15, 'KamarB3', 'B3', 300000, 'Tersedia', NULL, 2),
(16, 'KamarB4', 'B4', 300000, 'Tersedia', NULL, 2),
(17, 'KamarB5', 'B5', 300000, 'Tersedia', NULL, 2),
(18, 'KamarB6', 'B6', 300000, 'Tersedia', NULL, 2),
(19, 'KamarB7', 'B7', 300000, 'Tersedia', NULL, 2),
(20, 'KamarB8', 'B8', 300000, 'Tersedia', NULL, 2),
(21, 'KamarB9', 'B9', 300000, 'Tersedia', NULL, 2),
(22, 'KamarB10', 'B10', 300000, 'Tersedia', NULL, 2),
(23, 'KamarB11', 'B11', 300000, 'Tersedia', NULL, 2),
(24, 'KamarB12', 'B12', 300000, 'Tersedia', NULL, 2),
(25, 'KamarC1', 'C1', 300000, 'Tersedia', NULL, 3),
(26, 'KamarC2', 'C2', 300000, 'Tersedia', NULL, 3),
(27, 'KamarC3', 'C3', 300000, 'Tersedia', NULL, 3),
(28, 'KamarC4', 'C4', 300000, 'Tersedia', NULL, 3),
(29, 'KamarC5', 'C5', 300000, 'Tersedia', NULL, 3),
(30, 'KamarC6', 'C6', 300000, 'Tersedia', NULL, 3),
(31, 'KamarC7', 'C7', 300000, 'Tersedia', NULL, 3),
(32, 'KamarC8', 'C8', 300000, 'Tersedia', NULL, 3),
(33, 'KamarC9', 'C9', 300000, 'Tersedia', NULL, 3),
(34, 'KamarC10', 'C10', 300000, 'Tersedia', NULL, 3),
(35, 'KamarC11', 'C11', 300000, 'Tersedia', NULL, 3),
(36, 'KamarC12', 'C12', 300000, 'Tersedia', NULL, 3),
(37, 'KamarD1', 'D1', 300000, 'Tersedia', NULL, 4),
(38, 'KamarD2', 'D2', 300000, 'Tersedia', NULL, 4),
(39, 'KamarD3', 'D3', 300000, 'Tersedia', NULL, 4),
(40, 'KamarD4', 'D4', 300000, 'Tersedia', NULL, 4),
(41, 'KamarD5', 'D5', 300000, 'Tersedia', NULL, 4),
(42, 'KamarD6', 'D6', 300000, 'Tersedia', NULL, 4),
(43, 'KamarD7', 'D7', 300000, 'Tersedia', NULL, 4),
(44, 'KamarD8', 'D8', 300000, 'Tersedia', NULL, 4),
(45, 'KamarD9', 'D9', 300000, 'Tersedia', NULL, 4),
(46, 'KamarD10', 'D10', 300000, 'Tersedia', NULL, 4),
(47, 'KamarD11', 'D11', 300000, 'Tersedia', NULL, 4),
(48, 'KamarD12', 'D12', 300000, 'Tersedia', NULL, 4),
(49, 'KamarE1', 'E1', 300000, 'Tersedia', NULL, 5),
(50, 'KamarE2', 'E2', 300000, 'Tersedia', NULL, 5),
(51, 'KamarE3', 'E3', 300000, 'Tersedia', NULL, 5),
(52, 'KamarE4', 'E4', 300000, 'Tersedia', NULL, 5),
(53, 'KamarE5', 'E5', 300000, 'Tersedia', NULL, 5),
(54, 'KamarE6', 'E6', 300000, 'Tersedia', NULL, 5),
(55, 'KamarE7', 'E7', 300000, 'Tersedia', NULL, 5),
(56, 'KamarE8', 'E8', 300000, 'Tersedia', NULL, 5),
(57, 'KamarE9', 'E9', 300000, 'Tersedia', NULL, 5),
(58, 'KamarE10', 'E10', 300000, 'Tersedia', NULL, 5),
(59, 'KamarE11', 'E11', 300000, 'Tersedia', NULL, 5),
(60, 'KamarE12', 'E12', 300000, 'Tersedia', NULL, 5),
(61, 'KamarF1', 'F1', 300000, 'Tersedia', NULL, 6),
(62, 'KamarF2', 'F2', 300000, 'Tersedia', NULL, 6),
(63, 'KamarF3', 'F3', 300000, 'Tersedia', NULL, 6),
(64, 'KamarF4', 'F4', 300000, 'Tersedia', NULL, 6),
(65, 'KamarF5', 'F5', 300000, 'Tersedia', NULL, 6),
(66, 'KamarF6', 'F6', 300000, 'Tersedia', NULL, 6),
(67, 'KamarF7', 'F7', 300000, 'Tersedia', NULL, 6),
(68, 'KamarF8', 'F8', 300000, 'Tersedia', NULL, 6),
(69, 'KamarF9', 'F9', 300000, 'Tersedia', NULL, 6),
(70, 'KamarF10', 'F10', 300000, 'Tersedia', NULL, 6),
(71, 'KamarF11', 'F11', 300000, 'Tersedia', NULL, 6),
(72, 'KamarF12', 'F12', 300000, 'Tersedia', NULL, 6),
(73, 'KamarG1', 'G1', 300000, 'Tersedia', NULL, 7),
(74, 'KamarG2', 'G2', 300000, 'Tersedia', NULL, 7),
(75, 'KamarG3', 'G3', 300000, 'Tersedia', NULL, 7),
(76, 'KamarG4', 'G4', 300000, 'Tersedia', NULL, 7),
(77, 'KamarG5', 'G5', 300000, 'Tersedia', NULL, 7),
(78, 'KamarG6', 'G6', 300000, 'Tersedia', NULL, 7),
(79, 'KamarG7', 'G7', 300000, 'Tersedia', NULL, 7),
(80, 'KamarG8', 'G8', 300000, 'Tersedia', NULL, 7),
(81, 'KamarG9', 'G9', 300000, 'Tersedia', NULL, 7),
(82, 'KamarG10', 'G10', 300000, 'Tersedia', NULL, 7),
(83, 'KamarG11', 'G11', 300000, 'Tersedia', NULL, 7),
(84, 'KamarG12', 'G12', 300000, 'Tersedia', NULL, 7),
(85, 'KamarH1', 'H1', 300000, 'Tersedia', NULL, 8),
(86, 'KamarH2', 'H2', 300000, 'Tersedia', NULL, 8),
(87, 'KamarH3', 'H3', 300000, 'Tersedia', NULL, 8),
(88, 'KamarH4', 'H4', 300000, 'Tersedia', NULL, 8),
(89, 'KamarH5', 'H5', 300000, 'Tersedia', NULL, 8),
(90, 'KamarH6', 'H6', 300000, 'Tersedia', NULL, 8),
(91, 'KamarH7', 'H7', 300000, 'Tersedia', NULL, 8),
(92, 'KamarH8', 'H8', 300000, 'Tersedia', NULL, 8),
(93, 'KamarH9', 'H9', 300000, 'Tersedia', NULL, 8),
(94, 'KamarH10', 'H10', 300000, 'Tersedia', NULL, 8),
(95, 'KamarH11', 'H11', 300000, 'Tersedia', NULL, 8),
(96, 'KamarH12', 'H12', 300000, 'Tersedia', NULL, 8),
(97, 'KamarI1', 'I1', 300000, 'Tersedia', NULL, 9),
(98, 'KamarI2', 'I2', 300000, 'Tersedia', NULL, 9),
(99, 'KamarI3', 'I3', 300000, 'Tersedia', NULL, 9),
(100, 'KamarI4', 'I4', 300000, 'Tersedia', NULL, 9),
(101, 'KamarI5', 'I5', 300000, 'Tersedia', NULL, 9),
(102, 'KamarI6', 'I6', 300000, 'Tersedia', NULL, 9),
(103, 'KamarI7', 'I7', 300000, 'Tersedia', NULL, 9),
(104, 'KamarI8', 'I8', 300000, 'Tersedia', NULL, 9),
(105, 'KamarI9', 'I9', 300000, 'Tersedia', NULL, 9),
(106, 'KamarI10', 'I10', 300000, 'Tersedia', NULL, 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kamar_fasilitas`
--

CREATE TABLE `kamar_fasilitas` (
  `id` int NOT NULL,
  `idKamar` int DEFAULT NULL,
  `idFasilitas` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `kamar_fasilitas`
--

INSERT INTO `kamar_fasilitas` (`id`, `idKamar`, `idFasilitas`) VALUES
(1, 10, 1),
(2, 11, 1),
(3, 9, 2),
(4, 91, 1),
(5, 75, 2),
(6, 19, 2),
(7, 92, 2),
(8, 97, 1),
(9, 83, 1),
(10, 63, 2),
(11, 16, 1),
(12, 30, 1),
(13, 40, 1),
(14, 76, 1),
(15, 101, 2),
(16, 77, 1),
(17, 62, 1),
(18, 84, 2),
(19, 12, 1),
(20, 21, 2),
(21, 14, 2),
(22, 47, 2),
(23, 3, 2),
(24, 50, 2),
(25, 39, 2),
(26, 82, 1),
(27, 65, 2),
(28, 73, 1),
(29, 69, 1),
(30, 71, 1),
(31, 26, 1),
(32, 38, 1),
(33, 89, 2),
(34, 2, 2),
(35, 68, 2),
(36, 90, 2),
(37, 56, 1),
(38, 93, 2),
(39, 46, 2),
(40, 58, 2),
(41, 100, 1),
(42, 1, 2),
(43, 23, 2),
(44, 53, 1),
(45, 15, 2),
(46, 17, 1),
(47, 5, 2),
(48, 32, 2),
(49, 54, 1),
(50, 37, 2),
(51, 6, 2),
(52, 35, 1),
(53, 7, 2),
(54, 67, 2),
(55, 98, 2),
(56, 66, 2),
(57, 57, 2),
(58, 102, 1),
(59, 103, 2),
(60, 49, 2),
(61, 99, 2),
(62, 4, 2),
(63, 81, 1),
(64, 96, 1),
(65, 85, 2),
(66, 8, 1),
(67, 55, 1),
(68, 42, 2),
(69, 29, 2),
(70, 79, 2),
(71, 52, 2),
(72, 45, 2),
(73, 33, 2),
(74, 44, 2),
(75, 48, 2),
(76, 28, 1),
(77, 70, 2),
(78, 34, 2),
(79, 20, 1),
(80, 94, 2),
(81, 59, 2),
(82, 72, 1),
(83, 36, 2),
(84, 22, 1),
(85, 25, 2),
(86, 31, 2),
(87, 13, 1),
(88, 41, 1),
(89, 78, 2),
(90, 95, 2),
(91, 88, 1),
(92, 60, 2),
(93, 105, 2),
(94, 87, 2),
(95, 43, 1),
(96, 86, 2),
(97, 27, 2),
(98, 74, 1),
(99, 80, 2),
(100, 61, 2),
(101, 51, 1),
(102, 24, 1),
(103, 104, 1),
(104, 64, 1),
(105, 18, 2),
(106, 106, 1),
(128, 1, 3),
(129, 2, 3),
(130, 3, 3),
(131, 4, 3),
(132, 5, 3),
(133, 6, 3),
(134, 7, 3),
(135, 8, 3),
(136, 9, 3),
(137, 10, 3),
(138, 11, 3),
(139, 12, 3),
(140, 13, 3),
(141, 14, 3),
(142, 15, 3),
(143, 16, 3),
(144, 17, 3),
(145, 18, 3),
(146, 19, 3),
(147, 20, 3),
(148, 21, 3),
(149, 22, 3),
(150, 23, 3),
(151, 24, 3),
(152, 25, 3),
(153, 26, 3),
(154, 27, 3),
(155, 28, 3),
(156, 29, 3),
(157, 30, 3),
(158, 31, 3),
(159, 32, 3),
(160, 33, 3),
(161, 34, 3),
(162, 35, 3),
(163, 36, 3),
(164, 37, 3),
(165, 38, 3),
(166, 39, 3),
(167, 40, 3),
(168, 41, 3),
(169, 42, 3),
(170, 43, 3),
(171, 44, 3),
(172, 45, 3),
(173, 46, 3),
(174, 47, 3),
(175, 48, 3),
(176, 49, 3),
(177, 50, 3),
(178, 51, 3),
(179, 52, 3),
(180, 53, 3),
(181, 54, 3),
(182, 55, 3),
(183, 56, 3),
(184, 57, 3),
(185, 58, 3),
(186, 59, 3),
(187, 60, 3),
(188, 61, 3),
(189, 62, 3),
(190, 63, 3),
(191, 64, 3),
(192, 65, 3),
(193, 66, 3),
(194, 67, 3),
(195, 68, 3),
(196, 69, 3),
(197, 70, 3),
(198, 71, 3),
(199, 72, 3),
(200, 73, 3),
(201, 74, 3),
(202, 75, 3),
(203, 76, 3),
(204, 77, 3),
(205, 78, 3),
(206, 79, 3),
(207, 80, 3),
(208, 81, 3),
(209, 82, 3),
(210, 83, 3),
(211, 84, 3),
(212, 85, 3),
(213, 86, 3),
(214, 87, 3),
(215, 88, 3),
(216, 89, 3),
(217, 90, 3),
(218, 91, 3),
(219, 92, 3),
(220, 93, 3),
(221, 94, 3),
(222, 95, 3),
(223, 96, 3),
(224, 97, 3),
(225, 98, 3),
(226, 99, 3),
(227, 100, 3),
(228, 101, 3),
(229, 102, 3),
(230, 103, 3),
(231, 104, 3),
(232, 105, 3),
(233, 106, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pembayaran`
--

CREATE TABLE `pembayaran` (
  `idPembayaran` int NOT NULL,
  `tanggalPembayaran` varchar(45) NOT NULL,
  `batasPembayaran` varchar(45) NOT NULL,
  `durasiSewa` varchar(45) NOT NULL,
  `StatusPembayaran` enum('Lunas','Belum Lunas') NOT NULL,
  `metode_pembayaran` enum('QRIS','Transfer Bank','Langsung') NOT NULL,
  `idPenyewa` int DEFAULT NULL,
  `jatuh_tempo` date DEFAULT NULL,
  `id_pemesanan` int DEFAULT NULL,
  `bukti_transfer` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pemesanan`
--

CREATE TABLE `pemesanan` (
  `id_pemesanan` int NOT NULL,
  `pemesanan_kamar` date NOT NULL,
  `uang_muka` decimal(10,2) NOT NULL,
  `status_uang_muka` enum('Menunggu Pembayaran','Sudah Bayar') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Menunggu Pembayaran',
  `tenggat_uang_muka` date DEFAULT NULL,
  `mulai_menempati_kos` date DEFAULT NULL,
  `batas_menempati_kos` date DEFAULT NULL,
  `status` enum('Menunggu Pembayaran','Dikonfirmasi','Dibatalkan') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Menunggu Pembayaran',
  `id_penyewa` int DEFAULT NULL,
  `idKamar` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `penyewa`
--

CREATE TABLE `penyewa` (
  `idPenyewa` int NOT NULL,
  `namaPenyewa` varchar(100) NOT NULL,
  `noTelepon` varchar(15) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `fotoJaminan` varchar(255) DEFAULT NULL,
  `idKamar` int DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expiry` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Dumping data untuk tabel `penyewa`
--

INSERT INTO `penyewa` (`idPenyewa`, `namaPenyewa`, `noTelepon`, `email`, `password`, `fotoJaminan`, `idKamar`, `reset_token`, `token_expiry`, `created_at`) VALUES
(1, 'KakaPatria', '085707308476', 'kakapatria22@gmail.com', '$2y$10$1Nh.at/xjhsyrNGYzLB/dO6klG94aNTe3tK27KNVvd2/8pzu17sum', NULL, NULL, NULL, NULL, '2024-11-02 09:33:50'),
(2, 'Patria', '085707308476', 'kakapatria65@gmail.com', '$2y$10$0nC0lOEsS1ceDnckEWZSKubCmsZogwzkfeJY6B/4Lesoz3xZd0Zue', NULL, NULL, NULL, NULL, '2024-11-02 09:35:59'),
(3, 'KakaPatria', '085707308476', 'kakapatria66@gmail.com', '$2y$10$H5ex03jGgsyIBo.Mcgapuu8Hldq0yVCGMM0OZzMze2tG1XlyGMUze', NULL, NULL, NULL, NULL, '2024-11-02 09:41:07');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`idAdmin`);

--
-- Indeks untuk tabel `blok`
--
ALTER TABLE `blok`
  ADD PRIMARY KEY (`idBlok`);

--
-- Indeks untuk tabel `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD PRIMARY KEY (`idFasilitas`);

--
-- Indeks untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD PRIMARY KEY (`idKamar`),
  ADD KEY `fk_kamar_blok` (`idBlok`);

--
-- Indeks untuk tabel `kamar_fasilitas`
--
ALTER TABLE `kamar_fasilitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idKamar` (`idKamar`),
  ADD KEY `idFasilitas` (`idFasilitas`);

--
-- Indeks untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`idPembayaran`),
  ADD KEY `fk_idPenyewa` (`idPenyewa`),
  ADD KEY `fk_pembayaran_pemesanan` (`id_pemesanan`);

--
-- Indeks untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`),
  ADD KEY `fk_id_penyewa` (`id_penyewa`),
  ADD KEY `fk_pemesanan_kamar` (`idKamar`);

--
-- Indeks untuk tabel `penyewa`
--
ALTER TABLE `penyewa`
  ADD PRIMARY KEY (`idPenyewa`),
  ADD KEY `fk_idKamar` (`idKamar`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `idAdmin` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `blok`
--
ALTER TABLE `blok`
  MODIFY `idBlok` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `fasilitas`
--
ALTER TABLE `fasilitas`
  MODIFY `idFasilitas` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `kamar`
--
ALTER TABLE `kamar`
  MODIFY `idKamar` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT untuk tabel `kamar_fasilitas`
--
ALTER TABLE `kamar_fasilitas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=255;

--
-- AUTO_INCREMENT untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  MODIFY `idPembayaran` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `id_pemesanan` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `penyewa`
--
ALTER TABLE `penyewa`
  MODIFY `idPenyewa` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kamar`
--
ALTER TABLE `kamar`
  ADD CONSTRAINT `fk_kamar_blok` FOREIGN KEY (`idBlok`) REFERENCES `blok` (`idBlok`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kamar_fasilitas`
--
ALTER TABLE `kamar_fasilitas`
  ADD CONSTRAINT `kamar_fasilitas_ibfk_1` FOREIGN KEY (`idKamar`) REFERENCES `kamar` (`idKamar`),
  ADD CONSTRAINT `kamar_fasilitas_ibfk_2` FOREIGN KEY (`idFasilitas`) REFERENCES `fasilitas` (`idFasilitas`);

--
-- Ketidakleluasaan untuk tabel `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `fk_idPenyewa` FOREIGN KEY (`idPenyewa`) REFERENCES `penyewa` (`idPenyewa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pembayaran_pemesanan` FOREIGN KEY (`id_pemesanan`) REFERENCES `pemesanan` (`id_pemesanan`);

--
-- Ketidakleluasaan untuk tabel `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `fk_id_penyewa` FOREIGN KEY (`id_penyewa`) REFERENCES `penyewa` (`idPenyewa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pemesanan_kamar` FOREIGN KEY (`idKamar`) REFERENCES `kamar` (`idKamar`);

--
-- Ketidakleluasaan untuk tabel `penyewa`
--
ALTER TABLE `penyewa`
  ADD CONSTRAINT `fk_idKamar` FOREIGN KEY (`idKamar`) REFERENCES `kamar` (`idKamar`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
