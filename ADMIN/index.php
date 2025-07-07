<?php
include '../conexion.php';  

// Mostrar errores durante desarrollo
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Consultas
$totalUsuarios = $conn->query("SELECT COUNT(*) AS total FROM usuarios WHERE activo = 1")->fetch_assoc()['total'] ?? 0;
$totalPacientes = $conn->query("SELECT COUNT(*) AS total FROM usuarios WHERE rol = 'paciente' AND activo = 1")->fetch_assoc()['total'] ?? 0;
$totalPsicologos = $conn->query("SELECT COUNT(*) AS total FROM usuarios WHERE rol = 'psicologo' AND activo = 1")->fetch_assoc()['total'] ?? 0;
$totalCharlas = $conn->query("SELECT COUNT(*) AS total FROM charlas")->fetch_assoc()['total'] ?? 0;
$ultima = $conn->query("SELECT MAX(fecha_registro) as fecha FROM usuarios")->fetch_assoc()['fecha'] ?? '--';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
<header class="header">
    <nav class="navbar">
        <div class="logo">
            <img src="imagen/logo.png" alt="Psicovínculo" class="logo-psicovinculo" />
            <span>Psicovínculo</span>
        </div>
        <ul class="nav-centrado">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="usuarios.php">Usuarios</a></li>
            <li><a href="auditorios.php">Auditorios</a></li>
            <li><a href="charlas.php">Charlas</a></li>
        </ul>
        <div class="perfil">
            <a href="micuenta.php" title="Mi Cuenta">
                <img src="imagen/perfil.png" alt="Perfil" class="icono-perfil">
            </a>
        </div>
    </nav>
</header>

<div class="dashboard-container">
    <h1 class="dashboard-title">Bienvenido Administrador</h1>
    <p class="dashboard-subtitle">Resumen general del sistema y próximas charlas.</p>

    <div class="stats-grid">
        <div class="stat-card usuarios">
            <div class="stat-header">
                <div class="stat-icon usuarios">👥</div>
                <div class="stat-title">Total de Usuarios</div>
            </div>
            <div class="stat-number"><?php echo $totalUsuarios; ?></div>
        </div>

        <div class="stat-card pacientes">
            <div class="stat-header">
                <div class="stat-icon pacientes">🤝</div>
                <div class="stat-title">Total de Pacientes</div>
            </div>
            <div class="stat-number"><?php echo $totalPacientes; ?></div>
        </div>

        <div class="stat-card psicologos">
            <div class="stat-header">
                <div class="stat-icon psicologos">👩‍⚕️</div>
                <div class="stat-title">Total de Psicólogos</div>
            </div>
            <div class="stat-number"><?php echo $totalPsicologos; ?></div>
        </div>

        <div class="stat-card charlas">
            <div class="stat-header">
                <div class="stat-icon charlas">💬</div>
                <div class="stat-title">Total de Charlas</div>
            </div>
            <div class="stat-number"><?php echo $totalCharlas; ?></div>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>INFORMACIÓN</h3>
            <p><i class="fas fa-map-marker-alt"></i> Av. Isabel La Católica N. 23-52 y Madrid.</p>
            <p><i class="fas fa-phone"></i> <a href="tel:0960951729">0960951729</a></p>
            <p><i class="fas fa-envelope"></i> <a href="mailto:fabian.carsoia@ups.edu.co">fabian.carsoia@ups.edu.co</a></p>
        </div>

        <div class="footer-section">
            <h3>ATENCIÓN</h3>
            <p><i class="far fa-clock"></i> LUNES A VIERNES</p>
            <p>9:00 AM - 17:00 PM</p>
        </div>

        <div class="footer-section">
            <h3>NUESTROS SERVICIOS</h3>
            <ul class="services-list">
                <li>Tratamientos de Ansiedad</li>
                <li>Terapia para Depresión</li>
                <li>Manejo del Estrés</li>
                <li>Terapia para Crisis de Pánico</li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>REDES SOCIALES</h3>
            <div class="social-icons">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            </div>
            <div class="footer-link">
                <a href="https://www.deservicios.es/plos/muestras-redes-sociales-deservicios-totamacion" target="_blank">
                    Más información sobre nuestros servicios
                </a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 Psicovínculo. Todos los derechos reservados.</p>
    </div>
</footer>

</body>
</html>
