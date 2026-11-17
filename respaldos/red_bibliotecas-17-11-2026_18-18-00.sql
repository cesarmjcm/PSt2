SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `actividad`;
CREATE TABLE `actividad` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
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
  `id_comuna` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_biblioteca` (`id_biblioteca`),
  KEY `id_espacio_cultural` (`id_espacio_cultural`),
  KEY `id_tipo_actividad` (`id_tipo_actividad`),
  KEY `actividad_ibfk_nivel` (`id_nivel_impacto`),
  KEY `actividad_ibfk_responsable` (`responsable`),
  KEY `actividad_ibfk_comuna` (`id_comuna`),
  CONSTRAINT `actividad_ibfk_1` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`),
  CONSTRAINT `actividad_ibfk_2` FOREIGN KEY (`id_espacio_cultural`) REFERENCES `espacio_cultural` (`id`),
  CONSTRAINT `actividad_ibfk_3` FOREIGN KEY (`id_tipo_actividad`) REFERENCES `tipo_actividad` (`id`),
  CONSTRAINT `actividad_ibfk_comuna` FOREIGN KEY (`id_comuna`) REFERENCES `comuna` (`id`) ON DELETE SET NULL,
  CONSTRAINT `actividad_ibfk_nivel` FOREIGN KEY (`id_nivel_impacto`) REFERENCES `nivel_impacto` (`id`) ON DELETE SET NULL,
  CONSTRAINT `actividad_ibfk_responsable` FOREIGN KEY (`responsable`) REFERENCES `empleado` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `actividad` VALUES ('27', '15', NULL, 'Arístides Bastidas', '5', '3', 'hola', 'hola', '10', '2026-08-26', '12:53:00', 'Miércoles', 'confirmada', '15', NULL);
INSERT INTO `actividad` VALUES ('28', '15', NULL, 'Arístides Bastidas', '5', '3', 'holi', 'formativa', '21', '2026-08-05', '18:58:00', 'Miércoles', 'confirmada', '15', NULL);
INSERT INTO `actividad` VALUES ('30', '15', NULL, 'juego de ajedrez', '5', '3', 'hola', 'Formativa', '20', '2026-08-05', '13:38:00', 'Miércoles', 'confirmada', '13', NULL);
INSERT INTO `actividad` VALUES ('31', '15', NULL, 'Arístides Bastidas', '5', NULL, 'cosas', 'No definido', '0', '2026-08-20', '17:41:00', 'Jueves', 'confirmada', '13', NULL);
INSERT INTO `actividad` VALUES ('32', '15', NULL, 'Juego de Ajedrez', '5', '3', 'Actividad de jugar ajedrez realizada en Biblioteca', 'Formativa', '2', '2026-08-27', '13:04:00', 'Jueves', 'confirmada', '13', NULL);
INSERT INTO `actividad` VALUES ('33', '15', NULL, 'Juego de Ajedrez', '5', NULL, 'hola', 'No definido', '0', '2026-08-27', '16:33:00', 'Jueves', 'confirmada', '13', NULL);
INSERT INTO `actividad` VALUES ('34', '15', NULL, 'Juego de Ajedrez', '5', NULL, 'hola', 'No definido', '0', '2026-07-17', '16:39:00', 'Viernes', 'confirmada', '13', NULL);
INSERT INTO `actividad` VALUES ('36', '15', NULL, 'biblioteca', '5', NULL, 'a', 'jesus serrano', '1', '2026-08-13', '21:24:00', 'Jueves', 'confirmada', '13', NULL);
INSERT INTO `actividad` VALUES ('37', '15', NULL, 'juego de mesa', '5', NULL, 'cosas', 'No definido', '0', '2026-10-03', '16:55:00', 'Sábado', 'pendiente', '28', NULL);
INSERT INTO `actividad` VALUES ('38', NULL, '6', 'Felix Pifano', '5', '3', 'felix', 'No definido', '2', '2026-10-04', '18:12:00', 'Domingo', 'confirmada', '28', '53');
INSERT INTO `actividad` VALUES ('39', NULL, '5', 'juego de metras', '5', '3', 'van a jugar metras para entender como era este juego tradicional', 'para que vean los juegos tradicionales', '150', '2026-11-03', '10:00:00', 'Martes', 'pendiente', '13', '105');
INSERT INTO `actividad` VALUES ('40', '15', NULL, 'juego de metras', '5', NULL, 'cosas', 'No definido', '0', '2026-11-03', '10:00:00', 'Martes', 'confirmada', '28', '8');
INSERT INTO `actividad` VALUES ('41', NULL, '4', 'juego', '5', '3', 'cosas', 'No definido', '0', '2026-11-18', '16:12:00', 'Miércoles', 'pendiente', '29', '24');
INSERT INTO `actividad` VALUES ('42', '16', NULL, 'cena navideña', '5', '4', 'van hacer una cena navideña en chivacoa', 'No definido', '65', '2026-12-20', '20:00:00', 'Domingo', 'confirmada', '27', '52');
INSERT INTO `actividad` VALUES ('43', '15', NULL, 'juego de metras', '12', NULL, 'van a jugar metras para entender como era este juego tradicional', 'No definido', '15', '2026-11-19', '10:00:00', 'Jueves', 'pendiente', '13', NULL);

DROP TABLE IF EXISTS `asignacion`;
CREATE TABLE `asignacion` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_cargo` int(11) NOT NULL,
  `id_biblioteca` int(10) NOT NULL,
  `id_empleado` int(10) NOT NULL,
  `fecha_desde` date DEFAULT NULL,
  `fecha_hasta` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_biblioteca` (`id_biblioteca`),
  KEY `id_cargo` (`id_cargo`),
  KEY `id_empleado` (`id_empleado`),
  CONSTRAINT `asignacion_ibfk_1` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`),
  CONSTRAINT `asignacion_ibfk_2` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id`),
  CONSTRAINT `asignacion_ibfk_3` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `asignacion` VALUES ('1', '6', '21', '30', '2026-09-01', NULL);
INSERT INTO `asignacion` VALUES ('2', '5', '17', '31', '2009-03-30', '2024-09-30');
INSERT INTO `asignacion` VALUES ('3', '1', '18', '27', '2026-10-03', NULL);
INSERT INTO `asignacion` VALUES ('4', '4', '19', '32', '2024-01-10', NULL);

DROP TABLE IF EXISTS `biblioteca`;
CREATE TABLE `biblioteca` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `id_parroquia` int(10) NOT NULL,
  `Correo` varchar(30) NOT NULL DEFAULT '',
  `redes_sociales` varchar(30) NOT NULL DEFAULT '',
  `Direccion` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_biblioteca_nombre_parroquia` (`nombre`,`id_parroquia`),
  KEY `id_parroquia` (`id_parroquia`),
  CONSTRAINT `biblioteca_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `biblioteca` VALUES ('15', 'biblioteca', '54', 'cesarmcontre28@gmail.com', 'cesarm2', 'calle 4');
INSERT INTO `biblioteca` VALUES ('16', 'Felix Pifano', '47', '', '', '');
INSERT INTO `biblioteca` VALUES ('17', 'Oswaldo Méndez', '64', '', '', '');
INSERT INTO `biblioteca` VALUES ('18', 'Rómulo Gallegos', '62', '', '', '');
INSERT INTO `biblioteca` VALUES ('19', 'Rubén Suárez Moreno', '49', '', '', '');
INSERT INTO `biblioteca` VALUES ('20', 'Domingo Oviedo Parada', '67', '', '', '');
INSERT INTO `biblioteca` VALUES ('21', 'Josefa Marín de Narváez', '68', '', '', '');

DROP TABLE IF EXISTS `bitacora`;
CREATE TABLE `bitacora` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nom_dia` varchar(15) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `id_usu` int(10) DEFAULT NULL,
  `accion` varchar(30) NOT NULL,
  `descripcion` varchar(50) NOT NULL,
  `detalle` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usu` (`id_usu`),
  KEY `fecha` (`fecha`),
  CONSTRAINT `bitacora_ibfk_1` FOREIGN KEY (`id_usu`) REFERENCES `usuario` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=245 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `bitacora` VALUES ('1', 'Sabado', '2026-08-15', '20:02:49', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('2', 'Sabado', '2026-08-15', '21:32:57', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('3', 'Sabado', '2026-08-15', '21:33:05', '11', 'Editar', 'Municipio', 'Municipio #21 actualizado: Arístides Bastida');
INSERT INTO `bitacora` VALUES ('4', 'Sabado', '2026-08-15', '21:33:24', '11', 'Editar', 'Municipio', 'Municipio #21 actualizado: Arístides Bastidas');
INSERT INTO `bitacora` VALUES ('5', 'Sabado', '2026-08-15', '21:33:27', '11', 'Crear', 'Municipio', 'Municipio registrado: hola');
INSERT INTO `bitacora` VALUES ('6', 'Sabado', '2026-08-15', '21:33:34', '11', 'Eliminar', 'Municipio', 'Municipio #24 eliminado');
INSERT INTO `bitacora` VALUES ('7', 'Sabado', '2026-08-15', '21:34:46', '11', 'Editar', 'Solicitud', 'Solicitud #4 actualizada');
INSERT INTO `bitacora` VALUES ('8', 'Sabado', '2026-08-15', '21:46:28', '12', 'Login', 'Usuario', 'Inicio de sesión: jesus');
INSERT INTO `bitacora` VALUES ('9', 'Lunes', '2026-08-17', '19:17:18', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('10', 'Lunes', '2026-08-17', '19:30:26', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('11', 'Lunes', '2026-08-17', '20:55:48', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('12', 'Lunes', '2026-08-17', '20:56:24', '11', 'Crear', 'Municipio', 'Municipio registrado: hola');
INSERT INTO `bitacora` VALUES ('13', 'Lunes', '2026-08-17', '20:56:29', '11', 'Eliminar', 'Municipio', 'Municipio #25 eliminado');
INSERT INTO `bitacora` VALUES ('14', 'Lunes', '2026-08-17', '20:57:02', '11', 'Crear', 'Solicitud', 'Solicitud registrada para institución #2');
INSERT INTO `bitacora` VALUES ('15', 'Lunes', '2026-08-17', '23:26:27', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('16', 'Martes', '2026-08-18', '17:52:10', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('17', 'Martes', '2026-08-18', '23:46:34', '11', 'Crear', 'Actividad', 'Actividad registrada: Arístides Bastidas');
INSERT INTO `bitacora` VALUES ('18', 'Martes', '2026-08-18', '23:52:00', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('19', 'Miercoles', '2026-08-19', '00:10:47', '11', 'Crear', 'Actividad', 'Actividad registrada: Arístides Bastidas');
INSERT INTO `bitacora` VALUES ('20', 'Miercoles', '2026-08-19', '00:56:20', '11', 'Editar', 'Actividad', 'Actividad #28 actualizada: Arístides Bastidas');
INSERT INTO `bitacora` VALUES ('21', 'Miercoles', '2026-08-19', '18:46:26', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('22', 'Miercoles', '2026-08-19', '18:49:14', '11', 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas');
INSERT INTO `bitacora` VALUES ('23', 'Miercoles', '2026-08-19', '18:53:01', '11', 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas');
INSERT INTO `bitacora` VALUES ('24', 'Miercoles', '2026-08-19', '19:03:11', '11', 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas');
INSERT INTO `bitacora` VALUES ('25', 'Miercoles', '2026-08-19', '19:03:32', '11', 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas');
INSERT INTO `bitacora` VALUES ('26', 'Miercoles', '2026-08-19', '19:06:53', '11', 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas');
INSERT INTO `bitacora` VALUES ('27', 'Miercoles', '2026-08-19', '19:07:32', '11', 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas');
INSERT INTO `bitacora` VALUES ('28', 'Miercoles', '2026-08-19', '19:07:44', '11', 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas');
INSERT INTO `bitacora` VALUES ('29', 'Miercoles', '2026-08-19', '19:08:52', '11', 'Editar', 'Actividad', 'Actividad #27 actualizada: Arístides Bastidas');
INSERT INTO `bitacora` VALUES ('30', 'Miercoles', '2026-08-19', '19:12:47', '11', 'Crear', 'Actividad', 'Actividad registrada: hola');
INSERT INTO `bitacora` VALUES ('31', 'Miercoles', '2026-08-19', '19:36:11', '11', 'Crear', 'Actividad', 'Actividad registrada: juego de ajedrez');
INSERT INTO `bitacora` VALUES ('32', 'Miercoles', '2026-08-19', '19:41:21', '11', 'Crear', 'Actividad', 'Actividad registrada: Arístides Bastidas');
INSERT INTO `bitacora` VALUES ('33', 'Miercoles', '2026-08-19', '19:42:56', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('34', 'Domingo', '2026-08-23', '21:29:26', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('35', 'Domingo', '2026-08-23', '21:39:45', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('36', 'Domingo', '2026-08-23', '21:46:34', '11', 'Eliminar', 'Empleado', 'Empleado #26 eliminado');
INSERT INTO `bitacora` VALUES ('37', 'Domingo', '2026-08-23', '21:46:37', '11', 'Eliminar', 'Empleado', 'Empleado #23 eliminado');
INSERT INTO `bitacora` VALUES ('38', 'Domingo', '2026-08-23', '21:51:05', '11', 'Editar', 'Empleado', 'Empleado #13 actualizado: jesus serrano');
INSERT INTO `bitacora` VALUES ('39', 'Domingo', '2026-08-23', '21:53:17', '11', 'Editar', 'Solicitud', 'Solicitud #3 actualizada');
INSERT INTO `bitacora` VALUES ('40', 'Miercoles', '2026-08-26', '18:55:44', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('41', 'Miercoles', '2026-08-26', '19:00:39', '11', 'Crear', 'Actividad', 'Actividad registrada: Juego de Ajedrez');
INSERT INTO `bitacora` VALUES ('42', 'Miercoles', '2026-08-26', '19:01:18', '11', 'Editar', 'Actividad', 'Actividad #32 actualizada: Juego de Ajedrez');
INSERT INTO `bitacora` VALUES ('43', 'Miercoles', '2026-08-26', '22:18:29', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('44', 'Miercoles', '2026-08-26', '22:30:02', '11', 'Crear', 'Actividad', 'Actividad registrada: Juego de Ajedrez');
INSERT INTO `bitacora` VALUES ('45', 'Miercoles', '2026-08-26', '22:36:24', '11', 'Crear', 'Actividad', 'Actividad registrada: Juego de Ajedrez');
INSERT INTO `bitacora` VALUES ('46', 'Jueves', '2026-09-03', '23:41:51', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('47', 'Jueves', '2026-09-03', '23:42:14', '12', 'Login', 'Usuario', 'Inicio de sesión: jesus');
INSERT INTO `bitacora` VALUES ('48', 'Jueves', '2026-09-03', '23:56:21', '12', 'Crear', 'Actividad', 'Actividad registrada: juego de mesa');
INSERT INTO `bitacora` VALUES ('49', 'Jueves', '2026-09-03', '23:59:11', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('50', 'Viernes', '2026-09-04', '00:00:03', '11', 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa');
INSERT INTO `bitacora` VALUES ('51', 'Viernes', '2026-09-04', '00:00:08', '11', 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa');
INSERT INTO `bitacora` VALUES ('52', 'Viernes', '2026-09-04', '00:00:17', '11', 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa');
INSERT INTO `bitacora` VALUES ('53', 'Viernes', '2026-09-04', '00:03:32', '11', 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa');
INSERT INTO `bitacora` VALUES ('54', 'Viernes', '2026-09-04', '00:04:55', '11', 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa');
INSERT INTO `bitacora` VALUES ('55', 'Viernes', '2026-09-04', '00:05:00', '11', 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa');
INSERT INTO `bitacora` VALUES ('56', 'Miercoles', '2026-09-09', '22:14:47', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('57', 'Jueves', '2026-09-17', '20:28:37', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('58', 'Jueves', '2026-09-17', '20:35:11', '11', 'Eliminar', 'Tipo de actividad', 'Tipo de actividad #7 eliminado');
INSERT INTO `bitacora` VALUES ('59', 'Jueves', '2026-09-17', '20:35:14', '11', 'Crear', 'Tipo de actividad', 'Tipo de actividad registrado: hola');
INSERT INTO `bitacora` VALUES ('60', 'Jueves', '2026-09-17', '20:36:02', '11', 'Crear', 'Nivel de impacto', 'Nivel de impacto registrado: municipal');
INSERT INTO `bitacora` VALUES ('61', 'Jueves', '2026-09-17', '20:36:12', '11', 'Eliminar', 'Cargo', 'Cargo #3 eliminado');
INSERT INTO `bitacora` VALUES ('62', 'Jueves', '2026-09-17', '20:39:17', '11', 'Crear', 'Actividad', 'Actividad registrada: biblioteca');
INSERT INTO `bitacora` VALUES ('63', 'Jueves', '2026-09-17', '20:39:34', '11', 'Eliminar', 'Solicitud', 'Solicitud #4 eliminada');
INSERT INTO `bitacora` VALUES ('64', 'Jueves', '2026-09-17', '20:39:36', '11', 'Eliminar', 'Solicitud', 'Solicitud #6 eliminada');
INSERT INTO `bitacora` VALUES ('65', 'Jueves', '2026-09-17', '20:39:38', '11', 'Eliminar', 'Solicitud', 'Solicitud #3 eliminada');
INSERT INTO `bitacora` VALUES ('66', 'Jueves', '2026-09-17', '20:39:41', '11', 'Eliminar', 'Solicitud', 'Solicitud #1 eliminada');
INSERT INTO `bitacora` VALUES ('67', 'Jueves', '2026-09-17', '20:39:44', '11', 'Eliminar', 'Solicitud', 'Solicitud #5 eliminada');
INSERT INTO `bitacora` VALUES ('68', 'Jueves', '2026-09-17', '20:42:19', '12', 'Login', 'Usuario', 'Inicio de sesión: jesus');
INSERT INTO `bitacora` VALUES ('69', 'Jueves', '2026-09-17', '20:42:38', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('70', 'Jueves', '2026-09-17', '20:47:47', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('71', 'Viernes', '2026-10-02', '16:35:44', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/index.php');
INSERT INTO `bitacora` VALUES ('72', 'Viernes', '2026-10-02', '16:42:17', '11', 'Editar', 'Actividad', 'Actividad #35 actualizada: juego de mesa');
INSERT INTO `bitacora` VALUES ('73', 'Viernes', '2026-10-02', '16:42:48', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/main2.php');
INSERT INTO `bitacora` VALUES ('74', 'Viernes', '2026-10-02', '16:43:58', '11', 'Crear', 'Empleado', 'Empleado registrado: freddy espinoza');
INSERT INTO `bitacora` VALUES ('75', 'Viernes', '2026-10-02', '16:47:50', '11', 'Crear', 'Empleado', 'Empleado registrado: cesar ontreras');
INSERT INTO `bitacora` VALUES ('76', 'Viernes', '2026-10-02', '16:53:01', '11', 'Crear', 'Actividad', 'Actividad registrada: juego de mesa');
INSERT INTO `bitacora` VALUES ('77', 'Viernes', '2026-10-02', '16:59:21', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/maestro.php?tabla=institucion');
INSERT INTO `bitacora` VALUES ('78', 'Viernes', '2026-10-02', '17:05:23', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/maestro.php?tabla=empleado');
INSERT INTO `bitacora` VALUES ('79', 'Sabado', '2026-10-03', '18:05:25', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/bitacora.php');
INSERT INTO `bitacora` VALUES ('80', 'Sabado', '2026-10-03', '18:05:27', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('81', 'Sabado', '2026-10-03', '18:08:15', '11', 'Crear', 'Biblioteca', 'Biblioteca registrada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('82', 'Sabado', '2026-10-03', '18:08:40', '11', 'Editar', 'Empleado', 'Empleado #28 actualizado: cesar ontreras');
INSERT INTO `bitacora` VALUES ('83', 'Sabado', '2026-10-03', '18:09:10', '11', 'Crear', 'Usuario', 'Usuario registrado: cesar');
INSERT INTO `bitacora` VALUES ('87', 'Lunes', '2026-10-05', '16:02:33', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('88', 'Miercoles', '2026-10-07', '14:59:39', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('89', 'Miercoles', '2026-10-07', '14:59:43', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('90', 'Miercoles', '2026-10-07', '15:05:27', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/reportes_cargo/exportar_cargos.php');
INSERT INTO `bitacora` VALUES ('91', 'Miercoles', '2026-10-07', '15:05:47', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/reportes_cargo/exportar_cargos.php');
INSERT INTO `bitacora` VALUES ('92', 'Miercoles', '2026-10-07', '15:06:31', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/maestro.php?tabla=cargo');
INSERT INTO `bitacora` VALUES ('93', 'Miercoles', '2026-10-07', '16:25:01', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('94', 'Miercoles', '2026-10-07', '16:25:03', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('95', 'Miercoles', '2026-10-28', '10:35:57', '11', 'Editar', 'Solicitud', 'Solicitud #2 actualizada');
INSERT INTO `bitacora` VALUES ('96', 'Miercoles', '2026-10-28', '10:36:58', '11', 'Editar', 'Solicitud', 'Solicitud #2 actualizada');
INSERT INTO `bitacora` VALUES ('97', 'Sabado', '2026-10-31', '11:30:28', '11', 'Crear', 'Solicitud', 'Solicitud registrada para institución #1');
INSERT INTO `bitacora` VALUES ('98', 'Sabado', '2026-10-31', '11:31:17', '11', 'Crear', 'Actividad', 'Actividad registrada: juego de metras');
INSERT INTO `bitacora` VALUES ('99', 'Sabado', '2026-10-31', '11:34:00', '11', 'Crear', 'Empleado', 'Empleado registrado: maria narvaez');
INSERT INTO `bitacora` VALUES ('100', 'Viernes', '2026-11-06', '15:54:23', '11', 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('101', 'Viernes', '2026-11-06', '15:55:51', '11', 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('102', 'Viernes', '2026-11-06', '16:01:02', '11', 'Editar', 'Actividad', 'Actividad #39 actualizada: juego de metras');
INSERT INTO `bitacora` VALUES ('103', 'Viernes', '2026-11-06', '16:03:28', '11', 'Editar', 'Actividad', 'Actividad #39 actualizada: juego de metras');
INSERT INTO `bitacora` VALUES ('104', 'Viernes', '2026-11-06', '16:32:50', '11', 'Editar', 'Actividad', 'Actividad #39 actualizada: juego de metras');
INSERT INTO `bitacora` VALUES ('105', 'Viernes', '2026-11-06', '16:33:08', '11', 'Editar', 'Actividad', 'Actividad #39 actualizada: juego de metras');
INSERT INTO `bitacora` VALUES ('106', 'Viernes', '2026-11-06', '16:55:31', '11', 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('107', 'Viernes', '2026-11-06', '16:55:45', '11', 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('108', 'Viernes', '2026-11-06', '17:07:20', '11', 'Editar', 'Actividad', 'Actividad #39 actualizada: juego de metras');
INSERT INTO `bitacora` VALUES ('109', 'Viernes', '2026-11-06', '19:32:09', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('110', 'Viernes', '2026-11-06', '19:42:33', '11', 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('111', 'Viernes', '2026-11-06', '19:57:31', '11', 'Eliminar', 'Actividad', 'Actividad #24 eliminada');
INSERT INTO `bitacora` VALUES ('112', 'Viernes', '2026-11-06', '19:57:36', '11', 'Eliminar', 'Actividad', 'Actividad #29 eliminada');
INSERT INTO `bitacora` VALUES ('113', 'Viernes', '2026-11-06', '19:58:43', '11', 'Crear', 'Biblioteca', 'Biblioteca registrada: Oswaldo Méndez');
INSERT INTO `bitacora` VALUES ('114', 'Viernes', '2026-11-06', '19:59:26', '11', 'Crear', 'Biblioteca', 'Biblioteca registrada: Rómulo Gallegos');
INSERT INTO `bitacora` VALUES ('115', 'Viernes', '2026-11-06', '20:00:29', '11', 'Crear', 'Biblioteca', 'Biblioteca registrada: Rubén Suárez Moreno');
INSERT INTO `bitacora` VALUES ('116', 'Viernes', '2026-11-06', '20:01:35', '11', 'Crear', 'Biblioteca', 'Biblioteca registrada: Domingo Oviedo Parada');
INSERT INTO `bitacora` VALUES ('117', 'Sabado', '2026-11-07', '23:37:04', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/index.php');
INSERT INTO `bitacora` VALUES ('118', 'Sabado', '2026-11-07', '23:41:25', '11', 'Editar', 'Actividad', 'Actividad #39 actualizada: juego de metras');
INSERT INTO `bitacora` VALUES ('119', 'Sabado', '2026-11-07', '23:46:05', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/index.php');
INSERT INTO `bitacora` VALUES ('120', 'Sabado', '2026-11-07', '23:46:50', '11', 'Editar', 'Actividad', 'Actividad #39 actualizada: juego de metras');
INSERT INTO `bitacora` VALUES ('121', 'Sabado', '2026-11-07', '23:47:32', '11', 'Editar', 'Espacio cultural', 'Espacio cultural #5 actualizado: mi casa');
INSERT INTO `bitacora` VALUES ('122', 'Sabado', '2026-11-07', '23:47:50', '11', 'Editar', 'Actividad', 'Actividad #39 actualizada: juego de metras');
INSERT INTO `bitacora` VALUES ('123', 'Domingo', '2026-11-08', '00:01:31', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/main2.php');
INSERT INTO `bitacora` VALUES ('124', 'Domingo', '2026-11-08', '00:02:20', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/main2.php?accion=obtener_actividades');
INSERT INTO `bitacora` VALUES ('125', 'Domingo', '2026-11-08', '00:07:41', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/main2.php');
INSERT INTO `bitacora` VALUES ('126', 'Domingo', '2026-11-08', '13:25:59', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/index.php');
INSERT INTO `bitacora` VALUES ('127', 'Domingo', '2026-11-08', '13:26:00', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('128', 'Domingo', '2026-11-08', '13:27:40', '11', 'Crear', 'Actividad', 'Actividad registrada: juego de metras');
INSERT INTO `bitacora` VALUES ('129', 'Domingo', '2026-11-08', '13:38:56', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/main2.php');
INSERT INTO `bitacora` VALUES ('130', 'Lunes', '2026-11-09', '10:58:50', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/main2.php');
INSERT INTO `bitacora` VALUES ('131', 'Lunes', '2026-11-09', '10:58:52', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('132', 'Martes', '2026-11-10', '12:38:19', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/main2.php');
INSERT INTO `bitacora` VALUES ('133', 'Martes', '2026-11-10', '12:38:21', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('134', 'Martes', '2026-11-10', '12:50:59', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/configUser.php');
INSERT INTO `bitacora` VALUES ('135', 'Martes', '2026-11-10', '12:51:22', '11', 'Crear', 'Usuario', 'Usuario registrado: coord');
INSERT INTO `bitacora` VALUES ('136', 'Martes', '2026-11-10', '12:51:30', '14', 'Login', 'Usuario', 'Inicio de sesión: coord');
INSERT INTO `bitacora` VALUES ('137', 'Martes', '2026-11-10', '12:52:14', '14', 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('138', 'Martes', '2026-11-10', '12:52:19', '14', 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('139', 'Martes', '2026-11-10', '12:52:26', '14', 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('140', 'Martes', '2026-11-10', '12:52:32', '14', 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('141', 'Martes', '2026-11-10', '12:54:31', '14', 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('142', 'Martes', '2026-11-10', '13:02:05', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/configUser.php');
INSERT INTO `bitacora` VALUES ('143', 'Martes', '2026-11-10', '13:02:59', '14', 'Eliminar', 'Usuario', 'Usuario #13 eliminado');
INSERT INTO `bitacora` VALUES ('144', 'Martes', '2026-11-10', '13:03:14', '14', 'Crear', 'Usuario', 'Usuario registrado: cesar');
INSERT INTO `bitacora` VALUES ('145', 'Martes', '2026-11-10', '13:10:06', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/index.php');
INSERT INTO `bitacora` VALUES ('146', 'Martes', '2026-11-10', '13:10:45', '14', 'Crear', 'Espacio cultural', 'Espacio cultural registrado: juego de metras');
INSERT INTO `bitacora` VALUES ('147', 'Martes', '2026-11-10', '13:10:56', '14', 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('148', 'Martes', '2026-11-10', '13:12:55', '14', 'Crear', 'Actividad', 'Actividad registrada: juego');
INSERT INTO `bitacora` VALUES ('149', 'Martes', '2026-11-10', '13:14:13', '14', 'Editar', 'Actividad', 'Actividad #41 actualizada: juego');
INSERT INTO `bitacora` VALUES ('150', 'Martes', '2026-11-10', '13:17:31', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/index.php');
INSERT INTO `bitacora` VALUES ('151', 'Martes', '2026-11-10', '13:17:40', '14', 'Editar', 'Actividad', 'Actividad #38 actualizada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('152', 'Martes', '2026-11-10', '13:18:01', '14', 'Editar', 'Actividad', 'Actividad #41 actualizada: juego');
INSERT INTO `bitacora` VALUES ('153', 'Martes', '2026-11-10', '13:19:20', '14', 'Editar', 'Actividad', 'Actividad #41 actualizada: juego');
INSERT INTO `bitacora` VALUES ('154', 'Martes', '2026-11-10', '13:19:27', '14', 'Editar', 'Actividad', 'Actividad #41 actualizada: juego');
INSERT INTO `bitacora` VALUES ('155', 'Martes', '2026-11-10', '13:20:29', '14', 'Editar', 'Actividad', 'Actividad #41 actualizada: juego');
INSERT INTO `bitacora` VALUES ('156', 'Martes', '2026-11-10', '13:20:55', '14', 'Editar', 'Actividad', 'Actividad #41 actualizada: juego');
INSERT INTO `bitacora` VALUES ('157', 'Martes', '2026-11-10', '16:53:45', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('158', 'Martes', '2026-11-10', '17:21:33', '11', 'Editar', 'Actividad', 'Actividad #41 actualizada: juego');
INSERT INTO `bitacora` VALUES ('159', 'Martes', '2026-11-10', '17:21:58', '11', 'Editar', 'Espacio cultural', 'Espacio cultural #6 actualizado: casa de tu tio');
INSERT INTO `bitacora` VALUES ('160', 'Martes', '2026-11-10', '17:35:02', '11', 'Crear', 'Actividad', 'Actividad registrada: cena navideña');
INSERT INTO `bitacora` VALUES ('161', 'Martes', '2026-11-10', '17:35:49', '11', 'Editar', 'Actividad', 'Actividad #42 actualizada: cena navideña');
INSERT INTO `bitacora` VALUES ('162', 'Martes', '2026-11-10', '17:36:26', '11', 'Editar', 'Actividad', 'Actividad #42 actualizada: cena navideña');
INSERT INTO `bitacora` VALUES ('163', 'Martes', '2026-11-10', '17:42:06', '11', 'Editar', 'Actividad', 'Actividad #42 actualizada: cena navideña');
INSERT INTO `bitacora` VALUES ('164', 'Jueves', '2026-11-12', '09:33:02', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('165', 'Viernes', '2026-11-13', '21:13:20', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('166', 'Sabado', '2026-11-14', '07:59:49', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('167', 'Sabado', '2026-11-14', '08:02:04', '11', 'Crear', 'Institución', 'Institución registrada: martin tovar y tovar');
INSERT INTO `bitacora` VALUES ('168', 'Sabado', '2026-11-14', '08:05:15', '11', 'Crear', 'Institución', 'Institución registrada: Liceo Parque Leonor Bernabó');
INSERT INTO `bitacora` VALUES ('169', 'Sabado', '2026-11-14', '08:06:28', '11', 'Crear', 'Institución', 'Institución registrada: colegio Manuel cedeño');
INSERT INTO `bitacora` VALUES ('170', 'Sabado', '2026-11-14', '08:07:28', '11', 'Crear', 'Institución', 'Institución registrada: Complejo Educativo Trinidad Figuera');
INSERT INTO `bitacora` VALUES ('171', 'Sabado', '2026-11-14', '08:08:21', '11', 'Eliminar', 'Institución', 'Institución #2 eliminada');
INSERT INTO `bitacora` VALUES ('172', 'Sabado', '2026-11-14', '08:09:54', '11', 'Crear', 'Institución', 'Institución registrada: Colegio Santa Maria');
INSERT INTO `bitacora` VALUES ('173', 'Sabado', '2026-11-14', '08:11:16', '11', 'Crear', 'Institución', 'Institución registrada: Liceo Bolivariano Sabanita');
INSERT INTO `bitacora` VALUES ('174', 'Sabado', '2026-11-14', '08:13:37', '11', 'Crear', 'Institución', 'Institución registrada: Liceo Doctor Heriberto Núñez Oliveros');
INSERT INTO `bitacora` VALUES ('175', 'Sabado', '2026-11-14', '08:16:50', '11', 'Crear', 'Institución', 'Institución registrada: UPTYAB');
INSERT INTO `bitacora` VALUES ('176', 'Sabado', '2026-11-14', '08:19:19', '11', 'Crear', 'Institución', 'Institución registrada: Liceo Julio Urrutia');
INSERT INTO `bitacora` VALUES ('177', 'Sabado', '2026-11-14', '08:22:07', '11', 'Crear', 'Cargo', 'Cargo registrado: secretaria');
INSERT INTO `bitacora` VALUES ('178', 'Sabado', '2026-11-14', '08:22:52', '11', 'Crear', 'Cargo', 'Cargo registrado: Recepcionista');
INSERT INTO `bitacora` VALUES ('179', 'Sabado', '2026-11-14', '08:24:03', '11', 'Crear', 'Cargo', 'Cargo registrado: promotor de cultura');
INSERT INTO `bitacora` VALUES ('180', 'Sabado', '2026-11-14', '08:26:00', '11', 'Crear', 'Cargo', 'Cargo registrado: Asistente de sala');
INSERT INTO `bitacora` VALUES ('181', 'Sabado', '2026-11-14', '08:27:27', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PST2-main/controladores/parroquia_contr.php?action=listar');
INSERT INTO `bitacora` VALUES ('182', 'Sabado', '2026-11-14', '08:46:27', '11', 'Crear', 'Nivel de impacto', 'Nivel de impacto registrado: Escolar');
INSERT INTO `bitacora` VALUES ('183', 'Sabado', '2026-11-14', '08:46:37', '11', 'Crear', 'Nivel de impacto', 'Nivel de impacto registrado: Laboral');
INSERT INTO `bitacora` VALUES ('184', 'Sabado', '2026-11-14', '08:47:05', '11', 'Eliminar', 'Tipo de actividad', 'Tipo de actividad #8 eliminado');
INSERT INTO `bitacora` VALUES ('185', 'Sabado', '2026-11-14', '08:47:18', '11', 'Editar', 'Tipo de actividad', 'Tipo de actividad #4 actualizado: hola');
INSERT INTO `bitacora` VALUES ('186', 'Sabado', '2026-11-14', '08:48:15', '11', 'Eliminar', 'Actividad', 'Actividad #35 eliminada');
INSERT INTO `bitacora` VALUES ('187', 'Sabado', '2026-11-14', '08:48:37', '11', 'Eliminar', 'Actividad', 'Actividad #23 eliminada');
INSERT INTO `bitacora` VALUES ('188', 'Sabado', '2026-11-14', '08:48:46', '11', 'Eliminar', 'Tipo de actividad', 'Tipo de actividad #4 eliminado');
INSERT INTO `bitacora` VALUES ('189', 'Sabado', '2026-11-14', '08:57:06', '11', 'Crear', 'Tipo de actividad', 'Tipo de actividad registrado: Folklórica');
INSERT INTO `bitacora` VALUES ('190', 'Sabado', '2026-11-14', '08:57:20', '11', 'Crear', 'Tipo de actividad', 'Tipo de actividad registrado: Gastronómicas');
INSERT INTO `bitacora` VALUES ('191', 'Sabado', '2026-11-14', '08:57:44', '11', 'Editar', 'Tipo de actividad', 'Tipo de actividad #9 actualizado: Folklórica');
INSERT INTO `bitacora` VALUES ('192', 'Sabado', '2026-11-14', '08:58:53', '11', 'Crear', 'Tipo de actividad', 'Tipo de actividad registrado: Formativa');
INSERT INTO `bitacora` VALUES ('193', 'Sabado', '2026-11-14', '09:00:10', '11', 'Crear', 'Tipo de actividad', 'Tipo de actividad registrado: Artísticas');
INSERT INTO `bitacora` VALUES ('194', 'Sabado', '2026-11-14', '09:03:28', '11', 'Crear', 'Parroquia', 'Parroquia registrada: Aroa');
INSERT INTO `bitacora` VALUES ('195', 'Sabado', '2026-11-14', '09:03:42', '11', 'Crear', 'Biblioteca', 'Biblioteca registrada: Josefa Marín de Narváez');
INSERT INTO `bitacora` VALUES ('196', 'Sabado', '2026-11-14', '09:07:02', '11', 'Crear', 'Empleado', 'Empleado registrado: jesus bonilla');
INSERT INTO `bitacora` VALUES ('197', 'Sabado', '2026-11-14', '09:09:01', '11', 'Crear', 'Empleado', 'Empleado registrado: isaias Morales');
INSERT INTO `bitacora` VALUES ('198', 'Sabado', '2026-11-14', '09:09:27', '11', 'Editar', 'Empleado', 'Empleado #27 actualizado: freddy espinoza');
INSERT INTO `bitacora` VALUES ('199', 'Sabado', '2026-11-14', '09:11:01', '11', 'Crear', 'Empleado', 'Empleado registrado: manuela suarez');
INSERT INTO `bitacora` VALUES ('200', 'Sabado', '2026-11-14', '09:16:58', '11', 'Editar', 'Usuario', 'Usuario #12 actualizado: jesus');
INSERT INTO `bitacora` VALUES ('201', 'Sabado', '2026-11-14', '09:17:54', '11', 'Crear', 'Usuario', 'Usuario registrado: isaias');
INSERT INTO `bitacora` VALUES ('202', 'Sabado', '2026-11-14', '09:18:48', '11', 'Crear', 'Usuario', 'Usuario registrado: freddy');
INSERT INTO `bitacora` VALUES ('203', 'Sabado', '2026-11-14', '09:20:50', '11', 'Crear', 'Espacio cultural', 'Espacio cultural registrado: hola mundo');
INSERT INTO `bitacora` VALUES ('204', 'Sabado', '2026-11-14', '09:20:54', '11', 'Eliminar', 'Espacio cultural', 'Espacio cultural #7 eliminado');
INSERT INTO `bitacora` VALUES ('205', 'Sabado', '2026-11-14', '09:21:10', '11', 'Editar', 'Espacio cultural', 'Espacio cultural #5 actualizado: hola');
INSERT INTO `bitacora` VALUES ('206', 'Sabado', '2026-11-14', '09:24:34', '11', 'Crear', 'Espacio cultural', 'Espacio cultural registrado: Museo Carmelo Fernández');
INSERT INTO `bitacora` VALUES ('207', 'Sabado', '2026-11-14', '09:26:48', '11', 'Crear', 'Espacio cultural', 'Espacio cultural registrado: Parque Recreacional Leonor');
INSERT INTO `bitacora` VALUES ('208', 'Sabado', '2026-11-14', '09:30:29', '16', 'Login', 'Usuario', 'Inicio de sesión: isaias');
INSERT INTO `bitacora` VALUES ('209', 'Sabado', '2026-11-14', '09:31:01', '16', 'Editar', 'Tipo de actividad', 'Tipo de actividad #12 actualizado: Artísticas');
INSERT INTO `bitacora` VALUES ('210', 'Sabado', '2026-11-14', '11:10:33', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('211', 'Sabado', '2026-11-14', '12:58:27', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('212', 'Sabado', '2026-11-14', '12:58:47', '11', 'Editar', 'Parroquia', 'Parroquia #56 actualizada: Campo Elias');
INSERT INTO `bitacora` VALUES ('213', 'Sabado', '2026-11-14', '12:59:31', '11', 'Editar', 'Parroquia', 'Parroquia #3 actualizada: Albarico');
INSERT INTO `bitacora` VALUES ('214', 'Sabado', '2026-11-14', '18:46:48', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/');
INSERT INTO `bitacora` VALUES ('215', 'Sabado', '2026-11-14', '18:46:50', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('216', 'Sabado', '2026-11-14', '18:47:06', '11', 'Editar', 'Espacio cultural', 'Espacio cultural #5 actualizado: teatro');
INSERT INTO `bitacora` VALUES ('217', 'Sabado', '2026-11-14', '18:47:19', '11', 'Editar', 'Espacio cultural', 'Espacio cultural #4 actualizado: instalacion publica');
INSERT INTO `bitacora` VALUES ('218', 'Sabado', '2026-11-14', '18:47:37', '11', 'Editar', 'Espacio cultural', 'Espacio cultural #6 actualizado: escenario');
INSERT INTO `bitacora` VALUES ('219', 'Lunes', '2026-11-16', '21:06:12', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/');
INSERT INTO `bitacora` VALUES ('220', 'Lunes', '2026-11-16', '21:06:14', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('221', 'Lunes', '2026-11-16', '22:22:41', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/main2.php');
INSERT INTO `bitacora` VALUES ('222', 'Lunes', '2026-11-16', '22:22:43', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('223', 'Lunes', '2026-11-16', '22:23:07', '11', 'Crear', 'Actividad', 'Actividad registrada: juego de metras');
INSERT INTO `bitacora` VALUES ('224', 'Lunes', '2026-11-16', '22:23:45', '11', 'Editar', 'Actividad', 'Actividad #42 actualizada: cena navideña');
INSERT INTO `bitacora` VALUES ('225', 'Lunes', '2026-11-16', '22:24:02', '11', 'Editar', 'Actividad', 'Actividad #42 actualizada: cena navideña');
INSERT INTO `bitacora` VALUES ('226', 'Lunes', '2026-11-16', '22:27:02', '11', 'Crear', 'Parroquia', 'Parroquia registrada: teatro');
INSERT INTO `bitacora` VALUES ('227', 'Lunes', '2026-11-16', '22:27:16', '11', 'Eliminar', 'Parroquia', 'Parroquia #69 eliminada');
INSERT INTO `bitacora` VALUES ('228', 'Lunes', '2026-11-16', '22:28:18', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/maestro.php?tabla=comuna');
INSERT INTO `bitacora` VALUES ('229', 'Lunes', '2026-11-16', '22:33:22', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/index.php');
INSERT INTO `bitacora` VALUES ('230', 'Lunes', '2026-11-16', '22:33:41', '11', 'Editar', 'Actividad', 'Actividad #42 actualizada: cena navideña');
INSERT INTO `bitacora` VALUES ('231', 'Lunes', '2026-11-16', '22:33:54', '11', 'Editar', 'Actividad', 'Actividad #42 actualizada: cena navideña');
INSERT INTO `bitacora` VALUES ('232', 'Martes', '2026-11-17', '15:25:10', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/');
INSERT INTO `bitacora` VALUES ('233', 'Martes', '2026-11-17', '15:25:17', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('234', 'Martes', '2026-11-17', '15:25:28', '11', 'Editar', 'Municipio', 'Municipio #13 actualizado: Bolívara');
INSERT INTO `bitacora` VALUES ('235', 'Martes', '2026-11-17', '15:25:33', '11', 'Editar', 'Municipio', 'Municipio #13 actualizado: Bolívar');
INSERT INTO `bitacora` VALUES ('236', 'Martes', '2026-11-17', '16:39:33', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/');
INSERT INTO `bitacora` VALUES ('237', 'Martes', '2026-11-17', '16:39:40', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('238', 'Martes', '2026-11-17', '16:39:52', '11', 'Crear', 'Comuna', 'Comuna registrada: prueba');
INSERT INTO `bitacora` VALUES ('239', 'Martes', '2026-11-17', '16:40:12', '11', 'Eliminar', 'Comuna', 'Comuna #121 eliminada');
INSERT INTO `bitacora` VALUES ('240', 'Martes', '2026-11-17', '18:15:32', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/');
INSERT INTO `bitacora` VALUES ('241', 'Martes', '2026-11-17', '18:15:34', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('242', 'Martes', '2026-11-17', '18:15:40', '11', 'Editar', 'Parroquia', 'Parroquia #3 actualizada: Albaricos');
INSERT INTO `bitacora` VALUES ('243', 'Martes', '2026-11-17', '18:15:44', '11', 'Editar', 'Parroquia', 'Parroquia #3 actualizada: Albarico');
INSERT INTO `bitacora` VALUES ('244', 'Martes', '2026-11-17', '18:16:42', '11', 'Crear', 'Cargo', 'Cargo registrado: supervisior bibliotecario');

DROP TABLE IF EXISTS `cargo`;
CREATE TABLE `cargo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(36) NOT NULL,
  `Descripcion` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cargo_nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `cargo` VALUES ('1', 'coordinador', '2');
INSERT INTO `cargo` VALUES ('4', 'secretaria', 'es la secretaria del coordinador');
INSERT INTO `cargo` VALUES ('5', 'Recepcionista', 'Es la recepcionista de una biblioteca');
INSERT INTO `cargo` VALUES ('6', 'promotor de cultura', '');
INSERT INTO `cargo` VALUES ('7', 'Asistente de sala', 'Apoya en la atención directa al usuario');
INSERT INTO `cargo` VALUES ('8', 'supervisior bibliotecario', '');

DROP TABLE IF EXISTS `comuna`;
CREATE TABLE `comuna` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `id_parroquia` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_comuna_nombre_parroquia` (`nombre`,`id_parroquia`),
  KEY `id_parroquia` (`id_parroquia`),
  CONSTRAINT `comuna_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=122 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `comuna` VALUES ('105', 'AGRICOLA VOCES DEL CAMPO', '54');
INSERT INTO `comuna` VALUES ('41', 'AGRO ECOLOGICA TURISTICA GENER', '62');
INSERT INTO `comuna` VALUES ('44', 'AGRO INDUSTRIAL TURISTICA GENE', '61');
INSERT INTO `comuna` VALUES ('92', 'AGROPRODUCTIVA CARACARO BICENT', '46');
INSERT INTO `comuna` VALUES ('95', 'AGROPRODUCTIVA SEMBRANDO EN RE', '46');
INSERT INTO `comuna` VALUES ('91', 'AGROPRODUCTIVA ZONA SUR 5 DE M', '46');
INSERT INTO `comuna` VALUES ('64', 'AGROSOCIALISTA BOLIVAR Y CHAVE', '66');
INSERT INTO `comuna` VALUES ('43', 'AGROTURISTICA EL ESFUERZO Y LA', '62');
INSERT INTO `comuna` VALUES ('36', 'ANGEL RAFAEL GONZALEZ', '62');
INSERT INTO `comuna` VALUES ('38', 'CERRO AZUL TRONCAL 11', '62');
INSERT INTO `comuna` VALUES ('118', 'CHE GUEVARA LEGENDARIO SOCIALI', '52');
INSERT INTO `comuna` VALUES ('8', 'comuna', '54');
INSERT INTO `comuna` VALUES ('17', 'COMUNA SOCIALISTA AGROPRODUCTI', '55');
INSERT INTO `comuna` VALUES ('21', 'COMUNA SOCIALISTA PRODUCTIVA M', '55');
INSERT INTO `comuna` VALUES ('74', 'COMUNA SOCIALISTA PRODUCTIVA N', '64');
INSERT INTO `comuna` VALUES ('109', 'COMUNA SOCIALISTA PRODUCTIVA S', '60');
INSERT INTO `comuna` VALUES ('80', 'COMUNA SOCIALISTA PRODUCTIVA S', '65');
INSERT INTO `comuna` VALUES ('112', 'COMUNA SOCIALISTA Y PRODUCTIVA', '60');
INSERT INTO `comuna` VALUES ('47', 'ECOLOGICA JORGE RODRIGUEZ EJE', '47');
INSERT INTO `comuna` VALUES ('86', 'ECOSOCIALISTA BUENA VISTA', '59');
INSERT INTO `comuna` VALUES ('94', 'ECOSOCIALISTA CAMPAÑA ADMIRABL', '46');
INSERT INTO `comuna` VALUES ('96', 'ECOSOCIALISTA CORAZON DE MI PA', '46');
INSERT INTO `comuna` VALUES ('102', 'EDUCATIVA PRODUCTIVA ECOTURIST', '57');
INSERT INTO `comuna` VALUES ('52', 'EJE GUAYABAL', '47');
INSERT INTO `comuna` VALUES ('24', 'HIJOS DE CHAVEZ', '49');
INSERT INTO `comuna` VALUES ('12', 'LA NUEVA UNION DE LAS CUMARAGU', '55');
INSERT INTO `comuna` VALUES ('81', 'LA UNION DE LOS PUEBLOS SOCIAL', '65');
INSERT INTO `comuna` VALUES ('34', 'NIRGUA PRODUCTIVA', '62');
INSERT INTO `comuna` VALUES ('78', 'PRODUCTIVA FRANCISCO DE MIRAND', '64');
INSERT INTO `comuna` VALUES ('79', 'PRODUCTIVA SEGUIREMOS TENIENDO', '64');
INSERT INTO `comuna` VALUES ('16', 'PRODUCTIVA SOCIALISTA CHAVEZ P', '55');
INSERT INTO `comuna` VALUES ('115', 'PUEBLO TIERRA Y HOMBRES LIBRES', '53');
INSERT INTO `comuna` VALUES ('119', 'ROBERT SERRA', '52');
INSERT INTO `comuna` VALUES ('20', 'SIERRA AZUL CARABOBO', '55');
INSERT INTO `comuna` VALUES ('11', 'SOCIALISTA 12 DE ABRIL', '55');
INSERT INTO `comuna` VALUES ('61', 'SOCIALISTA 12 DE OCTUBRE', '66');
INSERT INTO `comuna` VALUES ('23', 'SOCIALISTA AGRARIA ENDOGENA EC', '49');
INSERT INTO `comuna` VALUES ('37', 'SOCIALISTA AGRICOLA LEGUMINOSA', '62');
INSERT INTO `comuna` VALUES ('110', 'SOCIALISTA AGRICOLA TURISTICA', '60');
INSERT INTO `comuna` VALUES ('46', 'SOCIALISTA AGROECOLOGICA SIMON', '47');
INSERT INTO `comuna` VALUES ('33', 'SOCIALISTA AGROECOLOGICA TURIS', '62');
INSERT INTO `comuna` VALUES ('55', 'SOCIALISTA AGROECOLOGICA YURUB', '3');
INSERT INTO `comuna` VALUES ('25', 'SOCIALISTA AGROPECUARIA MARIA', '49');
INSERT INTO `comuna` VALUES ('57', 'SOCIALISTA AGROPRODUCTIVA AURA', '3');
INSERT INTO `comuna` VALUES ('59', 'SOCIALISTA AGROPRODUCTIVA CIMA', '48');
INSERT INTO `comuna` VALUES ('58', 'SOCIALISTA AGROPRODUCTIVA ECOL', '48');
INSERT INTO `comuna` VALUES ('13', 'SOCIALISTA AGROPRODUCTIVA EJE', '55');
INSERT INTO `comuna` VALUES ('18', 'SOCIALISTA AGROPRODUCTIVA RIO', '55');
INSERT INTO `comuna` VALUES ('82', 'SOCIALISTA AGROTURISTICA ECOLO', '58');
INSERT INTO `comuna` VALUES ('26', 'SOCIALISTA AGROTURISTICA INDUS', '49');
INSERT INTO `comuna` VALUES ('117', 'SOCIALISTA AGROTURISTICA RIO T', '52');
INSERT INTO `comuna` VALUES ('62', 'SOCIALISTA AGROTURISTICA Y PRO', '66');
INSERT INTO `comuna` VALUES ('70', 'SOCIALISTA AGUA LINDA', '64');
INSERT INTO `comuna` VALUES ('66', 'SOCIALISTA ALI PRIMERA', '67');
INSERT INTO `comuna` VALUES ('15', 'SOCIALISTA ALIANZA CAMPESINA', '55');
INSERT INTO `comuna` VALUES ('45', 'SOCIALISTA AMBROCIO PEREIRA', '63');
INSERT INTO `comuna` VALUES ('10', 'SOCIALISTA ARBOL DE LAS TRES R', '55');
INSERT INTO `comuna` VALUES ('68', 'SOCIALISTA CAMUNARE ROJO', '67');
INSERT INTO `comuna` VALUES ('84', 'SOCIALISTA CENTAUROS DE PAEZ', '58');
INSERT INTO `comuna` VALUES ('50', 'SOCIALISTA CIUDADELA COMANDANT', '47');
INSERT INTO `comuna` VALUES ('73', 'SOCIALISTA COMANDANTE SUPREMO', '64');
INSERT INTO `comuna` VALUES ('54', 'SOCIALISTA CULTURAL AGROPRODUC', '3');
INSERT INTO `comuna` VALUES ('77', 'SOCIALISTA DIOS ES MI AMIGO', '64');
INSERT INTO `comuna` VALUES ('56', 'SOCIALISTA DR. JUAN JOSE DE MA', '3');
INSERT INTO `comuna` VALUES ('69', 'SOCIALISTA ECOLOGICA LINA RON', '64');
INSERT INTO `comuna` VALUES ('120', 'SOCIALISTA EL GUAYABO', '52');
INSERT INTO `comuna` VALUES ('63', 'SOCIALISTA EL RENACER DEL TIMO', '66');
INSERT INTO `comuna` VALUES ('103', 'SOCIALISTA EMPRENDEDORES DEL G', '57');
INSERT INTO `comuna` VALUES ('101', 'SOCIALISTA FERMIN ABDOM RAMIRE', '57');
INSERT INTO `comuna` VALUES ('90', 'SOCIALISTA FORJADORES DE LA PA', '59');
INSERT INTO `comuna` VALUES ('32', 'SOCIALISTA GUERREROS DE LA MON', '62');
INSERT INTO `comuna` VALUES ('40', 'SOCIALISTA GUERREROS JIRAHARA', '62');
INSERT INTO `comuna` VALUES ('85', 'SOCIALISTA INDEPENDENCIA 18 DE', '59');
INSERT INTO `comuna` VALUES ('100', 'SOCIALISTA INDEPENDENCIA Y PAT', '57');
INSERT INTO `comuna` VALUES ('116', 'SOCIALISTA JOSE JOAQUIN VEROES', '53');
INSERT INTO `comuna` VALUES ('97', 'SOCIALISTA JUAN MILLA', '46');
INSERT INTO `comuna` VALUES ('89', 'SOCIALISTA LA SABANA DE PALITO', '59');
INSERT INTO `comuna` VALUES ('113', 'SOCIALISTA LOS CAÑIZOS PALO QU', '53');
INSERT INTO `comuna` VALUES ('39', 'SOCIALISTA MANUEL CEDEÑO', '62');
INSERT INTO `comuna` VALUES ('19', 'SOCIALISTA MARIA MIRANDA', '55');
INSERT INTO `comuna` VALUES ('48', 'SOCIALISTA MARIA VALENTINA PIN', '47');
INSERT INTO `comuna` VALUES ('28', 'SOCIALISTA MAXIMO VIZCAYA', '56');
INSERT INTO `comuna` VALUES ('14', 'SOCIALISTA MELECIO TORRES', '55');
INSERT INTO `comuna` VALUES ('42', 'SOCIALISTA NEGRO MIGUEL DE BUR', '62');
INSERT INTO `comuna` VALUES ('104', 'SOCIALISTA PETRA BETANCOURT', '54');
INSERT INTO `comuna` VALUES ('60', 'SOCIALISTA POPULAR Y CHAVISTA', '48');
INSERT INTO `comuna` VALUES ('29', 'SOCIALISTA PRODUCTIVA AGROENDO', '56');
INSERT INTO `comuna` VALUES ('30', 'SOCIALISTA PRODUCTIVA DOMINGO', '56');
INSERT INTO `comuna` VALUES ('107', 'SOCIALISTA PRODUCTIVA FERROCAR', '60');
INSERT INTO `comuna` VALUES ('83', 'SOCIALISTA PRODUCTIVA TIERRAS', '58');
INSERT INTO `comuna` VALUES ('65', 'SOCIALISTA PRODUCTORAS Y PRODU', '66');
INSERT INTO `comuna` VALUES ('114', 'SOCIALISTA PUEBLOS UNIDOS SIMO', '53');
INSERT INTO `comuna` VALUES ('22', 'SOCIALISTA ROSA LUXEMBURGO', '49');
INSERT INTO `comuna` VALUES ('53', 'SOCIALISTA SAN FELIPE EL FUERT', '47');
INSERT INTO `comuna` VALUES ('35', 'SOCIALISTA SANTANA OCHOA', '62');
INSERT INTO `comuna` VALUES ('9', 'SOCIALISTA SIERRA NUBLADA', '55');
INSERT INTO `comuna` VALUES ('49', 'SOCIALISTA SIMON TORRES', '47');
INSERT INTO `comuna` VALUES ('106', 'SOCIALISTA SOBERANOS DE LA PAT', '54');
INSERT INTO `comuna` VALUES ('27', 'SOCIALISTA SOCIOPRODUCTIVA TRO', '49');
INSERT INTO `comuna` VALUES ('88', 'SOCIALISTA TRINIDAD VICTORIOSO', '59');
INSERT INTO `comuna` VALUES ('87', 'SOCIALISTA UN NUEVO RENACER 20', '59');
INSERT INTO `comuna` VALUES ('51', 'SOCIALISTA UNIDAD LUCHA BATALL', '47');
INSERT INTO `comuna` VALUES ('67', 'SOCIALISTA URACHICHEÑA HUGO RA', '67');
INSERT INTO `comuna` VALUES ('72', 'SOCIALISTA URIMIQUIRE', '64');
INSERT INTO `comuna` VALUES ('98', 'SOCIALISTA VENCEDORES Y VENCED', '46');
INSERT INTO `comuna` VALUES ('99', 'SOCIALISTA WILLIAM LARA VIVE', '57');
INSERT INTO `comuna` VALUES ('111', 'SOCIALISTA Y PRODUCTIVA JOSE F', '60');
INSERT INTO `comuna` VALUES ('108', 'SOCIALISTA Y PRODUCTIVA MANUEL', '60');
INSERT INTO `comuna` VALUES ('93', 'SOCIOPRODUCTIVA 7 RAICES CECIL', '46');
INSERT INTO `comuna` VALUES ('76', 'SOCIOPRODUCTIVA CHAVEZ VIVE', '64');
INSERT INTO `comuna` VALUES ('71', 'SOCIOPRODUCTIVA MUJERES BICENT', '64');
INSERT INTO `comuna` VALUES ('31', 'SOCIOPRODUCTIVA NEGRA MATEA', '56');
INSERT INTO `comuna` VALUES ('75', 'SOCIOPRODUCTIVA VALEROSO PEÑA', '64');

DROP TABLE IF EXISTS `empleado`;
CREATE TABLE `empleado` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(40) NOT NULL,
  `apellido` varchar(20) NOT NULL,
  `cedula` int(8) NOT NULL,
  `telefono` varchar(11) NOT NULL,
  `genero` varchar(10) NOT NULL,
  `edad` int(2) unsigned NOT NULL,
  `anios_de_servicio` int(2) unsigned NOT NULL,
  `id_cargo` int(10) NOT NULL,
  `id_biblioteca` int(10) NOT NULL,
  `fecha_inicio_cargo` date DEFAULT NULL,
  `fecha_fin_cargo` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cedula` (`cedula`),
  KEY `id_cargo` (`id_cargo`),
  KEY `id_biblioteca` (`id_biblioteca`),
  CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id`),
  CONSTRAINT `empleado_ibfk_biblioteca` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `empleado` VALUES ('13', 'jesus', 'serrano', '31982637', '04125240489', 'M', '1', '0', '1', '15', NULL, NULL);
INSERT INTO `empleado` VALUES ('15', 'jesus', 'soto', '32435607', '04125240489', '', '0', '0', '1', '15', NULL, NULL);
INSERT INTO `empleado` VALUES ('27', 'freddy', 'espinoza', '32626427', '04145058143', 'M', '20', '0', '1', '18', '2026-10-03', NULL);
INSERT INTO `empleado` VALUES ('28', 'cesar', 'ontreras', '31982333', '04127168235', 'M', '20', '18', '1', '16', '2026-10-04', NULL);
INSERT INTO `empleado` VALUES ('29', 'maria', 'narvaez', '15964066', '04261181304', 'M', '30', '7', '1', '16', '2019-02-10', NULL);
INSERT INTO `empleado` VALUES ('30', 'jesus', 'bonilla', '33027723', '04245669023', 'M', '19', '0', '6', '21', '2026-09-01', NULL);
INSERT INTO `empleado` VALUES ('31', 'isaias', 'Morales', '32546391', '04245543020', 'M', '39', '15', '5', '17', '2009-03-30', '2024-09-30');
INSERT INTO `empleado` VALUES ('32', 'manuela', 'suarez', '20435609', '04223220114', 'F', '28', '2', '4', '19', '2024-01-10', NULL);

DROP TABLE IF EXISTS `espacio_cultural`;
CREATE TABLE `espacio_cultural` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_parroquia` int(10) DEFAULT NULL,
  `nombre` varchar(30) NOT NULL,
  `capacidad` int(10) NOT NULL,
  `direccion` varchar(30) NOT NULL,
  `Metodo_contactar` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_espacio_cultural_nombre` (`nombre`),
  KEY `idx_espacio_cultural_parroquia` (`id_parroquia`),
  CONSTRAINT `espacio_cultural_ibfk_parroquia` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `espacio_cultural` VALUES ('4', '49', 'instalacion publica', '6', 'independencia', '');
INSERT INTO `espacio_cultural` VALUES ('5', '54', 'teatro', '10', 'independencia', '');
INSERT INTO `espacio_cultural` VALUES ('6', '47', 'escenario', '100', 'calle 4', '');
INSERT INTO `espacio_cultural` VALUES ('8', '56', 'Museo Carmelo Fernández', '100', '2 Av, entre Calles 13 y 14', '');
INSERT INTO `espacio_cultural` VALUES ('9', NULL, 'Parque Recreacional Leonor', '500', 'Final de la Av. Yurubí', '');

DROP TABLE IF EXISTS `institucion`;
CREATE TABLE `institucion` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_municipio` int(10) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `rif` varchar(12) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `direccion` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_institucion_rif` (`rif`),
  KEY `id_municipio` (`id_municipio`),
  CONSTRAINT `institucion_ibfk_1` FOREIGN KEY (`id_municipio`) REFERENCES `municipio` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `institucion` VALUES ('1', '4', 'Institucion 1', NULL, NULL, NULL);
INSERT INTO `institucion` VALUES ('3', '6', 'martin tovar y tovar', 'J-20014532-8', NULL, NULL);
INSERT INTO `institucion` VALUES ('4', '6', 'Liceo Parque Leonor Bernabó', 'J-01349009-2', NULL, NULL);
INSERT INTO `institucion` VALUES ('5', '2', 'colegio Manuel cedeño', 'J-30151643-5', NULL, NULL);
INSERT INTO `institucion` VALUES ('6', '2', 'Complejo Educativo Trinidad Figuera', 'J-56882144-7', NULL, NULL);
INSERT INTO `institucion` VALUES ('7', '5', 'Colegio Santa Maria', 'J-32435609-1', NULL, NULL);
INSERT INTO `institucion` VALUES ('8', '12', 'Liceo Bolivariano Sabanita', 'J-40001030-6', NULL, NULL);
INSERT INTO `institucion` VALUES ('9', '9', 'Liceo Doctor Heriberto Núñez Oliveros', 'J-15964066-3', NULL, NULL);
INSERT INTO `institucion` VALUES ('10', '4', 'UPTYAB', 'G-20012118-1', 'rectoriauptyaaracuy@gmail.com', 'Av. Alberto Ravell con Av. José Antonio Páez, frente a la Plaza de las Banderas');
INSERT INTO `institucion` VALUES ('11', '21', 'Liceo Julio Urrutia', 'J-54608443-4', NULL, NULL);

DROP TABLE IF EXISTS `municipio`;
CREATE TABLE `municipio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_municipio_nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `municipio` VALUES ('21', 'Arístides Bastidas');
INSERT INTO `municipio` VALUES ('13', 'Bolívar');
INSERT INTO `municipio` VALUES ('5', 'Bruzual');
INSERT INTO `municipio` VALUES ('6', 'Cocorote');
INSERT INTO `municipio` VALUES ('4', 'Independencia');
INSERT INTO `municipio` VALUES ('15', 'José Antonio Páez');
INSERT INTO `municipio` VALUES ('11', 'La Trinidad');
INSERT INTO `municipio` VALUES ('10', 'Manuel Monge');
INSERT INTO `municipio` VALUES ('9', 'Nirgua');
INSERT INTO `municipio` VALUES ('12', 'Peña');
INSERT INTO `municipio` VALUES ('2', 'San Felipe');
INSERT INTO `municipio` VALUES ('3', 'Sucre');
INSERT INTO `municipio` VALUES ('7', 'Urachiche');
INSERT INTO `municipio` VALUES ('8', 'Veroes');

DROP TABLE IF EXISTS `nivel_impacto`;
CREATE TABLE `nivel_impacto` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre_impacto` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nivel_impacto_nombre` (`nombre_impacto`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `nivel_impacto` VALUES ('3', 'Comunal');
INSERT INTO `nivel_impacto` VALUES ('7', 'Escolar');
INSERT INTO `nivel_impacto` VALUES ('4', 'estadal');
INSERT INTO `nivel_impacto` VALUES ('8', 'Laboral');
INSERT INTO `nivel_impacto` VALUES ('5', 'municipal');
INSERT INTO `nivel_impacto` VALUES ('6', 'nacional');

DROP TABLE IF EXISTS `parroquia`;
CREATE TABLE `parroquia` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `id_municipio` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_parroquia_nombre_municipio` (`nombre`,`id_municipio`),
  KEY `id_municipio` (`id_municipio`),
  CONSTRAINT `parroquia_ibfk_1` FOREIGN KEY (`id_municipio`) REFERENCES `municipio` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `parroquia` VALUES ('3', 'Albarico', '2');
INSERT INTO `parroquia` VALUES ('54', 'Arístides Bastidas', '21');
INSERT INTO `parroquia` VALUES ('68', 'Aroa', '10');
INSERT INTO `parroquia` VALUES ('55', 'Bolivar', '13');
INSERT INTO `parroquia` VALUES ('56', 'Campo Elias', '5');
INSERT INTO `parroquia` VALUES ('49', 'Chivacoa', '5');
INSERT INTO `parroquia` VALUES ('46', 'Cocorote', '6');
INSERT INTO `parroquia` VALUES ('52', 'El Guayabo', '8');
INSERT INTO `parroquia` VALUES ('53', 'Farriar', '8');
INSERT INTO `parroquia` VALUES ('57', 'Independencia', '4');
INSERT INTO `parroquia` VALUES ('58', 'José Antonio Paez', '15');
INSERT INTO `parroquia` VALUES ('59', 'La Trinidad', '11');
INSERT INTO `parroquia` VALUES ('60', 'Manuel Monge', '10');
INSERT INTO `parroquia` VALUES ('62', 'Nirgua', '9');
INSERT INTO `parroquia` VALUES ('61', 'Salom', '9');
INSERT INTO `parroquia` VALUES ('65', 'San Andres', '12');
INSERT INTO `parroquia` VALUES ('51', 'San Andres', '13');
INSERT INTO `parroquia` VALUES ('47', 'San Felipe', '2');
INSERT INTO `parroquia` VALUES ('48', 'San Javier', '2');
INSERT INTO `parroquia` VALUES ('66', 'Sucre', '3');
INSERT INTO `parroquia` VALUES ('63', 'Temeria', '9');
INSERT INTO `parroquia` VALUES ('67', 'Urachiche', '7');
INSERT INTO `parroquia` VALUES ('64', 'Yaritagua', '12');

DROP TABLE IF EXISTS `solicitud`;
CREATE TABLE `solicitud` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
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
  `estado` enum('pendiente','aprobada','rechazada','cancelada') NOT NULL DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  KEY `id_institucion` (`id_institucion`),
  KEY `idx_solicitud_id_biblioteca` (`id_biblioteca`),
  KEY `solicitud_ibfk_responsable` (`responsable`),
  CONSTRAINT `solicitud_ibfk_1` FOREIGN KEY (`id_institucion`) REFERENCES `institucion` (`id`),
  CONSTRAINT `solicitud_ibfk_biblioteca` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`),
  CONSTRAINT `solicitud_ibfk_responsable` FOREIGN KEY (`responsable`) REFERENCES `empleado` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `solicitud` VALUES ('2', '1', '16', 'juego de ajedrez', '2026-11-30', '2026-10-02 20:33:41', '14:16:00', 'Felix Pifano', '28', '100', '1', 'pendiente');
INSERT INTO `solicitud` VALUES ('7', '1', '15', 'juego de metras', '2026-11-03', '2026-10-31 11:30:28', '10:00:00', 'biblioteca', '13', '15', 'van a jugar metras para entender como era este juego tradicional', 'aprobada');

DROP TABLE IF EXISTS `tipo_actividad`;
CREATE TABLE `tipo_actividad` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(15) NOT NULL,
  `Descripcion` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `tipo_actividad` VALUES ('5', 'educativa', '');
INSERT INTO `tipo_actividad` VALUES ('9', 'Folklórica', 'Festivales de danza regionales etc');
INSERT INTO `tipo_actividad` VALUES ('10', 'Gastronómicas', '');
INSERT INTO `tipo_actividad` VALUES ('11', 'Formativa', 'Talleres de cerámica, pintura, etc');
INSERT INTO `tipo_actividad` VALUES ('12', 'Artísticas', 'Obras de teatro, entre otros');

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(15) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `rol` enum('administrador','coordinador','usuario') NOT NULL DEFAULT 'usuario',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuario_nombre` (`nombre`),
  UNIQUE KEY `uq_usuario_empleado` (`id_empleado`),
  CONSTRAINT `fk_usuario_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `usuario` VALUES ('11', 'cheddar', '$2y$10$gDEMnG9XXqwiKvUQNP95BOzYUQ/KcRkmZcCqfzHi.WvkowTeqRjwq', '04125240489', '15', 'administrador');
INSERT INTO `usuario` VALUES ('12', 'jesus', '$2y$10$22hr1NEq6GUFaPoOsnYmm.PeC.kGmRxsLQD.zgr265rZfhU54qqKG', '04125240489', '13', 'coordinador');
INSERT INTO `usuario` VALUES ('14', 'coord', '$2y$10$LAxF.VhNlb1CndPOQo.L0un5WUiloc5MBYeQXd0e/oVQQcJiUNlrG', '04261181304', '29', 'coordinador');
INSERT INTO `usuario` VALUES ('15', 'cesar', '$2y$10$bPRPSAtGXGSk404EfnbmmeNUk0HhRYEWAmV4EjyNvps62HReC3zme', '04127168235', '28', 'usuario');
INSERT INTO `usuario` VALUES ('16', 'isaias', '$2y$10$DKiwxbX8h.XUnAjxGlSRne3ZqJsXU4dCIlZm9BxPz4N20AKCqjefu', '04245543020', '31', 'usuario');
INSERT INTO `usuario` VALUES ('17', 'freddy', '$2y$10$i0Fjj3gvLB1fWQKyggpMu.rO0Hub4oSI.yAKzYoPv8vxoimRLZ2Ya', '04145058143', '27', 'administrador');

SET FOREIGN_KEY_CHECKS = 1;
