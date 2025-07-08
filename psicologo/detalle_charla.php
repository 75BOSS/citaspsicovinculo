<?php
session_start();
include '../conexion.php';


// Verificar que el usuario está autenticado y es psicólogo
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'psicologo') {
    echo "Acceso no autorizado.";
    exit();
}

$id_psicologo = $_SESSION['id'];

// Validar parámetro
if (!isset($_GET['id'])) {
    echo "Charla no especificada.";
    exit();
}

$id_charla = intval($_GET['id']);

// Verificar que la charla le pertenezca al psicólogo
$stmt = $conn->prepare("SELECT * FROM charlas WHERE id = ? AND id_psicologo = ?");
$stmt->bind_param("ii", $id_charla, $id_psicologo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "No tienes acceso a esta charla.";
    exit();
}

$charla = $resultado->fetch_assoc();
?>



<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle de la Charla</title>
  <link rel="stylesheet" href="estilos_psicologo/detalle-charla-psicologo.css">
  <link rel="stylesheet" href="estilos_psicologo/panel1-psicologo.css">
    <link rel="stylesheet" href="estilos_psicologo/index-psicologo.css">

</head>
<body>
<?php include 'header_psicologo.php'; ?>


  <main>
    <section class="detalle-charla">
      <h3><?= htmlspecialchars($charla['titulo']) ?></h3>
      <p><strong>Fecha:</strong> <?= $charla['fecha'] ?></p>
      <p><strong>Hora:</strong> <?= substr($charla['hora_inicio'], 0, 5) . ' - ' . substr($charla['hora_fin'], 0, 5) ?></p>
      <p><strong>Cupo Máximo:</strong> <?= $charla['cupo_maximo'] ?></p>
      <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($charla['descripcion'] ?? 'No especificada')) ?></p>

      <div class="botones-acciones">
        <a href="editar_charla.php?id=<?= $charla['id'] ?>" class="boton-editar">✏️ Editar</a>
        <a href="cancelar_charla.php?id=<?= $charla['id'] ?>" class="boton-cancelar" onclick="return confirm('¿Estás seguro de cancelar esta charla?')">❌ Cancelar</a>
      </div>
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
