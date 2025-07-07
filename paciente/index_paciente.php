<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'paciente') {
    header('Location: login.php');
    exit();
}

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio - Paciente</title>
    <link rel="stylesheet" href="estilos.css"> <!-- Si tienes CSS personalizado -->
</head>
<body>
    <!-- Barra de navegación -->
    <nav>
        <ul>
            <li><a href="index_paciente.php">Inicio</a></li>
            <li><a href="reservar_charla.php">Reservar charla</a></li>
            <li><a href="mi_cuenta.php">Mi cuenta</a></li>
            <li><a href="logout.php">Cerrar sesión</a></li>
        </ul>
    </nav>

    <main>
        <h1>Bienvenido, <?= htmlspecialchars($usuario['nombre']) ?> 👋</h1>
        <p>Desde aquí puedes buscar y reservar charlas impartidas por nuestros psicólogos.</p>

        <!-- Puedes añadir una lista destacada de charlas próximas o recomendaciones personalizadas -->
        <section>
            <h2>Charlas destacadas</h2>
            <p>(Aquí podrías cargar dinámicamente las charlas más recientes o populares)</p>
        </section>
    </main>
</body>
</html>
