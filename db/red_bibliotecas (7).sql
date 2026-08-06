-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-07-2026 a las 21:17:09
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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_comuna`
--

CREATE TABLE `actividad_comuna` (
  `id` int(10) NOT NULL,
  `id_comuna` int(10) NOT NULL,
  `id_actividad` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `Direccion` varchar(30) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `biblioteca`
--

INSERT INTO `biblioteca` (`id`, `nombre`, `id_parroquia`, `Correo`, `redes_sociales`, `Direccion`) VALUES
(1, 'Biblioteca 1', 4, '', '', ''),
(3, 'Biblioteca 3', 3, '', '', ''),
(7, 'felix pifano', 50, '', '', '000000000000000000000000000000');

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
(1, 'coordinador', '22222222222222222222222222222222');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comuna`
--

CREATE TABLE `comuna` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `id_parroquia` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
(13, 'Bolívar'),
(5, 'Bruzual'),
(6, 'Cocorote'),
(4, 'Independencia'),
(15, 'José Antonio Pá'),
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
(3, 'Comunal');

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
(50, 'Arístides Bastidas', 13),
(5, 'Campo Elías', 0),
(4, 'Chivacoa', 0),
(49, 'Chivacoa', 5),
(6, 'Cocorote', 0),
(46, 'Cocorote', 6),
(21, 'El Guayabo', 0),
(22, 'Farriar', 0),
(7, 'Independencia', 0),
(9, 'La Trinidad', 0),
(10, 'Manuel Monge', 0),
(13, 'Nirgua', 0),
(11, 'Salóm', 0),
(14, 'San Andrés', 0),
(51, 'San Andres', 13),
(18, 'San Felipe', 0),
(47, 'San Felipe', 2),
(16, 'San Javier', 0),
(48, 'San Javier', 2),
(19, 'Sucre', 0),
(12, 'Temerla', 0),
(20, 'Urachiche', 0),
(15, 'Yaritagua', 0);

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
(14, 19, 'responsable', 2147483647);

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
(4, 'eduativa', '000444444444444444444444444444');

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
  `id_empleado` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `nombre`, `clave`, `telefono`, `id_empleado`) VALUES
(6, 'jesus', '32435609', NULL, NULL),
(11, 'cheddar', '$2y$10$gDEMnG9XXqwiKvUQNP95BOzYUQ/KcRkmZcCqfzHi.WvkowTeqRjwq', '04127168235', NULL);

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
  ADD KEY `id_parroquia` (`id_parroquia`);

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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `actividad_comuna`
--
ALTER TABLE `actividad_comuna`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `actividad_espaciocultural`
--
ALTER TABLE `actividad_espaciocultural`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `biblioteca`
--
ALTER TABLE `biblioteca`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `comuna`
--
ALTER TABLE `comuna`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
-- AUTO_INCREMENT de la tabla `municipio`
--
ALTER TABLE `municipio`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `nivel_impacto`
--
ALTER TABLE `nivel_impacto`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `parroquia`
--
ALTER TABLE `parroquia`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `responsable`
--
ALTER TABLE `responsable`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `tipo_actividad`
--
ALTER TABLE `tipo_actividad`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  ADD CONSTRAINT `biblioteca_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`);

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
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
