<!-- Indicadores de Progreso -->
<div id="donde-estoy"> 
    <button type="button" class="progress-btn active" data-step="1" style="background-color: #f87c4b;">1</button>
    <button type="button" class="progress-btn" data-step="2" style="background-color: #ABAEB0;">2</button>
    <button type="button" class="progress-btn" data-step="3" style="background-color: #ABAEB0;">3</button>
</div>

<!-- Título Principal -->
<div> 
    <h2>Agregar un Tour</h2> 
</div>




<form id="tourForm" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST" enctype="multipart/form-data">
    <?php wp_nonce_field('procesar_tour_nonce_action', 'procesar_tour_nonce'); ?>
    <input type="hidden" name="action" value="procesar_tour">
  

    <div class="step" id="step-1">
        <h3>Paso 1: Información del Tour</h3>
        <div id="flex-container">
           <div class="flex">    
            <div class="flex-item">
                <label for="idioma">Idioma:</label>

                <select id="idioma" name="idioma">
                    <option value="">Seleccione</option>
                    <option value="Español">Español</option>
                    <option value="Inglés">Inglés</option> 
                    <option value="Francés">Francés</option>
                </select>
            </div>
        
            <div class="flex-item">
                <label for="duracion">Duración:</label>
                <input type="text" id="duracion" name="duracion_tour" placeholder="Duración (ej: 1h 10min)" >
            </div>

            <div class="flex-item">
                <label for="city">Ciudad:</label>
                <input type="text" id="city" name="ciudad" placeholder="ej: Córdoba" >
            </div>
        </div>
    </div> 
        <div class="flex-item">
            <label for="nombre_tour">Nombre del Tour:</label>
            <input type="text" id="nombre_tour" name="nombre_tour" >
        </div>
    
        <div id="cbcontainer"> 
            <fieldset id="categoria">
                <legend>Categoría:</legend>
                <div>
                    <input type="checkbox" id="ciudad" name="categoria[]" value="ciudad">
                    <label for="ciudad">Ciudad</label>
                </div>
                <div>
                    <input type="checkbox" id="naturaleza" name="categoria[]" value="naturaleza">
                    <label for="naturaleza">Naturaleza</label>
                </div>
                <div>
                    <input type="checkbox" id="cultura" name="categoria[]" value="cultura">
                    <label for="cultura">Cultura</label>
                </div>
                <div>
                    <input type="checkbox" id="festividad" name="categoria[]" value="festividad">
                    <label for="festividad">Festividad</label>
                </div>
                <div>
                    <input type="checkbox" id="ciencia" name="categoria[]" value="ciencia">
                    <label for="ciencia">Ciencia y Tecnología</label>
                </div>
                <div>
                    <input type="checkbox" id="otro" name="categoria[]" value="otro">
                    <label for="otro">Otro</label>
                </div>
            </fieldset>
        
            <fieldset id="ambiente">
                <legend>Ambiente:</legend>
                <div>
                    <input type="checkbox" id="interior" name="ambiente[]" value="interior">
                    <label for="interior">Interior</label>
                </div>
                <div>
                    <input type="checkbox" id="exterior" name="ambiente[]" value="exterior">
                    <label for="exterior">Exterior</label>
                </div>
            </fieldset>
        </div>
    
        <fieldset id="descripcion-container">
            <label for="descripcion">Descripción:</label>
            <textarea id="descripcion" name="detalles" required></textarea>
        </fieldset>
    
        <div class="navigation-buttons">
            <button type="button" class="next-btn">Siguiente</button>
        </div>
    </div>

   <!-- Paso 2: Crear Paradas -->
<div class="step" id="step-2" style="display: none;">
    <h3>Paso 2: Crear Paradas</h3>
    <fieldset id="paradaForm">
        <div class="parada">
            <label>Nombre de la Parada:</label>
            <input type="text" name="nombre_parada[]" >
        
            <!-- Contenedor de Imágenes -->
            <div id="contenedor-imagenes-0" class="flex">
                <div class="imagen">
                    <label>Nombre de la Imagen:</label>
                    <input type="text" name="nombre_imagen[0][]" placeholder="Nombre de la imagen" class="espacio">
                    <label>Agregar Imagen:</label>
                    <input type="file" name="archivo_imagen[0][]" accept="image/*" class="espacio">
                </div>
            </div>

            <!-- Botón para agregar una nueva imagen -->
            <button class="espacio agregar-imagen-boton" type="button" onclick="agregarImagen(0)">Agregar Imagen</button>
        
            <!-- Sección de Audio -->
            <fieldset class="seccion-audio">
                <label>Nombre del Audio:</label>
                <input type="text" name="audio_nombre[]" class="espacio">

                <label>Agregar Audio:</label>
                <input type="file" name="audio_archivo[]" accept="audio/*" class="espacio">
            </fieldset>

            <label>Coordenadas:</label>
            <input type="text" name="coordenadas[]" class="espacio">
        </div>
    </fieldset>

    <!-- Botón para agregar otra parada -->
    <button type="button" id="agregar-parada-boton">Agregar Otra Parada</button>

    <div class="navigation-buttons">
        <button type="button" class="prev-btn">Anterior</button>
        <button type="button" class="next-btn">Siguiente</button>
    </div>
</div>

    

    <!-- Paso 3: Información Adicional -->
    <div class="step" id="step-3" style="display: none;">
        <h3>Paso 3: Información Adicional</h3>
    
        <div id="adicional-container"> 
            <label for="adicional">Información Adicional:</label>
            <input type="text" id="adicional" name="comentarios" placeholder="Horarios, recomendaciones, etc.">
        </div>
    
        <div class="navigation-buttons">
            <button type="button" class="prev-btn">Anterior</button>
            <button type="submit" id="enviar-datos">Enviar Datos</button>
        </div>
    </div>
</form>

