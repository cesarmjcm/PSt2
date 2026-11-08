INSERT INTO asignacion (id_empleado, id_cargo, id_biblioteca, fecha_desde, fecha_hasta)
SELECT e.id, e.id_cargo, e.id_biblioteca, e.fecha_inicio_cargo, e.fecha_fin_cargo
FROM empleado e
WHERE NOT EXISTS (
    SELECT 1
    FROM asignacion a
    WHERE a.id_empleado = e.id
      AND a.id_cargo = e.id_cargo
      AND a.id_biblioteca = e.id_biblioteca
      AND a.fecha_desde <=> e.fecha_inicio_cargo
      AND a.fecha_hasta <=> e.fecha_fin_cargo
);
