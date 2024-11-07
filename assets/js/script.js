let paradaIndex = 0;

function agregarImagen(paradaIndex) {
    const contenedor = document.getElementById(`contenedor-imagenes-${paradaIndex}`);
    contenedor.insertAdjacentHTML('beforeend', `
        <div class="imagen">
            <label>Nombre de la Imagen:</label>
            <input type="text" name="nombre_imagen[${paradaIndex}][]" placeholder="Nombre de la imagen">
            <label>Agregar Imagen:</label>
            <input type="file" name="archivo_imagen[${paradaIndex}][]" accept="image/*">
        </div>
    `);
}


function agregarParada() {
    paradaIndex++;
    const paradaForm = document.getElementById('paradaForm');
    const newParada = document.createElement('div');
    newParada.classList.add('parada');
    newParada.innerHTML = `
        <hr>
        <label>Nombre de la Parada:</label>
        <input type="text" name="nombre_parada[]">
        <div id="contenedor-imagenes-${paradaIndex}">
            <div class="imagen">
                <label>Nombre de la Imagen:</label>
                <input type="text" name="nombre_imagen[${paradaIndex}][]" placeholder="Nombre de la imagen">
                <label>Agregar Imagen:</label>
                <input type="file" name="archivo_imagen[${paradaIndex}][]" accept="image/*">
            </div>
            <button type="button" onclick="agregarImagen(${paradaIndex})">Agregar Imagen</button>
        </div>
        <fieldset class="seccion-audio">
            <label>Nombre del Audio:</label>
            <input type="text" name="audio_nombre[]">
            <label>Agregar Audio:</label>
            <input type="file" name="audio_archivo[]" accept="audio/*">
        </fieldset>
        <label>Coordenadas:</label>
        <input type="text" name="coordenadas[]">
    `;
    paradaForm.appendChild(newParada);
}


document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('agregar-parada-boton').addEventListener('click', agregarParada);
});


document.addEventListener('DOMContentLoaded', function() {
    let currentStep = 1;
    const totalSteps = 3;

    function showStep(step) {
        for (let i = 1; i <= totalSteps; i++) {
            document.getElementById('step-' + i).style.display = 'none';
            document.querySelector(`.progress-btn[data-step="${i}"]`).classList.remove('active');
        }

        document.getElementById('step-' + step).style.display = 'block';
        document.querySelector(`.progress-btn[data-step="${step}"]`).classList.add('active');

        currentStep = step;
    }

    document.querySelectorAll('.next-btn').forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        });
    });

    document.querySelectorAll('.prev-btn').forEach(button => {
        button.addEventListener('click', () => {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        });
    });

    const agregarParadaBoton = document.getElementById('agregar-parada-boton');
    if (agregarParadaBoton) {
        agregarParadaBoton.addEventListener('click', agregarParada);
    } else {
        console.error('No se encontró el botón "Agregar Otra Parada"');
    }

    document.querySelectorAll('.progress-btn').forEach(button => {
        button.addEventListener('click', () => {
            const step = parseInt(button.getAttribute('data-step'));
            showStep(step);
        });
    });

    const tourForm = document.getElementById("tourForm");
    if (tourForm) {
        tourForm.addEventListener("submit", function() {
            console.log("Formulario enviado"); 
        });
    } else {
        console.error('No se encontró el formulario "tourForm"');
    }
});
