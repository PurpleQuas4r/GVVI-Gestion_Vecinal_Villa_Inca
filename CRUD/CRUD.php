<?php
// Conexión a la base de datos
$conexion = new mysqli('localhost', 'root', '', 'crud_db');

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Insertar o Actualizar Socio
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $rut = $_POST['rut'];
    $nombre_completo = $_POST['nombre_completo'];
    $apellido_completo = $_POST['apellido_completo'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $oficio_profecion = $_POST['oficio_profecion'];

    if ($id) {
        // Actualizar
        $sql = "UPDATE socios SET rut='$rut', nombre_completo='$nombre_completo', apellido_completo='$apellido_completo', direccion='$direccion' telefono='$telefono', 
                correo='$correo', fecha_nacimiento='$fecha_nacimiento', oficio_profecion='$oficio_profecion' WHERE id=$id";
    } else {
        // Insertar nuevo socio
        $sql = "INSERT INTO socios (rut, nombre_completo, apellido_completo, direccion, telefono, correo, fecha_nacimiento, oficio_profecion) 
                VALUES ('$rut', '$nombre_completo', '$apellido_completo', '$direccion', '$telefono', '$correo', '$fecha_nacimiento', '$oficio_profecion')";
    }

    if ($conexion->query($sql) === TRUE) {
        echo "<script>alert('El Socio a sido Registrado o Actualizado');</script>";
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
