<section>
	
	
	<div id="modalPlanificacion" class="modal" style="display:none;">
		<div class="modal-content-wrapper">
			<section class="formulario-planificacion">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
					<h2 class="section-title" style="margin: 0;">Nueva Planificación de Actividad</h2>
					<span class="close-button" style="cursor:pointer; font-size: 1.5rem;">&times;</span>
				</div>
				<div class="container__planificacion">
					
						<form id="form-planificacion" action="main.html" method="post" onsubmit="return validacionesformulario(this)">
						<button type="submit" class="btn-ingresar-planificacion">Ingresar planificación</button>
						
							<div class="planificacion-grid">
								<fieldset class="planificacion-group">
									
									<fieldset>
										
										<label for="plan-tipo">Tipo de actividad</label>
										<input type="text" id="plan-tipo" name="tipoActividad" placeholder="Ej. Conversatorio">
										<div id="fields-hidden" class="hidden">
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
										
									<label for="plan-municipio">Municipio</label>
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
									<div id="municipio-hidden" class="hidden">
									<fieldset>
										
									
									<label for="plan-parroquia">Parroquia</label>
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
									
										<label for="plan-comuna">Comuna</label>
										<select name="comuna" id="planificacion-comunas">
											<option value="">Seleccione una comuna</option>
										</select>
									</fieldset>
									<fieldset>
									
									<label for="plan-espacio">Espacio cultural</label>
									<input type="text" id="plan-espacio" name="espacioCultural" placeholder="Ej. Biblioteca Pública">
			
									</fieldset>
									</div>
									<fieldset>
										<legend>Responsable</legend>
									<label for="plan-responsable">Nombre</label>
									<input type="text" id="plan-responsable" name="responsable" placeholder="Ej. Carlos Salas">
			
									<label for="plan-telefono">Teléfono responsable</label>
									<input type="number" id="plan-telefono" name="telefonoResponsable" placeholder="Ej. 0412-3456789">
								</fieldset>
								</fieldset>
							</div>
						</div>
						</form>
						</div>
					</div>
				
						
					
				</form>
			</div>
		</div>
	</section>
