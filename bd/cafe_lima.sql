-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-08-2026 a las 01:25:03
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `cafe_lima`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'Desayunos', '2026-07-30 01:52:32', '2026-07-30 01:52:32'),
(2, 'Sánguches', '2026-07-30 01:52:32', '2026-07-30 01:52:32'),
(3, 'Ensaladas', '2026-07-30 01:52:32', '2026-07-30 01:52:32'),
(4, 'Fondos', '2026-07-30 01:52:32', '2026-07-30 01:52:32'),
(5, 'Pizzas', '2026-07-30 01:52:32', '2026-07-30 01:52:32'),
(6, 'Pastas', '2026-07-30 01:52:32', '2026-07-30 01:52:32'),
(7, 'Snacks', '2026-07-30 01:52:32', '2026-07-30 01:52:32'),
(8, 'Postres', '2026-07-30 01:52:32', '2026-07-30 01:52:32'),
(9, 'Bebidas', '2026-07-30 01:52:32', '2026-07-30 01:52:32'),
(11, 'CAFE AMERICANO', '2026-08-12 03:33:06', '2026-08-12 03:33:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
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
-- Estructura de tabla para la tabla `jobs`
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
-- Estructura de tabla para la tabla `job_batches`
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
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_07_29_174218_create_categories_table', 1),
(5, '2026_07_29_174225_create_products_table', 1),
(6, '2026_07_29_174232_create_product_status_histories_table', 1),
(7, '2026_07_29_184328_add_rol_to_users_table', 1),
(8, '2026_07_30_055435_add_destacado_to_products_table', 2),
(9, '2026_08_09_213634_create_product_consumptions_table', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('sabigailsolis25@gmail.com', '$2y$12$JxBhYUz79Yj3DENxiysvH.9ctTRMKauvh781Boloriw.dBgcqlZ5y', '2026-08-12 19:34:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `estado` tinyint(4) NOT NULL DEFAULT 1,
  `destacado` tinyint(1) NOT NULL DEFAULT 0,
  `observacion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `category_id`, `nombre`, `estado`, `destacado`, `observacion`, `created_at`, `updated_at`) VALUES
(3, 9, 'Cappuccino', 3, 1, 'café listo', '2026-07-30 03:54:29', '2026-08-12 03:33:52'),
(4, 8, 'Cheesecake', 3, 0, 'postres listos', '2026-07-30 03:55:07', '2026-08-10 02:09:13'),
(5, 5, 'Pizza xavi', 2, 0, 'pizza agotado', '2026-07-30 03:55:47', '2026-08-10 02:02:43'),
(6, 9, 'Café americano', 1, 1, 'rico', '2026-07-31 03:46:54', '2026-07-31 03:46:54'),
(7, 2, 'Pan con pollo', 3, 1, 'sale con mayonesa', '2026-08-10 02:02:14', '2026-08-10 02:02:22'),
(8, 7, 'Tequeños', 1, 0, 'con salsa de palta', '2026-08-10 02:06:40', '2026-08-10 02:06:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_consumptions`
--

CREATE TABLE `product_consumptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `consumed_at` datetime NOT NULL,
  `observation` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `product_consumptions`
--

INSERT INTO `product_consumptions` (`id`, `product_id`, `user_id`, `quantity`, `consumed_at`, `observation`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 1, '2026-08-09 17:42:00', 'venta registrada', '2026-08-10 03:42:58', '2026-08-10 03:42:58'),
(2, 6, 1, 2, '2026-08-10 12:15:00', 'sin azucar', '2026-08-09 23:11:44', '2026-08-09 23:11:44'),
(3, 4, 1, 3, '2026-08-09 18:37:00', 'cucharita adicional', '2026-08-09 23:37:59', '2026-08-09 23:37:59'),
(4, 6, 1, 2, '2026-08-10 22:36:00', 'con crema de leche', '2026-08-10 01:37:16', '2026-08-10 01:37:16'),
(5, 4, 1, 4, '2026-08-10 23:37:00', 'de freza', '2026-08-10 01:39:15', '2026-08-10 01:39:15'),
(6, 6, 1, 6, '2026-08-09 21:39:00', 'azúcar adicional', '2026-08-10 01:40:41', '2026-08-10 01:40:41'),
(7, 8, 1, 2, '2026-08-09 16:05:00', 'con mucho queso', '2026-08-10 02:07:44', '2026-08-10 02:07:44'),
(8, 7, 1, 1, '2026-08-09 20:10:00', 'con lechuga', '2026-08-10 02:08:14', '2026-08-10 02:08:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_status_histories`
--

CREATE TABLE `product_status_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `estado_anterior` tinyint(4) NOT NULL,
  `estado_nuevo` tinyint(4) NOT NULL,
  `observacion` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `product_status_histories`
--

INSERT INTO `product_status_histories` (`id`, `product_id`, `user_id`, `estado_anterior`, `estado_nuevo`, `observacion`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 2, 3, 'café listo', '2026-07-30 04:53:54', '2026-07-30 04:53:54'),
(2, 4, 1, 3, 1, 'postres listos', '2026-07-30 04:53:58', '2026-07-30 04:53:58'),
(3, 5, 1, 1, 2, 'pizza agotado', '2026-07-30 04:54:01', '2026-07-30 04:54:01'),
(4, 3, 1, 3, 1, 'café listo', '2026-07-30 05:12:10', '2026-07-30 05:12:10'),
(5, 5, 1, 2, 3, 'pizza agotado', '2026-07-30 05:12:25', '2026-07-30 05:12:25'),
(6, 5, 1, 3, 2, 'pizza agotado', '2026-07-30 07:08:47', '2026-07-30 07:08:47'),
(7, 5, 3, 2, 1, 'pizza agotado', '2026-07-30 09:00:57', '2026-07-30 09:00:57'),
(8, 3, 1, 1, 2, 'café listo', '2026-07-30 09:35:22', '2026-07-30 09:35:22'),
(9, 4, 1, 1, 2, 'postres listos', '2026-07-30 11:36:27', '2026-07-30 11:36:27'),
(10, 3, 1, 2, 3, 'café listo', '2026-07-31 03:45:57', '2026-07-31 03:45:57'),
(11, 5, 1, 1, 3, 'pizza agotado', '2026-07-31 03:46:14', '2026-07-31 03:46:14'),
(12, 4, 1, 2, 1, 'postres listos', '2026-07-31 03:46:22', '2026-07-31 03:46:22'),
(13, 4, 1, 1, 2, 'postres listos', '2026-07-31 03:48:02', '2026-07-31 03:48:02'),
(14, 4, 1, 2, 1, 'postres listos', '2026-08-09 23:36:55', '2026-08-09 23:36:55'),
(15, 3, 1, 3, 2, 'café listo', '2026-08-10 01:36:36', '2026-08-10 01:36:36'),
(16, 7, 1, 1, 3, 'sale con mayonesa', '2026-08-10 02:02:22', '2026-08-10 02:02:22'),
(17, 5, 1, 3, 2, 'pizza agotado', '2026-08-10 02:02:43', '2026-08-10 02:02:43'),
(18, 4, 1, 1, 3, 'postres listos', '2026-08-10 02:09:14', '2026-08-10 02:09:14'),
(19, 3, 1, 2, 1, 'café listo', '2026-08-12 03:33:44', '2026-08-12 03:33:44'),
(20, 3, 1, 1, 2, 'café listo', '2026-08-12 03:33:48', '2026-08-12 03:33:48'),
(21, 3, 1, 2, 3, 'café listo', '2026-08-12 03:33:52', '2026-08-12 03:33:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sessions`
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
-- Volcado de datos para la tabla `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6uYFkHRdkeJWafF6HcJIMcEPSG8GfuO5uetJs3tV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36 Edg/151.0.0.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWWNETkphUkRwc3dYUXAyYmJvNjNhOGZQbzhFNmlHZExkQXJvN0pPeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1786663367),
('g1AbHeknZZ3LlyEy1lq1TWd5cjPtJmF3CVWmMZx9', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/151.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUDBtbW5EcklLdDVsVGh2RHdqTHgyV0VrUGlDVVBmVWhyTlRtTHV0eSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fX0=', 1786662324);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `rol` tinyint(3) UNSIGNED NOT NULL DEFAULT 3,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `rol`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Abigail Solis', 'sabigailsolis25@gmail.com', NULL, '$2y$12$C.vYp0LtHZA4c6EakSd6Be7zaxX6sgsSKa2nso1z5lObi.XmjW.nW', 1, '5nBexdTQlMmva5vSDsl8lKxMxLNHwoS5J0lyhCI5VGMcOIF7U7xtThI6SjVi', '2026-07-30 01:53:29', '2026-07-31 04:43:27'),
(3, 'Suhamy Zarate', 'suhamyzarate15@gmail.com', NULL, '$2y$12$cyyIA.j/0j.2li9t/MPMzujr7Y9orh82Bh9nqCh23a8tqITFzfMKu', 2, 'rPOPALxVINJ15wFwVm6MdJSCBAW2Z6TKKqxfSD2iQCBYJBZgH0Fq7wqTeO79', '2026-07-30 08:52:31', '2026-07-31 04:48:10'),
(5, 'Yrsa Cueto', 'yrsacueto20@gmail.com', NULL, '$2y$12$IJJzmFVzyahXa7SSVcV84eI1m8jIJVrphQWRgpuYIlrtHZQGgW8mu', 3, NULL, '2026-07-30 09:51:11', '2026-07-30 09:51:11'),
(6, 'admin', 'admin@cafelima.test', NULL, '$2y$12$.fiMGtpa3IFsm0TF2pSdJ.IQprM4odqwdCMsIeQhbCnkf/e.XaBBq', 1, NULL, '2026-08-13 23:02:37', '2026-08-13 23:02:37'),
(7, 'cocina', 'cocina@cafelima.test', NULL, '$2y$12$4BN7HJPnGB4ythoV8BFd4uo5jLwARYQzhy8fSBlwMK.tvmi6o5Ddq', 2, NULL, '2026-08-13 23:03:56', '2026-08-13 23:03:56'),
(8, 'atencion', 'atencion@cafelima.test', NULL, '$2y$12$T1AYuzbZEhg3K4i3TwG0Qufo5AWNHl0QMnbz4DC.dLEaYLhv/l/Ry', 3, NULL, '2026-08-13 23:04:28', '2026-08-13 23:04:28');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_nombre_unique` (`nombre`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`);

--
-- Indices de la tabla `product_consumptions`
--
ALTER TABLE `product_consumptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_consumptions_product_id_foreign` (`product_id`),
  ADD KEY `product_consumptions_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `product_status_histories`
--
ALTER TABLE `product_status_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_status_histories_product_id_foreign` (`product_id`),
  ADD KEY `product_status_histories_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `product_consumptions`
--
ALTER TABLE `product_consumptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `product_status_histories`
--
ALTER TABLE `product_status_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `product_consumptions`
--
ALTER TABLE `product_consumptions`
  ADD CONSTRAINT `product_consumptions_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_consumptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `product_status_histories`
--
ALTER TABLE `product_status_histories`
  ADD CONSTRAINT `product_status_histories_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `product_status_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
