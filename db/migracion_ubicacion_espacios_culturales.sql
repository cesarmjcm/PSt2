USE `red_bibliotecas`;

ALTER TABLE `espacio_cultural`
  ADD COLUMN `id_parroquia` int(10) DEFAULT NULL AFTER `Metodo_contactar`,
  ADD COLUMN `id_comuna` int(10) DEFAULT NULL AFTER `id_parroquia`,
  ADD KEY `idx_espacio_cultural_parroquia` (`id_parroquia`),
  ADD KEY `idx_espacio_cultural_comuna` (`id_comuna`),
  ADD CONSTRAINT `espacio_cultural_ibfk_parroquia`
    FOREIGN KEY (`id_parroquia`) REFERENCES `parroquia` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `espacio_cultural_ibfk_comuna`
    FOREIGN KEY (`id_comuna`) REFERENCES `comuna` (`id`) ON DELETE SET NULL;
