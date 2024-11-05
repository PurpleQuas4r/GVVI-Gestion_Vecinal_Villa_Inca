<?php
// guardar_publicidad.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include('db.php');

// Verificar si el usuario está autenticado y el ID existe
if (!isset($_SESSION['user_id'])) {
    die("Error: Usuario no autenticado.");
}

$usuario_creador = $_SESSION['user_id'];

// Verificar que el usuario exista en la tabla `usuario`
$user_check_query = "SELECT id FROM usuario WHERE id = ?";
$user_check_stmt = $conn->prepare($user_check_query);
$user_check_stmt->bind_param("i", $usuario_creador);
$user_check_stmt->execute();
$user_check_stmt->store_result();

if ($user_check_stmt->num_rows === 0) {
    die("Error: El usuario con ID $usuario_creador no existe en la tabla `usuario`.");
}

// Obtener los datos del formulario
$titulo = $_POST['titulo'];
$mensaje = $_POST['mensaje'];
$remitente = $_POST['remitente'];
$notificar = isset($_POST['notificar']) ? 1 : 0;
$fecha_publicacion = date("Y-m-d");

// Insertar la publicidad en la tabla `publicaciones`
$query = "INSERT INTO publicaciones (usuario_creador, titulo, mensaje, remitente, fecha_publicacion, estado, notificacion)
          VALUES (?, ?, ?, ?, ?, 'activo', ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("issssi", $usuario_creador, $titulo, $mensaje, $remitente, $fecha_publicacion, $notificar);

if ($stmt->execute()) {
    // Obtener el ID de la publicación recién insertada
    $id_publicacion = $stmt->insert_id;

    // Procesar las imágenes (si existen)
    if (!empty($_FILES['imagenes']['name'][0])) {
        $imagenes = $_FILES['imagenes'];

        for ($i = 0; $i < count($imagenes['name']) && $i < 4; $i++) {
            $imagen_data = file_get_contents($imagenes['tmp_name'][$i]);
            $imagen_base64 = base64_encode($imagen_data);

            // Insertar la imagen en la tabla `publicidad_imagenes`
            $query_imagen = "INSERT INTO publicidad_imagenes (id_publicacion, imagen) VALUES (?, ?)";
            $stmt_imagen = $conn->prepare($query_imagen);
            $stmt_imagen->bind_param("is", $id_publicacion, $imagen_base64);
            $stmt_imagen->execute();
        }
    }

    // Redirigir a la página de publicaciones con un mensaje de éxito
    header("Location: dashboard-publicaciones.php?success=1");
    exit();
} else {
    echo "Error al insertar en publicaciones: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
