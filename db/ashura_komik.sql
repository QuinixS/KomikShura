-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Bulan Mei 2025 pada 17.49
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ashura_komik`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `manga_title` varchar(255) NOT NULL,
  `manga_image` varchar(255) NOT NULL,
  `manga_link` varchar(255) DEFAULT NULL,
  `manga_genre` varchar(255) NOT NULL,
  `chapter_info` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bookmarks`
--

INSERT INTO `bookmarks` (`id`, `user_email`, `manga_title`, `manga_image`, `manga_link`, `manga_genre`, `chapter_info`, `created_at`) VALUES
(107, 'doniarman13@gmail.com', 'Sousou no Frieren', 'assets/Frieren.jpg', 'mangas/Frieren_Chapter/Frieren_front.php', 'Adventure, Fantasy', 'Chapter 112', '2025-05-03 08:50:57'),
(108, 'doniarman13@gmail.com', 'One Punch Man', './assets/Onepunch.jpg', 'mangas/Vagabond_Chapter/Vagabond_front.php', 'Action, Comedy, Super Power, Supernatural, Seinen', 'Chapter 211, Chapter 210', '2025-05-03 08:51:00'),
(109, 'doniarman13@gmail.com', 'The Fragrant Flower Blooms with Dignity', 'assets/Flower.jpg', 'mangas/Flower_Chapter/Flower_Front.php', 'Action, Comedy, Super Power, Supernatural, Seinen', 'Chapter 211, Chapter 210', '2025-05-03 08:52:59'),
(144, 'doniarman13@gmail.com', 'Damedol to Sekai ni Hitori Dake no Fan', 'assets/idol.jpg', 'mangas/Idol_Chapter/Idol_front.php', 'Drama, Romance, Comedy', 'Urumin is an idol, but she can\'t sing or dance well, and she\'s got a dishonest character. Kimiya is the only fan in the world who likes her.', '2025-05-04 15:41:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Xeec', 'doniarman13@gmail.com', '$2y$10$IbpseAhEGvKKSgvoA9RvuOPzsRp8g0VC76rcaiMx92TeG59WqYxga', '2025-04-20 09:33:21'),
(2, 'Makina', 'bruhzoone69@gmail.com', '$2y$10$b27U.ZtWEyP3oxHazP4gFOKmjDIgVUAhzJDKZ1IiSJ4QDcYvzz4he', '2025-05-03 08:53:33');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
