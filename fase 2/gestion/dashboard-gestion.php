<?php
// dashboard-gestion.php

session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Verificar si el usuario está logueado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
    // Si no está logueado o no tiene ID en la sesión, redirigir a la página de login
    header("Location: /Login/login.html");
    exit();
}

// Incluir la conexión a la base de datos
require 'db.php';

// Obtener el ID del usuario desde la sesión
$user_id = $_SESSION['user_id'];

// Consultar el nombre completo del usuario (nombre + apellido) usando mysqli
$sqlUserName = "SELECT CONCAT(nombre, ' ', apellido) AS nombre_completo FROM usuario WHERE id = $user_id";
$resultUserName = mysqli_query($conn, $sqlUserName);

if ($resultUserName) {
    $rowUserName = mysqli_fetch_assoc($resultUserName);
    $userName = $rowUserName['nombre_completo'];
} else {
    // Si no se encuentra el usuario, redirigir al login
    header("Location: /Login/login.html");
    exit();
}

// Consultas a la BD
$query = "SELECT id, rut, nombre, apellido, direccion, telefono, correo_electronico, password, fecha_ingreso, rol FROM usuario";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error en la consulta: " . mysqli_error($conn)); // Esto mostrará el error si la consulta falla.
}

// Consultar el número total de vecinos
$query_total = "SELECT COUNT(*) as total_vecinos FROM usuario";
$result_total = mysqli_query($conn, $query_total);
$total_vecinos = mysqli_fetch_assoc($result_total)['total_vecinos'];

// Consultar los nuevos vecinos creados en el último mes
$query_new = "SELECT COUNT(*) as nuevos_vecinos FROM usuario WHERE fecha_ingreso >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
$result_new = mysqli_query($conn, $query_new);
$nuevos_vecinos = mysqli_fetch_assoc($result_new)['nuevos_vecinos'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto Villa Portal Oriente</title>

    <!-- Añadir el favicon -->
    <link rel="icon" href="icons/logoGV.png" type="image/x-icon">
    <link rel="shortcut icon" href="icons/logoGV.png" type="image/x-icon">

    <script defer src="script.js"></script>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="logo" href="https://villaportaloriente.com/dashboard-Admin.php">
            <img src="icons/logo_large.png" alt="Logo GVVI">
        </div>
        <div class="hamburger" id="hamburger">&#9776;</div>
        <div class="user-actions">
            <button class="icon-button profile-button" title="Ver perfil">
                <img src="icons/perfil.png" alt="Perfil">
            </button>
            <button class="icon-button logout-button" title="Cerrar sesión" onclick="window.location.href='logout.php'">
                <img src="icons/logout.png" alt="Cerrar sesión">
            </button>
        </div>
    </header>

    <!-- Sidebar -->
    <div class="container">
        <nav id="sidebar">
            <div class="user-info">
                <img src="icons/user-icon.png" alt="User Icon" class="user-icon">
                <p><?php echo htmlspecialchars($userName); ?></p> <!-- Muestra el nombre del usuario -->
                <span>Bienvenido</span>
            </div>
            <ul>
                <li><a href="../dashboard-Admin.php" class="menu-item"><img src="icons/home-icon.png" alt="Inicio" class="icon"> Inicio <span class="chevron"></span></a></li>
                <li><a href="#" class="menu-item"><img src="icons/users-icon.png" alt="Gestionar Usuarios" class="icon"> Gestionar Usuarios <span class="chevron"></span></a></li>
                <li><a href="#" class="menu-item"><img src="icons/reservation-icon.png" alt="Reserva de Espacios" class="icon"> Ver pagos mensuales <span class="chevron"></span></a></li>
                <li><a href="#" class="menu-item"><img src="icons/document-icon.png" alt="Certificados" class="icon"> Certificados <span class="chevron"></span></a></li>
                <li>
                    <a href="#" class="menu-item"><img src="icons/publish-icon.png" alt="Publicaciones" class="icon"> Publicaciones <span class="chevron">&#8250;</span></a>
                    <ul class="submenu">
                        <li><a href="#">Nueva Publicación</a></li>
                        <li><a href="#">Historial de Publicaciones</a></li>
                    </ul>
                </li>
                                <li>
                    <a href="#" class="menu-item">
                        <img src="icons/proyecto-icon.png" alt="Proyectos Vecinales" class="icon"> 
                        Proyectos Vecinales 
                        <span class="chevron">&#8250;</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="#">Proyecto 1</a></li>
                        <li><a href="#">Proyecto 2</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-item">
                        <img src="icons/team-icon.png" alt="Proyectos Comunitarios" class="icon"> 
                        Proyectos Comunitarios 
                        <span class="chevron">&#8250;</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="#">Comunidad 1</a></li>
                        <li><a href="#">Comunidad 2</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-item">
                        <img src="icons/reservation-icon.png" alt="Reserva de Espacios" class="icon"> 
                        Reserva de Espacios 
                        <span class="chevron">&#8250;</span>
                    </a>
                    <ul class="submenu">
                        <li><a href="#">Reservar Sala</a></li>
                        <li><a href="#">Historial de Reservas</a></li>
                    </ul>
                </li>
                <!-- Agrega más elementos de menú según sea necesario -->
            </ul>
        </nav>

        <!-- Estadísticas / Buscador -->
        <div class="main-content">
            <div class="card-container">
                <div class="card">
                    <div class="card-info">
                        <h4>N° de Vecinos</h4>
                        <div class="card-details">
                            <h2><?php echo $total_vecinos; ?></h2>
                            <?php if ($nuevos_vecinos > 0): ?>
                            <div class="status">
                                <span class="increase">+<?php echo $nuevos_vecinos; ?></span>
                                <p>Nuevos este mes</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="card-actions">
                        <form method="GET" action="" style="display: flex; align-items: center;">
                            <input type="text" name="search" class="search-input" placeholder="Ej: Miguel Donoso o 12345678-9" style="flex: 1; padding: 8px; margin-right: 10px; border: 1px solid #ccc; border-radius: 4px;">
                            <button type="submit" class="search-button" style="background-color: #007bff; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer;">Buscar</button>
                        </form>
                        <div class="add-button" style="margin-left: 10px;">
                            <img src="plus_icon.png" alt="Agregar" style="cursor: pointer;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- CRUD de Usuarios -->
            <div class="crud-container">
                <div class="table-responsive">
                    <table class="crud-table">
                        <thead>
                            <tr>
                                <th>Rut</th>
                                <th>Nombre completo</th>
                                <th>Direccion</th>
                                <th>Telefono</th>
                                <th>Correo</th>
                                <th>Fecha Ingreso</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['rut'] . "</td>";
                            echo "<td>" . $row['nombre'] . " " . $row['apellido'] . "</td>";
                            echo "<td>" . $row['direccion'] . "</td>";
                            echo "<td>" . $row['telefono'] . "</td>";
                            echo "<td>" . $row['correo_electronico'] . "</td>";
                            echo "<td>" . $row['fecha_ingreso'] . "</td>";
                            echo "<td>" . $row['rol'] . "</td>";
                            echo '<td>
                                    <button class="edit-btn" 
                                            title="Editar"
                                            data-id="' . $row['id'] . '" 
                                            data-nombre="' . $row['nombre'] . '" 
                                            data-direccion="' . $row['direccion'] . '" 
                                            data-telefono="' . $row['telefono'] . '" 
                                            data-correo="' . $row['correo_electronico'] . '" 
                                            data-rol="' . $row['rol'] . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20px" height="20px" fill="#ffffff">
                                            <path d="M2 17.25V21h3.75l11.06-11.06-3.75-3.75L2 17.25zM21.41 6.34c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0L15.13 4.21l3.75 3.75 2.53-2.53z"/>
                                        </svg>
                                    </button>
                                    <button class="delete-btn" 
                                            title="Borrar"
                                            data-nombre="' . $row['nombre'] . ' ' . $row['apellido'] . '" 
                                            data-direccion="' . $row['direccion'] . '">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20px" height="20px" fill="#ffffff">
                                            <path d="M16 9v10H8V9h8m-1-6H9V3H5v2h14V3h-4z"/>
                                        </svg>
                                    </button>
                                  </td>';
                            echo "</tr>";
                        }
                        ?>
                        </tbody>
                    </table>

                    <!-- Modal para Editar Usuario -->
                    <div id="editUserModal" class="modal">
                        <div class="modal-content">
                            <span class="close-btn">&times;</span>
                            <h2>Editar Usuario</h2>
                            <form id="editUserForm">
                                <input type="hidden" id="editUserId" name="user_id"> <!-- Campo oculto para el ID -->
                                
                                <label for="editName">Nombre:</label>
                                <input type="text" id="editName" name="nombre" required>
                            
                                <label for="editAddress">Dirección:</label>
                                <input type="text" id="editAddress" name="direccion" required>
                            
                                <label for="editPhone">Teléfono:</label>
                                <input type="tel" id="editPhone" name="telefono" required>
                            
                                <label for="editEmail">Correo Electrónico:</label>
                                <input type="email" id="editEmail" name="correo" required>
                            
                                <label for="editRole">Rol:</label>
                                <select id="editRole" name="rol">
                                    <option value="admin">Admin</option>
                                    <option value="vecino">Vecino</option>
                                    <option value="socio">Socio</option>
                                    <option value="superUser">SuperUser</option>
                                </select>
                            
                                <div class="modal-buttons">
                                    <button type="button" id="editConfirmBtn" class="confirm-btn">Confirmar</button>
                                    <button type="button" class="cancel-btn">Cancelar</button>
                                </div>
                            </form>

                        </div>
                    </div>

                    <!-- Modal para Eliminar Usuario -->
                    <div id="deleteUserModal" class="modal">
                        <div class="modal-content">
                            <h2>Confirmar Eliminación</h2>
                            <p>Estás a punto de eliminar al usuario:</p>
                            <p><strong>Nombre:</strong> <span id="deleteUserName"></span></p>
                            <p><strong>Dirección:</strong> <span id="deleteUserAddress"></span></p>
                            <p>Para borrar el usuario, escribe la palabra 'BORRAR'</p>
                            <input type="text" id="deleteConfirmInput" placeholder="BORRAR" />

                            <div class="modal-buttons">
                                <button class="delete-btn" 
                                    data-id="<?php echo $user['id']; ?>" 
                                    data-nombre="<?php echo $user['nombre']; ?>" 
                                    data-direccion="<?php echo $user['direccion']; ?>">
                                    Eliminar
                                </button>

                                <button type="button" class="cancel-btn-delete">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</body>
</html>
