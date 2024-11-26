<?php
/**
 * Plugin Name: Agregar Tour
 * Description: Plugin para agregar un tour mediante un formulario de tres pasos.
 * Version: 1.0
 * Author: Verónica
 * Text Domain: agregar-tour
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Registrar shortcode
function agregar_tour_shortcode() {
    ob_start();
    include plugin_dir_path(__FILE__) . 'templates/formulario.php';
    return ob_get_clean();
}
add_shortcode('agregar_tour', 'agregar_tour_shortcode');

// Encolar scripts y estilos
function agregar_tour_enqueue_scripts() {
    wp_enqueue_style(
        'agregar-tour-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap',
        array(),
        null
    );
    wp_enqueue_style('agregar-tour-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_script('agregar-tour-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'agregar_tour_enqueue_scripts');

// Función para reorganizar el array de archivos
function reArrayFiles($file_post, $parada_index) {
    $file_ary = array();
    if (isset($file_post['name'][$parada_index]) && is_array($file_post['name'][$parada_index])) {
        foreach ($file_post['name'][$parada_index] as $file_index => $name) {
            $file_ary[$file_index] = array(
                'name' => $file_post['name'][$parada_index][$file_index],
                'type' => $file_post['type'][$parada_index][$file_index],
                'tmp_name' => $file_post['tmp_name'][$parada_index][$file_index],
                'error' => $file_post['error'][$parada_index][$file_index],
                'size' => $file_post['size'][$parada_index][$file_index],
            );
        }
    }
    return $file_ary;
}

// Función para procesar imágenes de una parada
function procesar_imagenes_parada($parada_id, $parada_index, &$paradas_imagenes_urls) {
    global $wpdb;
    $imagenes_table = $wpdb->prefix . 'paradas_imagenes';

    if (isset($_POST['nombre_imagen'][$parada_index]) && !empty($_POST['nombre_imagen'][$parada_index]) && isset($_FILES['archivo_imagen']['name'][$parada_index])) {
        $img_nombres = $_POST['nombre_imagen'][$parada_index];
        $img_files = reArrayFiles($_FILES['archivo_imagen'], $parada_index);

        foreach ($img_nombres as $img_index => $img_nombre) {
            $img_nombre = sanitize_text_field($img_nombre);
            if (isset($img_files[$img_index])) {
                $image_file = $img_files[$img_index];
                if ($image_file['error'] === UPLOAD_ERR_OK) {
                    $uploaded_image = wp_handle_upload($image_file, array('test_form' => false));
                    if (!isset($uploaded_image['error'])) {
                        $archivo_imagen = $uploaded_image['url'];

                        // Insertar la imagen en la tabla
                        $wpdb->insert(
                            $imagenes_table,
                            array(
                                'parada_id' => $parada_id,
                                'nombre_imagen' => $img_nombre,
                                'archivo_imagen' => $archivo_imagen,
                            ),
                            array(
                                '%d', // parada_id
                                '%s', // nombre_imagen
                                '%s', // archivo_imagen
                            )
                        );

                        // Guardar la información de la imagen en el array
                        $paradas_imagenes_urls[$parada_index][] = array(
                            'nombre_imagen' => $img_nombre,
                            'archivo_imagen' => $archivo_imagen,
                        );
                    } else {
                        error_log('Error al subir la imagen: ' . $uploaded_image['error']);
                    }
                } else {
                    error_log('Error en el archivo de imagen: ' . $image_file['error']);
                }
            } else {
                error_log('No se encontró el archivo de imagen para el índice ' . $img_index);
            }
        }
    } else {
        error_log('No hay imágenes para la parada ' . $parada_index);
    }
}

// Procesar datos del formulario
function procesar_tour() {
    // Verificar nonce
    if (!isset($_POST['procesar_tour_nonce']) || !wp_verify_nonce($_POST['procesar_tour_nonce'], 'procesar_tour_nonce_action')) {
        wp_die('Nonce inválido');
    }

    global $wpdb;

    $paradas_imagenes_urls = array(); 
    $paradas_audios_urls = array();

    // Recopilar y sanitizar los datos
    $idioma = sanitize_text_field($_POST['idioma']);
    $nombre_tour = sanitize_text_field($_POST['nombre_tour']);
    $duracion = sanitize_text_field($_POST['duracion_tour']);
    $ciudad = sanitize_text_field($_POST['ciudad']);
    $categoria = isset($_POST['categoria']) ? implode(',', array_map('sanitize_text_field', (array)$_POST['categoria'])) : '';
    $ambiente = sanitize_text_field($_POST['ambiente']);
    $descripcion_tour = sanitize_textarea_field($_POST['descripcion_tour']);
    $distancia = sanitize_text_field($_POST['distancia']);
    $dificultad = sanitize_text_field($_POST['dificultad']);
    $recomendaciones = sanitize_textarea_field($_POST['recomendaciones']);
    
    $user_id = get_current_user_id(); 

    // Procesar imagen principal
    $imagen_principal_url = '';
    if (isset($_FILES['imagen_principal']['name']) && !empty($_FILES['imagen_principal']['name'])) {
        $imagen_principal = wp_handle_upload($_FILES['imagen_principal'], array('test_form' => false));
        if (!isset($imagen_principal['error'])) {
            $imagen_principal_url = $imagen_principal['url'];
        }
    }

    // Procesar audio principal
    $audio_principal_url = '';
    if (isset($_FILES['audio_principal']['name']) && !empty($_FILES['audio_principal']['name'])) {
        $audio_principal = wp_handle_upload($_FILES['audio_principal'], array('test_form' => false));
        if (!isset($audio_principal['error'])) {
            $audio_principal_url = $audio_principal['url'];
        }
    }

    // Insertar datos en la tabla wp_tours
    $tours_table = $wpdb->prefix . 'tours';

    $wpdb->insert(
        $tours_table,
        array(
            'user_id' => $user_id,
            'nombre_tour' => $nombre_tour,
            'ciudad' => $ciudad,
            'categoria' => $categoria,
            'duracion_tour' => $duracion,
            'ambiente' => $ambiente,
            'descripcion_tour' => $descripcion_tour,
            'idioma' => $idioma,
            'imagen_principal' => $imagen_principal_url, 
            'audio_principal' => $audio_principal_url,  
            'fecha_creacion' => current_time('mysql'),
            'distancia' => $distancia,
            'dificultad' => $dificultad,
            'recomendaciones' => $recomendaciones,
        
        ),
        array(
            '%d', // user_id
            '%s', // nombre_tour
            '%s', // ciudad
            '%s', // categoria
            '%s', // duracion_tour
            '%s', // ambiente
            '%s', // descripcion_tour
            '%s', // idioma
            '%s', // imagen_principal
            '%s', // audio_principal
            '%s', // fecha_creacion
            '%s', // distancia
            '%s', // dificultad
            '%s', // recomendaciones
        )
    );

    $tour_id = $wpdb->insert_id;

    // Procesar paradas
    $paradas_nombres = $_POST['nombre_parada'];
    $paradas_coordenadas = $_POST['coordenadas'];
    $paradas_audio_archivos = $_FILES['audio_archivo'];

    $paradas_table = $wpdb->prefix . 'paradas';

    foreach ($paradas_nombres as $index => $nombre_parada) {
        $nombre_parada = sanitize_text_field($nombre_parada);
        $coordenadas = sanitize_text_field($paradas_coordenadas[$index]);

        $audio_archivo = '';
        if (isset($paradas_audio_archivos['name'][$index]) && !empty($paradas_audio_archivos['name'][$index])) {
            $audio_file = array(
                'name' => $paradas_audio_archivos['name'][$index],
                'type' => $paradas_audio_archivos['type'][$index],
                'tmp_name' => $paradas_audio_archivos['tmp_name'][$index],
                'error' => $paradas_audio_archivos['error'][$index],
                'size' => $paradas_audio_archivos['size'][$index],
            );

            // Subir el archivo
            $uploaded_audio = wp_handle_upload($audio_file, array('test_form' => false));
            if (!isset($uploaded_audio['error'])) {
                $audio_archivo = $uploaded_audio['url'];
            }
        }

        // Guardar la URL del audio para usarla en el correo
        $paradas_audios_urls[$index] = $audio_archivo;

        // Insertar parada
        $wpdb->insert(
            $paradas_table,
            array(
                'tour_id' => $tour_id,
                'nombre_parada' => $nombre_parada,
                'coordenadas' => $coordenadas,
                'audio_archivo' => $audio_archivo,
            ),
            array(
                '%d', // tour_id
                '%s', // nombre_parada
                '%s', // coordenadas
                '%s', // audio_archivo
            )
        );

        // Obtener el ID de la parada recién insertada
        $parada_id = $wpdb->insert_id;

        // Procesar las imágenes de la parada
        procesar_imagenes_parada($parada_id, $index, $paradas_imagenes_urls);
    }

  

    // Procesar restaurantes
    if (!empty($_POST['nombre_restaurante'])) {
        $restaurantes_table = $wpdb->prefix . 'tour_restaurantes';
        foreach ($_POST['nombre_restaurante'] as $index => $nombre_restaurante) {
            $nombre_restaurante = sanitize_text_field($nombre_restaurante);
            $link_restaurante = sanitize_text_field($_POST['link_restaurante'][$index]);
            $precio = sanitize_text_field($_POST['precio'][$index]);
            $categorias_rest = sanitize_text_field($_POST['categorias_rest'][$index]);
            $destacados = sanitize_text_field($_POST['detacados'][$index]);

            $wpdb->insert(
                $restaurantes_table,
                array(
                    'tour_id' => $tour_id,
                    'nombre_restaurante' => $nombre_restaurante,
                    'link_restaurante' => $link_restaurante,
                    'categoria_rest' => $categorias_rest,
                    'precio' => $precio,
                    'comentarios_rest' => $destacados,
                ),
                array('%d', '%s', '%s', '%s', '%s', '%s')
            );
        }
    }

    // Procesar actividades
    if (!empty($_POST['nombre_actividad'])) {
        $eventos_table = $wpdb->prefix . 'tour_eventos';
        foreach ($_POST['nombre_actividad'] as $index => $nombre_actividad) {
            $titulo_evento = sanitize_text_field($nombre_actividad);
            $informacion_adicional = sanitize_text_field($_POST['informacion_adicional'][$index]);

            $wpdb->insert(
                $eventos_table,
                array(
                    'tour_id' => $tour_id,
                    'titulo_evento' => $titulo_evento,
                    'informacion_adicional' => $informacion_adicional,
                ),
                array('%d', '%s', '%s')
            );
        }
    }

    // Procesar platos
    $platos_imagenes_urls = array();
    if (!empty($_POST['plato_tipico'])) {
    $platos_table = $wpdb->prefix . 'tour_platos';
    foreach ($_POST['plato_tipico'] as $index => $plato_tipico) {
        $nombre_plato = sanitize_text_field($plato_tipico);

        // Procesar imagen del plato
        $imagen_plato_url = '';
        if (isset($_FILES['imagen_plato']['name'][$index]) && !empty($_FILES['imagen_plato']['name'][$index])) {
            $file = array(
                'name' => $_FILES['imagen_plato']['name'][$index],
                'type' => $_FILES['imagen_plato']['type'][$index],
                'tmp_name' => $_FILES['imagen_plato']['tmp_name'][$index],
                'error' => $_FILES['imagen_plato']['error'][$index],
                'size' => $_FILES['imagen_plato']['size'][$index]  
            ); 
            $upload = wp_handle_upload($file, array('test_form' => false));
            if (!isset($upload['error'])) {
                $imagen_plato_url = $upload['url'];
                // Guardar la URL de la imagen en el array con el índice correspondiente
                $platos_imagenes_urls[$index] = $imagen_plato_url;
            }
        }

            $wpdb->insert(
            $platos_table,
            array(
                'tour_id' => $tour_id,
                'plato_tipico' => $nombre_plato,
                'imagen_plato' => $imagen_plato_url,
            ),
            array('%d', '%s', '%s')
        );
        }
    }

    // Construir el mensaje del correo electrónico
    $message = "Se ha agregado un nuevo tour:\n\n";
    $message .= "Nombre del Tour: " . $nombre_tour . "\n";
    $message .= "Idioma: " . $idioma . "\n";
    $message .= "Duración: " . $duracion . "\n";
    $message .= "Ciudad: " . $ciudad . "\n";
    $message .= "Categoría: " . $categoria . "\n";
    $message .= "Ambiente: " . $ambiente . "\n";
    $message .= "Descripción del tour: " . $descripcion_tour . "\n\n";
    $message .= "Distancia aproximada: " . $distancia . "\n\n";
    $message .= "Nivel de dificultad: " . $dificultad . "\n\n";
    $message .= "Recomendaciones: " . $recomendaciones . "\n\n";

    if (!empty($audio_principal_url)) {
        $message .= "Audio principal URL: " . $audio_principal_url . "\n";
    }

    if (!empty($imagen_principal_url)) {
        $message .= "Imagen principal URL: " . $imagen_principal_url . "\n";
    }

    $message .= "Paradas:\n";

    foreach ($paradas_nombres as $index => $nombre_parada) {
        $message .= "Parada " . ($index + 1) . ":\n";
        $message .= "Nombre: " . $nombre_parada . "\n";
        $message .= "Coordenadas: " . $paradas_coordenadas[$index] . "\n";
      
        if (!empty($paradas_audios_urls[$index])) {
            $message .= "Audio URL: " . $paradas_audios_urls[$index] . "\n";
        }

        // Agregar todas las imágenes de la parada con su nombre
        if (!empty($paradas_imagenes_urls[$index])) {
            $message .= "Imágenes:\n";
            foreach ($paradas_imagenes_urls[$index] as $imagen) {
                $message .= " - " . ($imagen['nombre_imagen'] ?? 'Sin nombre') . ": " . ($imagen['archivo_imagen'] ?? 'Sin URL') . "\n";
            }
        }
        $message .= "\n";
    }

    if (!empty($_POST['nombre_restaurante'])) {
        $message .= "Restaurantes recomendados:\n";
        foreach ($_POST['nombre_restaurante'] as $index => $nombre_restaurante) {
            $link_restaurante = sanitize_text_field($_POST['link_restaurante'][$index]);
            $precio = sanitize_text_field($_POST['precio'][$index]);
            $message .= "- $nombre_restaurante ($precio): $link_restaurante\n";
        }
    }

    if (!empty($_POST['titulo_comentario'])) {
        $message .= "Eventos recomendados:\n";
        foreach ($_POST['titulo_comentario'] as $index => $titulo_comentario) {
            $informacion_adicional = sanitize_text_field($_POST['comentarios'][$index]);
            $message .= "- $titulo_comentario: $informacion_adicional\n";
        }
    }

   if (!empty($_POST['plato_tipico'])) {
    $message .= "Platos típicos:\n";
    foreach ($_POST['plato_tipico'] as $index => $plato_tipico) {
        $message .= "- " . $plato_tipico . "\n";
        if (!empty($platos_imagenes_urls[$index])) {
            $message .= "Imagen del plato: " . $platos_imagenes_urls[$index] . "\n";
        }
    }
}
    if (!empty($_POST['titulo_evento'])) {
        $message .= "Actividades recomendadas:\n";
        foreach ($_POST['titulo_evento'] as $index => $titulo_evento) {
            $informacion_adicional = sanitize_text_field($_POST['informacion_adicional'][$index]);
            $message .= "- $titulo_evento: $informacion_adicional\n";
        }
    }

    // Enviar correo electrónico
    $to = 'chase.veronica@gmail.com';
    $subject = 'Nuevo Tour Agregado';
    wp_mail($to, $subject, $message);

    // Redirigir después de procesar
    wp_redirect(home_url('/gracias/'));
    exit;
}
add_action('admin_post_procesar_tour', 'procesar_tour');
add_action('admin_post_nopriv_procesar_tour', 'procesar_tour');
?>



