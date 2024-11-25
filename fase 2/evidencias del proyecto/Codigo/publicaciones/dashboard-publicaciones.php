<?php
// dashboard-publicaciones.php
session_start();
include('db.php');

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirige al login si no hay sesión
    exit();
}

$usuario_creador = $_SESSION['user_id'];

// Consultar los datos completos del usuario autenticado
$user_query = "SELECT * FROM usuario WHERE id = ?";
$user_stmt = $conn->prepare($user_query);
$user_stmt->bind_param("i", $usuario_creador);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows > 0) {
    $user = $user_result->fetch_assoc();
    $userName = $user['nombre']; // Ajusta esto según el campo en la base de datos
    // Imprimir los datos del usuario en la consola
    echo "<script>console.log(" . json_encode($user) . ");</script>";
} else {
    die("Error: Usuario no encontrado.");
}


// Consulta para obtener todas las publicaciones
$query = "SELECT * FROM publicaciones";
$result = mysqli_query($conn, $query);

// Consulta para contar el número total de publicaciones
$totalQuery = "SELECT COUNT(*) as total FROM publicaciones";
$totalResult = mysqli_query($conn, $totalQuery);
$totalPublicaciones = mysqli_fetch_assoc($totalResult)['total'];

// Consulta para contar el número de publicaciones activas
$activeQuery = "SELECT COUNT(*) as active FROM publicaciones WHERE estado = 'Activa'";
$activeResult = mysqli_query($conn, $activeQuery);
$publicacionesActivas = mysqli_fetch_assoc($activeResult)['active'];
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
                <p><?php echo htmlspecialchars($userName); ?></p>
                <span>Bienvenido</span>
            </div>
            <ul>
                <li><a href="../dashboard-Admin.php" class="menu-item"><img src="icons/home-icon.png" alt="Inicio" class="icon"> Inicio <span class="chevron"></span></a></li>
                <li><a href="../gestion/dashboard-gestion.php" class="menu-item"><img src="icons/users-icon.png" alt="Gestionar Usuarios" class="icon"> Gestionar Usuarios <span class="chevron"></span></a></li>
                <li><a href="#" class="menu-item"><img src="icons/reservation-icon.png" alt="Reserva de Espacios" class="icon"> Ver pagos mensuales <span class="chevron"></span></a></li>
                <li><a href="#" class="menu-item"><img src="icons/document-icon.png" alt="Certificados" class="icon"> Certificados <span class="chevron"></span></a></li>
                <li><a href="#" class="menu-item"><img src="icons/publish-icon.png" alt="Publicaciones" class="icon"> Publicaciones <span class="chevron">&#8250;</span></a></li>
                <!-- Agrega más elementos de menú según sea necesario -->
            </ul>
        </nav>

        <!-- Contenido principal -->
        <div class="main-content">
            
            <!-- Mensaje de éxito o error -->
            <?php if (isset($_GET['success'])): ?>
                <p class="success-message">¡La publicidad se creó exitosamente!</p>
            <?php elseif (isset($_GET['error'])): ?>
                <p class="error-message">Hubo un error al crear la publicidad. Inténtalo de nuevo.</p>
            <?php endif; ?>
            
            <!-- Carta con información y barra de búsqueda -->
            <div class="card-container">
                <div class="card">
                    <div class="card-info">
                        <h4>N° de Publicaciones Totales</h4>
                        <div class="card-details">
                            <h2><?php echo $totalPublicaciones; ?></h2>
                        </div>
                    </div>
                    <div class="card-info">
                        <h4>N° de Publicaciones Activas</h4>
                        <div class="card-details">
                            <h2><?php echo $publicacionesActivas; ?></h2>
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

            <!-- CRUD de Publicaciones -->
            <div class="crud-container">
                <div class="table-responsive">
                    <table class="crud-table">
                        <thead>
                            <tr>
                                <th>ID Publicación</th>
                                <th>Usuario Creador</th>
                                <th>Título</th>
                                <th>Mensaje</th>
                                <th>Remitente</th>
                                <th>Fecha Publicación</th>
                                <th>Estado</th>
                                <th>Notificación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Verificar si hay resultados
                            if (mysqli_num_rows($result) > 0) {
                                // Recorrer cada registro y mostrarlo en la tabla
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['id_publicacion']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['usuario_creador']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['titulo']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['mensaje']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['remitente']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['fecha_publicacion']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['notificacion']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No hay publicaciones disponibles</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal para creación de publicidad -->
    <div id="modal-publicidad" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Crear Publicidad</h2>
            <form id="form-publicidad" method="POST" action="guardar_publicidad.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" name="titulo" required>
                </div>
            <div class="form-group">
                <label for="mensaje">Mensaje</label>
                <textarea id="mensaje" name="mensaje" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="remitente">Firma/Remitente</label>
                <input type="text" id="remitente" name="remitente" required>
            </div>
            <div class="form-group">
                <label>Imágenes (máximo 4)</label>
                <input type="file" name="imagenes[]" accept="image/*" multiple id="imagenes" max="4">
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" id="notificar" name="notificar">
                    Enviar notificación por correo a todos los usuarios
                </label>
            </div>
            <div class="form-group">
                <button type="submit" class="submit-btn">Crear Publicidad</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>

<script>
    // JavaScript para abrir y cerrar el modal
    document.querySelector('.add-button').addEventListener('click', function() {
        document.getElementById('modal-publicidad').style.display = 'block';
    });

    document.querySelector('.close-modal').addEventListener('click', function() {
        document.getElementById('modal-publicidad').style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target == document.getElementById('modal-publicidad')) {
            document.getElementById('modal-publicidad').style.display = 'none';
        }
    });
</script>
