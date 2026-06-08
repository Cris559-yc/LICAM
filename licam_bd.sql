-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando datos para la tabla licam_db.cache: ~0 rows (aproximadamente)

-- Volcando datos para la tabla licam_db.cache_locks: ~0 rows (aproximadamente)

-- Volcando datos para la tabla licam_db.categorias: ~8 rows (aproximadamente)
INSERT INTO `categorias` (`id`, `nombre`, `descripcion`, `icono`, `color`, `activo`, `created_at`, `updated_at`) VALUES
	(1, 'Calles y Baches', 'Pavimento dañado, hoyos, agrietamientos en calles y avenidas.', '🚧', '#EF4444', 1, '2026-05-29 13:07:09', '2026-05-29 13:07:09'),
	(2, 'Alumbrado Publico', 'Postes caidos, luminarias dañadas o apagadas.', '💡', '#F59E0B', 1, '2026-05-29 13:07:09', '2026-05-29 13:07:09'),
	(3, 'Basura y Limpieza', 'Zonas llenas de desechos, vertederos clandestinos.', '🗑️', '#10B981', 1, '2026-05-29 13:07:09', '2026-05-29 13:07:09'),
	(4, 'Agua y Drenaje', 'Fugas de agua, tuberias rotas, alcantarillado obstruido.', '💧', '#3B82F6', 1, '2026-05-29 13:07:09', '2026-05-29 13:07:09'),
	(5, 'Parques y Areas Verdes', 'Espacios publicos deteriorados, jardines abandonados.', '🌳', '#8B5CF6', 1, '2026-05-29 13:07:09', '2026-05-29 13:07:09'),
	(6, 'Señalizacion Vial', 'Semaforos, señales de transito dañadas o ausentes.', '🚦', '#EC4899', 1, '2026-05-29 13:07:09', '2026-05-29 13:07:09'),
	(7, 'Obras Publicas', 'Infraestructura municipal en mal estado.', '🏚️', '#06B6D4', 1, '2026-05-29 13:07:09', '2026-05-29 13:07:09'),
	(8, 'Otros', 'Otras incidencias urbanas que no encajan en categorias previas.', '📋', '#64748B', 1, '2026-05-29 13:07:09', '2026-05-29 13:07:09');

-- Volcando datos para la tabla licam_db.comentarios: ~2 rows (aproximadamente)
INSERT INTO `comentarios` (`id`, `reporte_id`, `user_id`, `contenido`, `created_at`, `updated_at`) VALUES
	(1, 4, 1, 'afecta los dos carriles el bache?', '2026-06-07 13:51:29', '2026-06-07 13:51:29'),
	(2, 4, 2, 'sip, esta en ambos carriles', '2026-06-07 14:09:31', '2026-06-07 14:09:31');

-- Volcando datos para la tabla licam_db.failed_jobs: ~0 rows (aproximadamente)

-- Volcando datos para la tabla licam_db.imagenes: ~3 rows (aproximadamente)
INSERT INTO `imagenes` (`id`, `reporte_id`, `url`, `nombre_archivo`, `tamano`, `tipo_mime`, `created_at`, `updated_at`) VALUES
	(1, 3, 'reportes/n16WQCdlj9tlzZn0HrSK3s2gfQ1dx3hQjCw0uWaG.jpg', 'baches.jpg', 221293, 'image/jpeg', '2026-06-07 11:46:39', '2026-06-07 11:46:39'),
	(2, 3, 'reportes/bQd19rJy3M8V1Yiw5PkC5HV2FRDV0tFY7waa4mUe.jpg', 'R.jpg', 1032952, 'image/jpeg', '2026-06-07 11:46:39', '2026-06-07 11:46:39'),
	(3, 4, 'reportes/raQg3yzYZTM2J4An4lLKl2FFOdl8EF1Eh6v4oEDo.jpg', 'R.jpg', 1032952, 'image/jpeg', '2026-06-07 12:46:06', '2026-06-07 12:46:06');

-- Volcando datos para la tabla licam_db.jobs: ~0 rows (aproximadamente)

-- Volcando datos para la tabla licam_db.job_batches: ~0 rows (aproximadamente)

-- Volcando datos para la tabla licam_db.migrations: ~11 rows (aproximadamente)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_05_29_051333_create_roles_table', 1),
	(5, '2026_05_29_051349_create_categorias_table', 1),
	(6, '2026_05_29_051725_add_custom_fields_to_users_table', 1),
	(7, '2026_05_29_051742_create_reportes_table', 1),
	(8, '2026_05_29_051754_create_imagenes_table', 1),
	(9, '2026_05_29_051805_create_seguimientos_table', 1),
	(10, '2026_05_29_051814_create_comentarios_table', 1),
	(11, '2026_05_29_071150_create_personal_access_tokens_table', 2);

-- Volcando datos para la tabla licam_db.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando datos para la tabla licam_db.personal_access_tokens: ~2 rows (aproximadamente)
INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
	(3, 'App\\Models\\User', 2, 'auth_token', '000b192a6c7292554f8b4908d5e5812a22b9acb2402cdbcf91b59923ca0bb96a', '["*"]', '2026-06-01 04:38:46', NULL, '2026-06-01 04:18:59', '2026-06-01 04:38:46'),
	(4, 'App\\Models\\User', 1, 'auth_token', '9f4e29f82076b3e3401b0d6b5b23a9af4b8c9aebc5a0d4bbedbdbcf82afcbcf3', '["*"]', '2026-06-01 04:37:02', NULL, '2026-06-01 04:32:33', '2026-06-01 04:37:02');

-- Volcando datos para la tabla licam_db.reportes: ~2 rows (aproximadamente)
INSERT INTO `reportes` (`id`, `user_id`, `categoria_id`, `titulo`, `descripcion`, `latitud`, `longitud`, `direccion`, `estado`, `prioridad`, `fecha_reporte`, `fecha_resolucion`, `created_at`, `updated_at`) VALUES
	(3, 2, 1, 'baches frente a escuela juan pablo', 'hay diferentes baches en diferentes puntos de la calle de la escuela', 13.41585878, -88.33237253, 'Calle principal camino a escuela juan pablo', 'resuelto', 'baja', '2026-06-07 11:46:38', '2026-06-07 12:07:53', '2026-06-07 11:46:38', '2026-06-07 12:07:53'),
	(4, 2, 1, 'Bache profundo', 'hay un bache super profundo en frente de la alcaldia', 13.41486655, -88.34462102, 'Avenida magistral, enfrente de la alcaldia', 'resuelto', 'baja', '2026-06-07 12:46:06', '2026-06-07 12:47:19', '2026-06-07 12:46:06', '2026-06-07 12:47:19');

-- Volcando datos para la tabla licam_db.roles: ~2 rows (aproximadamente)
INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `created_at`, `updated_at`) VALUES
	(1, 'ciudadano', 'Usuario que puede crear y dar seguimiento a sus reportes ciudadanos.', '2026-05-29 13:07:09', '2026-05-29 13:07:09'),
	(2, 'administrador', 'Personal de la alcaldia que clasifica y gestiona los reportes.', '2026-05-29 13:07:09', '2026-05-29 13:07:09');

-- Volcando datos para la tabla licam_db.seguimientos: ~2 rows (aproximadamente)
INSERT INTO `seguimientos` (`id`, `reporte_id`, `user_id`, `estado_anterior`, `estado_nuevo`, `observacion`, `created_at`, `updated_at`) VALUES
	(4, 3, 1, 'pendiente', 'resuelto', 'Ya enviamos personal para hacer el relleno', '2026-06-07 12:07:53', '2026-06-07 12:07:53'),
	(5, 4, 1, 'pendiente', 'resuelto', 'completado', '2026-06-07 12:47:19', '2026-06-07 12:47:19');

-- Volcando datos para la tabla licam_db.sessions: ~5 rows (aproximadamente)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('02wbyB4s76jDj8qy0myDBnGNYgM2EUkB7Gm4r8qC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'eyJfdG9rZW4iOiJtT25HTHhCcW1lajRFalk5OFZiVmltVHhGS0FBMzN0SlFIV3VGUFUxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780273210),
	('8ewktAaCYb8kuF1UWqpl52iYKHJaGdw6up2sXEnA', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'eyJfdG9rZW4iOiJTVTY0Y3VNSWRuQzlMRm40a2MxTm9wN2ttU0g4Z01YUFJUQ0twME1GIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9pbWdcL2VzY3Vkby1zYW4tam9yZ2UucG5nIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780384346),
	('rayQALmrLUZq5pcNWLh7Jd4tYY9MmjLIX3aBUakx', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'eyJfdG9rZW4iOiJQaWZKYTdMb0VOVk82a3VhOXRraDlsMGhtOVJDZUVhREVPanRSamtmIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780819720),
	('s3MUKbzJWXzmhgqX1EItDtWR11SIgBCNQA6Rl4Z1', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'eyJfdG9rZW4iOiI2NVlpaTlpcDFxSzZ1Q1lwVTNxOUczUmdQazdqc0JiMEV5MEd5c2pwIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9wYWdpbmEtcXVlLW5vLWV4aXN0ZSIsInJvdXRlIjpudWxsfSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1780867784),
	('U5XojJ8ogP4cGAoDEkFo5hVBeSuIvCG5OCT8tWLz', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.121.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', 'eyJfdG9rZW4iOiJ0RFZnd3JKOHhFenBMZnU3Z3VOZnNiT0VKYXFmY1FBS09WMm5PRjRsIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1780259978);

-- Volcando datos para la tabla licam_db.users: ~3 rows (aproximadamente)
INSERT INTO `users` (`id`, `rol_id`, `name`, `apellido`, `dui`, `telefono`, `direccion`, `activo`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 2, 'Ana', 'Ramirez', '01234567-8', '7777-8888', 'Caserio el mogote, San Jorge', 1, 'admin@licam.test', NULL, '$2y$12$/TTpO.ruENZDTOLM6xkUyO8sMqDdllquCt.h1emkwdLILCwzgTNki', NULL, '2026-05-29 13:07:09', '2026-05-29 13:07:09'),
	(2, 1, 'Carlos', 'Mendoza', '12345678-9', '7888-9900', 'Barrio concepcion, San Jorge', 1, 'carlos@licam.test', NULL, '$2y$12$ClXXOA1Im2k2tZKxHceiH.WO7RFEdbD69FSD4LVWYPOJMuhgwDhQS', NULL, '2026-05-29 13:07:09', '2026-05-29 13:07:09'),
	(3, 1, 'Maria', 'Lopez', NULL, '7999-1111', 'San Julian, San Jorge', 1, 'maria@test.com', NULL, '$2y$12$hEvzSHyecyaYmYVII3NC/.57bDa8tIuCiAtI4bXoXotUAd1rLxkuS', NULL, '2026-06-01 03:16:18', '2026-06-01 03:16:18');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
