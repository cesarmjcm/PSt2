-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-08-2026 a las 21:36:11
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
-- Base de datos: `red_bibliotecas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad`
--

CREATE TABLE `actividad` (
  `id` int(10) NOT NULL,
  `id_biblioteca` int(10) NOT NULL,
  `id_espacio_cultural` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `id_tipo_actividad` int(10) NOT NULL,
  `descripcion` text NOT NULL,
  `objetivo` varchar(50) NOT NULL,
  `participantes` int(5) NOT NULL,
  `fecha` date NOT NULL,
  `dia_semana` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `actividad`
--

INSERT INTO `actividad` (`id`, `id_biblioteca`, `id_espacio_cultural`, `nombre`, `id_tipo_actividad`, `descripcion`, `objetivo`, `participantes`, `fecha`, `dia_semana`) VALUES
(23, 15, 5, 'Biblioteca 1', 4, 'Solicitud 1', 'Responsable 1', 20, '2026-08-09', 'Domingo'),
(24, 15, 5, 'zona2', 4, '2', 'carlos', 222, '2026-08-12', 'Miércoles');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_comuna`
--

CREATE TABLE `actividad_comuna` (
  `id` int(10) NOT NULL,
  `id_comuna` int(10) NOT NULL,
  `id_actividad` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `actividad_comuna`
--

INSERT INTO `actividad_comuna` (`id`, `id_comuna`, `id_actividad`) VALUES
(16, 8, 23),
(17, 8, 24);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_espaciocultural`
--

CREATE TABLE `actividad_espaciocultural` (
  `id` int(10) NOT NULL,
  `id_actividad` int(10) NOT NULL,
  `id_biblioteca` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(15, 'biblioteca', 54, 'cesarmcontre28@gmail.com', 'cesarm2', 'calle 4', NULL);

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
(8, 'comuna', 54);

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
  `id_cargo` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id`, `nombre`, `apellido`, `cedula`, `telefono`, `id_cargo`) VALUES
(13, 'jesus', 'serrano', 31982637, '04125240489', 1),
(15, 'jesus', 'soto', 32435607, '04125240489', 1);

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
-- Estructura de tabla para la tabla `impacto_actividad`
--

CREATE TABLE `impacto_actividad` (
  `id` int(10) NOT NULL,
  `id_impacto` int(10) NOT NULL,
  `id_actividad` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `impacto_actividad`
--

INSERT INTO `impacto_actividad` (`id`, `id_impacto`, `id_actividad`) VALUES
(1, 3, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `institucion`
--

CREATE TABLE `institucion` (
  `id` int(10) NOT NULL,
  `id_municipio` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `institucion`
--

INSERT INTO `institucion` (`id`, `id_municipio`, `nombre`) VALUES
(1, 4, 'Institucion 1');

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
(22, 'Arístides Bastida'),
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
(4, 'estadal');

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
-- Estructura de tabla para la tabla `responsable`
--

CREATE TABLE `responsable` (
  `id` int(10) NOT NULL,
  `id_actividad` int(10) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `telefono` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `responsable`
--

INSERT INTO `responsable` (`id`, `id_actividad`, `nombre`, `telefono`) VALUES
(1, 6, 'a', 1),
(3, 9, '1', 2),
(4, 10, '1', 1),
(5, 11, 'a', 1),
(6, 12, '1', 1),
(7, 8, 'a', 1),
(8, 13, 'a', 1),
(9, 14, 'cesar contreras', 2147483647),
(10, 15, 'cesar contreras', 1231232131),
(11, 16, 'cesar contreras', 2147483647),
(12, 17, 'cesar contreras', 2147483647),
(13, 18, 'carlos', 2147483647),
(14, 19, 'responsable', 2147483647),
(15, 21, 'cesar contreras', 2147483647),
(16, 22, 'jesus serrano', 2147483647),
(17, 23, 'jesus serrano', 2147483647),
(18, 24, 'jesus serrano', 2147483647);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitud`
--

CREATE TABLE `solicitud` (
  `id` int(10) NOT NULL,
  `id_institucion` int(10) NOT NULL,
  `fecha_solicitud` date NOT NULL,
  `hora_solicitud` time NOT NULL,
  `lugar` varchar(100) NOT NULL,
  `responsable` varchar(50) NOT NULL,
  `participantes` int(10) NOT NULL DEFAULT 0,
  `descripcion` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud`
--

INSERT INTO `solicitud` (`id`, `id_institucion`, `fecha_solicitud`, `hora_solicitud`, `lugar`, `responsable`, `participantes`, `descripcion`) VALUES
(1, 1, '2026-08-09', '08:00:00', 'Biblioteca 1', 'Responsable 1', 20, 'Solicitud 1'),
(2, 1, '2026-08-06', '14:16:00', 'zona', 'cesar contreras', 2, '1'),
(3, 1, '2026-08-12', '15:36:00', 'zona2', 'carlos', 222, '2');

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
(5, 'educativa', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion`
--

CREATE TABLE `ubicacion` (
  `id` int(10) NOT NULL,
  `id_comuna` int(10) NOT NULL,
  `id_parroquia` int(10) NOT NULL,
  `id_municipio` int(10) NOT NULL,
  `nombre_comuna` varchar(20) NOT NULL,
  `nombre_parroquia` varchar(20) NOT NULL,
  `nombre_municipio` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(11, 'cheddar', '$2y$10$gDEMnG9XXqwiKvUQNP95BOzYUQ/KcRkmZcCqfzHi.WvkowTeqRjwq', '04125240489', 15, 'administrador');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividad`
--
ALTER TABLE `actividad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_biblioteca` (`id_biblioteca`),
  ADD KEY `id_espacio_cultural` (`id_espacio_cultural`),
  ADD KEY `id_tipo_actividad` (`id_tipo_actividad`);

--
-- Indices de la tabla `actividad_comuna`
--
ALTER TABLE `actividad_comuna`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_comuna` (`id_comuna`),
  ADD KEY `id_actividad` (`id_actividad`);

--
-- Indices de la tabla `actividad_espaciocultural`
--
ALTER TABLE `actividad_espaciocultural`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_actividad` (`id_actividad`,`id_biblioteca`),
  ADD KEY `actividad_espaciocultural_ibfk_1` (`id_biblioteca`);

--
-- Indices de la tabla `biblioteca`
--
ALTER TABLE `biblioteca`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_biblioteca_nombre_parroquia` (`nombre`,`id_parroquia`),
  ADD KEY `id_parroquia` (`id_parroquia`),
  ADD KEY `id_solicitud` (`id_solicitud_actividad`);

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
  ADD KEY `id_cargo` (`id_cargo`);

--
-- Indices de la tabla `espacio_cultural`
--
ALTER TABLE `espacio_cultural`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_espacio_cultural_nombre` (`nombre`);

--
-- Indices de la tabla `impacto_actividad`
--
ALTER TABLE `impacto_actividad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_impacto` (`id_impacto`),
  ADD KEY `id_actividad` (`id_actividad`);

--
-- Indices de la tabla `institucion`
--
ALTER TABLE `institucion`
  ADD PRIMARY KEY (`id`),
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
-- Indices de la tabla `responsable`
--
ALTER TABLE `responsable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_actividad` (`id_actividad`);

--
-- Indices de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_institucion` (`id_institucion`);

--
-- Indices de la tabla `tipo_actividad`
--
ALTER TABLE `tipo_actividad`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_comuna` (`id_comuna`),
  ADD KEY `id_parroquia` (`id_parroquia`),
  ADD KEY `id_municipio` (`id_municipio`);

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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `actividad_comuna`
--
ALTER TABLE `actividad_comuna`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `actividad_espaciocultural`
--
ALTER TABLE `actividad_espaciocultural`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `biblioteca`
--
ALTER TABLE `biblioteca`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `comuna`
--
ALTER TABLE `comuna`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `espacio_cultural`
--
ALTER TABLE `espacio_cultural`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `impacto_actividad`
--
ALTER TABLE `impacto_actividad`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `institucion`
--
ALTER TABLE `institucion`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `municipio`
--
ALTER TABLE `municipio`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `nivel_impacto`
--
ALTER TABLE `nivel_impacto`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `parroquia`
--
ALTER TABLE `parroquia`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `responsable`
--
ALTER TABLE `responsable`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `tipo_actividad`
--
ALTER TABLE `tipo_actividad`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividad`
--
ALTER TABLE `actividad`
  ADD CONSTRAINT `actividad_ibfk_1` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`),
  ADD CONSTRAINT `actividad_ibfk_2` FOREIGN KEY (`id_espacio_cultural`) REFERENCES `espacio_cultural` (`id`),
  ADD CONSTRAINT `actividad_ibfk_3` FOREIGN KEY (`id_tipo_actividad`) REFERENCES `tipo_actividad` (`id`);

--
-- Filtros para la tabla `actividad_comuna`
--
ALTER TABLE `actividad_comuna`
  ADD CONSTRAINT `actividad_comuna_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `actividad` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `actividad_comuna_ibfk_2` FOREIGN KEY (`id_comuna`) REFERENCES `comuna` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `biblioteca`
--
ALTER TABLE `biblioteca`
  ADD CONSTRAINT `biblioteca_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`),
  ADD CONSTRAINT `biblioteca_ibfk_2` FOREIGN KEY (`id_solicitud_actividad`) REFERENCES `solicitud` (`id`);

--
-- Filtros para la tabla `comuna`
--
ALTER TABLE `comuna`
  ADD CONSTRAINT `comuna_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`);

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id`);

--
-- Filtros para la tabla `institucion`
--
ALTER TABLE `institucion`
  ADD CONSTRAINT `institucion_ibfk_1` FOREIGN KEY (`id_municipio`) REFERENCES `municipio` (`id`);

--
-- Filtros para la tabla `solicitud`
--
ALTER TABLE `solicitud`
  ADD CONSTRAINT `solicitud_ibfk_1` FOREIGN KEY (`id_institucion`) REFERENCES `institucion` (`id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
