<?php
session_start();
include '../conexion.php';


// Verificar que el usuario esté autenticado y sea psicólogo
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'psicologo') {
  echo "<h2 style='color: red; text-align: center; margin-top: 50px;'>Acceso no autorizado.</h2>";
  exit;
}

$id_psicologo = $_SESSION['id'];

// Consultar charlas del psicólogo autenticado
$query = "
  SELECT 
    c.id, c.titulo, c.fecha, c.hora_inicio, c.hora_fin, c.cupo_maximo,
    a.nombre AS auditorio
  FROM charlas c
  JOIN auditorios a ON c.id_auditorio = a.id
  WHERE c.id_psicologo = ?
  ORDER BY c.fecha DESC
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_psicologo);
$stmt->execute();
$resultado = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Charlas Impartidas</title>
  <link rel="stylesheet" href="estilos_psicologo/charlas-com-psicologo.css">
     <link rel="stylesheet" href="estilos_psicologo/index-psicologo.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


</head>
<body>

<?php include 'header_psicologo.php'; ?>



<main>
  <table class="tabla-charlas">
    <thead>
      <tr>
        <th>Título</th>
        <th>Fecha</th>
        <th>Horario</th>
        <th>Auditorio</th>
        <th>Cupo Máximo</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($fila = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($fila['titulo']) ?></td>
          <td><?= $fila['fecha'] ?></td>
          <td><?= substr($fila['hora_inicio'], 0, 5) ?> - <?= substr($fila['hora_fin'], 0, 5) ?></td>
          <td><?= $fila['auditorio'] ?></td>
          <td><?= $fila['cupo_maximo'] ?></td>
        <td style="min-width: 160px;">
  <a href="ver_reservas.php?id=<?= $fila['id'] ?>">👥 Ver asistentes</a>
</td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
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
