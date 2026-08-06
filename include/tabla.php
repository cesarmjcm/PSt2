<div class="container_padre">
	<div class="tabla__container">
		<h1 class="planificacion__title">Planificación de Actividades Culturales</h1>
		<table class="tabla-planificacion">
			<thead>
				<tr>
					<th colspan="15" class="editar-header">
						<button class="btn-editar-tabla">
							<i class="fas fa-edit"></i> Editar Planificación
						</button>
					</th>
				</tr>
				<tr>
					<th>N°</th>
					<th>Tipo de actividad</th>
					<th>Día de la actividad</th>
					<th>Fecha de la actividad</th>
					<th>Descripción de la actividad</th>
					<th>Objetivo / enfoque</th>
					<th>Nivel de impacto</th>
					<th>Cant. participantes</th>
					<th>Municipio</th>
					<th>Parroquia</th>
					<th>Espacio cultural</th>
					<th>Comuna</th>
					<th>Responsable</th>
					<th>Teléfono responsable</th>
				</tr>
			</thead>
			<tbody>
    <?php
    try {
        require_once __DIR__ . '/../modelos/actividad.php';
        $actividadModel = new Actividad();
        // mostrarActividadesCompletas() trae los nombres reales vía JOIN
        // (municipio, parroquia, comuna, espacio cultural, nivel de impacto,
        // responsable). Si una actividad no tiene relación registrada en la
        // tabla puente correspondiente, ese campo llega NULL y se muestra
        // como 'N/A' — no significa que falte algo en el código, sino que
        // todavía no se ha asociado ese dato a la actividad.
        $actividades = $actividadModel->mostrarActividadesCompletas();
        $errorMessage = '';
    } catch (Exception $e) {
        $actividades = [];
        $errorMessage = $e->getMessage();
    }

    if (!empty($errorMessage)) {
        echo '<tr><td colspan="14">Aparecerá vacío porque aún no hay base de datos configurada. Detalle: ' . htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') . '</td></tr>';
    } elseif (empty($actividades)) {
        echo '<tr><td colspan="14">No hay actividades registradas.</td></tr>';
    } else {
        $idx = 1;
        foreach ($actividades as $a) {
            $fecha = $a['fecha'] ?? '';
            try {
                $fechaFmt = $fecha ? (new DateTime($fecha))->format('d/m/Y') : '';
            } catch (Exception $ex) {
                $fechaFmt = $fecha;
            }

            echo '<tr>';
            echo '<td>' . $idx++ . '</td>';
            echo '<td>' . htmlspecialchars($a['nombre'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($a['dia_semana'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($fechaFmt, ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($a['descripcion'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($a['objetivo'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($a['nivel_impacto'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($a['participantes'] ?? '', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($a['municipio'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($a['parroquia'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($a['espacio_cultural'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($a['comuna'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($a['responsable'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '<td>' . htmlspecialchars($a['telefono_responsable'] ?? 'N/A', ENT_QUOTES, 'UTF-8') . '</td>';
            echo '</tr>';
        }
    }
    ?>
</tbody>
		</table>
	</div>
</div>
