<section>

    <div id="modalEditarActividad" class="modal" style="display:none;">
        <div class="modal-content-wrapper">
            <section class="formulario-planificacion">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
                    <h2 class="section-title" style="margin: 0;">Editar Actividad</h2>
                    <span class="close-button-editar" style="cursor:pointer; font-size: 1.5rem;">&times;</span>
                </div>
                <div class="container__planificacion">
                    <form id="form-editar-actividad" action="../controladores/actividad_contr.php" method="post">
                        <input type="hidden" name="action" value="actualizar">
                        <input type="hidden" name="id" id="editar-id" value="">

                        <!-- Campos ocultos: conservan relaciones que NO se editan en este modal
                             (nivel de impacto, municipio, parroquia, comuna, espacio cultural,
                             responsable y teléfono). Si no se envían, actualizarActividadCompleta()
                             las borraría al reemplazar las relaciones de la actividad. -->
                        <input type="hidden" name="nivel_impacto" id="editar-nivel-impacto" value="">
                        <input type="hidden" name="municipio_id" id="editar-municipio-id" value="">
                        <input type="hidden" name="parroquia" id="editar-parroquia" value="">
                        <input type="hidden" name="comuna" id="editar-comuna" value="">
                        <input type="hidden" name="espacio_cultural" id="editar-espacio" value="">
                        <input type="hidden" name="id_biblioteca" id="editar-biblioteca" value="">
                        <input type="hidden" name="responsable" id="editar-responsable" value="">
                        <input type="hidden" name="telefono_responsable" id="editar-telefono" value="">
                        <input type="hidden" name="return_url" value="<?php echo htmlspecialchars($_SERVER['REQUEST_URI'] ?? '../src/index.php', ENT_QUOTES, 'UTF-8'); ?>">

                        <div class="planificacion-grid">
                            <fieldset class="planificacion-group">
                                <fieldset>
                                    <label for="editar-nombre">Nombre de la actividad</label>
                                    <input type="text" id="editar-nombre" maxlength="25" name="nombre" placeholder="Nombre de la actividad">

                                    <label for="editar-tipo">Tipo de actividad</label>
                                    <input type="text" id="editar-tipo" maxlength="25" name="tipo" placeholder="Ej. Conversatorio">

                                    <label for="editar-descripcion">Descripción</label>
                                    <textarea id="editar-descripcion" maxlength="100" name="descripcion" placeholder="Descripción breve"></textarea>

                                    
                                </fieldset>
                                <fieldset>
                                    

                                    <label for="editar-fecha">Fecha</label>
                                    <input type="date" id="editar-fecha" name="fecha">
                                    <input type="hidden" id="editar-dia" name="dia_semana" value="">
                                </fieldset>
                            </fieldset>
                        </div>

                        <button type="submit" class="btn-ingresar-planificacion">Guardar cambios</button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</section>
