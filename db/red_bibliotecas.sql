-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-11-2026 a las 01:03:17
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `red_bibliotecas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad`
--

CREATE TABLE `actividad` (
  `id` int(10) NOT NULL,
  `id_solicitud` int(10) DEFAULT NULL,
  `id_biblioteca` int(11) DEFAULT NULL,
  `id_espacio_cultural` int(11) DEFAULT NULL,
  `nombre` varchar(30) NOT NULL,
  `id_tipo_actividad` int(10) NOT NULL,
  `id_nivel_impacto` int(10) DEFAULT NULL,
  `descripcion` text NOT NULL,
  `objetivo` varchar(50) NOT NULL,
  `participantes` int(5) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time DEFAULT NULL,
  `dia_semana` text NOT NULL,
  `estado` enum('pendiente','confirmada','ejecutada','cancelada') NOT NULL DEFAULT 'pendiente',
  `responsable` int(10) DEFAULT NULL,
  `telefono_responsable` varchar(20) DEFAULT NULL,
  `id_parroquia` int(10) DEFAULT NULL,
  `id_comuna` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `actividad`
--

INSERT INTO `actividad` (`id`, `id_solicitud`, `id_biblioteca`, `id_espacio_cultural`, `nombre`, `id_tipo_actividad`, `id_nivel_impacto`, `descripcion`, `objetivo`, `participantes`, `fecha`, `hora`, `dia_semana`, `estado`, `responsable`, `telefono_responsable`, `id_parroquia`, `id_comuna`) VALUES
(23, NULL, 15, 5, 'Biblioteca 1', 4, NULL, 'Solicitud 1', 'Responsable 1', 20, '2026-08-09', NULL, 'Domingo', 'confirmada', 13, '2147483647', NULL, NULL),
(27, NULL, 15, NULL, 'Arístides Bastidas', 5, 3, 'hola', 'hola', 10, '2026-08-26', '12:53:00', 'Miércoles', 'confirmada', 15, '04125240489', NULL, NULL),
(28, NULL, 15, NULL, 'Arístides Bastidas', 5, 3, 'holi', 'formativa', 21, '2026-08-05', '18:58:00', 'Miércoles', 'confirmada', 15, '2147483647', NULL, NULL),
(30, NULL, 15, NULL, 'juego de ajedrez', 5, 3, 'hola', 'Formativa', 20, '2026-08-05', '13:38:00', 'Miércoles', 'confirmada', 13, '04125240489', NULL, NULL),
(31, NULL, 15, NULL, 'Arístides Bastidas', 5, NULL, 'cosas', 'No definido', 0, '2026-08-20', '17:41:00', 'Jueves', 'confirmada', 13, '04125240489', NULL, NULL),
(32, NULL, 15, NULL, 'Juego de Ajedrez', 5, 3, 'Actividad de jugar ajedrez realizada en Biblioteca', 'Formativa', 2, '2026-08-27', '13:04:00', 'Jueves', 'confirmada', 13, '04125240489', NULL, NULL),
(33, NULL, 15, NULL, 'Juego de Ajedrez', 5, NULL, 'hola', 'No definido', 0, '2026-08-27', '16:33:00', 'Jueves', 'confirmada', 13, '04125240489', NULL, NULL),
(34, NULL, 15, NULL, 'Juego de Ajedrez', 5, NULL, 'hola', 'No definido', 0, '2026-07-17', '16:39:00', 'Viernes', 'confirmada', 13, '04125240489', NULL, NULL),
(35, NULL, 15, NULL, 'juego de mesa', 4, 3, 'cosas', 'formal', 21, '2026-09-05', '19:55:00', 'Sábado', 'cancelada', 15, '04125240489', NULL, NULL),
(36, NULL, 15, NULL, 'biblioteca', 5, NULL, 'a', 'jesus serrano', 1, '2026-08-13', '21:24:00', 'Jueves', 'confirmada', 13, '04125240489', NULL, NULL),
(37, NULL, 15, NULL, 'juego de mesa', 5, NULL, 'cosas', 'No definido', 0, '2026-10-03', '16:55:00', 'Sábado', 'pendiente', 28, '04127168235', NULL, NULL),
(38, NULL, 16, NULL, 'Felix Pifano', 5, 6, 'felix', 'No definido', 0, '2026-10-04', '18:12:00', 'Domingo', 'pendiente', 28, '04127168235', 52, 118),
(39, NULL, NULL, 4, 'juego de metras', 5, 3, 'van a jugar metras para entender como era este juego tradicional', 'para que vean los juegos tradicionales', 150, '2026-11-03', '10:00:00', 'Martes', 'pendiente', 13, '04125240489', 56, 31);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asignacion`
--

CREATE TABLE `asignacion` (
  `id` int(10) NOT NULL,
  `id_cargo` int(11) NOT NULL,
  `id_biblioteca` int(10) NOT NULL,
  `fecha_desde` date DEFAULT NULL,
  `fecha_hasta` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `biblioteca`
--

CREATE TABLE `biblioteca` (
  `id` int(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `id_parroquia` int(10) NOT NULL,
  `Correo` varchar(30) NOT NULL DEFAULT '',
  `redes_sociales` varchar(30) NOT NULL DEFAULT '',
  `Direccion` varchar(30) NOT NULL DEFAULT '',
  `id_solicitud_actividad` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `biblioteca`
--

INSERT INTO `biblioteca` (`id`, `nombre`, `id_parroquia`, `Correo`, `redes_sociales`, `Direccion`, `id_solicitud_actividad`) VALUES
(15, 'biblioteca', 54, 'cesarmcontre28@gmail.com', 'cesarm2', 'calle 4', NULL),
(16, 'Felix Pifano', 47, '', '', '', NULL),
(17, 'Oswaldo Méndez', 64, '', '', '', NULL),
(18, 'Rómulo Gallegos', 62, '', '', '', NULL),
(19, 'Rubén Suárez Moreno', 49, '', '', '', NULL),
(20, 'Domingo Oviedo Parada', 67, '', '', '', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bitacora`
--

CREATE TABLE `bitacora` (
  `id` int(10) NOT NULL,
  `nom_dia` varchar(15) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_usu` int(10) DEFAULT NULL,
  `accion` varchar(30) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `detalle` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `bitacora`
--

INSERT INTO `bitacora` (`id`, `nom_dia`, `fecha`, `hora`, `id_usu`, `accion`, `descripcion`, `detalle`) VALUES
(1, 'Sabado', '2026-08-15', '20:02:49', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(2, 'Sabado', '2026-08-15', '21:32:57', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(3, 'Sabado', '2026-08-15', '21:33:05', 11, 'Editar', 'Municipio', 'Municipio #21 actualizado: Arístides Bastida'),
(4, 'Sabado', '2026-08-15', '21:33:24', 11, 'Editar', 'Municipio', 'Municipio #21 actualizado: Arístides Bastidas'),
(5, 'Sabado', '2026-08-15', '21:33:27', 11, 'Crear', 'Municipio', 'Municipio registrado: hola'),
(6, 'Sabado', '2026-08-15', '21:33:34', 11, 'Eliminar', 'Municipio', 'Municipio #24 eliminado'),
(7, 'Sabado', '2026-08-15', '21:34:46', 11, 'Editar', 'Solicitud', 'Solicitud #4 actualizada'),
(8, 'Sabado', '2026-08-15', '21:46:28', 12, 'Login', 'Usuario', 'Inicio de sesión: jesus'),
(9, 'Lunes', '2026-08-17', '19:17:18', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(10, 'Lunes', '2026-08-17', '19:30:26', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(11, 'Lunes', '2026-08-17', '20:55:48', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(12, 'Lunes', '2026-08-17', '20:56:24', 11, 'Crear', 'Municipio', 'Municipio registrado: hola'),
(13, 'Lunes', '2026-08-17', '20:56:29', 11, 'Eliminar', 'Municipio', 'Municipio #25 eliminado'),
(14, 'Lunes', '2026-08-17', '20:57:02', 11, 'Crear', 'Solicitud', 'Solicitud registrada para institución #2'),
(15, 'Lunes', '2026-08-17', '23:26:27', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(16, 'Martes', '2026-08-18', '17:52:10', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(17, 'Martes', '2026-08-18', '23:46:34', 11, 'Crear', 'Actividad', 'Actividad registrada: Arístides Bastidas'),
(18, 'Martes', '2026-08-18', '23:52:00', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(19, 'Miercoles', '2026-08-19', '00:10:47', 11, 'Crear', 'Actividad', 'Actividad registrada: Arístides Bastidas'),
(20, 'Miercoles', '2026-08-19', '00:56:20', 11, 'Editar', 'Actividad', 'Actividad #28 actualizada: Arístides Bastidas'),
(21, 'Miercoles', '2026-08-19', '18:46:26', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(22, 'Miercoles', '2026-08-19', '18:49:14', 11, 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas'),
(23, 'Miercoles', '2026-08-19', '18:53:01', 11, 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas'),
(24, 'Miercoles', '2026-08-19', '19:03:11', 11, 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas'),
(25, 'Miercoles', '2026-08-19', '19:03:32', 11, 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas'),
(26, 'Miercoles', '2026-08-19', '19:06:53', 11, 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas'),
(27, 'Miercoles', '2026-08-19', '19:07:32', 11, 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas'),
(28, 'Miercoles', '2026-08-19', '19:07:44', 11, 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas'),
(29, 'Miercoles', '2026-08-19', '19:08:52', 11, 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas'),
(30, 'Miercoles', '2026-08-19', '19:12:47', 11, 'Crear', 'Actividad', 'Actividad registrada: hola'),
(31, 'Miercoles', '2026-08-19', '19:36:11', 11, 'Crear', 'Actividad', 'Actividad registrada: juego de ajedrez'),
(32, 'Miercoles', '2026-08-19', '19:41:21', 11, 'Crear', 'Actividad', 'Actividad registrada: Arístides Bastidas'),
(33, 'Miercoles', '2026-08-19', '19:42:56', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(34, 'Domingo', '2026-08-23', '21:29:26', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(35, 'Domingo', '2026-08-23', '21:39:45', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(36, 'Domingo', '2026-08-23', '21:46:34', 11, 'Eliminar', 'Empleado', 'Empleado #26 eliminado'),
(37, 'Domingo', '2026-08-23', '21:46:37', 11, 'Eliminar', 'Empleado', 'Empleado #23 eliminado'),
(38, 'Domingo', '2026-08-23', '21:51:05', 11, 'Editar', 'Empleado', 'Empleado #13 actualizado: jesus serrano'),
(39, 'Domingo', '2026-08-23', '21:53:17', 11, 'Editar', 'Solicitud', 'Solicitud #3 actualizada'),
(40, 'Miercoles', '2026-08-26', '18:55:44', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(41, 'Miercoles', '2026-08-26', '19:00:39', 11, 'Crear', 'Actividad', 'Actividad registrada: Juego de Ajedrez'),
(42, 'Miercoles', '2026-08-26', '19:01:18', 11, 'Editar', 'Actividad', 'Actividad #32 actualizada: Juego de Ajedrez'),
(43, 'Miercoles', '2026-08-26', '22:18:29', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(44, 'Miercoles', '2026-08-26', '22:30:02', 11, 'Crear', 'Actividad', 'Actividad registrada: Juego de Ajedrez'),
(45, 'Miercoles', '2026-08-26', '22:36:24', 11, 'Crear', 'Actividad', 'Actividad registrada: Juego de Ajedrez'),
(46, 'Jueves', '2026-09-03', '23:41:51', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(47, 'Jueves', '2026-09-03', '23:42:14', 12, 'Login', 'Usuario', 'Inicio de sesión: jesus'),
(48, 'Jueves', '2026-09-03', '23:56:21', 12, 'Crear', 'Actividad', 'Actividad registrada: juego de mesa'),
(49, 'Jueves', '2026-09-03', '23:59:11', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(50, 'Viernes', '2026-09-04', '00:00:03', 11, 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa'),
(51, 'Viernes', '2026-09-04', '00:00:08', 11, 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa'),
(52, 'Viernes', '2026-09-04', '00:00:17', 11, 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa'),
(53, 'Viernes', '2026-09-04', '00:03:32', 11, 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa'),
(54, 'Viernes', '2026-09-04', '00:04:55', 11, 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa'),
(55, 'Viernes', '2026-09-04', '00:05:00', 11, 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa'),
(56, 'Miercoles', '2026-09-09', '22:14:47', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(57, 'Jueves', '2026-09-17', '20:28:37', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(58, 'Jueves', '2026-09-17', '20:35:11', 11, 'Eliminar', 'Tipo de actividad', 'Tipo de actividad #7 eliminado'),
(59, 'Jueves', '2026-09-17', '20:35:14', 11, 'Crear', 'Tipo de actividad', 'Tipo de actividad registrado: hola'),
(60, 'Jueves', '2026-09-17', '20:36:02', 11, 'Crear', 'Nivel de impacto', 'Nivel de impacto registrado: municipal'),
(61, 'Jueves', '2026-09-17', '20:36:12', 11, 'Eliminar', 'Cargo', 'Cargo #3 eliminado'),
(62, 'Jueves', '2026-09-17', '20:39:17', 11, 'Crear', 'Actividad', 'Actividad registrada: biblioteca'),
(63, 'Jueves', '2026-09-17', '20:39:34', 11, 'Eliminar', 'Solicitud', 'Solicitud #4 eliminada'),
(64, 'Jueves', '2026-09-17', '20:39:36', 11, 'Eliminar', 'Solicitud', 'Solicitud #6 eliminada'),
(65, 'Jueves', '2026-09-17', '20:39:38', 11, 'Eliminar', 'Solicitud', 'Solicitud #3 eliminada'),
(66, 'Jueves', '2026-09-17', '20:39:41', 11, 'Eliminar', 'Solicitud', 'Solicitud #1 eliminada'),
(67, 'Jueves', '2026-09-17', '20:39:44', 11, 'Eliminar', 'Solicitud', 'Solicitud #5 eliminada'),
(68, 'Jueves', '2026-09-17', '20:42:19', 12, 'Login', 'Usuario', 'Inicio de sesión: jesus'),
(69, 'Jueves', '2026-09-17', '20:42:38', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(70, 'Jueves', '2026-09-17', '20:47:47', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(71, 'Viernes', '2026-10-02', '16:35:44', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/index.php'),
(72, 'Viernes', '2026-10-02', '16:42:17', 11, 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa'),
(73, 'Viernes', '2026-10-02', '16:42:48', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/main2.php'),
(74, 'Viernes', '2026-10-02', '16:43:58', 11, 'Crear', 'Empleado', 'Empleado registrado: freddy espinoza'),
(75, 'Viernes', '2026-10-02', '16:47:50', 11, 'Crear', 'Empleado', 'Empleado registrado: cesar ontreras'),
(76, 'Viernes', '2026-10-02', '16:53:01', 11, 'Crear', 'Actividad', 'Actividad registrada: juego de mesa'),
(77, 'Viernes', '2026-10-02', '16:59:21', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/maestro.php?tabla=institucion'),
(78, 'Viernes', '2026-10-02', '17:05:23', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/maestro.php?tabla=empleado'),
(79, 'Sabado', '2026-10-03', '18:05:25', 11, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/bitacora.php'),
(80, 'Sabado', '2026-10-03', '18:05:27', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(81, 'Sabado', '2026-10-03', '18:08:15', 11, 'Crear', 'Biblioteca', 'Biblioteca registrada: Felix Pifano'),
(82, 'Sabado', '2026-10-03', '18:08:40', 11, 'Editar', 'Empleado', 'Empleado #28 actualizado: cesar ontreras'),
(83, 'Sabado', '2026-10-03', '18:09:10', 11, 'Crear', 'Usuario', 'Usuario registrado: cesar'),
(84, 'Sabado', '2026-10-03', '18:09:21', 13, 'Login', 'Usuario', 'Inicio de sesión: cesar'),
(85, 'Sabado', '2026-10-03', '18:10:02', 13, 'Crear', 'Actividad', 'Actividad registrada: Felix Pifano'),
(86, 'Lunes', '2026-10-05', '16:02:31', 13, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php'),
(87, 'Lunes', '2026-10-05', '16:02:33', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(88, 'Miercoles', '2026-10-07', '14:59:39', 11, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php'),
(89, 'Miercoles', '2026-10-07', '14:59:43', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(90, 'Miercoles', '2026-10-07', '15:05:27', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/reportes_cargo/exportar_cargos.php'),
(91, 'Miercoles', '2026-10-07', '15:05:47', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/reportes_cargo/exportar_cargos.php'),
(92, 'Miercoles', '2026-10-07', '15:06:31', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/maestro.php?tabla=cargo'),
(93, 'Miercoles', '2026-10-07', '16:25:01', 11, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php'),
(94, 'Miercoles', '2026-10-07', '16:25:03', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(95, 'Miercoles', '2026-10-28', '10:35:57', 11, 'Editar', 'Solicitud', 'Solicitud #2 actualizada'),
(96, 'Miercoles', '2026-10-28', '10:36:58', 11, 'Editar', 'Solicitud', 'Solicitud #2 actualizada'),
(97, 'Sabado', '2026-10-31', '11:30:28', 11, 'Crear', 'Solicitud', 'Solicitud registrada para institución #1'),
(98, 'Sabado', '2026-10-31', '11:31:17', 11, 'Crear', 'Actividad', 'Actividad registrada: juego de metras'),
(99, 'Sabado', '2026-10-31', '11:34:00', 11, 'Crear', 'Empleado', 'Empleado registrado: maria narvaez'),
(100, 'Viernes', '2026-11-06', '15:54:23', 11, 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano'),
(101, 'Viernes', '2026-11-06', '15:55:51', 11, 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano'),
(102, 'Viernes', '2026-11-06', '16:01:02', 11, 'Editar', 'Actividad', 'Actividad #39 actualizada: juego de metras'),
(103, 'Viernes', '2026-11-06', '16:03:28', 11, 'Editar', 'Actividad', 'Actividad #39 actualizada: juego de metras'),
(104, 'Viernes', '2026-11-06', '16:32:50', 11, 'Editar', 'Actividad', 'Actividad #39 actualizada: juego de metras'),
(105, 'Viernes', '2026-11-06', '16:33:08', 11, 'Editar', 'Actividad', 'Actividad #39 actualizada: juego de metras'),
(106, 'Viernes', '2026-11-06', '16:55:31', 11, 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano'),
(107, 'Viernes', '2026-11-06', '16:55:45', 11, 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano'),
(108, 'Viernes', '2026-11-06', '17:07:20', 11, 'Editar', 'Actividad', 'Actividad #39 actualizada: juego de metras'),
(109, 'Viernes', '2026-11-06', '19:32:09', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar'),
(110, 'Viernes', '2026-11-06', '19:42:33', 11, 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano'),
(111, 'Viernes', '2026-11-06', '19:57:31', 11, 'Eliminar', 'Actividad', 'Actividad #24 eliminada'),
(112, 'Viernes', '2026-11-06', '19:57:36', 11, 'Eliminar', 'Actividad', 'Actividad #29 eliminada'),
(113, 'Viernes', '2026-11-06', '19:58:43', 11, 'Crear', 'Biblioteca', 'Biblioteca registrada: Oswaldo Méndez'),
(114, 'Viernes', '2026-11-06', '19:59:26', 11, 'Crear', 'Biblioteca', 'Biblioteca registrada: Rómulo Gallegos'),
(115, 'Viernes', '2026-11-06', '20:00:29', 11, 'Crear', 'Biblioteca', 'Biblioteca registrada: Rubén Suárez Moreno'),
(116, 'Viernes', '2026-11-06', '20:01:35', 11, 'Crear', 'Biblioteca', 'Biblioteca registrada: Domingo Oviedo Parada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(36) NOT NULL,
  `Descripcion` varchar(40) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`id`, `nombre`, `Descripcion`) VALUES
(1, 'coordinador', '2');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comuna`
--

CREATE TABLE `comuna` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `id_parroquia` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `comuna`
--

INSERT INTO `comuna` (`id`, `nombre`, `id_parroquia`) VALUES
(105, 'AGRICOLA VOCES DEL CAMPO', 54),
(41, 'AGRO ECOLOGICA TURISTICA GENER', 62),
(44, 'AGRO INDUSTRIAL TURISTICA GENE', 61),
(92, 'AGROPRODUCTIVA CARACARO BICENT', 46),
(95, 'AGROPRODUCTIVA SEMBRANDO EN RE', 46),
(91, 'AGROPRODUCTIVA ZONA SUR 5 DE M', 46),
(64, 'AGROSOCIALISTA BOLIVAR Y CHAVE', 66),
(43, 'AGROTURISTICA EL ESFUERZO Y LA', 62),
(36, 'ANGEL RAFAEL GONZALEZ', 62),
(38, 'CERRO AZUL TRONCAL 11', 62),
(118, 'CHE GUEVARA LEGENDARIO SOCIALI', 52),
(8, 'comuna', 54),
(17, 'COMUNA SOCIALISTA AGROPRODUCTI', 55),
(21, 'COMUNA SOCIALISTA PRODUCTIVA M', 55),
(74, 'COMUNA SOCIALISTA PRODUCTIVA N', 64),
(109, 'COMUNA SOCIALISTA PRODUCTIVA S', 60),
(80, 'COMUNA SOCIALISTA PRODUCTIVA S', 65),
(112, 'COMUNA SOCIALISTA Y PRODUCTIVA', 60),
(47, 'ECOLOGICA JORGE RODRIGUEZ EJE', 47),
(86, 'ECOSOCIALISTA BUENA VISTA', 59),
(94, 'ECOSOCIALISTA CAMPAÑA ADMIRABL', 46),
(96, 'ECOSOCIALISTA CORAZON DE MI PA', 46),
(102, 'EDUCATIVA PRODUCTIVA ECOTURIST', 57),
(52, 'EJE GUAYABAL', 47),
(24, 'HIJOS DE CHAVEZ', 49),
(12, 'LA NUEVA UNION DE LAS CUMARAGU', 55),
(81, 'LA UNION DE LOS PUEBLOS SOCIAL', 65),
(34, 'NIRGUA PRODUCTIVA', 62),
(78, 'PRODUCTIVA FRANCISCO DE MIRAND', 64),
(79, 'PRODUCTIVA SEGUIREMOS TENIENDO', 64),
(16, 'PRODUCTIVA SOCIALISTA CHAVEZ P', 55),
(115, 'PUEBLO TIERRA Y HOMBRES LIBRES', 53),
(119, 'ROBERT SERRA', 52),
(20, 'SIERRA AZUL CARABOBO', 55),
(11, 'SOCIALISTA 12 DE ABRIL', 55),
(61, 'SOCIALISTA 12 DE OCTUBRE', 66),
(23, 'SOCIALISTA AGRARIA ENDOGENA EC', 49),
(37, 'SOCIALISTA AGRICOLA LEGUMINOSA', 62),
(110, 'SOCIALISTA AGRICOLA TURISTICA', 60),
(46, 'SOCIALISTA AGROECOLOGICA SIMON', 47),
(33, 'SOCIALISTA AGROECOLOGICA TURIS', 62),
(55, 'SOCIALISTA AGROECOLOGICA YURUB', 3),
(25, 'SOCIALISTA AGROPECUARIA MARIA', 49),
(57, 'SOCIALISTA AGROPRODUCTIVA AURA', 3),
(59, 'SOCIALISTA AGROPRODUCTIVA CIMA', 48),
(58, 'SOCIALISTA AGROPRODUCTIVA ECOL', 48),
(13, 'SOCIALISTA AGROPRODUCTIVA EJE', 55),
(18, 'SOCIALISTA AGROPRODUCTIVA RIO', 55),
(82, 'SOCIALISTA AGROTURISTICA ECOLO', 58),
(26, 'SOCIALISTA AGROTURISTICA INDUS', 49),
(117, 'SOCIALISTA AGROTURISTICA RIO T', 52),
(62, 'SOCIALISTA AGROTURISTICA Y PRO', 66),
(70, 'SOCIALISTA AGUA LINDA', 64),
(66, 'SOCIALISTA ALI PRIMERA', 67),
(15, 'SOCIALISTA ALIANZA CAMPESINA', 55),
(45, 'SOCIALISTA AMBROCIO PEREIRA', 63),
(10, 'SOCIALISTA ARBOL DE LAS TRES R', 55),
(68, 'SOCIALISTA CAMUNARE ROJO', 67),
(84, 'SOCIALISTA CENTAUROS DE PAEZ', 58),
(50, 'SOCIALISTA CIUDADELA COMANDANT', 47),
(73, 'SOCIALISTA COMANDANTE SUPREMO', 64),
(54, 'SOCIALISTA CULTURAL AGROPRODUC', 3),
(77, 'SOCIALISTA DIOS ES MI AMIGO', 64),
(56, 'SOCIALISTA DR. JUAN JOSE DE MA', 3),
(69, 'SOCIALISTA ECOLOGICA LINA RON', 64),
(120, 'SOCIALISTA EL GUAYABO', 52),
(63, 'SOCIALISTA EL RENACER DEL TIMO', 66),
(103, 'SOCIALISTA EMPRENDEDORES DEL G', 57),
(101, 'SOCIALISTA FERMIN ABDOM RAMIRE', 57),
(90, 'SOCIALISTA FORJADORES DE LA PA', 59),
(32, 'SOCIALISTA GUERREROS DE LA MON', 62),
(40, 'SOCIALISTA GUERREROS JIRAHARA', 62),
(85, 'SOCIALISTA INDEPENDENCIA 18 DE', 59),
(100, 'SOCIALISTA INDEPENDENCIA Y PAT', 57),
(116, 'SOCIALISTA JOSE JOAQUIN VEROES', 53),
(97, 'SOCIALISTA JUAN MILLA', 46),
(89, 'SOCIALISTA LA SABANA DE PALITO', 59),
(113, 'SOCIALISTA LOS CAÑIZOS PALO QU', 53),
(39, 'SOCIALISTA MANUEL CEDEÑO', 62),
(19, 'SOCIALISTA MARIA MIRANDA', 55),
(48, 'SOCIALISTA MARIA VALENTINA PIN', 47),
(28, 'SOCIALISTA MAXIMO VIZCAYA', 56),
(14, 'SOCIALISTA MELECIO TORRES', 55),
(42, 'SOCIALISTA NEGRO MIGUEL DE BUR', 62),
(104, 'SOCIALISTA PETRA BETANCOURT', 54),
(60, 'SOCIALISTA POPULAR Y CHAVISTA', 48),
(29, 'SOCIALISTA PRODUCTIVA AGROENDO', 56),
(30, 'SOCIALISTA PRODUCTIVA DOMINGO', 56),
(107, 'SOCIALISTA PRODUCTIVA FERROCAR', 60),
(83, 'SOCIALISTA PRODUCTIVA TIERRAS', 58),
(65, 'SOCIALISTA PRODUCTORAS Y PRODU', 66),
(114, 'SOCIALISTA PUEBLOS UNIDOS SIMO', 53),
(22, 'SOCIALISTA ROSA LUXEMBURGO', 49),
(53, 'SOCIALISTA SAN FELIPE EL FUERT', 47),
(35, 'SOCIALISTA SANTANA OCHOA', 62),
(9, 'SOCIALISTA SIERRA NUBLADA', 55),
(49, 'SOCIALISTA SIMON TORRES', 47),
(106, 'SOCIALISTA SOBERANOS DE LA PAT', 54),
(27, 'SOCIALISTA SOCIOPRODUCTIVA TRO', 49),
(88, 'SOCIALISTA TRINIDAD VICTORIOSO', 59),
(87, 'SOCIALISTA UN NUEVO RENACER 20', 59),
(51, 'SOCIALISTA UNIDAD LUCHA BATALL', 47),
(67, 'SOCIALISTA URACHICHEÑA HUGO RA', 67),
(72, 'SOCIALISTA URIMIQUIRE', 64),
(98, 'SOCIALISTA VENCEDORES Y VENCED', 46),
(99, 'SOCIALISTA WILLIAM LARA VIVE', 57),
(111, 'SOCIALISTA Y PRODUCTIVA JOSE F', 60),
(108, 'SOCIALISTA Y PRODUCTIVA MANUEL', 60),
(93, 'SOCIOPRODUCTIVA 7 RAICES CECIL', 46),
(76, 'SOCIOPRODUCTIVA CHAVEZ VIVE', 64),
(71, 'SOCIOPRODUCTIVA MUJERES BICENT', 64),
(31, 'SOCIOPRODUCTIVA NEGRA MATEA', 56),
(75, 'SOCIOPRODUCTIVA VALEROSO PEÑA', 64);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `id` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `cedula` int(8) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `genero` varchar(10) NOT NULL,
  `edad` int(2) UNSIGNED NOT NULL,
  `anios_de_servicio` int(2) UNSIGNED NOT NULL,
  `id_cargo` int(10) NOT NULL,
  `id_biblioteca` int(10) NOT NULL,
  `fecha_inicio_cargo` date DEFAULT NULL,
  `fecha_fin_cargo` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id`, `nombre`, `apellido`, `cedula`, `telefono`, `genero`, `edad`, `anios_de_servicio`, `id_cargo`, `id_biblioteca`, `fecha_inicio_cargo`, `fecha_fin_cargo`) VALUES
(13, 'jesus', 'serrano', 31982637, '04125240489', 'M', 1, 0, 1, 15, NULL, NULL),
(15, 'jesus', 'soto', 32435607, '04125240489', '', 0, 0, 1, 15, NULL, NULL),
(27, 'freddy', 'espinoza', 32626427, '04145058143', 'M', 20, 1, 1, 15, '2026-10-03', '2026-10-03'),
(28, 'cesar', 'ontreras', 31982333, '04127168235', 'M', 20, 18, 1, 16, '2026-10-04', NULL),
(29, 'maria', 'narvaez', 15964066, '04261181304', 'M', 30, 7, 1, 16, '2019-02-10', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `espacio_cultural`
--

CREATE TABLE `espacio_cultural` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `capacidad` int(10) NOT NULL,
  `direccion` varchar(30) NOT NULL,
  `Metodo_contactar` varchar(40) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `espacio_cultural`
--

INSERT INTO `espacio_cultural` (`id`, `nombre`, `capacidad`, `direccion`, `Metodo_contactar`) VALUES
(4, 'tu casa', 6, 'independencia', ''),
(5, 'mi casa', 10, 'independencia', 'mi casa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institucion`
--

CREATE TABLE `institucion` (
  `id` int(10) NOT NULL,
  `id_municipio` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `rif` varchar(12) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `institucion`
--

INSERT INTO `institucion` (`id`, `id_municipio`, `nombre`, `rif`, `correo`, `direccion`) VALUES
(1, 4, 'Institucion 1', NULL, NULL, NULL),
(2, 8, 'escuela', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipio`
--

CREATE TABLE `municipio` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `municipio`
--

INSERT INTO `municipio` (`id`, `nombre`) VALUES
(21, 'Arístides Bastidas'),
(13, 'Bolívar'),
(5, 'Bruzual'),
(6, 'Cocorote'),
(4, 'Independencia'),
(15, 'José Antonio Páez'),
(11, 'La Trinidad'),
(10, 'Manuel Monge'),
(9, 'Nirgua'),
(12, 'Peña'),
(2, 'San Felipe'),
(3, 'Sucre'),
(7, 'Urachiche'),
(8, 'Veroes');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nivel_impacto`
--

CREATE TABLE `nivel_impacto` (
  `id` int(10) NOT NULL,
  `nombre_impacto` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `nivel_impacto`
--

INSERT INTO `nivel_impacto` (`id`, `nombre_impacto`) VALUES
(3, 'Comunal'),
(4, 'estadal'),
(5, 'municipal'),
(6, 'nacional');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parroquia`
--

CREATE TABLE `parroquia` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `id_municipio` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `parroquia`
--

INSERT INTO `parroquia` (`id`, `nombre`, `id_municipio`) VALUES
(17, 'Albarico', 0),
(3, 'Albarico', 2),
(54, 'Arístides Bastidas', 21),
(55, 'Bolivar', 13),
(5, 'Campo Elías', 0),
(56, 'Campo Elias', 5),
(4, 'Chivacoa', 0),
(49, 'Chivacoa', 5),
(6, 'Cocorote', 0),
(46, 'Cocorote', 6),
(21, 'El Guayabo', 0),
(52, 'El Guayabo', 8),
(22, 'Farriar', 0),
(53, 'Farriar', 8),
(7, 'Independencia', 0),
(57, 'Independencia', 4),
(58, 'José Antonio Paez', 15),
(9, 'La Trinidad', 0),
(59, 'La Trinidad', 11),
(10, 'Manuel Monge', 0),
(60, 'Manuel Monge', 10),
(13, 'Nirgua', 0),
(62, 'Nirgua', 9),
(11, 'Salóm', 0),
(61, 'Salom', 9),
(14, 'San Andrés', 0),
(65, 'San Andres', 12),
(51, 'San Andres', 13),
(18, 'San Felipe', 0),
(47, 'San Felipe', 2),
(16, 'San Javier', 0),
(48, 'San Javier', 2),
(19, 'Sucre', 0),
(66, 'Sucre', 3),
(63, 'Temeria', 9),
(12, 'Temerla', 0),
(20, 'Urachiche', 0),
(67, 'Urachiche', 7),
(15, 'Yaritagua', 0),
(64, 'Yaritagua', 12);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud`
--

CREATE TABLE `solicitud` (
  `id` int(10) NOT NULL,
  `id_institucion` int(10) NOT NULL,
  `id_biblioteca` int(10) DEFAULT NULL,
  `nombre_actividad` varchar(100) NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `hora_solicitud` time NOT NULL,
  `lugar` varchar(100) NOT NULL,
  `responsable` int(10) NOT NULL,
  `participantes` int(10) NOT NULL DEFAULT 0,
  `descripcion` varchar(250) NOT NULL,
  `estado` enum('pendiente','aprobada','rechazada','cancelada') NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud`
--

INSERT INTO `solicitud` (`id`, `id_institucion`, `id_biblioteca`, `nombre_actividad`, `fecha_solicitud`, `fecha_registro`, `hora_solicitud`, `lugar`, `responsable`, `participantes`, `descripcion`, `estado`) VALUES
(2, 1, 16, 'juego de ajedrez', '2026-11-30', '2026-10-02 20:33:41', '14:16:00', 'Felix Pifano', 28, 100, '1', 'pendiente'),
(7, 1, 15, 'juego de metras', '2026-11-03', '2026-10-31 11:30:28', '10:00:00', 'biblioteca', 13, 15, 'van a jugar metras para entender como era este juego tradicional', 'aprobada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_actividad`
--

CREATE TABLE `tipo_actividad` (
  `id` int(10) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `Descripcion` varchar(40) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `tipo_actividad`
--

INSERT INTO `tipo_actividad` (`id`, `nombre`, `Descripcion`) VALUES
(4, 'eduativa', '0004'),
(5, 'educativa', ''),
(8, 'hola', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id` int(10) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `rol` enum('administrador','usuario') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `clave`, `telefono`, `id_empleado`, `rol`) VALUES
(11, 'cheddar', '$2y$10$gDEMnG9XXqwiKvUQNP95BOzYUQ/KcRkmZcCqfzHi.WvkowTeqRjwq', '04125240489', 15, 'administrador'),
(12, 'jesus', '$2y$10$A/oi0oFJTyOERvUGmZTbReSn6CeDT.kUzbpwx5TkBRRShSp14658a', '04125240489', 13, 'usuario'),
(13, 'cesar', '$2y$10$xzjKL4an985Ht74uyBixF.ECl..GSE.47mJhP3vdR7zG8T/AS4pDS', '04127168235', 28, 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividad`
--
ALTER TABLE `actividad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_solicitud` (`id_solicitud`),
  ADD KEY `id_biblioteca` (`id_biblioteca`),
  ADD KEY `id_espacio_cultural` (`id_espacio_cultural`),
  ADD KEY `id_tipo_actividad` (`id_tipo_actividad`),
  ADD KEY `actividad_ibfk_nivel` (`id_nivel_impacto`),
  ADD KEY `actividad_ibfk_responsable` (`responsable`),
  ADD KEY `actividad_ibfk_parroquia` (`id_parroquia`),
  ADD KEY `actividad_ibfk_comuna` (`id_comuna`);

--
-- Indices de la tabla `asignacion`
--
ALTER TABLE `asignacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_biblioteca` (`id_biblioteca`),
  ADD KEY `id_cargo` (`id_cargo`);

--
-- Indices de la tabla `biblioteca`
--
ALTER TABLE `biblioteca`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_biblioteca_nombre_parroquia` (`nombre`,`id_parroquia`),
  ADD KEY `id_parroquia` (`id_parroquia`),
  ADD KEY `id_solicitud` (`id_solicitud_actividad`);

--
-- Indices de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usu` (`id_usu`),
  ADD KEY `fecha` (`fecha`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cargo_nombre` (`nombre`);

--
-- Indices de la tabla `comuna`
--
ALTER TABLE `comuna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_comuna_nombre_parroquia` (`nombre`,`id_parroquia`),
  ADD KEY `id_parroquia` (`id_parroquia`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `id_cargo` (`id_cargo`),
  ADD KEY `id_biblioteca` (`id_biblioteca`);

--
-- Indices de la tabla `espacio_cultural`
--
ALTER TABLE `espacio_cultural`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_espacio_cultural_nombre` (`nombre`);

--
-- Indices de la tabla `institucion`
--
ALTER TABLE `institucion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_institucion_rif` (`rif`),
  ADD KEY `id_municipio` (`id_municipio`);

--
-- Indices de la tabla `municipio`
--
ALTER TABLE `municipio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_municipio_nombre` (`nombre`);

--
-- Indices de la tabla `nivel_impacto`
--
ALTER TABLE `nivel_impacto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_nivel_impacto_nombre` (`nombre_impacto`);

--
-- Indices de la tabla `parroquia`
--
ALTER TABLE `parroquia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_parroquia_nombre_municipio` (`nombre`,`id_municipio`),
  ADD KEY `id_municipio` (`id_municipio`);

--
-- Indices de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_institucion` (`id_institucion`),
  ADD KEY `idx_solicitud_id_biblioteca` (`id_biblioteca`),
  ADD KEY `solicitud_ibfk_responsable` (`responsable`);

--
-- Indices de la tabla `tipo_actividad`
--
ALTER TABLE `tipo_actividad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuario_nombre` (`nombre`),
  ADD UNIQUE KEY `uq_usuario_empleado` (`id_empleado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividad`
--
ALTER TABLE `actividad`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `asignacion`
--
ALTER TABLE `asignacion`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `biblioteca`
--
ALTER TABLE `biblioteca`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `comuna`
--
ALTER TABLE `comuna`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `espacio_cultural`
--
ALTER TABLE `espacio_cultural`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `institucion`
--
ALTER TABLE `institucion`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `municipio`
--
ALTER TABLE `municipio`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `nivel_impacto`
--
ALTER TABLE `nivel_impacto`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `parroquia`
--
ALTER TABLE `parroquia`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `tipo_actividad`
--
ALTER TABLE `tipo_actividad`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividad`
--
ALTER TABLE `actividad`
  ADD CONSTRAINT `actividad_ibfk_1` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`),
  ADD CONSTRAINT `actividad_ibfk_2` FOREIGN KEY (`id_espacio_cultural`) REFERENCES `espacio_cultural` (`id`),
  ADD CONSTRAINT `actividad_ibfk_3` FOREIGN KEY (`id_tipo_actividad`) REFERENCES `tipo_actividad` (`id`),
  ADD CONSTRAINT `actividad_ibfk_comuna` FOREIGN KEY (`id_comuna`) REFERENCES `comuna` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `actividad_ibfk_nivel` FOREIGN KEY (`id_nivel_impacto`) REFERENCES `nivel_impacto` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `actividad_ibfk_parroquia` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `actividad_ibfk_responsable` FOREIGN KEY (`responsable`) REFERENCES `empleado` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `actividad_ibfk_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `asignacion`
--
ALTER TABLE `asignacion`
  ADD CONSTRAINT `asignacion_ibfk_1` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`),
  ADD CONSTRAINT `asignacion_ibfk_2` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id`);

--
-- Filtros para la tabla `biblioteca`
--
ALTER TABLE `biblioteca`
  ADD CONSTRAINT `biblioteca_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`),
  ADD CONSTRAINT `biblioteca_ibfk_2` FOREIGN KEY (`id_solicitud_actividad`) REFERENCES `solicitud` (`id`);

--
-- Filtros para la tabla `bitacora`
--
ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_usu`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `comuna`
--
ALTER TABLE `comuna`
  ADD CONSTRAINT `comuna_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id`),
  ADD CONSTRAINT `empleado_ibfk_biblioteca` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`);

--
-- Filtros para la tabla `institucion`
--
ALTER TABLE `institucion`
  ADD CONSTRAINT `institucion_ibfk_1` FOREIGN KEY (`id_municipio`) REFERENCES `municipio` (`id`);

--
-- Filtros para la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD CONSTRAINT `solicitud_ibfk_1` FOREIGN KEY (`id_institucion`) REFERENCES `institucion` (`id`),
  ADD CONSTRAINT `solicitud_ibfk_biblioteca` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`),
  ADD CONSTRAINT `solicitud_ibfk_responsable` FOREIGN KEY (`responsable`) REFERENCES `empleado` (`id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
