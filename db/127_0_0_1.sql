-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-10-2026 a las 00:13:40
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
-- Base de datos: `crud`
--
CREATE DATABASE IF NOT EXISTS `crud` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `crud`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `usuario_id` int(10) NOT NULL,
  `usuario_nombre` varchar(70) NOT NULL,
  `usuario_apellido` varchar(70) NOT NULL,
  `usuario_email` varchar(100) NOT NULL,
  `usuario_usuario` varchar(30) NOT NULL,
  `usuario_clave` varchar(200) NOT NULL,
  `usuario_foto` varchar(535) NOT NULL,
  `usuario_creado` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_actualizado` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usuario_id`, `usuario_nombre`, `usuario_apellido`, `usuario_email`, `usuario_usuario`, `usuario_clave`, `usuario_foto`, `usuario_creado`, `usuario_actualizado`) VALUES
(1, 'Administrador', 'Principal', 'admin@admin.com', 'Administrador', '$2y$10$F0J8k.lFjgGAK6I/tcbhyuMKSaitXy8ENMSBVZWErIoA6.VSU8MQy', '', '2023-07-06 21:48:05', '2023-07-06 21:48:05');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usuario_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Base de datos: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

--
-- Volcado de datos para la tabla `pma__export_templates`
--

INSERT INTO `pma__export_templates` (`id`, `username`, `export_type`, `template_name`, `template_data`) VALUES
(1, 'root', 'database', 'db', '{\"quick_or_custom\":\"quick\",\"what\":\"sql\",\"structure_or_data_forced\":\"0\",\"table_select[]\":[\"actividad\",\"actividad_comuna\",\"actividad_espaciocultural\",\"biblioteca\",\"comuna\",\"empleado\",\"empleado_usuario\",\"espacio_cultural\",\"impacto_actividad\",\"municipio\",\"nivel_impacto\",\"parroquia\",\"rango_actividad\",\"rango_impacto\",\"responsable\",\"tipo_actividad\",\"ubicacion\",\"usuario\"],\"table_structure[]\":[\"actividad\",\"actividad_comuna\",\"actividad_espaciocultural\",\"biblioteca\",\"comuna\",\"empleado\",\"empleado_usuario\",\"espacio_cultural\",\"impacto_actividad\",\"municipio\",\"nivel_impacto\",\"parroquia\",\"rango_actividad\",\"rango_impacto\",\"responsable\",\"tipo_actividad\",\"ubicacion\",\"usuario\"],\"table_data[]\":[\"actividad\",\"actividad_comuna\",\"actividad_espaciocultural\",\"biblioteca\",\"comuna\",\"empleado\",\"empleado_usuario\",\"espacio_cultural\",\"impacto_actividad\",\"municipio\",\"nivel_impacto\",\"parroquia\",\"rango_actividad\",\"rango_impacto\",\"responsable\",\"tipo_actividad\",\"ubicacion\",\"usuario\"],\"aliases_new\":\"\",\"output_format\":\"sendit\",\"filename_template\":\"@DATABASE@\",\"remember_template\":\"on\",\"charset\":\"utf-8\",\"compression\":\"none\",\"maxsize\":\"\",\"codegen_structure_or_data\":\"data\",\"codegen_format\":\"0\",\"csv_separator\":\",\",\"csv_enclosed\":\"\\\"\",\"csv_escaped\":\"\\\"\",\"csv_terminated\":\"AUTO\",\"csv_null\":\"NULL\",\"csv_columns\":\"something\",\"csv_structure_or_data\":\"data\",\"excel_null\":\"NULL\",\"excel_columns\":\"something\",\"excel_edition\":\"win\",\"excel_structure_or_data\":\"data\",\"json_structure_or_data\":\"data\",\"json_unicode\":\"something\",\"latex_caption\":\"something\",\"latex_structure_or_data\":\"structure_and_data\",\"latex_structure_caption\":\"Estructura de la tabla @TABLE@\",\"latex_structure_continued_caption\":\"Estructura de la tabla @TABLE@ (continúa)\",\"latex_structure_label\":\"tab:@TABLE@-structure\",\"latex_relation\":\"something\",\"latex_comments\":\"something\",\"latex_mime\":\"something\",\"latex_columns\":\"something\",\"latex_data_caption\":\"Contenido de la tabla @TABLE@\",\"latex_data_continued_caption\":\"Contenido de la tabla @TABLE@ (continúa)\",\"latex_data_label\":\"tab:@TABLE@-data\",\"latex_null\":\"\\\\textit{NULL}\",\"mediawiki_structure_or_data\":\"structure_and_data\",\"mediawiki_caption\":\"something\",\"mediawiki_headers\":\"something\",\"htmlword_structure_or_data\":\"structure_and_data\",\"htmlword_null\":\"NULL\",\"ods_null\":\"NULL\",\"ods_structure_or_data\":\"data\",\"odt_structure_or_data\":\"structure_and_data\",\"odt_relation\":\"something\",\"odt_comments\":\"something\",\"odt_mime\":\"something\",\"odt_columns\":\"something\",\"odt_null\":\"NULL\",\"pdf_report_title\":\"\",\"pdf_structure_or_data\":\"structure_and_data\",\"phparray_structure_or_data\":\"data\",\"sql_include_comments\":\"something\",\"sql_header_comment\":\"\",\"sql_use_transaction\":\"something\",\"sql_compatibility\":\"NONE\",\"sql_structure_or_data\":\"structure_and_data\",\"sql_create_table\":\"something\",\"sql_auto_increment\":\"something\",\"sql_create_view\":\"something\",\"sql_procedure_function\":\"something\",\"sql_create_trigger\":\"something\",\"sql_backquotes\":\"something\",\"sql_type\":\"INSERT\",\"sql_insert_syntax\":\"both\",\"sql_max_query_size\":\"50000\",\"sql_hex_for_binary\":\"something\",\"sql_utc_time\":\"something\",\"texytext_structure_or_data\":\"structure_and_data\",\"texytext_null\":\"NULL\",\"xml_structure_or_data\":\"data\",\"xml_export_events\":\"something\",\"xml_export_functions\":\"something\",\"xml_export_procedures\":\"something\",\"xml_export_tables\":\"something\",\"xml_export_triggers\":\"something\",\"xml_export_views\":\"something\",\"xml_export_contents\":\"something\",\"yaml_structure_or_data\":\"data\",\"\":null,\"lock_tables\":null,\"as_separate_files\":null,\"csv_removeCRLF\":null,\"excel_removeCRLF\":null,\"json_pretty_print\":null,\"htmlword_columns\":null,\"ods_columns\":null,\"sql_dates\":null,\"sql_relation\":null,\"sql_mime\":null,\"sql_disable_fk\":null,\"sql_views_as_tables\":null,\"sql_metadata\":null,\"sql_create_database\":null,\"sql_drop_table\":null,\"sql_if_not_exists\":null,\"sql_simple_view_export\":null,\"sql_view_current_user\":null,\"sql_or_replace_view\":null,\"sql_truncate\":null,\"sql_delayed\":null,\"sql_ignore\":null,\"texytext_columns\":null}'),
(2, 'root', 'database', 'm', '{\"quick_or_custom\":\"quick\",\"what\":\"sql\",\"structure_or_data_forced\":\"0\",\"table_select[]\":[\"actividad\",\"actividad_comuna\",\"actividad_espaciocultural\",\"biblioteca\",\"cargo\",\"comuna\",\"empleado\",\"espacio_cultural\",\"impacto_actividad\",\"municipio\",\"nivel_impacto\",\"parroquia\",\"rango_act\",\"responsable\",\"tipo_actividad\",\"ubicacion\",\"usuario\"],\"table_structure[]\":[\"actividad\",\"actividad_comuna\",\"actividad_espaciocultural\",\"biblioteca\",\"cargo\",\"comuna\",\"empleado\",\"espacio_cultural\",\"impacto_actividad\",\"municipio\",\"nivel_impacto\",\"parroquia\",\"rango_act\",\"responsable\",\"tipo_actividad\",\"ubicacion\",\"usuario\"],\"table_data[]\":[\"actividad\",\"actividad_comuna\",\"actividad_espaciocultural\",\"biblioteca\",\"cargo\",\"comuna\",\"empleado\",\"espacio_cultural\",\"impacto_actividad\",\"municipio\",\"nivel_impacto\",\"parroquia\",\"rango_act\",\"responsable\",\"tipo_actividad\",\"ubicacion\",\"usuario\"],\"aliases_new\":\"\",\"output_format\":\"sendit\",\"filename_template\":\"@DATABASE@\",\"remember_template\":\"on\",\"charset\":\"utf-8\",\"compression\":\"none\",\"maxsize\":\"\",\"codegen_structure_or_data\":\"data\",\"codegen_format\":\"0\",\"csv_separator\":\",\",\"csv_enclosed\":\"\\\"\",\"csv_escaped\":\"\\\"\",\"csv_terminated\":\"AUTO\",\"csv_null\":\"NULL\",\"csv_columns\":\"something\",\"csv_structure_or_data\":\"data\",\"excel_null\":\"NULL\",\"excel_columns\":\"something\",\"excel_edition\":\"win\",\"excel_structure_or_data\":\"data\",\"json_structure_or_data\":\"data\",\"json_unicode\":\"something\",\"latex_caption\":\"something\",\"latex_structure_or_data\":\"structure_and_data\",\"latex_structure_caption\":\"Estructura de la tabla @TABLE@\",\"latex_structure_continued_caption\":\"Estructura de la tabla @TABLE@ (continúa)\",\"latex_structure_label\":\"tab:@TABLE@-structure\",\"latex_relation\":\"something\",\"latex_comments\":\"something\",\"latex_mime\":\"something\",\"latex_columns\":\"something\",\"latex_data_caption\":\"Contenido de la tabla @TABLE@\",\"latex_data_continued_caption\":\"Contenido de la tabla @TABLE@ (continúa)\",\"latex_data_label\":\"tab:@TABLE@-data\",\"latex_null\":\"\\\\textit{NULL}\",\"mediawiki_structure_or_data\":\"structure_and_data\",\"mediawiki_caption\":\"something\",\"mediawiki_headers\":\"something\",\"htmlword_structure_or_data\":\"structure_and_data\",\"htmlword_null\":\"NULL\",\"ods_null\":\"NULL\",\"ods_structure_or_data\":\"data\",\"odt_structure_or_data\":\"structure_and_data\",\"odt_relation\":\"something\",\"odt_comments\":\"something\",\"odt_mime\":\"something\",\"odt_columns\":\"something\",\"odt_null\":\"NULL\",\"pdf_report_title\":\"\",\"pdf_structure_or_data\":\"structure_and_data\",\"phparray_structure_or_data\":\"data\",\"sql_include_comments\":\"something\",\"sql_header_comment\":\"\",\"sql_use_transaction\":\"something\",\"sql_compatibility\":\"NONE\",\"sql_structure_or_data\":\"structure_and_data\",\"sql_create_table\":\"something\",\"sql_auto_increment\":\"something\",\"sql_create_view\":\"something\",\"sql_procedure_function\":\"something\",\"sql_create_trigger\":\"something\",\"sql_backquotes\":\"something\",\"sql_type\":\"INSERT\",\"sql_insert_syntax\":\"both\",\"sql_max_query_size\":\"50000\",\"sql_hex_for_binary\":\"something\",\"sql_utc_time\":\"something\",\"texytext_structure_or_data\":\"structure_and_data\",\"texytext_null\":\"NULL\",\"xml_structure_or_data\":\"data\",\"xml_export_events\":\"something\",\"xml_export_functions\":\"something\",\"xml_export_procedures\":\"something\",\"xml_export_tables\":\"something\",\"xml_export_triggers\":\"something\",\"xml_export_views\":\"something\",\"xml_export_contents\":\"something\",\"yaml_structure_or_data\":\"data\",\"\":null,\"lock_tables\":null,\"as_separate_files\":null,\"csv_removeCRLF\":null,\"excel_removeCRLF\":null,\"json_pretty_print\":null,\"htmlword_columns\":null,\"ods_columns\":null,\"sql_dates\":null,\"sql_relation\":null,\"sql_mime\":null,\"sql_disable_fk\":null,\"sql_views_as_tables\":null,\"sql_metadata\":null,\"sql_create_database\":null,\"sql_drop_table\":null,\"sql_if_not_exists\":null,\"sql_simple_view_export\":null,\"sql_view_current_user\":null,\"sql_or_replace_view\":null,\"sql_truncate\":null,\"sql_delayed\":null,\"sql_ignore\":null,\"texytext_columns\":null}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Volcado de datos para la tabla `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"red_bibliotecas\",\"table\":\"parroquia\"},{\"db\":\"red_bibliotecas\",\"table\":\"actividad\"},{\"db\":\"red_bibliotecas\",\"table\":\"comuna\"},{\"db\":\"red_bibliotecas\",\"table\":\"biblioteca\"},{\"db\":\"red_bibliotecas\",\"table\":\"municipio\"},{\"db\":\"red_bibliotecas\",\"table\":\"actividad_espaciocultural\"},{\"db\":\"red_bibliotecas\",\"table\":\"actividad_comuna\"},{\"db\":\"red_bibliotecas\",\"table\":\"cargo\"},{\"db\":\"red_bibliotecas\",\"table\":\"empleado\"},{\"db\":\"red_bibliotecas\",\"table\":\"nivel_impacto\"}]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Volcado de datos para la tabla `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2026-07-13 18:55:42', '{\"Console\\/Mode\":\"collapse\",\"lang\":\"es\"}');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indices de la tabla `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indices de la tabla `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indices de la tabla `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indices de la tabla `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indices de la tabla `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indices de la tabla `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indices de la tabla `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indices de la tabla `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indices de la tabla `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indices de la tabla `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indices de la tabla `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indices de la tabla `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indices de la tabla `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indices de la tabla `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indices de la tabla `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indices de la tabla `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indices de la tabla `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Base de datos: `red_bibliotecas`
--
CREATE DATABASE IF NOT EXISTS `red_bibliotecas` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `red_bibliotecas`;

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
  `descripcion` text NOT NULL,
  `objetivo` varchar(50) NOT NULL,
  `participantes` int(5) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time DEFAULT NULL,
  `dia_semana` text NOT NULL,
  `estado` enum('pendiente','confirmada','ejecutada','cancelada') NOT NULL DEFAULT 'pendiente',
  `responsable` varchar(100) DEFAULT NULL,
  `telefono_responsable` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `actividad`
--

INSERT INTO `actividad` (`id`, `id_solicitud`, `id_biblioteca`, `id_espacio_cultural`, `nombre`, `id_tipo_actividad`, `descripcion`, `objetivo`, `participantes`, `fecha`, `hora`, `dia_semana`, `estado`, `responsable`, `telefono_responsable`) VALUES
(23, NULL, 15, 5, 'Biblioteca 1', 4, 'Solicitud 1', 'Responsable 1', 20, '2026-08-09', NULL, 'Domingo', 'confirmada', 'jesus serrano', '2147483647'),
(24, NULL, 15, 5, 'zona2', 4, '2', 'carlos', 222, '2026-08-12', NULL, 'Miércoles', 'confirmada', 'jesus serrano', '2147483647'),
(27, NULL, 15, NULL, 'Arístides Bastidas', 5, 'hola', 'hola', 10, '2026-08-26', '12:53:00', 'Miércoles', 'confirmada', 'jesus soto', '04125240489'),
(28, NULL, 15, NULL, 'Arístides Bastidas', 5, 'holi', 'formativa', 21, '2026-08-05', '18:58:00', 'Miércoles', 'confirmada', 'jesus soto', '2147483647'),
(29, NULL, NULL, 5, 'hola', 5, 'cosas', 'No definido', 0, '2026-08-19', '13:16:00', 'Miércoles', 'confirmada', 'jesus serrano', '04125240489'),
(30, NULL, 15, NULL, 'juego de ajedrez', 5, 'hola', 'Formativa', 20, '2026-08-05', '13:38:00', 'Miércoles', 'confirmada', 'jesus serrano', '04125240489'),
(31, NULL, 15, NULL, 'Arístides Bastidas', 5, 'cosas', 'No definido', 0, '2026-08-20', '17:41:00', 'Jueves', 'confirmada', 'jesus serrano', '04125240489'),
(32, NULL, 15, NULL, 'Juego de Ajedrez', 5, 'Actividad de jugar ajedrez realizada en Biblioteca', 'Formativa', 2, '2026-08-27', '13:04:00', 'Jueves', 'confirmada', 'jesus serrano', '04125240489'),
(33, NULL, 15, NULL, 'Juego de Ajedrez', 5, 'hola', 'No definido', 0, '2026-08-27', '16:33:00', 'Jueves', 'confirmada', 'jesus serrano', '04125240489'),
(34, NULL, 15, NULL, 'Juego de Ajedrez', 5, 'hola', 'No definido', 0, '2026-07-17', '16:39:00', 'Viernes', 'confirmada', 'jesus serrano', '04125240489'),
(35, NULL, 15, NULL, 'juego de mesa', 4, 'cosas', 'formal', 21, '2026-09-05', '19:55:00', 'Sábado', 'cancelada', 'jesus soto', '04125240489'),
(36, NULL, 15, NULL, 'biblioteca', 5, 'a', 'jesus serrano', 1, '2026-08-13', '21:24:00', 'Jueves', 'confirmada', 'jesus serrano', '04125240489'),
(37, NULL, 15, NULL, 'juego de mesa', 5, 'cosas', 'No definido', 0, '2026-10-03', '16:55:00', 'Sábado', 'pendiente', 'cesar ontreras', '04127168235'),
(38, NULL, 16, NULL, 'Felix Pifano', 5, 'felix', 'No definido', 0, '2026-10-04', '18:12:00', 'Domingo', 'pendiente', 'cesar ontreras', '04127168235');

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
(17, 8, 24),
(20, 8, 28),
(28, 8, 27),
(29, 8, 29),
(30, 8, 30),
(31, 8, 31),
(33, 8, 32),
(34, 8, 33),
(35, 8, 34),
(43, 8, 36),
(44, 8, 35),
(45, 8, 37);

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
(15, 'biblioteca', 54, 'cesarmcontre28@gmail.com', 'cesarm2', 'calle 4', NULL),
(16, 'Felix Pifano', 47, '', '', '', NULL);

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
(87, 'Lunes', '2026-10-05', '16:02:33', 11, 'Login', 'Usuario', 'Inicio de sesión: cheddar');

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
  `genero` varchar(10) NOT NULL,
  `edad` int(2) UNSIGNED NOT NULL,
  `anios_de_servicio` int(2) UNSIGNED NOT NULL,
  `id_cargo` int(10) NOT NULL,
  `id_biblioteca` int(10) NOT NULL,
  `fecha_inicio_cargo` date DEFAULT NULL,
  `fecha_fin_cargo` date DEFAULT NULL
) ;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`id`, `nombre`, `apellido`, `cedula`, `telefono`, `genero`, `edad`, `anios_de_servicio`, `id_cargo`, `id_biblioteca`, `fecha_inicio_cargo`, `fecha_fin_cargo`) VALUES
(13, 'jesus', 'serrano', 31982637, '04125240489', 'M', 1, 0, 1, 15, NULL, NULL),
(15, 'jesus', 'soto', 32435607, '04125240489', '', 0, 0, 1, 15, NULL, NULL),
(27, 'freddy', 'espinoza', 32626427, '04145058143', 'M', 20, 1, 1, 15, '2026-10-03', '2026-10-03'),
(28, 'cesar', 'ontreras', 31982333, '04127168235', 'M', 20, 18, 1, 16, '2026-10-04', NULL);

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
(1, 3, 9),
(3, 3, 28),
(11, 3, 27),
(12, 3, 30),
(13, 3, 32),
(21, 3, 35);

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
(5, 'municipal');

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
  `fecha_solicitud` date NOT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp(),
  `hora_solicitud` time NOT NULL,
  `lugar` varchar(100) NOT NULL,
  `responsable` varchar(50) NOT NULL,
  `participantes` int(10) NOT NULL DEFAULT 0,
  `descripcion` varchar(250) NOT NULL,
  `estado` enum('pendiente','aprobada','rechazada','cancelada') NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `solicitud`
--

INSERT INTO `solicitud` (`id`, `id_institucion`, `id_biblioteca`, `fecha_solicitud`, `fecha_registro`, `hora_solicitud`, `lugar`, `responsable`, `participantes`, `descripcion`, `estado`) VALUES
(2, 1, NULL, '2026-08-06', '2026-10-02 20:33:41', '14:16:00', 'zona', 'cesar contreras', 2, '1', 'pendiente');

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
  ADD KEY `idx_solicitud_id_biblioteca` (`id_biblioteca`);

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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT de la tabla `actividad_comuna`
--
ALTER TABLE `actividad_comuna`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `actividad_espaciocultural`
--
ALTER TABLE `actividad_espaciocultural`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `biblioteca`
--
ALTER TABLE `biblioteca`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `bitacora`
--
ALTER TABLE `bitacora`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `comuna`
--
ALTER TABLE `comuna`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `espacio_cultural`
--
ALTER TABLE `espacio_cultural`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `impacto_actividad`
--
ALTER TABLE `impacto_actividad`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

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
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `parroquia`
--
ALTER TABLE `parroquia`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `solicitud`
--
ALTER TABLE `solicitud`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
  ADD CONSTRAINT `actividad_ibfk_solicitud` FOREIGN KEY (`id_solicitud`) REFERENCES `solicitud` (`id`) ON DELETE SET NULL;

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
  ADD CONSTRAINT `solicitud_ibfk_biblioteca` FOREIGN KEY (`id_biblioteca`) REFERENCES `biblioteca` (`id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_empleado` FOREIGN KEY (`id_empleado`) REFERENCES `empleado` (`id`) ON DELETE SET NULL;
--
-- Base de datos: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
