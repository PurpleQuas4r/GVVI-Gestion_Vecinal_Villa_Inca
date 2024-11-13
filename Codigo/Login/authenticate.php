<?php
// Archivo: authenticate.php
require 'db.php'; // Importamos la conexión a la base de datos

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); // Aseguramos que la sesión se inicie al principio del script

// Comprobamos si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Preparamos la consulta para buscar el email en la base de datos
        $stmt = $pdo->prepare('SELECT * FROM usuario WHERE correo_electronico = :email');
        $stmt->bindParam(':email', $email);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificamos si se encontró un usuario
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Si se encontró el usuario, verificamos la contraseña sin hash
                if ($password === $user['password']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['logged_in'] = true;
                    header('Location: /dashboard-Admin.php');
                    exit();
                } else {
                    echo "Contraseña incorrecta.";
            }
        } else {
            // No se encontró el usuario
            echo "Usuario no encontrado.";
        }
    } catch (PDOException $e) {
        // Capturamos errores de la base de datos y los imprimimos en la consola
        echo "Error en la base de datos: " . $e->getMessage();
        die("Error en la base de datos.");
    }
} else {
    // Si no se envió el formulario, redirigimos al formulario de login
    header('Location: login.php');
    exit();
}
?>
