<?php
include '../conexion.php';
session_start();

function nivelExperiencia($charlas) {
    if ($charlas >= 10) return "⭐ Experto";
    if ($charlas >= 5) return "🔰 Intermedio";
    return "🌱 Principiante";
}

$_SESSION['usuario_id'] = 8; // Asegúrate de tener un usuario con ID 1

$id_psicologo = $_SESSION['usuario_id'];

// Verificamos que haya conexión y el usuario exista
$stmt = $conexion->prepare("SELECT id, nombre, correo, telefono, foto, codigo_estudiante FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_psicologo);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    die("No se encontró un usuario con el ID $id_psicologo");
}

$foto = isset($usuario['foto']) ? trim($usuario['foto']) : '';
$ruta_foto = (!empty($foto)) ? $foto : 'imagen_psicologo/descarga.jpeg';

// Charlas impartidas
$stmt2 = $conexion->prepare("SELECT COUNT(*) as total FROM charlas WHERE id_psicologo = ?");
$stmt2->bind_param("i", $id_psicologo);
$stmt2->execute();
$total_charlas = $stmt2->get_result()->fetch_assoc()['total'] ?? 0;

$experiencia = nivelExperiencia($total_charlas);

// Última charla
$stmt3 = $conexion->prepare("SELECT titulo, fecha FROM charlas WHERE id_psicologo = ? ORDER BY fecha DESC LIMIT 1");
$stmt3->bind_param("i", $id_psicologo);
$stmt3->execute();
$ultima_charla = $stmt3->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Cuenta</title>
  <link rel="stylesheet" href="estilos_psicologo/index-psicologo.css">
    <link rel="stylesheet" href="estilos_psicologo/cuenta-psicologo.css">

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>

<?php include 'header-psicologo.php'; ?>

<main>
  <section class="tarjeta">
    <h3>Tarjeta de presentación</h3>
    <img src="<?= htmlspecialchars($ruta_foto) ?>" alt="Foto de perfil" class="foto-perfil-grande">
    <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']) ?></p>
    <p><strong>Correo:</strong> <?= htmlspecialchars($usuario['correo']) ?></p>
    <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono']) ?></p>
    <p><strong>Código Estudiante:</strong> <?= htmlspecialchars($usuario['codigo_estudiante']) ?></p>
    <p><strong>Charlas impartidas:</strong> <?= $total_charlas ?> (<?= $experiencia ?>)</p>
    <p><strong>Última charla:</strong> <?= $ultima_charla ? htmlspecialchars($ultima_charla['titulo']) . ' (' . $ultima_charla['fecha'] . ')' : 'Aún no hay charlas' ?></p>
    <a href="editar-perfil.php" class="boton-editar">✏️ Editar Perfil</a>
    <a href="charlas-impartidas.php" class="boton-charlas">📋 Ver Charlas Impartidas</a>
  </section>
</main>
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
        </div>
    </div>
    
    <div class="footer-bottom">
        <p>&copy; 2025 Psicovínculo. Todos los derechos reservados.</p>
    </div>

</footer>
</body>
</html>
