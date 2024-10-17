<?php
// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'crud_db');

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Insertar o Actualizar Socio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'];
    $rut = $_POST['rut'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];

    if ($id) {
        // Actualizar
        $sql = "UPDATE socios SET nombre='$nombre', rut='$rut', telefono='$telefono', correo='$correo' WHERE id=$id";
    } else {
        // Insertar nuevo socio
        $sql = "INSERT INTO socios (nombre, rut, telefono, correo) VALUES ('$nombre', '$rut', '$telefono', '$correo')";
    }

    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('Socio Registrado o Actualizado');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conexion->error;
    }
}

// Eliminar Socio
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conexion->query("DELETE FROM socios WHERE id=$id");
    echo "<script>alert('Socio Eliminado');</script>";
}

// Leer Socios (para mostrar en la tabla)
$result = $conexion->query("SELECT * FROM socios");

// Cerrar la conexión
$conexion->close();
?>
