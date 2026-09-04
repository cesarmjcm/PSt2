








SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


;
;
;
;










create database red_bibliotecas;
use red_bibliotecas;
CREATE TABLE `actividad` (
  `id` int(10) NOT NULL,
  `id_biblioteca` int(11) DEFAULT NULL,
  `id_espacio_cultural` int(11) DEFAULT NULL,
  `nombre` varchar(30) NOT NULL,
  `id_tipo_actividad` int(10) NOT NULL,
  `descripcion` text NOT NULL,
  `objetivo` varchar(50) NOT NULL,
  `participantes` int(5) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time DEFAULT NULL,
  `dia_semana` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





INSERT INTO `actividad` (`id`, `id_biblioteca`, `id_espacio_cultural`, `nombre`, `id_tipo_actividad`, `descripcion`, `objetivo`, `participantes`, `fecha`, `hora`, `dia_semana`) VALUES
(23, 15, 5, 'Biblioteca 1', 4, 'Solicitud 1', 'Responsable 1', 20, '2026-08-09', NULL, 'Domingo'),
(24, 15, 5, 'zona2', 4, '2', 'carlos', 222, '2026-08-12', NULL, 'Miércoles'),
(27, 15, NULL, 'Arístides Bastidas', 5, 'hola', 'hola', 10, '2026-08-26', '12:53:00', 'Miércoles'),
(28, 15, NULL, 'Arístides Bastidas', 5, 'holi', 'formativa', 21, '2026-08-05', '18:58:00', 'Miércoles'),
(29, NULL, 5, 'hola', 5, 'cosas', 'No definido', 0, '2026-08-19', '13:16:00', 'Miércoles'),
(30, 15, NULL, 'juego de ajedrez', 5, 'hola', 'Formativa', 20, '2026-08-05', '13:38:00', 'Miércoles'),
(31, 15, NULL, 'Arístides Bastidas', 5, 'cosas', 'No definido', 0, '2026-08-20', '17:41:00', 'Jueves');







CREATE TABLE `actividad_comuna` (
  `id` int(10) NOT NULL,
  `id_comuna` int(10) NOT NULL,
  `id_actividad` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





INSERT INTO `actividad_comuna` (`id`, `id_comuna`, `id_actividad`) VALUES
(16, 8, 23),
(17, 8, 24),
(20, 8, 28),
(28, 8, 27),
(29, 8, 29),
(30, 8, 30),
(31, 8, 31);







CREATE TABLE `actividad_espaciocultural` (
  `id` int(10) NOT NULL,
  `id_actividad` int(10) NOT NULL,
  `id_biblioteca` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;







CREATE TABLE `biblioteca` (
  `id` int(10) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `id_parroquia` int(10) NOT NULL,
  `Correo` varchar(30) NOT NULL DEFAULT '',
  `redes_sociales` varchar(30) NOT NULL DEFAULT '',
  `Direccion` varchar(30) NOT NULL DEFAULT '',
  `id_solicitud_actividad` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





INSERT INTO `biblioteca` (`id`, `nombre`, `id_parroquia`, `Correo`, `redes_sociales`, `Direccion`, `id_solicitud_actividad`) VALUES
(15, 'biblioteca', 54, 'cesarmcontre28@gmail.com', 'cesarm2', 'calle 4', NULL);







CREATE TABLE `bitacora` (
  `id` int(10) NOT NULL,
  `nom_dia` varchar(15) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_usu` int(10) NOT NULL,
  `accion` varchar(30) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `detalle` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





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
(33, 'Miercoles', '2026-08-19', '19:42:56', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar');







CREATE TABLE `cargo` (
  `id` int(11) NOT NULL,
  `nombre` varchar(36) NOT NULL,
  `Descripcion` varchar(40) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;





INSERT INTO `cargo` (`id`, `nombre`, `Descripcion`) VALUES
(1, 'coordinador', '2'),
(3, 'hola', 'peso');







CREATE TABLE `comuna` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `id_parroquia` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





INSERT INTO `comuna` (`id`, `nombre`, `id_parroquia`) VALUES
(8, 'comuna', 54);







CREATE TABLE `empleado` (
  `id` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `cedula` int(8) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `Genero` varchar(10) NOT NULL,
  `Edad` int(2) UNSIGNED NOT NULL,
  `años_de_servicio` int(2) UNSIGNED NOT NULL,
  `id_cargo` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





INSERT INTO `empleado` (`id`, `nombre`, `apellido`, `cedula`, `telefono`, `Genero`, `Edad`, `años_de_servicio`, `id_cargo`) VALUES
(13, 'jesus', 'serrano', 31982637, '04125240489', '', 0, 0, 1),
(15, 'jesus', 'soto', 32435607, '04125240489', '', 0, 0, 1);







CREATE TABLE `espacio_cultural` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `capacidad` int(10) NOT NULL,
  `direccion` varchar(30) NOT NULL,
  `Metodo_contactar` varchar(40) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





INSERT INTO `espacio_cultural` (`id`, `nombre`, `capacidad`, `direccion`, `Metodo_contactar`) VALUES
(4, 'tu casa', 6, 'independencia', ''),
(5, 'mi casa', 10, 'independencia', 'mi casa');







CREATE TABLE `impacto_actividad` (
  `id` int(10) NOT NULL,
  `id_impacto` int(10) NOT NULL,
  `id_actividad` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





INSERT INTO `impacto_actividad` (`id`, `id_impacto`, `id_actividad`) VALUES
(1, 3, 9),
(3, 3, 28),
(11, 3, 27),
(12, 3, 30);







CREATE TABLE `institucion` (
  `id` int(10) NOT NULL,
  `id_municipio` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;





INSERT INTO `institucion` (`id`, `id_municipio`, `nombre`) VALUES
(1, 4, 'Institucion 1'),
(2, 8, 'escuela');







CREATE TABLE `municipio` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





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







CREATE TABLE `nivel_impacto` (
  `id` int(10) NOT NULL,
  `nombre_impacto` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





INSERT INTO `nivel_impacto` (`id`, `nombre_impacto`) VALUES
(3, 'Comunal'),
(4, 'estadal');







CREATE TABLE `parroquia` (
  `id` int(10) NOT NULL,
  `nombre` varchar(30) NOT NULL,
  `id_municipio` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





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







CREATE TABLE `responsable` (
  `id` int(10) NOT NULL,
  `id_actividad` int(10) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





INSERT INTO `responsable` (`id`, `id_actividad`, `nombre`, `telefono`) VALUES
(1, 6, 'a', '1'),
(3, 9, '1', '2'),
(4, 10, '1', '1'),
(5, 11, 'a', '1'),
(6, 12, '1', '1'),
(7, 8, 'a', '1'),
(8, 13, 'a', '1'),
(9, 14, 'cesar contreras', '2147483647'),
(10, 15, 'cesar contreras', '1231232131'),
(11, 16, 'cesar contreras', '2147483647'),
(12, 17, 'cesar contreras', '2147483647'),
(13, 18, 'carlos', '2147483647'),
(14, 19, 'responsable', '2147483647'),
(15, 21, 'cesar contreras', '2147483647'),
(16, 22, 'jesus serrano', '2147483647'),
(17, 23, 'jesus serrano', '2147483647'),
(18, 24, 'jesus serrano', '2147483647'),
(21, 28, 'jesus soto', '2147483647'),
(29, 27, 'jesus soto', '04125240489'),
(30, 29, 'jesus serrano', '04125240489'),
(31, 30, 'jesus serrano', '04125240489'),
(32, 31, 'jesus serrano', '04125240489');







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





INSERT INTO `solicitud` (`id`, `id_institucion`, `fecha_solicitud`, `hora_solicitud`, `lugar`, `responsable`, `participantes`, `descripcion`) VALUES
(1, 1, '2026-08-09', '08:00:00', 'Biblioteca 1', 'Responsable 1', 20, 'Solicitud 1'),
(2, 1, '2026-08-06', '14:16:00', 'zona', 'cesar contreras', 2, '1'),
(3, 1, '2026-08-12', '15:36:00', 'zona2', 'carlos', 222, '2'),
(4, 1, '2026-08-13', '21:24:00', 'biblioteca', 'jesus serrano', 1, 'a'),
(5, 2, '2026-08-07', '18:52:00', 'biblioteca', 'jesus serrano', 1, ''),
(6, 2, '2026-08-13', '16:56:00', 'biblioteca', 'jesus serrano', 1, 'a');







CREATE TABLE `tipo_actividad` (
  `id` int(10) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `Descripcion` varchar(40) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





INSERT INTO `tipo_actividad` (`id`, `nombre`, `Descripcion`) VALUES
(4, 'eduativa', '0004'),
(5, 'educativa', '');







CREATE TABLE `ubicacion` (
  `id` int(10) NOT NULL,
  `id_comuna` int(10) NOT NULL,
  `id_parroquia` int(10) NOT NULL,
  `id_municipio` int(10) NOT NULL,
  `nombre_comuna` varchar(20) NOT NULL,
  `nombre_parroquia` varchar(20) NOT NULL,
  `nombre_municipio` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;







CREATE TABLE `usuario` (
  `id` int(10) NOT NULL,
  `nombre` varchar(15) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `rol` enum('administrador','usuario') NOT NULL DEFAULT 'usuario'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;





INSERT INTO `usuario` (`id`, `nombre`, `clave`, `telefono`, `id_empleado`, `rol`) VALUES
(11, 'cheddar', '$2y$10$gDEMnG9XXqwiKvUQNP95BOzYUQ/KcRkmZcCqfzHi.WvkowTeqRjwq', '04125240489', 15, 'administrador'),
(12, 'jesus', '$2y$10$A/oi0oFJTyOERvUGmZTbReSn6CeDT.kUzbpwx5TkBRRShSp14658a', '04125240489', 13, 'usuario');








ALTER TABLE `actividad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_biblioteca` (`id_biblioteca`),
  ADD KEY `id_espacio_cultural` (`id_espacio_cultural`),
  ADD KEY `id_tipo_actividad` (`id_tipo_actividad`);




ALTER TABLE `actividad_comuna`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_comuna` (`id_comuna`),
  ADD KEY `id_actividad` (`id_actividad`);




ALTER TABLE `actividad_espaciocultural`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_actividad` (`id_actividad`,`id_biblioteca`),
  ADD KEY `actividad_espaciocultural_ibfk_1` (`id_biblioteca`);




ALTER TABLE `biblioteca`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_biblioteca_nombre_parroquia` (`nombre`,`id_parroquia`),
  ADD KEY `id_parroquia` (`id_parroquia`),
  ADD KEY `id_solicitud` (`id_solicitud_actividad`);




ALTER TABLE `bitacora`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usu` (`id_usu`),
  ADD KEY `fecha` (`fecha`);




ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cargo_nombre` (`nombre`);




ALTER TABLE `comuna`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_comuna_nombre_parroquia` (`nombre`,`id_parroquia`),
  ADD KEY `id_parroquia` (`id_parroquia`);




ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `id_cargo` (`id_cargo`);




ALTER TABLE `espacio_cultural`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_espacio_cultural_nombre` (`nombre`);




ALTER TABLE `impacto_actividad`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_impacto` (`id_impacto`),
  ADD KEY `id_actividad` (`id_actividad`);




ALTER TABLE `institucion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_municipio` (`id_municipio`);




ALTER TABLE `municipio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_municipio_nombre` (`nombre`);




ALTER TABLE `nivel_impacto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_nivel_impacto_nombre` (`nombre_impacto`);




ALTER TABLE `parroquia`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_parroquia_nombre_municipio` (`nombre`,`id_municipio`),
  ADD KEY `id_municipio` (`id_municipio`);




ALTER TABLE `responsable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_actividad` (`id_actividad`);




ALTER TABLE `solicitud`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_institucion` (`id_institucion`);




ALTER TABLE `tipo_actividad`
  ADD PRIMARY KEY (`id`);




ALTER TABLE `ubicacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_comuna` (`id_comuna`),
  ADD KEY `id_parroquia` (`id_parroquia`),
  ADD KEY `id_municipio` (`id_municipio`);




ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuario_nombre` (`nombre`),
  ADD UNIQUE KEY `uq_usuario_empleado` (`id_empleado`);








ALTER TABLE `actividad`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;




ALTER TABLE `actividad_comuna`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;




ALTER TABLE `actividad_espaciocultural`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;




ALTER TABLE `biblioteca`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;




ALTER TABLE `bitacora`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;




ALTER TABLE `cargo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;




ALTER TABLE `comuna`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;




ALTER TABLE `empleado`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;




ALTER TABLE `espacio_cultural`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;




ALTER TABLE `impacto_actividad`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;




ALTER TABLE `institucion`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;




ALTER TABLE `municipio`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;




ALTER TABLE `nivel_impacto`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;




ALTER TABLE `parroquia`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;




ALTER TABLE `responsable`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;




ALTER TABLE `solicitud`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;




ALTER TABLE `tipo_actividad`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;




ALTER TABLE `usuario`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;








ALTER TABLE `actividad`
  ADD CONSTRAINT `actividad_ibfk_1` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`),
  ADD CONSTRAINT `actividad_ibfk_2` FOREIGN KEY (`id_espacio_cultural`) REFERENCES `espacio_cultural` (`id`),
  ADD CONSTRAINT `actividad_ibfk_3` FOREIGN KEY (`id_tipo_actividad`) REFERENCES `tipo_actividad` (`id`);




ALTER TABLE `actividad_comuna`
  ADD CONSTRAINT `actividad_comuna_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `actividad` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `actividad_comuna_ibfk_2` FOREIGN KEY (`id_comuna`) REFERENCES `comuna` (`id`) ON DELETE CASCADE;




ALTER TABLE `biblioteca`
  ADD CONSTRAINT `biblioteca_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`),
  ADD CONSTRAINT `biblioteca_ibfk_2` FOREIGN KEY (`id_solicitud_actividad`) REFERENCES `solicitud` (`id`);




ALTER TABLE `bitacora`
  ADD CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_usu`) REFERENCES `usuario` (`id`) ON DELETE CASCADE;




ALTER TABLE `comuna`
  ADD CONSTRAINT `comuna_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`);




ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id`);




ALTER TABLE `institucion`
  ADD CONSTRAINT `institucion_ibfk_1` FOREIGN KEY (`id_municipio`) REFERENCES `municipio` (`id`);




ALTER TABLE `solicitud`
  ADD CONSTRAINT `solicitud_ibfk_1` FOREIGN KEY (`id_institucion`) REFERENCES `institucion` (`id`);




ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id`) ON DELETE SET NULL;
COMMIT;

;
;
;
