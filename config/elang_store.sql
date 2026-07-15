-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 16, 2026 at 09:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;

--
-- Database: `elang_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
    `id_admin` int(11) NOT NULL,
    `username` varchar(50) NOT NULL,
    `password` varchar(50) NOT NULL,
    `no_wa` varchar(20) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO
    `admin` (
        `id_admin`,
        `username`,
        `password`,
        `no_wa`
    )
VALUES (
        1,
        'admin',
        '123',
        '081234567890'
    );

-- --------------------------------------------------------

--
-- Table structure for table `akun`
--

CREATE TABLE `akun` (
    `id_akun` int(11) NOT NULL,
    `id_game` int(11) DEFAULT NULL,
    `nama_akun` varchar(100) NOT NULL,
    `spesifikasi` text DEFAULT NULL,
    `harga` int(11) NOT NULL,
    `foto` varchar(255) DEFAULT NULL,
    `status` enum('tersedia', 'terjual') DEFAULT 'tersedia'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `akun`
--

INSERT INTO
    `akun` (
        `id_akun`,
        `id_game`,
        `nama_akun`,
        `spesifikasi`,
        `harga`,
        `foto`,
        `status`
    )
VALUES (
        1,
        1,
        'Alucard Sultan',
        'Level 120, Mythical Glory, 150 hero, 320 skin, sisa diamond 5000',
        450000,
        'alucard.jpg',
        'tersedia'
    ),
    (
        2,
        1,
        'Gusion God',
        'Level 95, Mythic Honor, 135 hero, 280 skin, sisa diamond 3200',
        375000,
        'gusion.jpg',
        'terjual'
    ),
    (
        3,
        1,
        'Lunox Dream',
        'Level 88, Legend V, 120 hero, 220 skin, skin Legend',
        275000,
        'lunox.jpg',
        'terjual'
    ),
    (
        4,
        2,
        'M416 Glacier',
        'Level 75, Ace Dominant, skin M416 Glacier, outfit premium lengkap',
        350000,
        'm416.jpg',
        'tersedia'
    ),
    (
        5,
        2,
        'Kar98k Master',
        'Level 72, Ace, skin Kar98k Glacier, outfit Valorant',
        275000,
        'kar98k.jpg',
        'terjual'
    ),
    (
        6,
        2,
        'SCARL Dream',
        'Level 68, Crown III, skin SCARL lengkap, setanah merah',
        225000,
        'scarl.jpg',
        'tersedia'
    ),
    (
        7,
        3,
        'DJ Alok Pro',
        'Level 80, Heroic, punya DJ Alok, Chrono, Skyler, bundle lengkap',
        295000,
        'alok.jpg',
        'tersedia'
    ),
    (
        8,
        3,
        'Wukong Sultan',
        'Level 75, Grandmaster, skin Wukong, bundle elite, emote langka',
        195000,
        '1771230276_pecel.jpeg',
        'tersedia'
    ),
    (
        9,
        4,
        'Reyna Main',
        'Diamond 3, skin Reaver, Prime, Oni, battle pass lengkap',
        425000,
        'reyna.jpg',
        'tersedia'
    ),
    (
        10,
        4,
        'Jett God',
        'Platinum 2, skin Elderflame, Glitchpop, agent lengkap',
        325000,
        '1771221695_penyet.webp',
        'tersedia'
    );

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
    `id_galeri` int(11) NOT NULL,
    `id_akun` int(11) NOT NULL,
    `foto` varchar(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
ADD PRIMARY KEY (`id_galeri`),
ADD KEY `id_akun` (`id_akun`);

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
MODIFY `id_galeri` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
    `id_game` int(11) NOT NULL,
    `nama_game` varchar(50) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `game`
--

INSERT INTO
    `game` (`id_game`, `nama_game`)
VALUES (1, 'Mobile Legends'),
    (2, 'PUBG Mobile'),
    (3, 'Free Fire'),
    (4, 'Valorant'),
    (5, 'Genshin Impact'),
    (6, 'Call of Duty Mobile');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
    `id_pembelian` int(11) NOT NULL,
    `id_akun` int(11) DEFAULT NULL,
    `nama_pembeli` varchar(100) DEFAULT NULL,
    `no_wa_pembeli` varchar(20) DEFAULT NULL,
    `tanggal_beli` datetime DEFAULT NULL,
    `status_bayar` enum('sudah', 'belum') DEFAULT 'belum'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan`
--

CREATE TABLE `pengaturan` (
    `id_pengaturan` int(11) NOT NULL,
    `nama_toko` varchar(100) DEFAULT NULL,
    `slogan` varchar(200) DEFAULT NULL,
    `no_wa` varchar(20) DEFAULT NULL,
    `email` varchar(100) DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Dumping data for table `pengaturan`
--

INSERT INTO
    `pengaturan` (
        `id_pengaturan`,
        `nama_toko`,
        `slogan`,
        `no_wa`,
        `email`
    )
VALUES (
        1,
        'ELANG STORE',
        'elang ni boss',
        '081234567890',
        'elangstore@gmail.com'
    );

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin` ADD PRIMARY KEY (`id_admin`);

--
-- Indexes for table `akun`
--
ALTER TABLE `akun`
ADD PRIMARY KEY (`id_akun`),
ADD KEY `id_game` (`id_game`);

--
-- Indexes for table `game`
--
ALTER TABLE `game` ADD PRIMARY KEY (`id_game`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
ADD PRIMARY KEY (`id_pembelian`),
ADD KEY `id_akun` (`id_akun`);

--
-- Indexes for table `pengaturan`
--
ALTER TABLE `pengaturan` ADD PRIMARY KEY (`id_pengaturan`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 2;

--
-- AUTO_INCREMENT for table `akun`
--
ALTER TABLE `akun`
MODIFY `id_akun` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 11;

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
MODIFY `id_game` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 7;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
MODIFY `id_pembelian` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 4;

--
-- AUTO_INCREMENT for table `pengaturan`
--
ALTER TABLE `pengaturan`
MODIFY `id_pengaturan` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `akun`
--
ALTER TABLE `akun`
ADD CONSTRAINT `akun_ibfk_1` FOREIGN KEY (`id_game`) REFERENCES `game` (`id_game`);

--
-- Constraints for table `galeri`
--
ALTER TABLE `galeri`
ADD CONSTRAINT `galeri_ibfk_1` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id_akun`) ON DELETE CASCADE;

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
ADD CONSTRAINT `pembelian_ibfk_1` FOREIGN KEY (`id_akun`) REFERENCES `akun` (`id_akun`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;