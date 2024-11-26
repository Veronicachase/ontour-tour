let paradaIndex = 0;
let restauranteIndex = 0;
let eventoIndex = 0;
let platoIndex=0;


function agregarImagen(paradaIndex) {
  const contenedor = document.getElementById(`contenedor-imagenes-${paradaIndex}`);
  contenedor.insertAdjacentHTML(
    "beforeend",
    `
      <div class="imagen">
          <label>Nombre de la Imagen:</label>
          <input type="text" name="nombre_imagen[${paradaIndex}][]" placeholder="Nombre de la imagen" class="espacio">
          <label>Agregar Imagen:</label>
          <input type="file" name="archivo_imagen[${paradaIndex}][]" accept="image/*" class="espacio">
          <button type="button" id="eliminar-imagen-boton" class="eliminar-parada-imagen" onclick="eliminarImagen(event)">Eliminar imagen</button>
      </div>
    `
  );
}

function agregarParada() {
  paradaIndex++;
  const paradaForm = document.getElementById("paradaForm");
  const newParada = document.createElement("div");
  newParada.classList.add("parada");
  newParada.innerHTML = `
    <h6>Nueva parada</h6>
    <hr class="linea-divisoria">
    <label>Nombre de la Parada:</label>
    <input type="text" name="nombre_parada[]">
    <div id="contenedor-imagenes-${paradaIndex}">
        <div class="imagen">
            <label>Nombre de la Imagen:</label>
            <input type="text" name="nombre_imagen[${paradaIndex}][]" placeholder="Nombre de la imagen" class="espacio">
            <label>Agregar Imagen:</label>
            <input type="file" name="archivo_imagen[${paradaIndex}][]" accept="image/*" class="espacio">
        </div> 
    </div>
  
    <button class="espacio agregar-imagen-boton" type="button" onclick="agregarImagen(${paradaIndex})">Agregar Imagen</button>

    <fieldset class="seccion-audio">
        <legend class="titulo-naranja">Audio de la parada</legend>
        <label>Agregar Audio:</label>
        <input type="file" name="audio_archivo[]" accept="audio/*" class="espacio">
    </fieldset>
    <fieldset>
        <legend class="titulo-naranja">Coordenadas</legend>
        <input type="text" name="coordenadas[]" class="espacio" placeholder="eje.:37.89155, -4.77275">
    </fieldset>
      <button type="button" id="eliminar-parada-boton" class="eliminar-parada-boton" onclick="eliminarParada(event)">Eliminar Parada</button>
  `;
  paradaForm.appendChild(newParada);
}


function agregarPlato() {
  platoIndex++;
  const platoContainer = document.getElementById("platos-Container");
  const newPlato = document.createElement("div");
  newPlato.classList.add("nuevo-plato");
  newPlato.innerHTML = `
      <fieldset id="plato-container"> 
            <legend class="titulo-naranja">Plato típico:</legend>
            <label for="nuevo-plato">Plato típico:</label>
            <input type="text" name="plato_tipico[]" placeholder="Ej: Gazpacho">

           <label>Agregar Imagen del plato:</label>
            <input type="file" name="imagen_plato[]" accept="image/*" class="espacio">
        </fieldset>
        <button type="button" class="eliminar-plato" onclick="eliminarPlato(event)">Eliminar Plato</button>
  `;

  const agregarPlatoButton = platoContainer.querySelector('.agregar-plato');

  platoContainer.insertBefore(newPlato, agregarPlatoButton);
}



function agregarEvento() {
  eventoIndex++;
  const eventosContainer = document.getElementById("eventosContainer");
  const newEvento = document.createElement("div");
  newEvento.classList.add("nuevo-evento");
  newEvento.innerHTML = `
      <fieldset>
          <legend class="titulo-naranja">Nuevo evento:</legend>
          <label for="titulo-adicional">Título del evento recomendado:</label>
          <input type="text" name="titulo_comentario[]" placeholder="Ej: Espectáculo de flamenco">

          <label for="adicional">Información Adicional:</label>
          <input type="text" name="comentarios[]" placeholder="Horarios, recomendaciones, etc.">
      </fieldset> 
      <button type="button" class="eliminar-act" onclick="eliminarActividad(event)">Eliminar actividad</button>
    
  `;

  const agregarEventButton = eventosContainer.querySelector('.agregar-act');

  eventosContainer.insertBefore(newEvento, agregarEventButton);
}



function agregarRestaurante() {
  restauranteIndex++;
  const restaurantesContainer = document.getElementById("restaurantesContainer");
  const newRestaurante = document.createElement("div");
  newRestaurante.classList.add("nuevo-restaurante");
  newRestaurante.innerHTML = `
       <fieldset > 
            <legend class="titulo-naranja">Restaurantes recomendados:</legend>
            <label for="restaurante">Nombre del restaurante:</label>
            <input type="text" name="nombre_restaurante[]" placeholder="Ej: El mesón de María">

            <label for="link-restaurante">Link a su web:</label>
            <input type="text" name="link_restaurante[]" placeholder="Puedes agregar el link de Google">

            <label for="categoria-rest">Categoria:</label>
            <select class="espaciado-opciones" name="categorias_Rest[]">
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
          </fieldset > 
    <button type="button" class="eliminar-rest" onclick="eliminarRestaurante(event)">Eliminar Restaurante</button>
    
  `;
  const agregarRestButton = document.querySelector('.agregar-rest');
   restaurantesContainer.insertBefore(newRestaurante, agregarRestButton);
}

document.addEventListener("DOMContentLoaded", function () {
  let currentStep = 1;
  const totalSteps = 3;

  function showStep(step) {
    for (let i = 1; i <= totalSteps; i++) {
      const stepElement = document.getElementById("step-" + i);
      const progressButton = document.querySelector(`.progress-btn[data-step="${i}"]`);
      if (stepElement) stepElement.style.display = "none";
      if (progressButton) progressButton.classList.remove("active");
    }

    const activeStepElement = document.getElementById("step-" + step);
    const activeProgressButton = document.querySelector(`.progress-btn[data-step="${step}"]`);
    if (activeStepElement) activeStepElement.style.display = "block";
    if (activeProgressButton) activeProgressButton.classList.add("active");

    currentStep = step;
  }
 

  // Botones "Siguiente"
  document.querySelectorAll(".next-btn").forEach((button) => {
    button.addEventListener("click", () => {
      if (currentStep < totalSteps) {
        showStep(currentStep + 1);
      }
    });
  });

  // Botones "Anterior"
  document.querySelectorAll(".prev-btn").forEach((button) => {
    button.addEventListener("click", () => {
      if (currentStep > 1) {
        showStep(currentStep - 1);
      }
    });
  });

  // Botones de progreso
  document.querySelectorAll(".progress-btn").forEach((button) => {
    button.addEventListener("click", () => {
      const step = parseInt(button.getAttribute("data-step"));
      showStep(step);
    });
  });

  // Mostrar el primer paso al cargar la página
  showStep(1);

  // Eventos adicionales
  const agregarParadaBoton = document.getElementById("agregar-parada-boton");
  if (agregarParadaBoton) {
    agregarParadaBoton.addEventListener("click", agregarParada);
  } else {
    console.error('No se encontró el botón "Agregar Otra Parada"');
  }

  const tourForm = document.getElementById("tourForm");
  if (tourForm) {
    tourForm.addEventListener("submit", function () {
      console.log("Formulario enviado");
    });
  } else {
    console.error('No se encontró el formulario "tourForm"');
  }
});
  
  // Botones para eliminar parada, restaurante, evento y plato
  
  function eliminarParada(e) {
    const parada = e.target.closest(".parada");
    if (parada) {
        if (confirm("¿Estás seguro de que deseas eliminar esta parada?")) {
            parada.remove();
            alert("La parada ha sido eliminada");
        }
    } else {
        alert("No se encontró la parada a eliminar.");
    }
}

function eliminarRestaurante(e) {
    const rest = e.target.closest(".nuevo-restaurante");
    if (rest) {
        if (confirm("¿Estás seguro de que deseas eliminar este restaurante?")) {
            rest.remove();
            alert("El restaurante ha sido eliminado");
        }
    } else {
        alert("No se encontró el restaurante a eliminar.");
    }
}

function eliminarPlato(e) {
    const plato = e.target.closest(".nuevo-plato");
    if (plato) {
        if (confirm("¿Estás seguro de que deseas eliminar este plato?")) {
            plato.remove();
            alert("El plato ha sido eliminado");
        }
    } else {
        alert("No se encontró el plato a eliminar.");
    }
}

function eliminarActividad(e) {
    const actividad = e.target.closest(".nuevo-evento");
    if (actividad) {
        if (confirm("¿Estás seguro de que deseas eliminar esta actividad?")) {
            actividad.remove();
            alert("La actividad ha sido eliminado");
        }
    } else {
        alert("No se encontró la actividad a eliminar.");
    }
}
        
        
     function eliminarImagen(e) {
    const paradaImagen = e.target.closest(".imagen");
    if (paradaImagen) {
        if (confirm("¿Estás seguro de que deseas eliminar esta imagen?")) {
            paradaImagen.remove();
            alert("La imagen ha sido ha sido eliminada");
        }
    }else {
        alert("No se encontró la imagen a eliminar.");
    }
    
     } 
   

