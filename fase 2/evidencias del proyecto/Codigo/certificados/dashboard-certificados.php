<?php
// dashboard-certificados.php
session_start(); // Iniciar la sesión

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

// Consultar el nombre completo del usuario (nombre + apellido)
$sqlUserName = "SELECT CONCAT(nombre, ' ', apellido) AS nombre_completo FROM usuario WHERE id = :user_id";
$stmtUserName = $pdo->prepare($sqlUserName);
$stmtUserName->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtUserName->execute();
$userName = $stmtUserName->fetch(PDO::FETCH_ASSOC)['nombre_completo'];

// Si no se encuentra el usuario, redirigir al login
if (!$userName) {
    header("Location: /Login/login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proyecto Villa Portal Oriente</title>
    <link rel="icon" href="icons/logoGV.png" type="image/x-icon">
    <link rel="shortcut icon" href="icons/logoGV.png" type="image/x-icon">
    <script src="script.js" defer></script>
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
                <p><?php echo htmlspecialchars($userName); ?></p> <!-- Nombre dinámico -->
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
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Contenedor para la barra de búsqueda y el título -->
            <div class="certificate-header">
                <h2>Solicitudes de Certificados</h2>
                <div class="search-bar-container">
                    <div class="search-bar">
                        <button class="btn search-btn">Buscar</button>
                        <input type="text" placeholder="Buscar por nombre o tipo de certificado">
                    </div>
                    <button class="btn issue-btn">Emitir Certificado</button>
                </div>
            </div>
        
            <!-- Tabla de certificados -->
            <div class="certificate-table-container">
                <table class="certificate-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo Solicitud</th>
                            <th>Solicitud de</th>
                            <th>Estado Solicitud</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>10/10/2024</td>
                            <td>Residencia</td>
                            <td>[Nombre usuario]</td>
                            <td>Pendiente</td>
                            <td><button class="btn action-btn">Atender</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal para Atender Certificado -->
    <div id="certificateModal" class="modal">
        <div class="modal-content">
            <button class="close-modal-btn" aria-label="Cerrar modal">&times;</button>
            <h2>Atender Certificado</h2>
            <div class="modal-body">
                <div class="modal-left">
                    <label>Solicitante</label>
                    <input type="text" value="[nombre usuario]" readonly>
    
                    <label>Dirección</label>
                    <input type="text" value="[Dirección]" readonly>
    
                    <label>Estado Actual</label>
                    <input type="text" value="Pendiente" readonly>
    
                    <label>Tipo de Certificado</label>
                    <input type="text" value="Residencia" readonly>
    
                    <label>N° telefónico solicitante</label>
                    <input type="text" value="[N° telefónico]" readonly>
                </div>
                <div class="modal-right">
                    <p>Solicitado el:</p>
                    <h3>10/10/2024</h3>
                    <p>Imagen Adjunta (Comprobante)</p>
                    <div class="image-placeholder"></div>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn accept-btn">Aceptar</button>
                <button class="btn reject-btn">Rechazar</button>
            </div>
        </div>
    </div>    
</body>
</html>
