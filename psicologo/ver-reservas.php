<?php
session_start();
include '../conexion.php';


$id_psicologo = $_SESSION['usuario_id'];

if (!isset($_GET['id'])) {
    echo "Charla no especificada.";
    exit();
}

$id_charla = intval($_GET['id']);

// Verificar que la charla pertenece al psicólogo
$stmt_verif = $conexion->prepare("SELECT titulo FROM charlas WHERE id = ? AND id_psicologo = ?");
$stmt_verif->bind_param("ii", $id_charla, $id_psicologo);
$stmt_verif->execute();
$res_verif = $stmt_verif->get_result();

if ($res_verif->num_rows === 0) {
    echo "No tienes permiso para ver esta charla.";
    exit();
}

$titulo_charla = $res_verif->fetch_assoc()['titulo'];

// Obtener asistentes (reservas de pacientes)
$stmt = $conexion->prepare("
    SELECT u.nombre, u.correo, r.fecha_reserva, r.estado
    FROM reservas r
    JOIN usuarios u ON r.id_paciente = u.id
    WHERE r.id_charla = ?
");
$stmt->bind_param("i", $id_charla);
$stmt->execute();
$reservas = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Asistentes a la Charla</title>
  <link rel="stylesheet" href="estilos_psicologo/charlas-com-psicologo.css">
    <link rel="stylesheet" href="estilos_psicologo/index-psicologo.css">
       <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<?php include 'header-psicologo.php'; ?>


<main>
<?php if ($reservas->num_rows > 0): ?>
  <table class="tabla-charlas">
    <thead>
      <tr>
        <th>Nombre</th>
        <th>Correo</th>
        <th>Fecha de Reserva</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($fila = $reservas->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($fila['nombre']) ?></td>
          <td><?= htmlspecialchars($fila['correo']) ?></td>
          <td><?= $fila['fecha_reserva'] ?></td>
          <td>
            <span class="estado <?= strtolower($fila['estado']) ?>">
              <?= htmlspecialchars(ucfirst($fila['estado'])) ?>
            </span>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
<?php else: ?>
  <p style="text-align:center;">Esta charla aún no tiene asistentes registrados.</p>
<?php endif; ?>
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
            <div class="footer-link">
                <a href="servicios.html" target="_blank">
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
