-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 04 Feb 2026 pada 17.11
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_kasir`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Makanan', '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(2, 'Minuman', '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(3, 'Snack', '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(4, 'Alat Tulis', '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(5, 'Elektronik', '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(6, 'Kesehatan', '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(7, 'Kosmetik', '2026-02-04 13:25:51', '2026-02-04 13:25:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `name`, `phone`, `created_at`, `updated_at`) VALUES
(2, 'Budi Santoso', '081234567890', '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(3, 'Siti Aminah', '082345678901', '2026-01-18 16:05:21', '2026-01-18 16:05:21');

-- --------------------------------------------------------

--
-- Struktur dari tabel `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `method` enum('cash','debit','credit','e-wallet','transfer') NOT NULL,
  `amount` float NOT NULL,
  `cash` float DEFAULT NULL,
  `change` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `payments`
--

INSERT INTO `payments` (`id`, `transaction_id`, `method`, `amount`, `cash`, `change`, `created_at`, `updated_at`) VALUES
(1, 2, 'cash', 35000, 50000, 15000, '2026-02-02 13:43:42', '2026-02-02 13:43:42'),
(2, 3, 'cash', 44000, 50000, 6000, '2026-02-04 15:29:31', '2026-02-04 15:29:31'),
(3, 4, 'cash', 109000, 120000, 11000, '2026-02-04 15:47:55', '2026-02-04 15:47:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `price` float NOT NULL DEFAULT 0,
  `cost` float NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `category_id`, `barcode`, `name`, `price`, `cost`, `stock`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, '8992761111111', 'Indomie Goreng', 3500, 2800, 96, 1, '2026-01-18 16:05:21', '2026-02-04 15:47:55'),
(2, 1, '8992761222222', 'Indomie Soto', 3500, 2800, 80, 1, '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(3, 2, '8992771333333', 'Aqua 600ml', 4000, 3200, 150, 1, '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(4, 2, '8992771444444', 'Teh Botol Sosro', 5000, 4000, 113, 1, '2026-01-18 16:05:21', '2026-02-02 13:43:42'),
(5, 3, '8992781555555', 'Chitato Rasa Sapi Panggang', 10000, 8500, 47, 1, '2026-01-18 16:05:21', '2026-02-04 15:47:55'),
(6, 3, '8992781666666', 'Taro Net Rumput Laut', 8000, 6500, 60, 1, '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(7, 4, '8992791777777', 'Pensil 2B Staedtler', 2500, 2000, 200, 1, '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(9, 5, '8992801999999', 'Baterai AA Alkaline', 15000, 12000, 70, 1, '2026-01-18 16:05:21', '2026-02-04 15:47:55'),
(10, 6, '8992802000000', 'Masker Medis Sensi', 25000, 20000, 100, 1, '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(11, 2, '2208768045354', 'Galon Le minerale', 22000, 20000, 98, 1, '2026-02-04 14:00:10', '2026-02-04 15:29:31');

-- --------------------------------------------------------

--
-- Struktur dari tabel `stock_logs`
--

CREATE TABLE `stock_logs` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `type` enum('in','out','adjustment') NOT NULL,
  `reference` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `stock_logs`
--

INSERT INTO `stock_logs` (`id`, `product_id`, `qty`, `type`, `reference`, `notes`, `created_at`) VALUES
(1, 4, -7, 'out', 'TRX-20260202144342-8adc3f', 'Sale', '2026-02-02 13:43:42');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `transaction_code` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `transaction_date` datetime NOT NULL DEFAULT current_timestamp(),
  `subtotal` float NOT NULL DEFAULT 0,
  `discount` float NOT NULL DEFAULT 0,
  `tax` float NOT NULL DEFAULT 0,
  `total` float NOT NULL DEFAULT 0,
  `status` enum('pending','selesai','batal') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `transaction_code`, `user_id`, `customer_id`, `transaction_date`, `subtotal`, `discount`, `tax`, `total`, `status`, `created_at`, `updated_at`) VALUES
(2, 'TRX-20260202144342-8adc3f', 1, NULL, '2026-02-02 14:43:42', 35000, 0, 0, 35000, 'selesai', '2026-02-02 13:43:42', '2026-02-02 13:43:42'),
(3, 'TRX-20260204-001', 1, NULL, '2026-02-04 22:29:31', 44000, 0, 0, 44000, 'selesai', '2026-02-04 15:29:31', '2026-02-04 15:29:31'),
(4, 'TRX-20260204-002', 1, 2, '2026-02-04 22:47:55', 119000, 10000, 0, 109000, 'selesai', '2026-02-04 15:47:55', '2026-02-04 15:47:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` float NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `discount` float NOT NULL DEFAULT 0,
  `total` float NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `transaction_items`
--

INSERT INTO `transaction_items` (`id`, `transaction_id`, `product_id`, `price`, `qty`, `discount`, `total`, `created_at`, `updated_at`) VALUES
(1, 2, 4, 5000, 7, 0, 35000, '2026-02-02 13:43:42', '2026-02-02 13:43:42'),
(2, 3, 11, 22000, 2, 0, 44000, '2026-02-04 15:29:31', '2026-02-04 15:29:31'),
(3, 4, 9, 15000, 5, 0, 75000, '2026-02-04 15:47:55', '2026-02-04 15:47:55'),
(4, 4, 5, 10000, 3, 0, 30000, '2026-02-04 15:47:55', '2026-02-04 15:47:55'),
(5, 4, 1, 3500, 4, 0, 14000, '2026-02-04 15:47:55', '2026-02-04 15:47:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir','pemilik') NOT NULL DEFAULT 'kasir',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Administrator', 'admin', '$2y$10$bsYWxuVo7UQccfnFVsnd8.HesK5wfKgZvrEx8GR7gnn2Qr8ZKbK7a', 'admin', '2026-01-18 16:05:21', '2026-01-18 16:23:54'),
(2, 'Kasir 1', 'kasir1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'kasir', '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(3, 'Pemilik Toko', 'pemilik', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pemilik', '2026-01-18 16:05:21', '2026-01-18 16:05:21'),
(4, 'sir kasir', 'sirkasir', '$2y$10$uyF3JKoPW.ja4TfXOgSQfOT5b8QzwlcROhio03c6AO.kSsfHRM6nG', 'kasir', '2026-02-04 14:23:05', '2026-02-04 14:23:26');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_phone` (`phone`);

--
-- Indeks untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transaction` (`transaction_id`),
  ADD KEY `idx_method` (`method`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `idx_barcode` (`barcode`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indeks untuk tabel `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_product` (`product_id`),
  ADD KEY `idx_type` (`type`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_code` (`transaction_code`),
  ADD KEY `idx_transaction_code` (`transaction_code`),
  ADD KEY `idx_transaction_date` (`transaction_date`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_customer` (`customer_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indeks untuk tabel `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transaction` (`transaction_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_role` (`role`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `stock_logs`
--
ALTER TABLE `stock_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `stock_logs`
--
ALTER TABLE `stock_logs`
  ADD CONSTRAINT `stock_logs_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `transaction_items_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transaction_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
