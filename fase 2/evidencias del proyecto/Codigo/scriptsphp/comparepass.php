<?php
$hash_en_base_datos = 'h$2y$10$194gOUFWzWYl1uc6F.2PK.sZEi58nRysvI9V1G1fp96Ym7Z2qgKXa'; // Copia el hash desde la base de datos
$password_ingresada = 'userPass';

if (password_verify($password_ingresada, $hash_en_base_datos)) {
    echo "¡Contraseña verificada!";
} else {
    echo "Contraseña incorrecta.";
}

?>
