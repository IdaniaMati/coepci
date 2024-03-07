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
  `comentario` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.concursos: ~2 rows (aproximadamente)
INSERT IGNORE INTO `concursos` (`id`, `descripcion`, `fechaIni1ronda`, `fechaIni2ronda`, `fechaFin`, `comentario`, `created_at`, `updated_at`) VALUES
	(28, 'COEPCI 2024', '2024-02-26', '2024-02-27', '2024-02-28', 'porque si', '2024-02-29 01:19:26', '2024-02-29 01:19:26'),
	(29, 'COEPCI 2025', '2025-02-28', '2024-03-01', '2024-02-29', 'adasd', '2024-02-29 22:58:46', '2024-02-29 22:58:46');

-- Volcando estructura para tabla coepci.empleados
CREATE TABLE IF NOT EXISTS `empleados` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id_grup` bigint unsigned NOT NULL,
  `curp` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_paterno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `apellido_materno` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `empleados_id_grup_foreign` (`id_grup`),
  CONSTRAINT `empleados_id_grup_foreign` FOREIGN KEY (`id_grup`) REFERENCES `grupos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.empleados: ~142 rows (aproximadamente)
INSERT IGNORE INTO `empleados` (`id`, `id_grup`, `curp`, `nombre`, `apellido_paterno`, `apellido_materno`, `cargo`, `created_at`, `updated_at`) VALUES
	(1, 1, 'VASA761123HQRZLD08', 'Adolfo Eduardo', 'Vázquez', 'Salazar', 'Coordinación De Tecnologías De La Información', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(2, 1, '123552', 'Edgar Leonel', 'Xool', 'Cab', 'Departamento de Desarrollo de Sistemas e Infraestructura Tecnológica', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(3, 1, '2378343', 'Jesús Eduardo', 'Balderás', 'Castro', 'Departamento de Desarrollo de Sistemas e Infraestructura Tecnológica', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(4, 1, 'AERR641025MYNRSY01', 'Reyna Valdivia', 'Arceo', 'Rosado', 'Despacho de la Secretaria de la Contraloría', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(5, 1, 'SAZE960726MMCLGL03', 'María Elena', 'Salgado', 'Zagal', 'Delegación Benito Juárez', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(6, 2, 'HERS701102HYNRMR03', 'Sergio André', 'Herrera', 'Romero', 'Departamento de Supervisión Técnica de Obra Pública', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(7, 2, 'CAMA820927HYNNRL03', 'José Alberto', 'Canul', 'Martínez', 'Coordinación General Administrativa', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(8, 2, '34534', 'José María', 'Pino', 'Rusconi', 'Coordinación De Tecnologías De La Información', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(9, 2, '345', 'Critina', 'Echanove', 'Manzanillo', 'Coordinación De Tecnologías De La Información', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(10, 2, 'PUMN761022HQRGNC01', 'Nicolás', 'Puga', 'Mendoza', 'Coordinación De  Supervisión De Obra Pública, Adquisiciones Y Servicios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(11, 2, 'ROER840810MQRDKS00', 'Rosa Maria', 'Rodriguez', 'Ek', 'Coordinación de Recursos Humanos', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(12, 3, '745', 'José Ruben', 'Sánchez', 'Loza', 'Departamento de Supervisión Técnica de Obra Pública', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(13, 3, '4745', 'José Carlos', 'Tut', 'Martinez', 'Departamento de Supervisión Técnica de Obra Pública', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(14, 3, '5645', 'Jonathan Azur', 'Rosado', 'Caballero', 'Departamento de Supervisión Técnica de Obra Pública', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(15, 3, '456', 'Jesús Alberto', 'Bañuelos', 'Jiménez', 'Departamento de Supervisión Técnica de Obra Pública', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(16, 3, '456', 'Victor Manuel', 'Briceño', 'García', 'Coordinación De  Supervisión De Obra Pública, Adquisiciones Y Servicios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(17, 3, '7656', 'Marvin Daniel', 'Arellano', 'de la Cruz', 'Coordinación De  Supervisión De Obra Pública, Adquisiciones Y Servicios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(18, 3, '578657', 'José Vladimir', 'Tuz', 'Canto', 'Coordinación De  Supervisión De Obra Pública, Adquisiciones Y Servicios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(19, 1, 'DOCJ750330HQRMRQ02', 'Joaquin Enrique', 'Dominguez', 'Cruz', 'Departamento de Verificación de Obra con Laboratorio Movil', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(20, 1, 'rth56', 'Ileana Guadalupe', 'Poot', 'Ocejo', 'Coordinación De  Supervisión De Obra Pública, Adquisiciones Y Servicios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(21, 1, '74567', 'Cesar Augusto ', 'Herrera', 'Romero', 'Coordinación De  Supervisión De Obra Pública, Adquisiciones Y Servicios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(22, 1, '4u6547', 'Gustavo Misael', 'Caamal', 'Poot', 'Coordinación De  Supervisión De Obra Pública, Adquisiciones Y Servicios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(23, 1, 'j445j47', 'Jorge Luis', 'León ', 'León', 'Coordinación De  Supervisión De Obra Pública, Adquisiciones Y Servicios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(24, 1, 'PETA850618HQRRRN05', 'Ángel Rey', 'Perez', 'Trujeque', 'Departamento de Análisis de Precios Unitarios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(25, 2, '4u467', 'José', 'Gómez', 'Agustín', 'Departamento de Desarrollo de Sistemas e Infraestructura Tecnológica', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(26, 2, 'tryjtryj', 'Idania', 'Martínez', 'Torres', 'Coordinación De Tecnologías De La Información', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(27, 2, 'rty', 'LUCELLY CARMINIA ', 'BATES', 'SILVA', 'Delegación Benito Juárez', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(28, 2, 'AACE950922HQRRRD06', 'Edgar', 'Araos', 'Cortés', 'Coordinación De Tecnologías De La Información', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(29, 2, 'trjtr', 'Erik', 'Palacios', 'Glez', 'Delegación Benito Juárez', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(30, 2, 'rk6rytkj', 'Alejandra', 'Castillo', 'Martin', 'Delegación Benito Juárez', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(31, 2, 'tryjtryj', 'Elias Eleazar', 'Chi', 'Matus', 'Delegación Benito Juárez', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(32, 3, 'tyjtryj', 'JOSE MELVIN', 'MAY', 'PEREZ', 'Delegación Benito Juárez', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(33, 3, 'GUCV890420MQRZBR09', 'Verónica Rubí ', 'Guzmán', 'Cabrera', 'Departamento de Capacitación y Seguimiento a la Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(34, 3, 'NODJ690308HYNHZN05', 'Juan de Dios', 'Noh ', 'Dzul', 'Coordinación Operativa de Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(35, 3, 'RULJ820830HQRZPR01', 'Jorge Ramón', 'Ruíz', 'López', 'Departamento de Capacitación y Seguimiento a la Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(36, 3, 'FERE820803HPLRND06', 'Eduardo Alejandro', 'Freyre', 'Reinhartd', 'Coordinación Operativo de Planeación, Evaluación y Vinculación Interinstitucional', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(37, 3, 'RIME830424HQRVRD03', 'Edwin Omar', 'Rivero', 'Martínez', 'Departamento de Seguimiento Institucional', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(38, 3, 'PUTI930723MQRCZV03', 'Ivette Amairani', 'Puc', 'Tuz', 'Departamento de Planeación y Evaluación', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(39, 2, 'IERI740914MYNNMS06', 'Isla del Carmen', 'Interián ', 'Ramírez', 'Coordinación General de Planeación y Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(40, 2, 'MECA890417HQRDML09', 'Alfonso Alexander', 'Medina ', 'Camara', 'Coordinación de Seguimiento de Auditorías con Recursos Federales y Estatales', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(41, 2, 'HOBR760315MQRLSS08', 'Rosalba de Guadalupe', 'Hoil', 'Baas', 'Departamento de Seguimiento de Auditorías A', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(42, 2, 'TUTE920223HYNZZL06', 'Eliel Giovanny', 'Tzul', 'Tuz', 'Departamento de Seguimiento de Auditorías B', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(43, 2, 'CAVE840116HQRMSD05', 'Edwin Damin', 'Camara', 'Vasquez', 'Coordinación de Investigación en Auditorias de Tipo Federal y Estatal', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(44, 2, 'HEPD910524MQRRRN01', 'Diana Patricia ', 'Hernández', 'Priego', 'Departamento de Investigación de Auditorias', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(45, 2, 'VASN840121MQRLNV05', 'Naivy Marcela ', 'Valle ', 'Santiago', 'Departamento de Capacitación y Seguimiento a la Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(46, 2, 'CORI890406MTCNMR09', 'Iris Guadalupe', 'Concha ', 'Ramírez', 'Secretaría Particular', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(47, 3, 'RIAL880116MQRVLR03', 'Laura Lucía', 'Rivas', 'Alonzo', 'Coordinación Operativa de Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(48, 3, 'FITK940715MQRLRR00', 'Karla Janette', 'Fields', 'Trujillo', 'Coordinación de Vinculación y Seguimiento', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(49, 3, 'HECJ780107HQRRNR04', 'Jorge Carlos', 'Herrera ', 'Canto', 'Coordinación de Acceso a la Información', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(50, 3, 'LIZK911206MQRBRR09', 'Karen Janette', 'Mc Liberty', 'Zurita', 'Departamento de Capacitación y Vinculación', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(51, 3, 'BAFS921010HQRTRM02', 'ROBERTO RICARDO', 'TORRES', 'GUZMAN', 'Coordinación General de Comunicación Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(52, 3, 'GOEM671222MYNNSR10', 'Martina Evelia', 'Gónzalez', 'Escalante', 'Coordinación General de Planeación y Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(53, 3, 'TALA910623HQRDGR06', 'Aarón', 'Tadeo', 'Lugo', 'Departamento de Planeación y Evaluación', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(54, 3, 'NUMJ710919HQRXNS09', 'Jesús Santos', 'Núñez', 'Mendoza', 'Departamento de Planeación y Evaluación', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(55, 3, 'TARB580817HYNHMR06', 'Bartolomé', 'Táh', 'y Ramírez', 'Departamento de Planeación y Evaluación', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(56, 3, 'BAFS921010HQRTRM02', 'Samuel de Jesus', 'Bautista', 'Fernandez', 'Departamento de Seguimiento Institucional', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(57, 3, 'JUGC820224MTSRRY02', 'Cynthia Juanita', 'Juárez', 'Guardado', 'Coordinación General de Comunicación Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(58, 3, 'DIVF640804HDFZLL09', 'Félix', 'Díaz', 'Villalobos', 'Coordinación General de Transparencia, Acceso a la Información Pública y Protección de Datos Personales', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(59, 3, 'GAZS820916MDFRP14', 'Silvia Alicia', 'García ', 'Zapata', 'Mesa de Resoluciones A', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(60, 3, 'PERA921226MYNRVD00', 'Adriana Guadalupe', 'Pérez', 'Rivero', 'Coordinación General de Sustanciación y Resoluciones', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(61, 3, 'GOBJ750816HQRMSV06', 'Javier', 'Gómez', 'Bustillos', 'Subsecretaría de Resoluciones y Normatividad', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(62, 3, 'AUBJ90121HQRGRN05', 'Jonathan', 'Aguilar', 'Barrera', 'Departamento de Apoyo Administrativo', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(63, 3, 'PEMM910614MQRRXR08', 'Maricruz', 'Pérez', 'Muñoz', 'Mesa de Resoluciones A', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(64, 3, 'CUGA860614HYNPNN09', 'Ángel de Jesús', 'Cupul ', 'González', 'Coordinación General de Normatividad y Regulación', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(65, 3, 'PEHA860429HTCRRR03', 'Artemio', 'Pérez ', 'Hernández', 'Secretaría de Acuerdos de Sustanciación e Instrucción 1', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(66, 3, 'HELN860712MQRRPL00', 'Nolvia', 'Hernández', 'López', 'Secretaría de Acuerdos de Sustanciación e Instrucción 2', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(67, 3, 'AOMS960704HQRRNL03', 'José Silverio ', 'Arjona', 'Montiel', 'Departamento de Resoluciones 1', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(68, 3, 'LOPN940514MQRPCY01', 'Nayeli Anahi', 'López', 'Pacheco', 'Mesa de Sustanciación e Instrucción A', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(69, 3, 'ROHG701214MVZMRL00', 'Guillermina', 'Romero', 'Hernández', 'Departamento de Resoluciones 2', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(70, 3, 'COTJ700116HCCLPL07', 'Julio Cesar ', 'Colli', 'Tapia', 'Departamento de Nómina', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(71, 3, 'MOLE800922MVZLPL06', 'Elizabeth', 'Molina', 'López', 'Coordinación de Contabilidad y Control Presupuestal', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(72, 3, 'MAEJ880214HQRRSR08', 'Jorge Edgardo', 'Martín', 'Estañol', 'Coordianción de Recursos Financieros', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(73, 1, 'MEMC781130HSLDNR03', 'Carlos Alberto', 'Medina ', 'Montaño', 'Departamento de Control Financiero', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(74, 1, 'GOHA850719HQRNRL07', 'Alfredo', 'González', 'Hernández', 'Coordinación de Recursos Materiales, Servicios Generales y Archivo Documental', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(75, 1, 'VISL810526MQRCNL07', 'Liliana Maribel', 'Victoria', 'Sánchez', 'Departamento de Adqusiciones', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(76, 1, 'CAML781023MYNHDY05', 'Leydi Lucia', 'Chan', 'Medina', 'Departamento de Auditoria Financiera de Obra Pública, Adquisiciones y Servicios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(77, 1, 'AUEC771122HQRNSR01', 'Carlos Mario', 'Angulo ', 'Espinosa', 'Coordinación General de Seguimiento e Investigación de Auditorías de Recursos Federales y Estatales', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(78, 1, 'WAMJ701025HVZLNM05', 'Jaime', 'Wall', 'Montoya', 'Departamento de Auditoria de Concursos y Normatividad', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(79, 1, 'POKL660919HQRLMN05', 'Leonardo', 'Pool', 'Kumul', 'Departamento de Gestión de la Información', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(80, 1, 'LECA780531MYNNND19', 'Aida Leticia', 'León', 'Canto', 'Subsecretaria de Investigación y Vinculación', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(81, 1, 'MAGM791219HCSRMR02', 'Mario ', 'Martínez', 'Gómez', 'Coordinación General de Investigación', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(82, 1, 'CASM741004MQRSNR08', 'Margely Alicia', 'Castro', 'Santeliz', 'Coordinación de Investigación A', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(83, 1, 'BARJ930828HTCZML05', 'Julio Cesar ', 'Baeza', 'Ramírez', 'Coordinación de Investigación B', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(84, 1, 'SAMN770612MQRNLY01', 'Nayeli', 'Santiago', 'Melchor', 'Departamento de Investigación A-2', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(85, 2, 'FOVC980909MQRLLL08', 'Clarissa ', 'Flores', 'Velázquez', 'Departamento de Investigación A-1', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(86, 2, 'PUCS990722MQRCNH04', 'Shecid Maricela', 'Pucheta', 'Canto', 'Departamento de Investigación B-1', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(87, 2, 'LELL891201MCCNNL09', 'Lilia Veronica', 'León', 'León', 'Departamento de Investigación B-2', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(88, 2, 'PESA990920MDFXNN00', 'Ana Karen', 'Peña ', 'Sánchez', 'Coordinación de Quejas y Denuncias', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(89, 2, 'SAPA970928MQRNBL09', 'Alejandra', 'Santiago', 'Pablo', 'Departamento de Denuncias A', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(90, 2, 'CIGL001203MDFRRGA9', 'Luigina Karel', 'Cristerna ', 'Guerrero', 'Departamento de Denuncias B', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(91, 2, 'PAFL900904HTCDRC05', 'Lucas Alejandro', 'Padilla ', 'Franyutti', 'Coordinación General Jurídica y de Vinculación', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(92, 2, 'PAFL900904HTCDRC05', 'Alejandra', 'Tzuc', 'Coob', 'Coordinación de Situación Patrimonial', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(93, 2, 'PAMK940611MQRLRR03', 'Karla Yasury', 'Palomo', 'Marin', 'Departamento de Situación Patrimonial A', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(94, 2, 'GOLD990708MQRMZL02', 'Lilia Yareli', 'Gómez', 'Dzib', 'Departamento de Situación Patrimonial B', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(95, 2, 'OADE991111MQRCRD03', 'Ediht Haydee ', 'Ocaña', 'Duran', 'Coordinación Jurídica y de Entrega Recepción', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(96, 1, 'SAPC960503MQRNBR00', 'Maria de la Cruz', 'Santiago ', 'Pablo', 'Departamento Jurídico', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(97, 2, 'OUVL720625MPLLRT00', 'Leticia', 'Olguin', 'Vargas', 'Coordinación General de Sustanciación y Resoluciones', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(98, 2, 'CATJ880328HQRSPS09', 'Jesus Fabian', 'Castillo ', 'Tapia', 'Coordinación Jurídica y de Proyectos', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(99, 2, 'SAIV771118MQRNNC00', 'Victoria Guadalupe', 'Sandy', 'Interian', 'Coordinación de Evaluación de Procesos y Mejora Continua', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(100, 2, 'ROTE970108MPLSVSO7', 'Estefania', 'Rosas', 'Tovar', 'Coordinación de Evaluación Normativa', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(101, 2, 'TUMD640518MDFRNN05', 'Diana', 'Trujillo', 'Mondragón', 'Subsecretaría de Auditoría y Control Interno', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(102, 2, 'MASA751031HYNRNN06', 'Ángel Eduardo ', 'Mares', 'Sánchez', 'Coordinación General de los Organos Internos de Control', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(103, 2, 'SOS891230HCCSNN08', 'Ángel David', 'Sosa ', 'Sánchez', 'Departamento de Seguimiento Normativo', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(104, 2, 'AUHM780727HQRNXS08', 'Moisés Abraham', 'Angulo ', 'Hu ', 'Coordinación General de los Organos Internos de Control', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(105, 2, 'MAAC850530MQRTRN08', 'Candy Cecilia', 'Matos', 'Arguelles', 'Coordinación de Evaluación Administrativa', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(106, 2, 'GACY691010MQRRNT07', 'Yatie Abigail', 'García', 'Cen', 'Coordinación General de Comisarios de Entidades', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(107, 3, 'GACC730705HGTRBR05', 'Cruz', 'Garcia', 'Cabrera', 'Coordinación de Entidades del Sector Agropecuario, Infraestructura y Hacienda', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(108, 3, 'SOCA670206MCCLRN02', 'María de los Ángeles ', 'Solís ', 'Cruz', 'Coordinación de Entidades del Sector Educación Media Superior', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(109, 3, 'CACS810619MQRNNM09', 'Suemi Argelia ', 'Canto ', 'Cen', 'Coordinación de Entidades del Sector Turístico y Económico', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(110, 3, 'REMR730327HQRYXG01', 'Roger Ruben', 'Reyes', 'Moo', 'Coordinación de Entidades del Sector Educativo y Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(111, 3, 'VAAC750416MQRNCR03', 'Carolina', 'Vanegas', 'Aceves', 'Coordinación Jurídica de Comisarios de Entidades', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(112, 3, 'GUSW740119MYNTSN00', 'Wendy Veronica', 'Gutierrez', 'Sosa', 'Coordinación de Entidades del Sector Salud y Gobierno', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(113, 3, 'RIUN811204MYNVCN08', 'Nancy Patricia', 'Rivero', 'Uc', 'Coordinación Operativa y de Entidades de Educación Superior', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(114, 1, 'HEBA860922HYNRRL09', 'José Alan', 'Herrera ', 'Borges', 'Subsecretaría De Fiscalización E Investigación De Obra Pública, Adquisiciones Y Servicios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(115, 3, 'CAPY780912MCCBXL08', 'Yolanda', 'Caballero', 'De Pau', 'Departamento de Apoyo Técnico y Administrativo', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(116, 3, 'SAGA721127HQRNNN11', 'Ángel Leopoldo ', 'Sánchez', 'González', 'Coordinación General De Fiscalización De Obra Pública, Adquisiciones Y Servicios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(117, 3, 'BOZG780829MVZRPR06', 'Graciela', 'Borges', 'Zepeda', 'Coordinación de Auditoria de Obra Pública, Adquisiciones y Servicios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(118, 3, 'CACI620403HYNMSS09', 'Israel', 'Caamal', 'Castillo', 'Departamento de Auditoria Técnica de Obra Pública, Adquisiciones y Servicios', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(119, 3, 'SIIP660726HQRMTS01', 'Pastor', 'Sima', 'Iuit', 'Coordinación de Apoyo Juridico de los Órganos Internos de Control', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(120, 3, 'SECO870917MYNGHL08', 'Olivia Irani', 'Segura ', 'Chan', 'Coordianción de los Órganos Internos de Control A', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(121, 1, 'COCA690412HQRCNN00', 'José Andrés', 'Cocom', 'Can', 'Departamento de Enlace Administrativo de los Órganos Internos de Control', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(122, 1, 'SOHO710710MTCZRL05', 'Olivia Alenajdra', 'Souza ', 'Hernándezz', 'Departamento de Evaluación y Seguimiento de los Órganos Internos de Control', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(123, 1, 'EISM740302MQRSSR09', 'Marilú', 'Espinoza', 'Sosa', 'Coordinación de los Órganos Internos de Control B', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(124, 1, 'REFN830810MTCYLL04', 'Nilda', 'Reyas ', 'Flores', 'Departamento de Coordinación de los Órganos Internos de Control B', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(125, 1, 'YACV890725HQRMHC00', 'Victor Alfonso', 'Yam ', 'Cahuil', 'Coordinación General de Auditoría y Control Interno', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(126, 1, 'AOCN960403MQRCCV06', 'Navilena', 'Acosta ', 'Cocom', 'Departamento de Evaluación y Seguimiento de Auditoria y Control Interno', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(127, 2, 'COCL840226HQRRHN10', 'Lenin Amilcar ', 'Correa', 'Chulim', 'Coordinación de Apoyo Juridico de Auditoria y Control Interno', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(128, 2, 'AURO890410HGTRDS00', 'Osmar Vladimir', 'Argüelles', 'Rodríguez', 'Coordinación de Auditoria Gubernamental', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(129, 2, 'CAHE860322MQRMRL03', 'Elsy Concepción', 'Campos ', 'Hernández', 'Departamento de Enlace con Auditores Especiales', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(130, 2, 'HEMG750207HQRRDV05', 'Geovany', 'Hernández', 'Medina', 'Departamento de Auditoría Externa', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(131, 2, 'CACC910205MQRCHL02', 'Celeste Ismerai', 'Cauich', 'Chan', 'Coordinación de Control Interno', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(132, 1, 'LOSU921108HQRPNL03', 'Ulises', 'López', 'Santos', 'Departamento de Capacitación y Seguimiento a la Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(133, 3, 'ROCF000831MQRVSRA4', 'Frida Montserrat', 'Rovira', 'Castro', 'Departamento de Capacitación y Seguimiento a la Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(134, 3, 'goqp781008hqrnnd00', 'Pedro Alberto', 'González', 'Quintero', 'Departamento de Capacitación y Seguimiento a la Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(135, 3, 'AALL740717MMCRPC07', 'Lucero ', ' Araujo ', 'López', 'Delegación Benito Juárez', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(136, 3, 'GATC800831MDFRRL01', 'Claudia', 'García ', 'Torres', 'Delegación Benito Juárez', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(137, 3, 'YAPI670114HQRMLD06', 'Idelfonso', 'Yam', 'Pool', 'Departamento de Capacitación y Seguimiento a la Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(138, 3, 'LIBY981020HQRZZL03', 'Yael', 'Lizama', 'Baeza', 'Departamento de Capacitación y Seguimiento a la Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(139, 1, 'CARA000113HQRSDNA9', 'Angel Daniel ', 'Castilla', 'Rodríguez', 'Departamento de Capacitación y Seguimiento a la Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(140, 3, 'SACS951115MCCRST04', 'STEPHANIE IVANA', 'SARRICOLEA', 'COSGALLA', 'Departamento de Apoyo Técnico y Administrativo', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(141, 3, 'CUFG000509MTCRJDA8', 'María Guadalupe', 'De la Cruz', 'Fajardo', 'Coordinación General de Planeación y Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15'),
	(142, 3, 'AUHA911201HQRGRN05', 'José Antonio', 'Aguilar', 'Hernández', 'Departamento de Capacitación y Seguimiento a la Contraloría Social', '2024-02-29 22:59:15', '2024-02-29 22:59:15');

-- Volcando estructura para tabla coepci.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id_emp` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=191 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.ganadores: ~14 rows (aproximadamente)
INSERT IGNORE INTO `ganadores` (`id`, `id_conc`, `id_grup`, `id_emp`, `created_at`, `updated_at`) VALUES
	(156, 28, 1, 'Adolfo Eduardo Vázquez Salazar', '2024-02-29 02:51:54', '2024-02-29 02:51:54'),
	(157, 28, 1, 'Aida Leticia León Canto', '2024-02-29 02:51:54', '2024-02-29 02:51:54'),
	(158, 28, 2, 'Alejandra Castillo Martin', '2024-02-29 02:51:54', '2024-02-29 02:51:54'),
	(159, 28, 2, 'Alejandra Santiago Pablo', '2024-02-29 02:51:54', '2024-02-29 02:51:54'),
	(160, 28, 3, 'Aarón Tadeo Lugo', '2024-02-29 02:51:54', '2024-02-29 02:51:54'),
	(161, 28, 3, 'Ángel de Jesús Cupul  González', '2024-02-29 02:51:54', '2024-02-29 02:51:54'),
	(162, 28, 3, 'Adriana Guadalupe Pérez Rivero', '2024-02-29 02:51:54', '2024-02-29 02:51:54'),
	(184, 29, 1, 'Margely Alicia Castro Santeliz', '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(185, 29, 1, 'Olivia Alenajdra Souza  Hernándezz', '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(186, 29, 2, 'LUCELLY CARMINIA  BATES SILVA', '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(187, 29, 2, 'Sergio André Herrera Romero', '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(188, 29, 3, 'Verónica Rubí  Guzmán Cabrera', '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(189, 29, 3, 'Victor Manuel Briceño García', '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(190, 29, 3, 'Yolanda Caballero De Pau', '2024-02-29 23:43:33', '2024-02-29 23:43:33');

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
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla coepci.historico_votos: ~0 rows (aproximadamente)
INSERT IGNORE INTO `historico_votos` (`id`, `nombre`, `id_grup`, `id_conc`, `ronda`, `novotos`, `created_at`, `updated_at`) VALUES
	(34, 'Adolfo Eduardo Vázquez Salazar', 1, 28, 1, 3, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(35, 'Aida Leticia León Canto', 1, 28, 1, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(36, 'Alejandra Castillo Martin', 2, 28, 1, 2, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(37, 'Alejandra Santiago Pablo', 2, 28, 1, 2, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(38, 'Aarón Tadeo Lugo', 3, 28, 1, 2, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(39, 'Adriana Guadalupe Pérez Rivero', 3, 28, 1, 2, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(40, 'Ángel de Jesús Cupul  González', 3, 28, 1, 2, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(41, 'Alfredo González Hernández', 1, 28, 1, 3, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(42, 'Angel Daniel  Castilla Rodríguez', 1, 28, 1, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(43, 'Alejandra Tzuc Coob', 2, 28, 1, 2, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(44, 'Alfonso Alexander Medina  Camara', 2, 28, 1, 2, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(45, 'Ángel Leopoldo  Sánchez González', 3, 28, 1, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(46, 'Artemio Pérez  Hernández', 3, 28, 1, 2, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(47, 'Bartolomé Táh y Ramírez', 3, 28, 1, 2, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(48, 'Ángel Rey Perez Trujeque', 1, 28, 1, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(49, 'Carlos Alberto Medina  Montaño', 1, 28, 1, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(50, 'Ana Karen Peña  Sánchez', 2, 28, 1, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(51, 'Ángel David Sosa  Sánchez', 2, 28, 1, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(52, 'Carolina Vanegas Aceves', 3, 28, 1, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(53, 'Claudia García  Torres', 3, 28, 1, 2, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(54, 'Cruz Garcia Cabrera', 3, 28, 1, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(55, 'Adolfo Eduardo Vázquez Salazar', 1, 28, 2, 3, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(56, 'Aida Leticia León Canto', 1, 28, 2, 2, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(57, 'Alejandra Castillo Martin', 2, 28, 2, 3, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(58, 'Alejandra Santiago Pablo', 2, 28, 2, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(59, 'Aarón Tadeo Lugo', 3, 28, 2, 3, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(60, 'Adriana Guadalupe Pérez Rivero', 3, 28, 2, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(61, 'Ángel de Jesús Cupul  González', 3, 28, 2, 3, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(62, 'Ángel Rey Perez Trujeque', 1, 28, 2, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(63, 'Ana Karen Peña  Sánchez', 2, 28, 2, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(64, 'Bartolomé Táh y Ramírez', 3, 28, 2, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(65, 'Alfonso Alexander Medina  Camara', 2, 28, 2, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(66, 'Artemio Pérez  Hernández', 3, 28, 2, 1, '2024-02-29 02:55:09', '2024-02-29 02:55:09'),
	(67, 'Victor Alfonso Yam  Cahuil', 1, 29, 1, 1, '2024-02-29 22:59:49', '2024-02-29 22:59:49'),
	(68, 'Ulises López Santos', 1, 29, 1, 1, '2024-02-29 22:59:49', '2024-02-29 22:59:49'),
	(69, 'Yatie Abigail García Cen', 2, 29, 1, 1, '2024-02-29 22:59:49', '2024-02-29 22:59:49'),
	(70, 'Victoria Guadalupe Sandy Interian', 2, 29, 1, 1, '2024-02-29 22:59:50', '2024-02-29 22:59:50'),
	(71, 'Yolanda Caballero De Pau', 3, 29, 1, 1, '2024-02-29 22:59:50', '2024-02-29 22:59:50'),
	(72, 'Yael Lizama Baeza', 3, 29, 1, 1, '2024-02-29 22:59:50', '2024-02-29 22:59:50'),
	(73, 'Wendy Veronica Gutierrez Sosa', 3, 29, 1, 1, '2024-02-29 22:59:50', '2024-02-29 22:59:50'),
	(74, 'Reyna Valdivia Arceo Rosado', 1, 29, 1, 1, '2024-02-29 23:03:32', '2024-02-29 23:03:32'),
	(75, 'Olivia Alenajdra Souza  Hernándezz', 1, 29, 1, 1, '2024-02-29 23:03:32', '2024-02-29 23:03:32'),
	(76, 'Shecid Maricela Pucheta Canto', 2, 29, 1, 1, '2024-02-29 23:03:32', '2024-02-29 23:03:32'),
	(77, 'Sergio André Herrera Romero', 2, 29, 1, 1, '2024-02-29 23:03:32', '2024-02-29 23:03:32'),
	(78, 'Victor Manuel Briceño García', 3, 29, 1, 1, '2024-02-29 23:03:32', '2024-02-29 23:03:32'),
	(79, 'Verónica Rubí  Guzmán Cabrera', 3, 29, 1, 1, '2024-02-29 23:03:32', '2024-02-29 23:03:32'),
	(80, 'Suemi Argelia  Canto  Cen', 3, 29, 1, 1, '2024-02-29 23:03:32', '2024-02-29 23:03:32'),
	(81, 'Margely Alicia Castro Santeliz', 1, 29, 1, 1, '2024-02-29 23:04:08', '2024-02-29 23:04:08'),
	(82, 'Maria de la Cruz Santiago  Pablo', 1, 29, 1, 1, '2024-02-29 23:04:08', '2024-02-29 23:04:08'),
	(83, 'LUCELLY CARMINIA  BATES SILVA', 2, 29, 1, 1, '2024-02-29 23:04:08', '2024-02-29 23:04:08'),
	(84, 'Luigina Karel Cristerna  Guerrero', 2, 29, 1, 1, '2024-02-29 23:04:08', '2024-02-29 23:04:08'),
	(85, 'ROBERTO RICARDO TORRES GUZMAN', 3, 29, 1, 1, '2024-02-29 23:04:08', '2024-02-29 23:04:08'),
	(86, 'Roger Ruben Reyes Moo', 3, 29, 1, 1, '2024-02-29 23:04:08', '2024-02-29 23:04:08'),
	(87, 'Samuel de Jesus Bautista Fernandez', 3, 29, 1, 1, '2024-02-29 23:04:08', '2024-02-29 23:04:08'),
	(88, 'Victor Alfonso Yam  Cahuil', 1, 29, 1, 2, '2024-02-29 23:04:42', '2024-02-29 23:04:42'),
	(89, 'Yatie Abigail García Cen', 2, 29, 1, 2, '2024-02-29 23:04:42', '2024-02-29 23:04:42'),
	(90, 'Yolanda Caballero De Pau', 3, 29, 1, 2, '2024-02-29 23:04:42', '2024-02-29 23:04:42'),
	(91, 'Wendy Veronica Gutierrez Sosa', 3, 29, 1, 2, '2024-02-29 23:04:42', '2024-02-29 23:04:42'),
	(92, 'Reyna Valdivia Arceo Rosado', 1, 29, 1, 2, '2024-02-29 23:04:42', '2024-02-29 23:04:42'),
	(93, 'Shecid Maricela Pucheta Canto', 2, 29, 1, 2, '2024-02-29 23:04:42', '2024-02-29 23:04:42'),
	(94, 'Victor Manuel Briceño García', 3, 29, 1, 2, '2024-02-29 23:04:42', '2024-02-29 23:04:42'),
	(95, 'Victor Alfonso Yam  Cahuil', 1, 29, 1, 3, '2024-02-29 23:05:36', '2024-02-29 23:05:36'),
	(96, 'Yatie Abigail García Cen', 2, 29, 1, 3, '2024-02-29 23:05:36', '2024-02-29 23:05:36'),
	(97, 'Yolanda Caballero De Pau', 3, 29, 1, 3, '2024-02-29 23:05:36', '2024-02-29 23:05:36'),
	(98, 'Shecid Maricela Pucheta Canto', 2, 29, 1, 3, '2024-02-29 23:05:36', '2024-02-29 23:05:36'),
	(99, 'Verónica Rubí  Guzmán Cabrera', 3, 29, 1, 2, '2024-02-29 23:05:36', '2024-02-29 23:05:36'),
	(100, 'Marilú Espinoza Sosa', 1, 29, 1, 1, '2024-02-29 23:05:36', '2024-02-29 23:05:36'),
	(101, 'Eduardo Alejandro Freyre Reinhartd', 3, 29, 1, 1, '2024-02-29 23:05:36', '2024-02-29 23:05:36'),
	(102, 'Margely Alicia Castro Santeliz', 1, 29, 2, 1, '2024-02-29 23:06:58', '2024-02-29 23:06:58'),
	(103, 'Olivia Alenajdra Souza  Hernándezz', 1, 29, 2, 1, '2024-02-29 23:06:58', '2024-02-29 23:06:58'),
	(104, 'LUCELLY CARMINIA  BATES SILVA', 2, 29, 2, 1, '2024-02-29 23:06:58', '2024-02-29 23:06:58'),
	(105, 'Sergio André Herrera Romero', 2, 29, 2, 1, '2024-02-29 23:06:58', '2024-02-29 23:06:58'),
	(106, 'Verónica Rubí  Guzmán Cabrera', 3, 29, 2, 1, '2024-02-29 23:06:58', '2024-02-29 23:06:58'),
	(107, 'Victor Manuel Briceño García', 3, 29, 2, 1, '2024-02-29 23:06:58', '2024-02-29 23:06:58'),
	(108, 'Wendy Veronica Gutierrez Sosa', 3, 29, 2, 1, '2024-02-29 23:06:58', '2024-02-29 23:06:58'),
	(109, 'Margely Alicia Castro Santeliz', 1, 29, 2, 2, '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(110, 'Olivia Alenajdra Souza  Hernándezz', 1, 29, 2, 2, '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(111, 'LUCELLY CARMINIA  BATES SILVA', 2, 29, 2, 2, '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(112, 'Sergio André Herrera Romero', 2, 29, 2, 2, '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(113, 'Verónica Rubí  Guzmán Cabrera', 3, 29, 2, 3, '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(114, 'Victor Manuel Briceño García', 3, 29, 2, 2, '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(115, 'Victor Alfonso Yam  Cahuil', 1, 29, 2, 1, '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(116, 'Yatie Abigail García Cen', 2, 29, 2, 1, '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(117, 'Yolanda Caballero De Pau', 3, 29, 2, 2, '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(118, 'Yael Lizama Baeza', 3, 29, 2, 1, '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(119, 'Reyna Valdivia Arceo Rosado', 1, 29, 2, 1, '2024-02-29 23:43:33', '2024-02-29 23:43:33'),
	(120, 'Victoria Guadalupe Sandy Interian', 2, 29, 2, 1, '2024-02-29 23:43:33', '2024-02-29 23:43:33');

-- Volcando estructura para tabla coepci.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.migrations: ~0 rows (aproximadamente)

-- Volcando estructura para tabla coepci.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.password_reset_tokens: ~0 rows (aproximadamente)

-- Volcando estructura para tabla coepci.personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.registros: ~56 rows (aproximadamente)
INSERT IGNORE INTO `registros` (`id`, `id_vot`, `id_nom`, `id_grup`, `id_conc`, `ronda`, `created_at`, `updated_at`) VALUES
	(1, 1, 125, 1, 29, 1, '2024-02-29 22:59:46', '2024-02-29 22:59:46'),
	(2, 1, 132, 1, 29, 1, '2024-02-29 22:59:46', '2024-02-29 22:59:46'),
	(3, 1, 106, 2, 29, 1, '2024-02-29 22:59:47', '2024-02-29 22:59:47'),
	(4, 1, 99, 2, 29, 1, '2024-02-29 22:59:47', '2024-02-29 22:59:47'),
	(5, 1, 115, 3, 29, 1, '2024-02-29 22:59:47', '2024-02-29 22:59:47'),
	(6, 1, 138, 3, 29, 1, '2024-02-29 22:59:47', '2024-02-29 22:59:47'),
	(7, 1, 112, 3, 29, 1, '2024-02-29 22:59:47', '2024-02-29 22:59:47'),
	(8, 4, 4, 1, 29, 1, '2024-02-29 23:03:29', '2024-02-29 23:03:29'),
	(9, 4, 122, 1, 29, 1, '2024-02-29 23:03:29', '2024-02-29 23:03:29'),
	(10, 4, 86, 2, 29, 1, '2024-02-29 23:03:29', '2024-02-29 23:03:29'),
	(11, 4, 6, 2, 29, 1, '2024-02-29 23:03:29', '2024-02-29 23:03:29'),
	(12, 4, 16, 3, 29, 1, '2024-02-29 23:03:29', '2024-02-29 23:03:29'),
	(13, 4, 33, 3, 29, 1, '2024-02-29 23:03:29', '2024-02-29 23:03:29'),
	(14, 4, 109, 3, 29, 1, '2024-02-29 23:03:30', '2024-02-29 23:03:30'),
	(15, 5, 82, 1, 29, 1, '2024-02-29 23:04:04', '2024-02-29 23:04:04'),
	(16, 5, 96, 1, 29, 1, '2024-02-29 23:04:05', '2024-02-29 23:04:05'),
	(17, 5, 27, 2, 29, 1, '2024-02-29 23:04:05', '2024-02-29 23:04:05'),
	(18, 5, 90, 2, 29, 1, '2024-02-29 23:04:05', '2024-02-29 23:04:05'),
	(19, 5, 51, 3, 29, 1, '2024-02-29 23:04:05', '2024-02-29 23:04:05'),
	(20, 5, 110, 3, 29, 1, '2024-02-29 23:04:05', '2024-02-29 23:04:05'),
	(21, 5, 56, 3, 29, 1, '2024-02-29 23:04:05', '2024-02-29 23:04:05'),
	(22, 6, 125, 1, 29, 1, '2024-02-29 23:04:39', '2024-02-29 23:04:39'),
	(23, 6, 4, 1, 29, 1, '2024-02-29 23:04:39', '2024-02-29 23:04:39'),
	(24, 6, 106, 2, 29, 1, '2024-02-29 23:04:39', '2024-02-29 23:04:39'),
	(25, 6, 86, 2, 29, 1, '2024-02-29 23:04:40', '2024-02-29 23:04:40'),
	(26, 6, 115, 3, 29, 1, '2024-02-29 23:04:40', '2024-02-29 23:04:40'),
	(27, 6, 112, 3, 29, 1, '2024-02-29 23:04:40', '2024-02-29 23:04:40'),
	(28, 6, 16, 3, 29, 1, '2024-02-29 23:04:40', '2024-02-29 23:04:40'),
	(29, 7, 123, 1, 29, 1, '2024-02-29 23:05:33', '2024-02-29 23:05:33'),
	(30, 7, 125, 1, 29, 1, '2024-02-29 23:05:33', '2024-02-29 23:05:33'),
	(31, 7, 106, 2, 29, 1, '2024-02-29 23:05:33', '2024-02-29 23:05:33'),
	(32, 7, 86, 2, 29, 1, '2024-02-29 23:05:33', '2024-02-29 23:05:33'),
	(33, 7, 115, 3, 29, 1, '2024-02-29 23:05:33', '2024-02-29 23:05:33'),
	(34, 7, 33, 3, 29, 1, '2024-02-29 23:05:33', '2024-02-29 23:05:33'),
	(35, 7, 36, 3, 29, 1, '2024-02-29 23:05:34', '2024-02-29 23:05:34'),
	(36, 1, 82, 1, 29, 2, '2024-02-29 23:06:54', '2024-02-29 23:06:54'),
	(37, 1, 122, 1, 29, 2, '2024-02-29 23:06:55', '2024-02-29 23:06:55'),
	(38, 1, 27, 2, 29, 2, '2024-02-29 23:06:55', '2024-02-29 23:06:55'),
	(39, 1, 6, 2, 29, 2, '2024-02-29 23:06:55', '2024-02-29 23:06:55'),
	(40, 1, 33, 3, 29, 2, '2024-02-29 23:06:55', '2024-02-29 23:06:55'),
	(41, 1, 16, 3, 29, 2, '2024-02-29 23:06:55', '2024-02-29 23:06:55'),
	(42, 1, 112, 3, 29, 2, '2024-02-29 23:06:55', '2024-02-29 23:06:55'),
	(43, 4, 82, 1, 29, 2, '2024-02-29 23:42:43', '2024-02-29 23:42:43'),
	(44, 4, 125, 1, 29, 2, '2024-02-29 23:42:43', '2024-02-29 23:42:43'),
	(45, 4, 27, 2, 29, 2, '2024-02-29 23:42:43', '2024-02-29 23:42:43'),
	(46, 4, 106, 2, 29, 2, '2024-02-29 23:42:44', '2024-02-29 23:42:44'),
	(47, 4, 33, 3, 29, 2, '2024-02-29 23:42:44', '2024-02-29 23:42:44'),
	(48, 4, 115, 3, 29, 2, '2024-02-29 23:42:44', '2024-02-29 23:42:44'),
	(49, 4, 138, 3, 29, 2, '2024-02-29 23:42:44', '2024-02-29 23:42:44'),
	(50, 5, 122, 1, 29, 2, '2024-02-29 23:43:13', '2024-02-29 23:43:13'),
	(51, 5, 4, 1, 29, 2, '2024-02-29 23:43:13', '2024-02-29 23:43:13'),
	(52, 5, 6, 2, 29, 2, '2024-02-29 23:43:13', '2024-02-29 23:43:13'),
	(53, 5, 99, 2, 29, 2, '2024-02-29 23:43:14', '2024-02-29 23:43:14'),
	(54, 5, 16, 3, 29, 2, '2024-02-29 23:43:14', '2024-02-29 23:43:14'),
	(55, 5, 33, 3, 29, 2, '2024-02-29 23:43:14', '2024-02-29 23:43:14'),
	(56, 5, 115, 3, 29, 2, '2024-02-29 23:43:14', '2024-02-29 23:43:14');

-- Volcando estructura para tabla coepci.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla coepci.users: ~1 rows (aproximadamente)
INSERT IGNORE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(2, 'Edgar Araos', 'edgar.araos@qroo.gob.mx', NULL, '$2y$12$md2BIntZdrbkLpckkz1rQ.PSG543wPJ1H3Y2HjwPN6ZmDCcPbpnke', 'fYMJEkJYzJGufcX15LrWhHNrG7SHUb2R5kKwcExp12EuI8hzqQTQmbzyVJfy', '2024-02-15 02:34:01', '2024-03-01 00:33:20');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
