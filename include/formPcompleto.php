<section class="planificacion">
	<div class="container__planificacion">
		<div class="formulario-planificacion">
			<!-- Botón que abre el modal de nueva actividad -->
			<div style="display:flex; justify-content:flex-end; margin-bottom:12px;">
				<button class="btn-primary"><i class="fas fa-plus"></i> Nueva Actividad</button>
			</div>
			<form id="form-planificacion" action="main.html" method="post" onsubmit="return validacionesformulario(this)">
			<button type="submit" class="btn-ingresar-planificacion">Ingresar planificación</button>
			
				<div class="planificacion-grid">
					<fieldset class="planificacion-group">
						
						<fieldset>
							<legend>Actividad</legend>
							<label for="plan-tipo">Tipo de actividad</label>
							<input type="text" id="plan-tipo" name="tipoActividad" placeholder="Ej. Conversatorio">
							<label for="plan-descripcion">Descripción de la actividad</label>
							<textarea id="plan-descripcion" name="descripcionActividad" placeholder="Descripción breve"></textarea>
							<label for="plan-impacto">Nivel de impacto</label>
							<select name="nivel__impacto" id="nivel-impacto">
								<option value="">Nivel de Impacto</option>
								<option value="Local">Local</option>
								<option value="Comunal">Comunal</option>
								<option value="Regional">Regional</option>
							</select>
							<label for="plan-participantes">Cant. participantes</label>
							<input type="number" id="plan-participantes" name="cantidadParticipantes" placeholder="Ej. 40">
									<label for="plan-objetivo">Objetivo / enfoque</label>
									<input type="text" id="plan-objetivo" name="objetivoEnfoque" placeholder="Ej. Formativa">
						</fieldset>
						<fieldset>
							<legend>Horario</legend>
						<label for="plan-dia">Día de la actividad</label>
						<select name="diaActividad" id="plan-dia">
						  <option value="">Seleccione un día</option>
						  <option value="Lunes">Lunes</option>
						  <option value="Martes">Martes</option>
						  <option value="Miércoles">Miércoles</option>
						  <option value="Jueves">Jueves</option>
						  <option value="Viernes">Viernes</option>
						</select>

						<label for="plan-fecha">Fecha de la actividad</label>
						<input type="date" id="plan-fecha" name="fechaActividad" placeholder="Ej. 20/04/2026">

						<label for="plan-hora">Hora de la actividad</label>
						<input type="time" id="plan-hora" name="horaActividad" placeholder="Ej. 09:00">
					</fieldset>



					</fieldset>
				<fieldset class="planificacion-group">
					
						<fieldset>
							<legend>Municipio</legend>
						
						<select name="Municipio" id="planificacion-municipios">
							<option value="">Seleccione un municipio</option>
							<option value="San Felipe">San Felipe</option>
							<option value="Sucre">Sucre</option>
							<option value="Independencia">Independencia</option>
							<option value="Bruzual">Bruzual</option>
							<option value="Cocorote">Cocorote</option>
							<option value="Urachiche">Urachiche</option>
							<option value="Veroes">Veroes</option>
							<option value="Nirgua">Nirgua</option>
							<option value="Manuel Monge">Manuel Monge</option>
							<option value="La Trinidad">La Trinidad</option>
							<option value="Peña">Peña</option>
							<option value="Bolívar">Bolívar</option>
							<option value="Arístides Bastidas">Arístides Bastidas</option>
							<option value="José Antonio Páez">José Antonio Páez</option>
						</select>
						<fieldset>
							<legend>Parroquia</legend>
					
						<input list="planificacion-parroquias" id="plan-parroquia" name="parroquia" placeholder="Seleccione una parroquia">
						<datalist size="5" name="parroquia" id="planificacion-parroquias">
							<option value="">Seleccione una parroquia</option>
							<option value="Arístides Bastidas">Arístides Bastidas</option>
							<option value="Bolívar">Bolívar</option>
							<option value="Chivacoa">Chivacoa</option>
							<option value="Campo Elías">Campo Elías</option>
							<option value="Cocorote">Cocorote</option>
							<option value="Independencia">Independencia</option>
							<option value="José Antonio Páez">José Antonio Páez</option>
							<option value="La Trinidad">La Trinidad</option>
							<option value="Manuel Monge">Manuel Monge</option>
							<option value="Salóm">Salóm</option>
							<option value="Temerla">Temerla</option>
							<option value="Nirgua">Nirgua</option>
							<option value="San Andrés">San Andrés</option>
							<option value="Yaritagua">Yaritagua</option>
							<option value="San Javier">San Javier</option>
							<option value="Albarico">Albarico</option>
							<option value="San Felipe">San Felipe</option>
							<option value="Sucre">Sucre</option>
							<option value="Urachiche">Urachiche</option>
							<option value="El Guayabo">El Guayabo</option>
							<option value="Farriar">Farriar</option>
						</datalist>
						</fieldset>
						<fieldset>
							<legend>Comuna</legend>
						
							<select name="comuna" id="planificacion-comunas">
								<option value="">Seleccione una comuna</option>
							</select>
						</fieldset>
						<fieldset>
							<legend>Espacio cultural</legend>
						
						<input type="text" id="plan-espacio" name="espacioCultural" placeholder="Ej. Biblioteca Pública">

						</fieldset>

						<fieldset>
							<legend>Responsable</legend>
						<label for="plan-responsable">Nombre</label>
						<input type="text" id="plan-responsable" name="responsable" placeholder="Ej. Carlos Salas">

						<label for="plan-telefono">Teléfono responsable</label>
						<input type="number" id="plan-telefono" name="telefonoResponsable" placeholder="Ej. 0412-3456789">
					</fieldset>
					</fieldset>
				</div>
			</form>
			</div>
		</div>

	<!-- Modal de creación/edición de planificación -->
	<div id="modalPlanificacion" class="modal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:2000; align-items:center; justify-content:center;">
		<div class="modal-content-wrapper" style="background:#fff; border-radius:12px; max-width:920px; width:100%; margin:16px; padding:18px;">
			<section class="formulario-planificacion">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
					<h2 class="section-title" style="margin: 0;">Nueva Planificación de Actividad</h2>
					<span class="close-button" style="cursor:pointer; font-size: 1.5rem;">&times;</span>
				</div>
				<!-- Aquí podemos reutilizar campos del formulario si se desea -->
				<p style="margin:0 0 12px;">Rellena los datos y presiona "Ingresar planificación".</p>
				<div style="text-align:right;">
					<button class="btn-ingresar-planificacion">Ingresar planificación</button>
				</div>
			</section>
		</div>
	</div>

	</section>
