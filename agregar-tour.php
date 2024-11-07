<?php
/**
 * Plugin Name: Agregar Tour
 * Description: Plugin para agregar un tour mediante un formulario de tres pasos.
 * Version: 1.0
 * Author: Tu Nombre
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
    wp_enqueue_style('agregar-tour-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_script('agregar-tour-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'agregar_tour_enqueue_scripts');

// Función para reorganizar el array de archivos
function reArrayFiles($file_post, $parada_index) {
    $file_ary = array();
    if (isset($file_post['name'][$parada_index]) && is_array($file_post['name'][$parada_index])) {
        $file_count = count($file_post['name'][$parada_index]);
        $file_keys = array_keys($file_post);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$parada_index][$i];
            }
        }
    }
    return $file_ary;
}

// Función para procesar imágenes de una parada
function procesar_imagenes_parada($parada_id, $parada_index, &$paradas_imagenes_urls) {
    global $wpdb;

    $imagenes_table = $wpdb->prefix . 'paradas_imagenes';

    // Verificar si existen imágenes para esta parada
    if (isset($_POST['nombre_imagen'][$parada_index]) && !empty($_POST['nombre_imagen'][$parada_index]) && isset($_FILES['archivo_imagen']['name'][$parada_index])) {
        // Asignar variables después de verificar que existen
        $img_nombres = $_POST['nombre_imagen'][$parada_index];
        $img_files = reArrayFiles($_FILES['archivo_imagen'], $parada_index);

        // Recorremos los nombres de las imágenes
        foreach ($img_nombres as $img_index => $img_nombre) {
            $img_nombre = sanitize_text_field($img_nombre);

            // Verificar que el archivo de imagen existe
            if (isset($img_files[$img_index])) {
                $image_file = $img_files[$img_index];

                if ($image_file['error'] === UPLOAD_ERR_OK) {
                    // Subir la imagen
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
                        // Guardar la URL de la imagen para usarla en el correo
                        $paradas_imagenes_urls[$parada_index][] = $archivo_imagen;
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
    $paradas_audios_urls = array(); // Inicializar array para URLs de audios

    // Recopilar y sanitizar los datos
    $idioma = sanitize_text_field($_POST['idioma']);
    $nombre_tour = sanitize_text_field($_POST['nombre_tour']);
    $duracion = sanitize_text_field($_POST['duracion_tour']);
    $ciudad = sanitize_text_field($_POST['ciudad']);
    $categoria = isset($_POST['categoria']) ? implode(',', array_map('sanitize_text_field', $_POST['categoria'])) : '';
    $ambiente = isset($_POST['ambiente']) ? implode(',', array_map('sanitize_text_field', $_POST['ambiente'])) : '';
    $detalles = sanitize_textarea_field($_POST['detalles']);
    $informacion_adicional = sanitize_textarea_field($_POST['comentarios']);
    $user_id = get_current_user_id(); 

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
            'detalles' => $detalles,
            'idioma' => $idioma,
            'comentarios' => $informacion_adicional,
            'fecha_creacion' => current_time('mysql'),
        ),
        array(
            '%d', // user_id
            '%s', // nombre_tour
            '%s', // ciudad
            '%s', // categoria
            '%s', // duracion_tour
            '%s', // ambiente
            '%s', // detalles
            '%s', // idioma
            '%s', // comentarios
            '%s', // fecha_creacion
        )
    );

    $tour_id = $wpdb->insert_id;

    $paradas_nombres = $_POST['nombre_parada'];
    $paradas_coordenadas = $_POST['coordenadas'];
    $paradas_audio_nombres = $_POST['audio_nombre'];
    $paradas_audio_archivos = $_FILES['audio_archivo'];

    $paradas_table = $wpdb->prefix . 'paradas';

    foreach ($paradas_nombres as $index => $nombre_parada) {
        $nombre_parada = sanitize_text_field($nombre_parada);
        $coordenadas = sanitize_text_field($paradas_coordenadas[$index]);
        $audio_nombre = sanitize_text_field($paradas_audio_nombres[$index]);

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
                'audio_nombre' => $audio_nombre,
                'audio_archivo' => $audio_archivo,
            
            ),

          
            array(
                '%d', // tour_id
                '%s', // nombre_parada
                '%s', // coordenadas
                '%s', // audio_nombre
                '%s', // audio_archivo
            

            )
        );

        // Obtener el ID de la parada recién insertada
        $parada_id = $wpdb->insert_id;

        // Procesar las imágenes de la parada
        procesar_imagenes_parada($parada_id, $index, $paradas_imagenes_urls);
    }

    // Construir el mensaje del correo electrónico
    $message = "Se ha agregado un nuevo tour:\n\n";
    $message .= "Nombre del Tour: " . $nombre_tour . "\n";
    $message .= "Idioma: " . $idioma . "\n";
    $message .= "Duración: " . $duracion . "\n";
    $message .= "Ciudad: " . $ciudad . "\n";
    $message .= "Categoría: " . $categoria . "\n";
    $message .= "Ambiente: " . $ambiente . "\n";
    $message .= "Detalles: " . $detalles . "\n";
    $message .= "Información Adicional: " . $informacion_adicional . "\n\n";

    $message .= "Paradas:\n";

    foreach ($paradas_nombres as $index => $nombre_parada) {
        $message .= "Parada " . ($index + 1) . ":\n";
        $message .= "Nombre: " . $nombre_parada . "\n";
        $message .= "Coordenadas: " . $paradas_coordenadas[$index] . "\n";
        $message .= "Audio Nombre: " . $paradas_audio_nombres[$index] . "\n";
        if (!empty($paradas_audios_urls[$index])) {
            $message .= "Audio URL: " . $paradas_audios_urls[$index] . "\n";
        }

        // Agregar las imágenes de la parada
        if (!empty($paradas_imagenes_urls[$index])) {
            $message .= "Imágenes:\n";
            foreach ($paradas_imagenes_urls[$index] as $imagen_url) {
                $message .= $imagen_url . "\n";
            }
        }
        $message .= "\n";
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
