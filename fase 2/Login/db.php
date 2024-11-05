<?php
// Archivo: db.php

$host = 'localhost';
$db = 'villapor_GVVI'; // Nombre de la base de datos
$user = 'villapor'; // Usuario de MySQL
$password = '7#xSWU9za93;Ww'; // Contrasena de MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}
?>
