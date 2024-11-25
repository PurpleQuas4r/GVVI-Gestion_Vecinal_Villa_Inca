<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'db.php'; // Incluir la conexión a la base de datos

header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id'])) {
        $user_id = intval($_POST['user_id']);

        // Eliminar registros en tablas dependientes que hacen referencia a actividad_comunitaria
        $deleteDependencies = [
            "DELETE FROM inscripcion_actividad WHERE id_actividadComunitaria IN (SELECT id_actividadComunitaria FROM actividad_comunitaria WHERE id_usuario = $user_id)",
            "DELETE FROM actividad_comunitaria WHERE id_usuario = $user_id",
            "DELETE FROM proyecto_vecinal WHERE id_usuario = $user_id",
            "DELETE FROM certificado WHERE id_usuario = $user_id",
            "DELETE FROM carga_familiar WHERE id_usuario = $user_id",
            "DELETE FROM notificacion WHERE id_usuario = $user_id",
            "DELETE FROM reserva_espacio WHERE id_usuario = $user_id"
        ];

        // Ejecutar cada consulta para eliminar dependencias
        foreach ($deleteDependencies as $query) {
            if (!mysqli_query($conn, $query)) {
                echo json_encode(["status" => "error", "message" => "Error al eliminar dependencias: " . mysqli_error($conn)]);
                exit();
            }
        }

        // Eliminar el usuario una vez que no tiene dependencias
        $query = "DELETE FROM usuario WHERE id = $user_id";
        if (mysqli_query($conn, $query)) {
            echo json_encode(["status" => "success", "message" => "Usuario eliminado correctamente"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al eliminar el usuario: " . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ID de usuario no proporcionado"]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido"]);
}
?>
