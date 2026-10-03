ALTER TABLE solicitud
    ADD COLUMN id_biblioteca INT(10) NULL AFTER id_institucion,
    ADD KEY idx_solicitud_id_biblioteca (id_biblioteca);

UPDATE solicitud s
JOIN (
    SELECT LOWER(TRIM(nombre)) AS nombre_normalizado, MIN(id) AS id
    FROM biblioteca
    GROUP BY LOWER(TRIM(nombre))
    HAVING COUNT(*) = 1
) b ON LOWER(TRIM(s.lugar)) = b.nombre_normalizado
SET s.id_biblioteca = b.id;

ALTER TABLE solicitud
    ADD CONSTRAINT solicitud_ibfk_biblioteca
        FOREIGN KEY (id_biblioteca) REFERENCES biblioteca (id);
