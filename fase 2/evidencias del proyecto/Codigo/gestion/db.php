<?php
$host = 'localhost';
$db = 'villapor_GVVI';
$user = 'villapor';
$password = '7#xSWU9za93;Ww';

// Crear la conexión con mysqli
$conn = mysqli_connect($host, $user, $password, $db);

// Verificar la conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>
