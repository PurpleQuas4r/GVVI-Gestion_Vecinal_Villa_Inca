<?php
// dashboard-Admin.php

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



// Consultas para obtener datos desde la base de datos
// Obtener número de vecinos (usuarios)
$sqlVecinos = "SELECT COUNT(*) AS total_vecinos FROM usuario";
$stmtVecinos = $pdo->prepare($sqlVecinos);
$stmtVecinos->execute();
$totalVecinos = $stmtVecinos->fetch(PDO::FETCH_ASSOC)['total_vecinos'];

// Obtener número de socios (usuarios cuyo rol es 'socio')
$sqlSocios = "SELECT COUNT(*) AS total_socios FROM usuario WHERE rol = 'socio'";
$stmtSocios = $pdo->prepare($sqlSocios);
$stmtSocios->execute();
$totalSocios = $stmtSocios->fetch(PDO::FETCH_ASSOC)['total_socios'];

// Obtener número de usuarios creados en los últimos 30 días
$sqlUsuariosRecientes = "SELECT COUNT(*) AS usuarios_recientes FROM usuario WHERE fecha_ingreso >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
$stmtUsuariosRecientes = $pdo->prepare($sqlUsuariosRecientes);
$stmtUsuariosRecientes->execute();
$usuariosRecientes = $stmtUsuariosRecientes->fetch(PDO::FETCH_ASSOC)['usuarios_recientes'];

// Obtener número total de solicitudes pendientes
$sqlSolicitudes = "SELECT COUNT(*) AS total_solicitudes FROM certificado WHERE estado_solicitud = 'pendiente'";
$stmtSolicitudes = $pdo->prepare($sqlSolicitudes);
$stmtSolicitudes->execute();
$totalSolicitudes = $stmtSolicitudes->fetch(PDO::FETCH_ASSOC)['total_solicitudes'];

// Obtener número de solicitudes pendientes creadas en el último mes
$sqlSolicitudesRecientes = "SELECT COUNT(*) AS solicitudes_recientes FROM certificado WHERE estado_solicitud = 'pendiente' AND fecha_solicitud >= DATE_SUB(NOW(), INTERVAL 1 MONTH)";
$stmtSolicitudesRecientes = $pdo->prepare($sqlSolicitudesRecientes);
$stmtSolicitudesRecientes->execute();
$solicitudesRecientes = $stmtSolicitudesRecientes->fetch(PDO::FETCH_ASSOC)['solicitudes_recientes'];

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
    <header>
        <div class="logo" href="https://villaportaloriente.com/dashboard-Admin.php">
            <img src="assets/img/logo_large.png" alt="Logo GVVI">
        </div>
        <div class="hamburger" id="hamburger">&#9776;</div>
        <div class="user-actions">
            <button class="icon-button profile-button" title="Ver perfil">
                <img src="assets/icons/perfil.png" alt="Perfil">
            </button>
            <button class="icon-button logout-button" title="Cerrar sesión" onclick="window.location.href='logout.php'">
                <img src="assets/icons/logout.png" alt="Cerrar sesión">
            </button>
        </div>
    </header>

    <div class="container">
        <nav id="sidebar">
            <div class="user-info">
                <img src="assets/icons/user-icon.png" alt="User Icon" class="user-icon">
                <p><?php echo htmlspecialchars($userName); ?></p> <!-- Muestra el nombre del usuario -->
                <span>Bienvenido</span>
            </div>
            <ul>
                <li>
                    <a href="dashboard-Admin.php" class="menu-item">
                        <img src="assets/icons/home-icon.png" alt="Inicio" class="icon"> 
                        Inicio 
                        <span class="chevron"></span>
                    </a>
                </li>
                <li>
                    <a href="gestion/dashboard-gestion.php" class="menu-item no-prevent">
                        <img src="assets/icons/users-icon.png" alt="Gestionar Usuarios" class="icon"> 
                        Gestionar Usuarios 
                        <span class="chevron"></span>
                    </a>
                </li>
                <li>
                    <a href="#" class="menu-item">
                        <img src="assets/icons/reservation-icon.png" alt="Reserva de Espacios" class="icon"> 
                        Ver pagos mensuales
                        <span class="chevron"></span>
                    </a>
                </li>
                <li>
                    <a href="#" class="menu-item">
                        <img src="assets/icons/document-icon.png" alt="Certificados" class="icon"> 
                        Certificados 
                        <span class="chevron"></span>
                    </a>
                </li>
                <li>
                    <a href="publicaciones/dashboard-publicaciones.php" class="menu-item">
                        <img src="assets/icons/publish-icon.png" alt="Publicaciones" class="icon"> 
                        Publicaciones 
                        <span class="chevron"></span>
                    </a>
                </li>
                <li>
                    <a href="#" class="menu-item">
                        <img src="assets/icons/proyecto-icon.png" alt="Proyectos Vecinales" class="icon"> 
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
                        <img src="assets/icons/team-icon.png" alt="Proyectos Comunitarios" class="icon"> 
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
                        <img src="assets/icons/reservation-icon.png" alt="Reserva de Espacios" class="icon"> 
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

        <div class="main-content">
            <!-- Sección de estadísticas -->
            <div class="stats-cards">
                <div class="stats-card">
                    <div>
                        <h4>N° de Vecinos</h4>
                        <h2><?php echo $totalVecinos; ?></h2> <!-- Datos dinámicos -->
                        
                        <!-- Mostrar status solo si hay usuarios creados hace al menos un mes -->
                        <?php if ($usuariosRecientes > 0): ?>
                        <div class="status">
                            <span class="increase"><?php echo "+{$usuariosRecientes}"; ?></span>
                            <p>Desde el último mes</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="icon-container">
                        <img src="assets/icons/User_icon-removebg-preview.png" alt="Vecinos">
                    </div>
                </div>
                <div class="stats-card">
                    <div>
                        <h4>N° de Socios</h4>
                        <h2><?php echo $totalSocios; ?></h2> <!-- Datos dinámicos -->
                        
                        <!-- Mostrar status solo si hay usuarios creados hace al menos un mes -->
                        <?php if ($usuariosRecientes > 0): ?>
                        <div class="status">
                            <span class="increase"><?php echo "+{$usuariosRecientes}"; ?></span>
                            <p>Desde el último mes</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="icon-container">
                        <img src="assets/icons/Partner_icon-removebg-preview (1).png" alt="Socios">
                    </div>
                </div>
                <div class="stats-card">
                    <div>
                        <h4>Solicitudes Pendientes</h4>
                        
                        <!-- Mostrar 0 en el h2 si no hay solicitudes recientes -->
                        <h2><?php echo ($solicitudesRecientes > 0) ? $totalSolicitudes : 0; ?></h2>

                        <!-- Mostrar incremento o mensaje de 'Sin solicitudes nuevas' -->
                        <?php if ($solicitudesRecientes > 0): ?>
                        <div class="status">
                            <span class="increase"><?php echo "+{$solicitudesRecientes}"; ?></span>
                            <p>Desde el último mes</p>
                        </div>
                        <?php else: ?>
                        <div class="status">
                            <p>Sin solicitudes nuevas</p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="icon-container">
                        <img src="assets/icons/Document_icon-removebg-preview.png" alt="Solicitudes">
                    </div>
                </div>
            </div>

            <!-- Código para las otras tarjetas -->
            <main>
                <div class="card">
                    <div class="image-container">
                        <img src="assets/icons/sample-icon.png" alt="Imagen">
                    </div>
                    <h4>Certificados</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <button>Ver más</button>
                </div>
                <div class="card">
                    <div class="image-container">
                        <img src="assets/icons/sample-icon.png" alt="Imagen">
                    </div>
                    <h4>Usuarios</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <button>Ver más</button>
                </div>
                <div class="card">
                    <div class="image-container">
                        <img src="assets/icons/sample-icon.png" alt="Imagen">
                    </div>
                    <h4>Publicaciones</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <button>Ver más</button>
                </div>
                <div class="card">
                    <div class="image-container">
                        <img src="assets/icons/sample-icon.png" alt="Imagen">
                    </div>
                    <h4>Proyectos Vecinales</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <button>Ver más</button>
                </div>
                <div class="card">
                    <div class="image-container">
                        <img src="assets/icons/sample-icon.png" alt="Imagen">
                    </div>
                    <h4>Proyectos Comunitarios</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <button>Ver más</button>
                </div>
                <div class="card">
                    <div class="image-container">
                        <img src="assets/icons/sample-icon.png" alt="Imagen">
                    </div>
                    <h4>Reservar Espacios</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <button>Ver más</button>
                </div>
                <div class="card">
                    <div class="image-container">
                        <img src="assets/icons/sample-icon.png" alt="Imagen">
                    </div>
                    <h4>Ver pagos</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    <button>Ver más</button>
                </div>
                
            </main>
        </div>
    </div>
</body>
</html>
