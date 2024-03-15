-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.0.30 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Volcando estructura para tabla coepci.concursos
CREATE TABLE IF NOT EXISTS `concursos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fechaIni1ronda` date DEFAULT NULL,
  `fechaIni2ronda` date DEFAULT NULL,
  `fechaFin` date DEFAULT NULL,
  `comentario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `id_depen` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.concursos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla coepci.configuracion
CREATE TABLE IF NOT EXISTS `configuracion` (
  `id` int NOT NULL,
  `veda` int NOT NULL DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla coepci.configuracion: ~1 rows (aproximadamente)
INSERT IGNORE INTO `configuracion` (`id`, `veda`, `updated_at`) VALUES
	(1, 0, '2024-03-15 03:32:29');

-- Volcando estructura para tabla coepci.dependencias
CREATE TABLE IF NOT EXISTS `dependencias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descripcion` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `created_at` timestamp NOT NULL,
  `updated_at` timestamp NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla coepci.dependencias: ~75 rows (aproximadamente)
INSERT IGNORE INTO `dependencias` (`id`, `descripcion`, `created_at`, `updated_at`) VALUES
	(1, 'Administración del Patrimonio de la Beneficencia Pública del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(2, 'Administración Portuaria Integral', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(3, 'Agencia de Proyectos Estratégicos del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(4, 'Archivo General Del Estado De Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(5, 'Centro de Conciliación Laboral del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(6, 'Centro De Estudios De Bachillerato Técnico "Eva Sámano De López Mateos"', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(7, 'Centro de Evaluación del Desempeño del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(8, 'Centro de Evaluación y Control de Confianza', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(9, 'Centro Estatal de Evaluación y Control de Confianza', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(10, 'Colegio de Bachilleres del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(11, 'Colegio de Educación Profesional Técnica del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(12, 'Colegio de Estudios Científicos y Tecnológicos del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(13, 'Comisión de Agua Potable y Alcantarillado', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(14, 'Comisión de Búsqueda de Personas del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(15, 'Comisión Ejecutiva de Atención a Víctimas del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(16, 'Comisión Estatal De Mejora Regulatoria', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(17, 'Comisión para la Juventud y el Deporte de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(18, 'Consejería Jurídica del Poder Ejecutivo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(19, 'Consejo de Promoción Turística de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(20, 'Consejo Quintanarroense de Ciencia y Tecnología', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(21, 'Coordinación Estatal de Protección Civil de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(22, 'Coordinación General de Comunicación del Gobierno del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(23, 'Despacho de la Gobernadora del Estado (Secretaría Particular)', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(24, 'Fundación de Parques y Museos de Cozumel', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(25, 'Instituto de Biodiversidad y Áreas Naturales Protegidas del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(26, 'Instituto de Capacitación para el Trabajo del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(27, 'Instituto de Infraestructura Física Educativa del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(28, 'Instituto de la Cultura y las Artes de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(29, 'Instituto de Movilidad de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(30, 'Instituto Estatal Para La Educación De Jóvenes Y Adultos', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(31, 'Instituto Geográfico y Catastral del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(32, 'Instituto para el Desarrollo del Pueblo Maya y las Comunidades Indígenas del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(33, 'Instituto para el Desarrollo y Financiamiento del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(34, 'Instituto Quintanarroense de Innovación y Tecnología', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(35, 'Instituto Quintanarroense de la Juventud', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(36, 'Instituto Quintanarroense de la Mujer', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(37, 'Instituto Tecnológico Superior de Felipe Carrillo Puerto', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(38, 'Junta de Asistencia Social Privada de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(39, 'Oficialia Mayor', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(40, 'Procuraduría de Protección al Ambiente del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(41, 'Procuraduría Fiscal del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(42, 'Registro Público de la Propiedad y del Comercio del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(43, 'Representación del Gobierno del Estado en la Ciudad de México, Distrito Federal', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(44, 'Secretaría De Desarrollo Agropecuario, Rural Y Pesca', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(45, 'Secretaría De Desarrollo Económico', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(46, 'Secretaría De Desarrollo Social', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(47, 'Secretaría de Desarrollo Territorial Urbano Sustentable', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(48, 'Secretaría de Ecología y Medio Ambiente', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(49, 'Secretaría de Educación', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(50, 'Secretaría de Finanzas y Planeación', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(51, 'Secretaría de Gobierno', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(52, 'Secretaria de la Contraloría', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(53, 'Secretaría De Obras Públicas', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(54, 'Secretaría de Salud', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(55, 'Secretaría de Seguridad Ciudadana', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(56, 'Secretaría de Turismo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(57, 'Secretaría Del Trabajo Y Previsión Social', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(58, 'Secretaría Ejecutiva del Sistema Anticorrupción del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(59, 'Secretaría Ejecutiva del Sistema Estatal de Protección de los Derechos de Niñas, Niños y Adolescentes', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(60, 'Secretariado Ejecutivo del Sistema Estatal de Seguridad Pública', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(61, 'Servicio de Administración Tributaria del Estado de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(62, 'Servicio Estatal del Empleo y Capacitación para el Trabajo de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(63, 'Servicios Educativos de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(64, 'Servicios Estatales de Salud', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(65, 'Sistema para el Desarrollo Integral de la Familia', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(66, 'Sistema Quintanarroense de Comunicación Social', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(67, 'Universidad del Caribe', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(68, 'Universidad Intercultural de la Zona Maya de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(69, 'Universidad Politécnica de Bacalar', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(70, 'Universidad Politécnica de Quintana Roo', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(71, 'Universidad Tecnológica Chetumal', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(72, 'Universidad Tecnológica de Cancún', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(73, 'Universidad Tecnológica de la Riviera Maya', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(74, 'Universidad Tecnologica de Tulum', '2024-02-29 01:19:00', '2024-02-29 01:19:00'),
	(75, 'VIP Servicios Aéreos Ejecutivos', '2024-02-29 01:19:00', '2024-02-29 01:19:00');

-- Volcando estructura para tabla coepci.empleados
CREATE TABLE IF NOT EXISTS `empleados` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_grup` bigint unsigned NOT NULL,
  `curp` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_paterno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_materno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_depen` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `empleados_id_grup_foreign` (`id_grup`),
  CONSTRAINT `empleados_id_grup_foreign` FOREIGN KEY (`id_grup`) REFERENCES `grupos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=285 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.empleados: ~0 rows (aproximadamente)

-- Volcando estructura para tabla coepci.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.failed_jobs: ~0 rows (aproximadamente)

-- Volcando estructura para tabla coepci.ganadores
CREATE TABLE IF NOT EXISTS `ganadores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_conc` bigint unsigned NOT NULL,
  `id_grup` bigint unsigned NOT NULL,
  `id_emp` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.ganadores: ~0 rows (aproximadamente)

-- Volcando estructura para tabla coepci.grupos
CREATE TABLE IF NOT EXISTS `grupos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `grupo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.grupos: ~3 rows (aproximadamente)
INSERT IGNORE INTO `grupos` (`id`, `grupo`, `created_at`, `updated_at`) VALUES
	(1, 'Grupo 1 - Niveles Altos', '2024-01-30 16:15:25', '2024-01-30 16:15:26'),
	(2, 'Grupo 2 - Mandos Medios', '2024-01-30 16:23:24', '2024-01-30 16:23:25'),
	(3, 'Grupo 3 - Personal General', '2024-01-30 16:23:43', '2024-01-30 16:23:44');

-- Volcando estructura para tabla coepci.historico_votos
CREATE TABLE IF NOT EXISTS `historico_votos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `id_grup` int NOT NULL,
  `id_conc` int NOT NULL,
  `ronda` int NOT NULL,
  `novotos` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla coepci.historico_votos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla coepci.imagenes
CREATE TABLE IF NOT EXISTS `imagenes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` mediumtext COLLATE utf8mb4_spanish2_ci NOT NULL,
  `ruta` mediumtext COLLATE utf8mb4_spanish2_ci NOT NULL,
  `tipo` varchar(50) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla coepci.imagenes: ~2 rows (aproximadamente)
INSERT IGNORE INTO `imagenes` (`id`, `nombre`, `ruta`, `tipo`, `estado`, `created_at`, `updated_at`) VALUES
	(1, 'LogoSecoes', '/assets/img/logo-2.png', 'logo', 1, NULL, '2024-03-15 03:32:29'),
	(2, 'Escudo', '/assets/img/veda/Escudo.png', 'logo', 0, NULL, '2024-03-15 03:32:29');

-- Volcando estructura para tabla coepci.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.migrations: ~5 rows (aproximadamente)
INSERT IGNORE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(53, '2014_10_12_000000_create_users_table', 1),
	(54, '2014_10_12_100000_create_password_reset_tokens_table', 1),
	(55, '2019_08_19_000000_create_failed_jobs_table', 1),
	(56, '2019_12_14_000001_create_personal_access_tokens_table', 1),
	(57, '2024_02_29_195054_create_permission_tables', 1);

-- Volcando estructura para tabla coepci.model_has_permissions
CREATE TABLE IF NOT EXISTS `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.model_has_permissions: ~0 rows (aproximadamente)

-- Volcando estructura para tabla coepci.model_has_roles
CREATE TABLE IF NOT EXISTS `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.model_has_roles: ~3 rows (aproximadamente)
INSERT IGNORE INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
	(1, 'App\\Models\\User', 1),
	(2, 'App\\Models\\User', 5),
	(2, 'App\\Models\\User', 6);

-- Volcando estructura para tabla coepci.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla coepci.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.permissions: ~22 rows (aproximadamente)
INSERT IGNORE INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'Modulo_Usuario', 'web', '2024-03-06 03:05:04', '2024-03-06 03:05:04'),
	(3, 'Modulo_Roles', 'web', '2024-03-08 00:18:04', '2024-03-08 00:18:04'),
	(4, 'Modulo_Permisos', 'web', '2024-03-08 00:18:11', '2024-03-08 00:18:11'),
	(7, 'Modulo_Dashboard', 'web', '2024-03-09 01:28:17', '2024-03-09 01:28:17'),
	(8, 'Modulo_Ajustes', 'web', '2024-03-09 01:28:26', '2024-03-09 01:28:26'),
	(9, 'Crear_usuarios', 'web', '2024-03-09 01:38:41', '2024-03-09 01:38:41'),
	(10, 'Editar_usuarios', 'web', '2024-03-09 02:08:09', '2024-03-09 02:08:09'),
	(11, 'Eliminar_usuarios', 'web', '2024-03-09 02:08:17', '2024-03-09 02:08:17'),
	(12, 'Asignar_roles_usuarios', 'web', '2024-03-09 02:08:47', '2024-03-09 02:08:47'),
	(13, 'Crear_roles', 'web', '2024-03-09 02:13:24', '2024-03-09 02:13:24'),
	(14, 'Editar_roles', 'web', '2024-03-09 02:13:33', '2024-03-09 02:13:33'),
	(15, 'Eliminar_roles', 'web', '2024-03-09 02:13:39', '2024-03-09 02:13:39'),
	(16, 'Crear_permisos', 'web', '2024-03-09 02:13:48', '2024-03-09 02:13:48'),
	(17, 'Editar_permisos', 'web', '2024-03-09 02:13:54', '2024-03-09 02:13:54'),
	(18, 'Eliminar_permisos', 'web', '2024-03-09 02:14:02', '2024-03-09 02:14:02'),
	(19, 'Asignar_roles_permisos', 'web', '2024-03-09 02:15:05', '2024-03-09 02:15:05'),
	(20, 'Modulo_Dependencias', 'web', '2024-03-13 21:32:07', '2024-03-13 21:32:07'),
	(21, 'Modulo_Veda', 'web', '2024-03-13 21:32:13', '2024-03-13 21:32:13'),
	(22, 'Crear_dependencias', 'web', '2024-03-13 21:37:16', '2024-03-13 21:37:16'),
	(23, 'Editar_dependencias', 'web', '2024-03-13 21:37:21', '2024-03-13 21:37:21'),
	(24, 'Eliminar_dependencias', 'web', '2024-03-13 21:37:26', '2024-03-13 21:37:26'),
	(25, 'Activar_Veda', 'web', '2024-03-13 22:49:02', '2024-03-13 22:49:02');

-- Volcando estructura para tabla coepci.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.personal_access_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla coepci.registros
CREATE TABLE IF NOT EXISTS `registros` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_vot` bigint unsigned NOT NULL,
  `id_nom` bigint unsigned NOT NULL,
  `id_grup` bigint unsigned NOT NULL,
  `id_conc` bigint unsigned NOT NULL,
  `ronda` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `registros_id_vot_foreign` (`id_vot`),
  KEY `registros_id_nom_foreign` (`id_nom`),
  KEY `registros_id_grup_foreign` (`id_grup`),
  KEY `registros_id_conc_foreign` (`id_conc`),
  CONSTRAINT `registros_id_conc_foreign` FOREIGN KEY (`id_conc`) REFERENCES `concursos` (`id`),
  CONSTRAINT `registros_id_grup_foreign` FOREIGN KEY (`id_grup`) REFERENCES `grupos` (`id`),
  CONSTRAINT `registros_id_nom_foreign` FOREIGN KEY (`id_nom`) REFERENCES `empleados` (`id`),
  CONSTRAINT `registros_id_vot_foreign` FOREIGN KEY (`id_vot`) REFERENCES `empleados` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=204 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.registros: ~0 rows (aproximadamente)

-- Volcando estructura para tabla coepci.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.roles: ~2 rows (aproximadamente)
INSERT IGNORE INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
	(1, 'Administrador', 'web', '2024-03-06 02:50:51', '2024-03-06 02:50:51'),
	(2, 'Usuario', 'web', '2024-03-06 02:52:44', '2024-03-06 02:52:44');

-- Volcando estructura para tabla coepci.role_has_permissions
CREATE TABLE IF NOT EXISTS `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.role_has_permissions: ~24 rows (aproximadamente)
INSERT IGNORE INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
	(1, 1),
	(3, 1),
	(4, 1),
	(7, 1),
	(8, 1),
	(9, 1),
	(10, 1),
	(11, 1),
	(12, 1),
	(13, 1),
	(14, 1),
	(15, 1),
	(16, 1),
	(17, 1),
	(18, 1),
	(19, 1),
	(20, 1),
	(21, 1),
	(22, 1),
	(23, 1),
	(24, 1),
	(25, 1),
	(7, 2),
	(8, 2);

-- Volcando estructura para tabla coepci.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_depen` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.users: ~1 rows (aproximadamente)
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `id_depen`, `created_at`, `updated_at`) VALUES
	(1, 'Idania Martinez', 'idania.mati@gmail.com', NULL, '$2y$12$rPvc41Jl3dmHjEVuOOuwMO722GcQqy3feqlM6eRZJ9ICoOFazE7FK', NULL, 52, '2024-03-06 00:24:53', '2024-03-09 01:33:59');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
