-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 25, 2026 at 07:52 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pixelcraftslabstudio`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE `contacts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `service` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `created_at`, `updated_at`, `name`, `email`, `subject`, `service`, `message`, `is_read`) VALUES
(1, '2026-03-28 08:41:09', '2026-03-28 08:41:09', 'Mehak Arman', 'mehakarmaan1@gmail.com', 'Test msg', NULL, '!H', 0),
(2, '2026-03-28 08:41:28', '2026-03-28 08:41:28', 'Mehak Arman', 'mehakarmaan1@gmail.com', 'Test msg', NULL, '!H', 0),
(3, '2026-03-28 08:41:56', '2026-04-13 14:36:58', 'Mehak Arman', 'mehakarmaan1@gmail.com', 'Test msg', NULL, '!H', 1),
(4, '2026-03-28 08:47:28', '2026-04-13 14:50:45', 'Mehak Arman', 'mehakarmaan1@gmail.com', 'Test msg 22222222222222', NULL, 'HIIIIIIIIIIIIIIIIII', 1),
(6, '2026-04-25 12:47:22', '2026-04-25 12:47:22', 'Arman', 'i@armansabir.com', NULL, 'Web Development', '676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767676767', 0),
(7, '2026-04-25 13:51:03', '2026-04-25 13:51:03', 'Arman', 'i@armansabir.com', NULL, 'Offline Game Development', 'zdxfcghjkl', 0);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `footer_links`
--

CREATE TABLE `footer_links` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `group_name` varchar(255) NOT NULL DEFAULT 'Quick Links',
  `label` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `footer_links`
--

INSERT INTO `footer_links` (`id`, `group_name`, `label`, `url`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Quick Links', 'Home', '/', 0, 1, NULL, NULL),
(2, 'Quick Links', 'Contact', '/contact', 0, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_02_18_183626_create_services_table', 1),
(5, '2026_02_18_183628_create_contacts_table', 1),
(6, '2026_02_18_183629_create_orders_table', 1),
(7, '2026_02_19_192837_create_portfolios_table', 2),
(8, '2026_02_19_192844_create_messages_table', 2),
(9, '2026_02_23_214851_create_particles_table', 3),
(11, '2026_03_10_113322_create_portfolios_table', 4),
(12, '2026_03_28_123932_update_contacts_table', 5),
(13, '2026_04_12_103952_add_role_to_users_table', 6),
(14, '2026_04_12_103953_create_settings_table', 6),
(15, '2026_04_12_103953_update_contacts_table_for_admin', 6),
(16, '2026_04_12_103954_create_footer_links_table', 6),
(17, '2026_04_12_103954_create_nav_items_table', 6),
(18, '2026_04_12_103954_create_pages_table', 6),
(19, '2026_04_12_103955_create_sections_table', 6),
(20, '2026_04_12_103955_create_testimonials_table', 6),
(21, '2026_04_12_103957_create_team_members_table', 6),
(22, '2026_04_12_105222_drop_particles_table', 7),
(23, '2026_04_13_181556_add_icon_to_services_table', 8);

-- --------------------------------------------------------

--
-- Table structure for table `nav_items`
--

CREATE TABLE `nav_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_button` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nav_items`
--

INSERT INTO `nav_items` (`id`, `label`, `url`, `sort_order`, `is_active`, `is_button`, `created_at`, `updated_at`) VALUES
(1, 'Home', '/', 1, 1, 0, NULL, NULL),
(2, 'About', '/about', 2, 1, 0, NULL, NULL),
(3, 'Services', '/services', 3, 1, 0, NULL, NULL),
(4, 'Portfolio', '/portfolio', 4, 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `page_key` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portfolios`
--

CREATE TABLE `portfolios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `portfolios`
--

INSERT INTO `portfolios` (`id`, `title`, `description`, `image`, `link`, `created_at`, `updated_at`) VALUES
(1, 'Test Project 2', 'Another test project for styling.', 'portfolio/zHAXWRt0ii1DrsyMYIFRUi4X8L9ICPGBFWUhm6QC.png', 'https://pixelcraftslab.com/', '2026-03-10 07:42:40', '2026-04-13 15:08:37'),
(3, 'Test Project 1', 'For styles testing...', 'portfolio/N6FWt7jVh38cWdAtoUkj7RrRtVb16PX4u3z4Ta4H.png', 'https://pixelcraftslab.com/', '2026-04-13 13:37:44', '2026-04-13 15:05:51');

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `page_key` varchar(255) NOT NULL,
  `section_key` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `description`, `created_at`, `updated_at`, `icon`) VALUES
(5, 'Utility App Development', 'We design and develop practical, lightweight applications focused on solving real-world problems.\r\n\r\nFrom productivity tools and calculators to custom-purpose applications, every solution is built with simplicity, speed, and usability in mind. Our goal is to create apps that feel intuitive, perform efficiently, and deliver clear value without unnecessary complexity.\r\n\r\nWhether for personal use, business workflows, or niche tasks, we focus on building tools that people can rely on daily.', '2026-04-25 17:24:40', '2026-04-25 17:24:40', 'app'),
(6, 'Offline Game Development', 'We create engaging offline-friendly games that prioritize performance, accessibility, and user experience.\r\n\r\nThese games are designed to run smoothly without constant internet access, making them ideal for broader usability across devices and environments. We focus on creating interactive, enjoyable experiences that are lightweight, responsive, and easy to pick up.\r\n\r\nFrom simple interactive concepts to more structured gameplay systems, our approach balances creativity with technical efficiency.', '2026-04-25 17:24:40', '2026-04-25 17:24:40', 'game'),
(7, 'Web & Platform Development', 'We build modern websites and supporting platforms that complement your apps, games, or digital presence.\r\n\r\nThis includes landing pages, portfolio sites, and structured platforms that help present your product clearly and professionally. Every build is optimized for performance, responsiveness, and scalability, ensuring it works seamlessly across devices.\r\n\r\nThe focus is always on clean structure, fast loading, and a polished user experience.', '2026-04-25 17:24:40', '2026-04-25 17:24:40', 'web'),
(8, 'UI/UX Design', 'We design user interfaces that are clear, intuitive, and visually balanced.\r\n\r\nOur approach focuses on usability first — ensuring users can navigate your product easily, understand its purpose quickly, and interact with it without friction. We combine layout clarity, visual hierarchy, and modern aesthetics to create interfaces that feel natural and engaging.\r\n\r\nGood design is not just about looks — it’s about how effortlessly users can use your product.', '2026-04-25 17:24:40', '2026-04-25 17:24:40', 'design'),
(9, 'Performance Optimization', 'We improve the speed, responsiveness, and efficiency of your digital product.\r\n\r\nThis includes reducing load times, optimizing assets, refining code structure, and ensuring smooth interaction across devices. The goal is to deliver a seamless experience where everything feels fast, stable, and reliable — especially important for apps and games.', '2026-04-25 17:24:40', '2026-04-25 17:24:40', 'speed'),
(10, 'Maintenance & Support', 'We provide ongoing updates, improvements, and technical support to keep your product running smoothly.\r\n\r\nAs your needs evolve, we help refine features, fix issues, and ensure everything stays aligned with modern standards. This allows your app, game, or platform to remain stable, relevant, and ready for future growth.', '2026-04-25 17:24:40', '2026-04-25 17:24:40', 'support');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
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
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('m9HkzsyBx8j6aNNUON8PK1w0aBdLpDteolAB4wDG', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUFZYRlBhOTVJNnpQcHZVdEw0b09yOVowWEVQVnRLV2RKdW1uMkw5ZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1777139480);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_name` varchar(255) NOT NULL DEFAULT 'PixelCraftsLabStudio',
  `brand_tagline` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `favicon_path` varchar(255) DEFAULT NULL,
  `admin_email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `linked_in` varchar(255) DEFAULT NULL,
  `x` varchar(255) DEFAULT NULL,
  `tiktok` varchar(255) DEFAULT NULL,
  `pinterest` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `whatsapp` varchar(255) DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `brand_tagline`, `logo_path`, `favicon_path`, `admin_email`, `phone`, `location`, `instagram`, `linked_in`, `x`, `tiktok`, `pinterest`, `youtube`, `facebook`, `whatsapp`, `footer_text`, `created_at`, `updated_at`) VALUES
(1, 'PixelCraftsLabStudio', NULL, NULL, NULL, 'admin@pixelcraftslabstudio.com', '+971 56 701 8403', 'Dubai, UAE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-13 14:18:03', '2026-04-13 14:18:03');

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `name`, `role`, `bio`, `image`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Mehak Arman', 'Founder, Creative Director & Digital Marketing Lead', 'Leads the vision, branding, and overall direction of PixelCraftsLabStudio. Oversees creative strategy, digital presence, and audience growth while ensuring every project aligns with a strong, modern brand identity. Combines creativity with strategic thinking to deliver impactful digital experiences.', 'team/22RPejylwi0liOaJqP23dw8rlzLSKGokLYFJyHqv.png', 0, 1, '2026-04-13 14:37:58', '2026-04-13 14:46:15'),
(2, 'Sahil Arman', 'Lead Backend Engineer', 'Heads backend architecture and system development for web and application projects. Specializes in building scalable, secure, and high-performance systems, ensuring every product is robust, efficient, and production-ready.', 'team/Q4e0fZV365WfYaJYIAEIzb9S48InXC1Pfvd3XoAe.png', 0, 1, '2026-04-13 14:38:23', '2026-04-13 14:45:54'),
(3, 'Hamdan Arman', 'Game Developer', 'Designs and develops interactive gameplay systems and immersive experiences. Focuses on performance optimization, game mechanics, and delivering engaging, high-quality gaming solutions.', 'team/QvhfCoo6xE9yDpcCoQASeXG4fLOGk7niVbsfqfXs.png', 0, 1, '2026-04-13 14:38:45', '2026-04-13 14:46:02'),
(4, 'Mobeen Bhalli', 'API & Integration Engineer', 'Handles API development and system integrations, enabling seamless communication between platforms. Ensures smooth data flow, reliable backend connectivity, and efficient integration across all digital products.', 'team/kSsegaCWHBpnOyx7W7eNWy1DGpJeBNpBd5Ax0PtD.png', 0, 1, '2026-04-13 14:39:08', '2026-04-13 14:45:23');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `quote` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `name`, `designation`, `quote`, `image`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(6, 'Mehak Arman', 'Pixel Crafts Lab Studio\'s Admin', 'Be The First To Leave A Review : )', NULL, 1, 1, '2026-04-13 14:57:27', '2026-04-13 14:58:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'test@example.com', '2026-02-18 14:51:29', '$2y$12$S1rGw7VF3MnX4QeKdEWiq.szHaBkpFGEwHn3o8qJ6I21GSh2xAdaK', 'user', '6MG9uFKlw9', '2026-02-18 14:51:29', '2026-02-18 14:51:29'),
(2, 'Admin', 'admin@pixelcraftslabstudio.com', NULL, '$2y$12$9BaPlWJdyO0PwHOtBtHDHOF0/duu4gUACVI2FXAJdD1jVK3KfpKuK', 'admin', NULL, '2026-04-12 09:43:12', '2026-04-13 15:14:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `footer_links`
--
ALTER TABLE `footer_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nav_items`
--
ALTER TABLE `nav_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pages_page_key_unique` (`page_key`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `portfolios`
--
ALTER TABLE `portfolios`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `footer_links`
--
ALTER TABLE `footer_links`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `nav_items`
--
ALTER TABLE `nav_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `portfolios`
--
ALTER TABLE `portfolios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
