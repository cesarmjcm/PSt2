SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `actividad`;
CREATE TABLE `actividad` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_solicitud` int(10) DEFAULT NULL,
  `id_biblioteca` int(11) DEFAULT NULL,
  `id_espacio_cultural` int(11) DEFAULT NULL,
  `nombre` varchar(30) NOT NULL,
  `id_tipo_actividad` int(10) NOT NULL,
  `descripcion` text NOT NULL,
  `objetivo` varchar(50) NOT NULL,
  `participantes` int(5) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time DEFAULT NULL,
  `dia_semana` text NOT NULL,
  `estado` enum('pendiente','confirmada','ejecutada','cancelada') NOT NULL DEFAULT 'pendiente',
  `responsable` varchar(100) DEFAULT NULL,
  `telefono_responsable` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_solicitud` (`id_solicitud`),
  KEY `id_biblioteca` (`id_biblioteca`),
  KEY `id_espacio_cultural` (`id_espacio_cultural`),
  KEY `id_tipo_actividad` (`id_tipo_actividad`),
  CONSTRAINT `actividad_ibfk_1` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`),
  CONSTRAINT `actividad_ibfk_2` FOREIGN KEY (`id_espacio_cultural`) REFERENCES `espacio_cultural` (`id`),
  CONSTRAINT `actividad_ibfk_3` FOREIGN KEY (`id_tipo_actividad`) REFERENCES `tipo_actividad` (`id`),
  CONSTRAINT `actividad_ibfk_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `actividad` VALUES ('23', NULL, '15', '5', 'Biblioteca 1', '4', 'Solicitud 1', 'Responsable 1', '20', '2026-08-09', NULL, 'Domingo', 'confirmada', 'jesus serrano', '2147483647');
INSERT INTO `actividad` VALUES ('24', NULL, '15', '5', 'zona2', '4', '2', 'carlos', '222', '2026-08-12', NULL, 'Miércoles', 'confirmada', 'jesus serrano', '2147483647');
INSERT INTO `actividad` VALUES ('27', NULL, '15', NULL, 'Arístides Bastidas', '5', 'hola', 'hola', '10', '2026-08-26', '12:53:00', 'Miércoles', 'confirmada', 'jesus soto', '04125240489');
INSERT INTO `actividad` VALUES ('28', NULL, '15', NULL, 'Arístides Bastidas', '5', 'holi', 'formativa', '21', '2026-08-05', '18:58:00', 'Miércoles', 'confirmada', 'jesus soto', '2147483647');
INSERT INTO `actividad` VALUES ('29', NULL, NULL, '5', 'hola', '5', 'cosas', 'No definido', '0', '2026-08-19', '13:16:00', 'Miércoles', 'confirmada', 'jesus serrano', '04125240489');
INSERT INTO `actividad` VALUES ('30', NULL, '15', NULL, 'juego de ajedrez', '5', 'hola', 'Formativa', '20', '2026-08-05', '13:38:00', 'Miércoles', 'confirmada', 'jesus serrano', '04125240489');
INSERT INTO `actividad` VALUES ('31', NULL, '15', NULL, 'Arístides Bastidas', '5', 'cosas', 'No definido', '0', '2026-08-20', '17:41:00', 'Jueves', 'confirmada', 'jesus serrano', '04125240489');
INSERT INTO `actividad` VALUES ('32', NULL, '15', NULL, 'Juego de Ajedrez', '5', 'Actividad de jugar ajedrez realizada en Biblioteca', 'Formativa', '2', '2026-08-27', '13:04:00', 'Jueves', 'confirmada', 'jesus serrano', '04125240489');
INSERT INTO `actividad` VALUES ('33', NULL, '15', NULL, 'Juego de Ajedrez', '5', 'hola', 'No definido', '0', '2026-08-27', '16:33:00', 'Jueves', 'confirmada', 'jesus serrano', '04125240489');
INSERT INTO `actividad` VALUES ('34', NULL, '15', NULL, 'Juego de Ajedrez', '5', 'hola', 'No definido', '0', '2026-07-17', '16:39:00', 'Viernes', 'confirmada', 'jesus serrano', '04125240489');
INSERT INTO `actividad` VALUES ('35', NULL, '15', NULL, 'juego de mesa', '4', 'cosas', 'formal', '21', '2026-09-05', '19:55:00', 'Sábado', 'cancelada', 'jesus soto', '04125240489');
INSERT INTO `actividad` VALUES ('36', NULL, '15', NULL, 'biblioteca', '5', 'a', 'jesus serrano', '1', '2026-08-13', '21:24:00', 'Jueves', 'confirmada', 'jesus serrano', '04125240489');
INSERT INTO `actividad` VALUES ('37', NULL, '15', NULL, 'juego de mesa', '5', 'cosas', 'No definido', '0', '2026-10-03', '16:55:00', 'Sábado', 'pendiente', 'cesar ontreras', '04127168235');
INSERT INTO `actividad` VALUES ('38', NULL, '16', NULL, 'Felix Pifano', '5', 'felix', 'No definido', '0', '2026-10-04', '18:12:00', 'Domingo', 'pendiente', 'cesar ontreras', '04127168235');
INSERT INTO `actividad` VALUES ('39', NULL, NULL, '5', 'zona', '5', '1', 'No definido', '2', '2026-08-06', '14:16:00', 'Jueves', 'pendiente', 'freddy espinoza', '04145058143');
INSERT INTO `actividad` VALUES ('40', NULL, '15', NULL, 'biblioteca', '5', 'hola', 'No definido', '1', '2026-10-09', '12:44:00', 'Viernes', 'pendiente', 'freddy espinoza', '04145058143');

DROP TABLE IF EXISTS `actividad_comuna`;
CREATE TABLE `actividad_comuna` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_comuna` int(10) NOT NULL,
  `id_actividad` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_comuna` (`id_comuna`),
  KEY `id_actividad` (`id_actividad`),
  CONSTRAINT `actividad_comuna_ibfk_1` FOREIGN KEY (`id_actividad`) REFERENCES `actividad` (`id`) ON DELETE CASCADE,
  CONSTRAINT `actividad_comuna_ibfk_2` FOREIGN KEY (`id_comuna`) REFERENCES `comuna` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `actividad_comuna` VALUES ('16', '8', '23');
INSERT INTO `actividad_comuna` VALUES ('17', '8', '24');
INSERT INTO `actividad_comuna` VALUES ('20', '8', '28');
INSERT INTO `actividad_comuna` VALUES ('28', '8', '27');
INSERT INTO `actividad_comuna` VALUES ('29', '8', '29');
INSERT INTO `actividad_comuna` VALUES ('30', '8', '30');
INSERT INTO `actividad_comuna` VALUES ('31', '8', '31');
INSERT INTO `actividad_comuna` VALUES ('33', '8', '32');
INSERT INTO `actividad_comuna` VALUES ('34', '8', '33');
INSERT INTO `actividad_comuna` VALUES ('35', '8', '34');
INSERT INTO `actividad_comuna` VALUES ('43', '8', '36');
INSERT INTO `actividad_comuna` VALUES ('44', '8', '35');
INSERT INTO `actividad_comuna` VALUES ('45', '8', '37');
INSERT INTO `actividad_comuna` VALUES ('46', '8', '39');

DROP TABLE IF EXISTS `actividad_espaciocultural`;
CREATE TABLE `actividad_espaciocultural` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_actividad` int(10) NOT NULL,
  `id_biblioteca` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_actividad` (`id_actividad`,`id_biblioteca`),
  KEY `actividad_espaciocultural_ibfk_1` (`id_biblioteca`),
  CONSTRAINT `idactividad` FOREIGN KEY (`id_actividad`) REFERENCES `actividad` (`id`),
  CONSTRAINT `idbiblioteca` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `biblioteca`;
CREATE TABLE `biblioteca` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `id_parroquia` int(10) NOT NULL,
  `Correo` varchar(30) NOT NULL DEFAULT '',
  `redes_sociales` varchar(30) NOT NULL DEFAULT '',
  `Direccion` varchar(30) NOT NULL DEFAULT '',
  `id_solicitud_actividad` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_biblioteca_nombre_parroquia` (`nombre`,`id_parroquia`),
  KEY `id_parroquia` (`id_parroquia`),
  KEY `id_solicitud` (`id_solicitud_actividad`),
  CONSTRAINT `biblioteca_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`),
  CONSTRAINT `biblioteca_ibfk_2` FOREIGN KEY (`id_solicitud_actividad`) REFERENCES `solicitud` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `biblioteca` VALUES ('15', 'biblioteca', '54', 'cesarmcontre28@gmail.com', 'cesarm2', 'calle 4', NULL);
INSERT INTO `biblioteca` VALUES ('16', 'Felix Pifano', '47', '', '', '', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
INSERT INTO `bitacora` VALUES ('84', 'Sabado', '2026-10-03', '18:09:21', '13', 'Login', 'Usuario', 'Inicio de sesión: cesar');
INSERT INTO `bitacora` VALUES ('85', 'Sabado', '2026-10-03', '18:10:02', '13', 'Crear', 'Actividad', 'Actividad registrada: Felix Pifano');
INSERT INTO `bitacora` VALUES ('86', 'Lunes', '2026-10-05', '16:02:31', '13', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('87', 'Lunes', '2026-10-05', '16:02:33', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('88', 'Miercoles', '2026-10-07', '14:59:39', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('89', 'Miercoles', '2026-10-07', '14:59:43', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('90', 'Miercoles', '2026-10-07', '15:05:27', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/reportes_cargo/exportar_cargos.php');
INSERT INTO `bitacora` VALUES ('91', 'Miercoles', '2026-10-07', '15:05:47', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/reportes_cargo/exportar_cargos.php');
INSERT INTO `bitacora` VALUES ('92', 'Miercoles', '2026-10-07', '15:06:31', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/maestro.php?tabla=cargo');
INSERT INTO `bitacora` VALUES ('93', 'Miercoles', '2026-10-07', '16:25:01', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('94', 'Miercoles', '2026-10-07', '16:25:03', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('95', 'Jueves', '2026-10-08', '12:41:09', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('96', 'Jueves', '2026-10-08', '12:41:12', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('97', 'Jueves', '2026-10-08', '12:41:34', '11', 'Crear', 'Actividad', 'Actividad registrada: zona');
INSERT INTO `bitacora` VALUES ('98', 'Jueves', '2026-10-08', '12:42:17', '11', 'Crear', 'Solicitud', 'Solicitud registrada para institución #2');
INSERT INTO `bitacora` VALUES ('99', 'Jueves', '2026-10-08', '12:42:33', '11', 'Crear', 'Actividad', 'Actividad registrada: biblioteca');
INSERT INTO `bitacora` VALUES ('100', 'Jueves', '2026-10-08', '12:51:02', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('101', 'Jueves', '2026-10-08', '13:57:10', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('102', 'Jueves', '2026-10-08', '13:57:12', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('103', 'Jueves', '2026-10-08', '18:47:18', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('104', 'Jueves', '2026-10-08', '18:47:28', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('105', 'Jueves', '2026-10-08', '18:47:38', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('106', 'Domingo', '2026-10-11', '14:30:39', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('107', 'Domingo', '2026-10-11', '14:30:43', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('108', 'Domingo', '2026-10-11', '17:48:58', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('109', 'Domingo', '2026-10-11', '19:22:20', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('110', 'Domingo', '2026-10-11', '19:22:25', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('111', 'Domingo', '2026-10-11', '19:22:28', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('112', 'Miercoles', '2026-10-14', '22:23:17', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('113', 'Miercoles', '2026-10-14', '22:23:21', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('114', 'Miercoles', '2026-10-14', '22:23:23', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('115', 'Miercoles', '2026-10-14', '22:54:59', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/index.php?q=bibliote');
INSERT INTO `bitacora` VALUES ('116', 'Domingo', '2026-10-18', '20:51:59', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('117', 'Domingo', '2026-10-18', '20:52:01', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('118', 'Domingo', '2026-10-18', '20:52:02', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('119', 'Jueves', '2026-10-22', '20:47:34', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('120', 'Jueves', '2026-10-22', '20:47:34', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('121', 'Jueves', '2026-10-22', '20:47:34', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('122', 'Jueves', '2026-10-22', '20:47:35', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/solicitud.php');
INSERT INTO `bitacora` VALUES ('123', 'Jueves', '2026-10-22', '20:47:38', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('124', 'Jueves', '2026-10-22', '20:48:45', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('125', 'Domingo', '2026-10-25', '12:19:19', '11', 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/');
INSERT INTO `bitacora` VALUES ('126', 'Domingo', '2026-10-25', '12:19:21', '11', 'Login', 'Usuario', 'Inicio de sesión: cheddar');
INSERT INTO `bitacora` VALUES ('127', 'Domingo', '2026-10-25', '12:27:57', '11', 'Exportar', 'Respaldo BD', 'Respaldo creado: red_bibliotecas-25-10-2026_17-27-57.sql');
INSERT INTO `bitacora` VALUES ('128', 'Domingo', '2026-10-25', '12:31:16', NULL, 'Acceso Denegado', 'Seguridad', 'Intento de acceso sin autenticación a: /PSt2-main2/src/respaldos.php');
INSERT INTO `bitacora` VALUES ('129', 'Domingo', '2026-10-25', '12:31:35', '11', 'Exportar', 'Respaldo BD', 'Respaldo creado: red_bibliotecas-25-10-2026_17-31-35.sql');

DROP TABLE IF EXISTS `cargo`;
CREATE TABLE `cargo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(36) NOT NULL,
  `Descripcion` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_cargo_nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `cargo` VALUES ('1', 'coordinador', '2');

DROP TABLE IF EXISTS `comuna`;
CREATE TABLE `comuna` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `id_parroquia` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_comuna_nombre_parroquia` (`nombre`,`id_parroquia`),
  KEY `id_parroquia` (`id_parroquia`),
  CONSTRAINT `comuna_ibfk_1` FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `comuna` VALUES ('8', 'comuna', '54');

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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `empleado` VALUES ('13', 'jesus', 'serrano', '31982637', '04125240489', 'M', '1', '0', '1', '15', NULL, NULL);
INSERT INTO `empleado` VALUES ('15', 'jesus', 'soto', '32435607', '04125240489', '', '0', '0', '1', '15', NULL, NULL);
INSERT INTO `empleado` VALUES ('27', 'freddy', 'espinoza', '32626427', '04145058143', 'M', '20', '1', '1', '15', '2026-10-03', '2026-10-03');
INSERT INTO `empleado` VALUES ('28', 'cesar', 'ontreras', '31982333', '04127168235', 'M', '20', '18', '1', '16', '2026-10-04', NULL);

DROP TABLE IF EXISTS `espacio_cultural`;
CREATE TABLE `espacio_cultural` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `capacidad` int(10) NOT NULL,
  `direccion` varchar(30) NOT NULL,
  `Metodo_contactar` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_espacio_cultural_nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `espacio_cultural` VALUES ('4', 'tu casa', '6', 'independencia', '');
INSERT INTO `espacio_cultural` VALUES ('5', 'mi casa', '10', 'independencia', 'mi casa');

DROP TABLE IF EXISTS `impacto_actividad`;
CREATE TABLE `impacto_actividad` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_impacto` int(10) NOT NULL,
  `id_actividad` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_impacto` (`id_impacto`),
  KEY `id_actividad` (`id_actividad`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `impacto_actividad` VALUES ('1', '3', '9');
INSERT INTO `impacto_actividad` VALUES ('3', '3', '28');
INSERT INTO `impacto_actividad` VALUES ('11', '3', '27');
INSERT INTO `impacto_actividad` VALUES ('12', '3', '30');
INSERT INTO `impacto_actividad` VALUES ('13', '3', '32');
INSERT INTO `impacto_actividad` VALUES ('21', '3', '35');

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `institucion` VALUES ('1', '4', 'Institucion 1', NULL, NULL, NULL);
INSERT INTO `institucion` VALUES ('2', '8', 'escuela', NULL, NULL, NULL);

DROP TABLE IF EXISTS `municipio`;
CREATE TABLE `municipio` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_municipio_nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `nivel_impacto` VALUES ('3', 'Comunal');
INSERT INTO `nivel_impacto` VALUES ('4', 'estadal');
INSERT INTO `nivel_impacto` VALUES ('5', 'municipal');

DROP TABLE IF EXISTS `parroquia`;
CREATE TABLE `parroquia` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(30) NOT NULL,
  `id_municipio` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_parroquia_nombre_municipio` (`nombre`,`id_municipio`),
  KEY `id_municipio` (`id_municipio`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `parroquia` VALUES ('17', 'Albarico', '0');
INSERT INTO `parroquia` VALUES ('3', 'Albarico', '2');
INSERT INTO `parroquia` VALUES ('54', 'Arístides Bastidas', '21');
INSERT INTO `parroquia` VALUES ('55', 'Bolivar', '13');
INSERT INTO `parroquia` VALUES ('5', 'Campo Elías', '0');
INSERT INTO `parroquia` VALUES ('56', 'Campo Elias', '5');
INSERT INTO `parroquia` VALUES ('4', 'Chivacoa', '0');
INSERT INTO `parroquia` VALUES ('49', 'Chivacoa', '5');
INSERT INTO `parroquia` VALUES ('6', 'Cocorote', '0');
INSERT INTO `parroquia` VALUES ('46', 'Cocorote', '6');
INSERT INTO `parroquia` VALUES ('21', 'El Guayabo', '0');
INSERT INTO `parroquia` VALUES ('52', 'El Guayabo', '8');
INSERT INTO `parroquia` VALUES ('22', 'Farriar', '0');
INSERT INTO `parroquia` VALUES ('53', 'Farriar', '8');
INSERT INTO `parroquia` VALUES ('7', 'Independencia', '0');
INSERT INTO `parroquia` VALUES ('57', 'Independencia', '4');
INSERT INTO `parroquia` VALUES ('58', 'José Antonio Paez', '15');
INSERT INTO `parroquia` VALUES ('9', 'La Trinidad', '0');
INSERT INTO `parroquia` VALUES ('59', 'La Trinidad', '11');
INSERT INTO `parroquia` VALUES ('10', 'Manuel Monge', '0');
INSERT INTO `parroquia` VALUES ('60', 'Manuel Monge', '10');
INSERT INTO `parroquia` VALUES ('13', 'Nirgua', '0');
INSERT INTO `parroquia` VALUES ('62', 'Nirgua', '9');
INSERT INTO `parroquia` VALUES ('11', 'Salóm', '0');
INSERT INTO `parroquia` VALUES ('61', 'Salom', '9');
INSERT INTO `parroquia` VALUES ('14', 'San Andrés', '0');
INSERT INTO `parroquia` VALUES ('65', 'San Andres', '12');
INSERT INTO `parroquia` VALUES ('51', 'San Andres', '13');
INSERT INTO `parroquia` VALUES ('18', 'San Felipe', '0');
INSERT INTO `parroquia` VALUES ('47', 'San Felipe', '2');
INSERT INTO `parroquia` VALUES ('16', 'San Javier', '0');
INSERT INTO `parroquia` VALUES ('48', 'San Javier', '2');
INSERT INTO `parroquia` VALUES ('19', 'Sucre', '0');
INSERT INTO `parroquia` VALUES ('66', 'Sucre', '3');
INSERT INTO `parroquia` VALUES ('63', 'Temeria', '9');
INSERT INTO `parroquia` VALUES ('12', 'Temerla', '0');
INSERT INTO `parroquia` VALUES ('20', 'Urachiche', '0');
INSERT INTO `parroquia` VALUES ('67', 'Urachiche', '7');
INSERT INTO `parroquia` VALUES ('15', 'Yaritagua', '0');
INSERT INTO `parroquia` VALUES ('64', 'Yaritagua', '12');

DROP TABLE IF EXISTS `solicitud`;
CREATE TABLE `solicitud` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `id_institucion` int(10) NOT NULL,
  `id_biblioteca` int(10) DEFAULT NULL,
  `fecha_solicitud` date NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `hora_solicitud` time NOT NULL,
  `lugar` varchar(100) NOT NULL,
  `responsable` varchar(50) NOT NULL,
  `participantes` int(10) NOT NULL DEFAULT 0,
  `descripcion` varchar(250) NOT NULL,
  `estado` enum('pendiente','aprobada','rechazada','cancelada') NOT NULL DEFAULT 'pendiente',
  PRIMARY KEY (`id`),
  KEY `id_institucion` (`id_institucion`),
  KEY `idx_solicitud_id_biblioteca` (`id_biblioteca`),
  CONSTRAINT `solicitud_ibfk_1` FOREIGN KEY (`id_institucion`) REFERENCES `institucion` (`id`),
  CONSTRAINT `solicitud_ibfk_biblioteca` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `solicitud` VALUES ('2', '1', NULL, '2026-08-06', '2026-10-02 20:33:41', '14:16:00', 'zona', 'cesar contreras', '2', '1', 'pendiente');
INSERT INTO `solicitud` VALUES ('7', '2', '15', '2026-10-09', '2026-10-08 12:42:17', '12:44:00', 'biblioteca', 'freddy espinoza', '1', 'hola', 'pendiente');

DROP TABLE IF EXISTS `tipo_actividad`;
CREATE TABLE `tipo_actividad` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(15) NOT NULL,
  `Descripcion` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `tipo_actividad` VALUES ('4', 'eduativa', '0004');
INSERT INTO `tipo_actividad` VALUES ('5', 'educativa', '');
INSERT INTO `tipo_actividad` VALUES ('8', 'hola', '');

DROP TABLE IF EXISTS `ubicacion`;
CREATE TABLE `ubicacion` (
  `id` int(10) NOT NULL,
  `id_comuna` int(10) NOT NULL,
  `id_parroquia` int(10) NOT NULL,
  `id_municipio` int(10) NOT NULL,
  `nombre_comuna` varchar(20) NOT NULL,
  `nombre_parroquia` varchar(20) NOT NULL,
  `nombre_municipio` varchar(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_comuna` (`id_comuna`),
  KEY `id_parroquia` (`id_parroquia`),
  KEY `id_municipio` (`id_municipio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(15) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `id_empleado` int(11) DEFAULT NULL,
  `rol` enum('administrador','usuario') NOT NULL DEFAULT 'usuario',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_usuario_nombre` (`nombre`),
  UNIQUE KEY `uq_usuario_empleado` (`id_empleado`),
  CONSTRAINT `fk_usuario_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `usuario` VALUES ('11', 'cheddar', '$2y$10$gDEMnG9XXqwiKvUQNP95BOzYUQ/KcRkmZcCqfzHi.WvkowTeqRjwq', '04125240489', '15', 'administrador');
INSERT INTO `usuario` VALUES ('12', 'jesus', '$2y$10$A/oi0oFJTyOERvUGmZTbReSn6CeDT.kUzbpwx5TkBRRShSp14658a', '04125240489', '13', 'usuario');
INSERT INTO `usuario` VALUES ('13', 'cesar', '$2y$10$xzjKL4an985Ht74uyBixF.ECl..GSE.47mJhP3vdR7zG8T/AS4pDS', '04127168235', '28', 'usuario');

SET FOREIGN_KEY_CHECKS = 1;
