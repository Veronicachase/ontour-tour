<!-- Indicadores de Progreso -->
<div id="donde-estoy"> 
    <button type="button" class="progress-btn active" data-step="1" >1</button>
    <button type="button" class="progress-btn" data-step="2" >2</button>
    <button type="button" class="progress-btn" data-step="3">3</button>
</div>

<!-- Título Principal -->
<div> 
   <!-- <h2>AGREGAR UN TOUR </h2> -->
</div>

<form id="tourForm" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST" enctype="multipart/form-data">
    <?php wp_nonce_field('procesar_tour_nonce_action', 'procesar_tour_nonce'); ?>
    <input type="hidden" name="action" value="procesar_tour">

    <!-- Paso 1: Información del Tour -->
     <div class="step" id="step-1">
        <h3>Paso 1: Información del Tour</h3>
        <div id="flex-container" class="flex">
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
                <label for="city">Ciudad, país:</label>
                <input type="text" id="city" name="ciudad" placeholder="ej: Córdoba, España">
            </div>
        </div>
        
        <div class="flex">
            
             <div class="flex-item">
                <label for="duracion">Tiempo estimado del recorrido:</label>
                <input type="text" id="duracion" name="duracion_tour" placeholder="Duración (ej: 1h 10min)">
            </div>
            
             <div class="flex-item">
                <label for="distancia">Distancia aproximada:</label>
                <input type="text" id="distancia" name="distancia" placeholder="Distancia (ej: 5km)">
            </div>
            
            <div class="flex-item">
            <label for="dificultad">Nivel de dificultad:</label>
            <select class="espaciado-opciones" name="dificultad[]" id="dificultad">
                <option value="basico">Básico</option>
                <option value="intermedio">Intermedio</option>
                <option value="avanzado">Avanzado</option>
                
            </select>
        </div>
            
        </div>
        
        <div class="flex-item">
            <label for="nombre_tour">Nombre del Tour:</label>
            <input type="text" id="nombre_tour" name="nombre_tour">
        </div>
        
        <div id="cbcontainer" > 
           <label for="categoria" class="titulo-naranja">Categoría:</label>
            <select class="espaciado-opciones" name="categoria[]" id="categoria">
                <option value="ciudad">Ciudad</option>
                <option value="naturaleza">Naturaleza</option>
                <option value="cultura">Cultura</option>
                <option value="festividad">Festividad</option>
                <option value="ciencia">Ciencia y Tecnología</option>
                <option value="otro">Otro</option>
            </select>

            <label for="ambiente" class="titulo-naranja">Ambiente:</label>
            <select class="espaciado-opciones" name="ambiente" id="ambiente">
                <option value="interior">Interior</option>
                <option value="exterior">Exterior</option>
            </select>
           
        </div>

             <label for="descripcion" class="titulo-naranja"> Descripción  </label>
            <textarea id="descripcion"  name="descripcion_tour" required placeholder="Rápida descripción  del tour"></textarea>
  

        <fieldset class="imagen-principal">
            <legend class="titulo-naranja">Agregar imagen principal del tour:</legend>
            <input type="file" name="imagen_principal" accept="image/*" class="espacio">
        </fieldset>

        <p class="texto-indicativo">
            Cuenta una breve introducción sobre ti, cuáles son los principales puntos por donde va a pasar el tour y la información que se considere relevante. 
            La Presentación del Tour podrá escucharlo cualquier usuario antes de iniciar el Tour, por tanto, recomendamos hacer este audio esmero.
        </p>

        <fieldset class="audio-principal">
            <legend class="titulo-naranja">Audio de presentación:</legend>
            <input type="file" name="audio_principal" accept="audio/*" class="espacio">
            <small class="form-text text-muted">Duración máxima: 1:30 mins</small>
        </fieldset>
        
        <label for="recomendaciones" class="titulo-naranja"> Recomendaciones </label>
        <textarea id="recomendaciones"  name="recomendaciones" placeholder="ej.: EL mejor horario es.., se recomienda llevar agua"></textarea>

        <div class="navigation-buttons">
            <button type="button" class="next-btn">Siguiente</button>
        </div>
        
        
    </div>

    <!-- Paso 2: Crear Paradas -->
    <div class="step" id="step-2" style="display: none;">
    <h3>Paso 2: Crear Paradas</h3>
    <div id="paradaForm">
        <div class="parada">
            <label>Nombre de la Parada:</label>
            <input type="text" name="nombre_parada[]">

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
                <legend class="titulo-naranja">Audio de la parada</legend>
                <input type="file" name="audio_archivo[]" accept="audio/*" class="espacio">
            </fieldset>

            <!-- Coordenadas -->
            <fieldset>
                <legend class="titulo-naranja">Coordenadas</legend>
                <input type="text" name="coordenadas[]" class="espacio" placeholder="eje.:37.89155, -4.77275">
            </fieldset>
        </div>
    </div>

    <!-- Botón para agregar y eliminar otra parada -->
   <!-- <button type="button" id="eliminar-parada-boton" class="eliminar-parada-boton" onclick="eliminarParada(event)">Eliminar Parada</button>-->

    <button type="button" id="agregar-parada-boton">Agregar Otra Parada</button>

    <div class="navigation-buttons">
        <button type="button" class="prev-btn">Anterior</button>
        <button type="button" class="next-btn">Siguiente</button>
    </div>
</div>

    <!-- Paso 3: Información Adicional -->
    <div class="step" id="step-3" style="display: none;">


    <h3 class="titulo-naranja  titulo" >Paso 3: Información Adicional</h3>
        
<div id="platos-Container"> 
    <h6 class="titulo-naranja"> Platos típicos recomendados</h6>
    <div class="nuevo-plato">  
        <fieldset id="plato-container"> 
            <legend class="titulo-naranja">Plato típico:</legend>
            <label for="nuevo-plato">Plato típico:</label>
            <input type="text" name="plato_tipico[]" placeholder="Ej: Paella">

            <label>Agregar Imagen del plato:</label>
            <input type="file" name="imagen_plato[]" accept="image/*" class="espacio">
        </fieldset>
    </div>

    <button type="button" class="eliminar-plato" onclick="eliminarPlato(event)">Eliminar Plato</button>
    <button type="button" class="agregar-plato" onclick="agregarPlato()">Agregar Plato</button>
</div>

<div id="restaurantesContainer"> 

     <div class="nuevo-restaurante"> 
        <fieldset > 
            <legend class="titulo-naranja">Restaurantes recomendados:</legend>
            <label for="restaurante">Nombre del restaurante:</label>
            <input type="text" name="nombre_restaurante[]" placeholder="Ej: El mesón de María">

            <label for="link-restaurante">Link a su web:</label>
            <input type="text" name="link_restaurante[]" placeholder="Puedes agregar el link de Google">

            <label for="categoria-rest">Categoria:</label>
            <select class="espaciado-opciones" name="categorias_rest[]">
                <option value="gastronomia">Gastronomía</option>
                <option value="vistas">Buenas vistas</option>
                <option value="copas">Copas</option>
                <option value="desayunos">Desayunos & Meriendas</option>
            </select>

            <label for="precio">Precio:</label>
            <select class="espaciado-opciones" name="precio[]">
                <option value="economico">€</option>
                <option value="medio">€€</option>
                <option value="caro">€€€</option>
            </select>
            
            <label for="destacado">Comentarios:</label>
            <input type="text" name="detacados[]" placeholder="ej.: Comida típica, Croquetería, Buen asado, pulpo espectacular">
           
        </fieldset>
     </div>
    
     <button type="button" class="eliminar-rest" onclick="eliminarRestaurante(event)">Eliminar Restaurante</button>
      <button type="button" class="agregar-rest" onclick="agregarRestaurante()">Agregar Restaurante</button>
</div>



<div id="eventosContainer"> 
<h6 class="titulo-naranja"> Eventos Recomendados</h6>
    <div class="nuevo-evento">  
        <fieldset id="adicional-container"> 
            <legend class="titulo-naranja">Nuevo actividad:</legend>
            <label for="titulo-adicional">Título de la actividad recomendada:</label>
            <input type="text" name="titulo_evento[]" placeholder="Ej: Espectáculo de flamenco">

            <label for="adicional">Información Adicional:</label>
            <input type="text" name="informacion_adicional[]" placeholder="Horarios, recomendaciones, etc.">
        </fieldset>
        
     </div>
     
     <button type="button" class="eliminar-act" onclick="eliminarActividad(event)">Eliminar actividad</button>
     <button type="button" class="agregar-act" onclick="agregarEvento()">Agregar actividad</button>
</div>


        

        <div class="navigation-buttons">
            <button type="button" class="prev-btn">Anterior</button>
            <button type="submit" id="enviar-datos">Enviar Datos</button>
        </div>
    </div>
</form>


