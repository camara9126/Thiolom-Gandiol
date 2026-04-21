-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 24 mars 2026 à 14:34
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `quincaillerie`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

CREATE TABLE `articles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nom` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `gal_1` varchar(255) DEFAULT NULL,
  `gal_2` varchar(255) DEFAULT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT 1,
  `categorie_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `stock_min` int(11) NOT NULL DEFAULT 5,
  `etiquette` enum('promo','nouveau','vedette') DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `nom`, `slug`, `description`, `image`, `gal_1`, `gal_2`, `statut`, `categorie_id`, `created_at`, `updated_at`, `stock`, `stock_min`, `etiquette`, `prix`) VALUES
(1, 'pompe a eau', 'pompe-a-eau', 'pompe a eau tres puissant', 'imgArticles/1773842822WhatsApp Image 2025-12-22 at 12.37.15_5edb6335.jpg', 'C:\\Users\\user\\AppData\\Local\\Temp\\phpABFD.tmp', NULL, 1, 2, '2026-03-18 13:07:02', '2026-03-18 13:07:02', 30, 5, 'promo', 500000.00),
(2, 'Échelle télescopique', 'echelle-telescopique', 'Échelle télescopique dur a base d\'aluminium', 'imgArticles/1774261167echelle-aluminium.jpg', NULL, 'C:\\Users\\user\\AppData\\Local\\Temp\\phpDFF0.tmp', 1, 7, '2026-03-18 13:20:22', '2026-03-23 09:19:27', 50, 5, 'nouveau', 70000.00),
(7, 'Marteau de charpentier Stanley', 'marteau-de-charpentier-stanley', 'Marteau de charpentier Stanley', 'imgArticles/1774261516marteau-stanley.jpg', NULL, NULL, 1, 6, '2026-03-23 09:25:16', '2026-03-23 09:25:16', 50, 5, 'nouveau', 7500.00),
(8, 'Perceuse Bosch Professional', 'perceuse-bosch-professional', 'Perceuse Bosch Professional', 'imgArticles/1774261613Copie de Perceuse.jpg', NULL, NULL, 1, 6, '2026-03-23 09:26:53', '2026-03-23 09:26:53', 50, 5, 'promo', 85000.00),
(9, 'Pompe à eau 1.5CV', 'pompe-a-eau-15cv', 'Pompe à eau 1.5CV', 'imgArticles/1774261738pompe-eau.jpg', NULL, NULL, 1, 2, '2026-03-23 09:28:58', '2026-03-23 09:28:58', 40, 5, 'nouveau', 75000.00),
(10, 'scie cieculaire', 'scie-cieculaire', 'scie cieculaire', 'imgArticles/1774263245scie-circulaire.jpg', NULL, NULL, 0, 6, '2026-03-23 09:54:05', '2026-03-23 09:54:05', 50, 5, 'nouveau', 145000.00),
(11, 'Peinture Dulux Mate 10L', 'peinture-dulux-mate-10l', 'Peinture Dulux Mate 10L', 'imgArticles/1774263469peinture-akzo.jpg', NULL, NULL, 1, 9, '2026-03-23 09:57:49', '2026-03-23 09:57:49', 45, 5, 'nouveau', 25000.00),
(12, 'ciment sococim', 'ciment-sococim', 'ciment sococim', 'imgArticles/1774263561ciment.png', NULL, NULL, 1, 7, '2026-03-23 09:59:21', '2026-03-23 09:59:21', 50, 5, 'nouveau', 3500.00),
(13, 'ciment dangote', 'ciment-dangote', 'ciment dangote', 'imgArticles/1774263610ciment-dangote.jpg', NULL, NULL, 1, 7, '2026-03-23 10:00:10', '2026-03-23 10:00:10', 50, 5, 'nouveau', 3500.00),
(14, 'Fer à béton HA12 (barre 12m)', 'fer-a-beton-ha12-barre-12m', 'Fer à béton HA12 (barre 12m)', 'imgArticles/1774267431fer-a-beton.jpg', NULL, NULL, 1, 7, '2026-03-23 11:03:51', '2026-03-23 11:03:51', 50, 5, NULL, 6900.00),
(15, 'Visseuse Bosch', 'visseuse-bosch', 'Visseuse Bosch', 'imgArticles/1774267665imagesperceuse-bosch.jpg', NULL, NULL, 1, 7, '2026-03-23 11:07:45', '2026-03-23 11:07:45', 40, 5, NULL, 120000.00),
(16, 'parpaing creux', 'parpaing-creux', 'parpaing creux', 'imgArticles/1774267772parpaing.jpg', NULL, NULL, 1, 7, '2026-03-23 11:09:32', '2026-03-23 11:09:32', 50, 5, NULL, 500.00),
(17, 'Meuleuse Dewalt 125mm', 'meuleuse-dewalt-125mm', 'Meuleuse Dewalt 125mm', 'imgArticles/1774267843meuleuse-dewalt.jpg', NULL, NULL, 0, 7, '2026-03-23 11:10:43', '2026-03-24 09:27:49', 30, 5, NULL, 65000.00),
(18, 'brouette', 'brouette', 'brouette', 'imgArticles/1774267921brouette.jpg', NULL, NULL, 0, 7, '2026-03-23 11:12:01', '2026-03-24 09:26:12', 40, 5, NULL, 30000.00);

-- --------------------------------------------------------

--
-- Structure de la table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`, `slug`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Électricité', 'electricite', 'categorie Électricité', 'imgCategories/1773838224Logo for BCM-GROUPE IT Services Company.png', '2026-03-18 11:50:24', '2026-03-18 11:50:24'),
(2, 'Plomberie', 'plomberie', 'categorie Plomberie', 'imgCategories/1773838726Logo for BCM-GROUPE IT Services Company.png', '2026-03-18 11:58:46', '2026-03-18 11:58:46'),
(3, 'Menuiserie', 'menuiserie', 'categorie Menuiserie', 'imgCategories/1773838802Logo for BCM-GROUPE IT Services Company.png', '2026-03-18 12:00:02', '2026-03-18 12:00:02'),
(4, 'Irrigation', 'irrigation', 'categorie Irrigation', 'imgCategories/1773838940Logo for BCM-GROUPE IT Services Company.png', '2026-03-18 12:02:20', '2026-03-18 12:02:20'),
(5, 'Plomberie sanitaire', 'plomberie-sanitaire', 'categorie Plomberie sanitaire', 'imgCategories/1773838967Logo for BCM-GROUPE IT Services Company.png', '2026-03-18 12:02:47', '2026-03-18 12:02:47'),
(6, 'Outillage', 'outillage', 'categorie outillage', 'imgCategories/1773841691Logo for BCM-GROUPE IT Services Company.png', '2026-03-18 12:48:11', '2026-03-18 12:48:11'),
(7, 'Materiaux', 'materiaux', NULL, 'imgCategories/1773841817Logo for BCM-GROUPE IT Services Company.png', '2026-03-18 12:50:17', '2026-03-18 12:50:17'),
(9, 'Peinture', 'peinture', 'Peinture', 'imgCategories/1774263420Logo for BCM-GROUPE IT Services Company.png', '2026-03-23 09:57:00', '2026-03-23 09:57:00');

-- --------------------------------------------------------

--
-- Structure de la table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_17_134004_create_categorie_table', 2),
(5, '2026_03_18_130639_add_column_to_articles_table', 3),
(6, '2026_03_18_130811_add_column_to_articles_table', 3),
(7, '2026_03_18_134127_add_column_to_articles_table', 4),
(8, '2026_03_18_135728_add_column_to_articles_table', 5);

-- --------------------------------------------------------

--
-- Structure de la table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('j1bVz9SUi7I9j8LTLpSLydJfH1ChafZQ5NLbd5W6', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoicExuZXUweW4yQUlZMjgwWk5DZlF6VkNpeXBYUGFPVUxTR2c3cUl1SCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwNC9jb250YWN0IjtzOjU6InJvdXRlIjtzOjc6ImNvbnRhY3QiO31zOjM6InVybCI7YTowOnt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1774359110);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'amadou camara', 'ecobusiness@gmail.com', NULL, '$2y$12$DhrAtwhXfuiJkyaEMYtiSuBMVfOl0SG2jMLaRoAhUV8BMDBz6mAwu', NULL, '2026-03-17 10:00:35', '2026-03-17 10:00:35');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Index pour la table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Index pour la table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Index pour la table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Index pour la table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
