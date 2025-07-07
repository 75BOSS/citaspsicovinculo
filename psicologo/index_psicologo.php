<?php
session_start();
include '../conexion.php';

// Verificar sesión
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'psicologo') {
    echo "Acceso denegado.";
    exit;
}

$id_psicologo = $_SESSION['id'];

// Obtener datos del psicólogo
$stmt = $conn->prepare("SELECT nombre, correo, telefono FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_psicologo);
$stmt->execute();
$result = $stmt->get_result();
$perfil = $result->fetch_assoc();

// Total de charlas
$total_result = $conn->query("SELECT COUNT(*) as total FROM charlas WHERE id_psicologo = $id_psicologo");
$total_row = $total_result->fetch_assoc();
$total = $total_row['total'] ?? 0;

// Próxima charla
$prox_result = $conn->query("SELECT * FROM charlas WHERE id_psicologo = $id_psicologo AND fecha >= CURDATE() ORDER BY fecha ASC, hora_inicio ASC LIMIT 1");
$prox_charla = $prox_result->fetch_assoc();

// Próximas charlas
$proximas_charlas = $conn->query("SELECT * FROM charlas WHERE id_psicologo = $id_psicologo AND fecha >= CURDATE() ORDER BY fecha ASC LIMIT 5");

// Charlas por mes
$charlas_por_mes = array_fill(1, 12, 0);
$sql_mes = "SELECT MONTH(fecha) as mes, COUNT(*) as cantidad 
            FROM charlas 
            WHERE id_psicologo = $id_psicologo 
              AND YEAR(fecha) = YEAR(CURDATE())
            GROUP BY MONTH(fecha)";
$result_mes = $conn->query($sql_mes);
while ($row = $result_mes->fetch_assoc()) {
    $charlas_por_mes[(int)$row['mes']] = (int)$row['cantidad'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Panel del Psicólogo</title>
     <link rel="stylesheet" href="estilos_psicologo/temas-psicologo.css">
   <link rel="stylesheet" href="estilos_psicologo/index-psicologo.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>
<?php include 'header-psicologo.php'; ?>


  <main class="panel-main">
    <section class="bienvenida">
      <h2>Bienvenido, <?= htmlspecialchars($perfil['nombre'] ?? 'Psicólogo') ?></h2>
      <p class="intro">Resumen de tu actividad, próximas charlas y estado de tu perfil.</p>
      <div class="stats">
        <div class="stat-box">
          <h3>Charlas Programadas</h3>
          <p><?= $total ?></p>
        </div>
        <div class="stat-box">
          <h3>Próxima Charla</h3>
          <p><?= $prox_charla ? $prox_charla['fecha'] . ' - ' . substr($prox_charla['hora_inicio'], 0, 5) : 'Sin charlas' ?></p>
        </div>
        <div class="stat-box">
          <h3>Total de Charlas</h3>
          <p><?= $total ?></p>
        </div>
        <div class="stat-box">
          <h3>Última Actualización</h3>
          <p><?= date('d M Y') ?></p>
        </div>
      </div>
    </section>

<section class="temas-disponibles">
  <h3>Temas Disponibles</h3>
  <div class="temas-grid">
    <div class="tema-card">
      <img src="imagen_psicologo/depresion-psicologo.jpg" alt="Depresión">
      <h4>DEPRESIÓN</h4>
      <p>Supera síntomas depresivos y recupera la alegría de vivir.</p>
      <a href="generar_charla.php?tema=Depresión">Programar Charla</a>
    </div>
    <div class="tema-card">
      <img src="imagen_psicologo/icono_ansiedad-psicologo.jpg" alt="Ansiedad">
      <h4>ANSIEDAD</h4>
      <p>Maneja la ansiedad y el estrés con herramientas eficaces.</p>
      <a href="generar_charla.php?tema=Ansiedad">Programar Charla</a>
    </div>
    <div class="tema-card">
      <img src="imagen_psicologo/icono_autoestima-psicologo.jpg" alt="Autoestima">
      <h4>PROBLEMAS DE AUTOESTIMA</h4>
      <p>Fortalece tu autoimagen y relación contigo mismo.</p>
      <a href="generar_charla.php?tema=icono_autoestima">Programar Charla</a>
    </div>
<div class="tema-card">
  <img src="imagen_psicologo/icono_traumayabuso-psicologo.jpg" alt="Trauma y abuso">
  <h4>Trauma y Abuso</h4>
  <p>Aprende a reconocer, afrontar y sanar experiencias traumáticas y de abuso emocional o físico.</p>
  <a href="generar_charla.php?tema=traumayabuso">Programar Charla</a>
</div>

<div class="tema-card">
  <img src="imagen_psicologo/icono_trastornoalimenticio-psicologo.jpg" alt="Trastorno alimenticio">
  <h4>Trastornos Alimenticios</h4>
  <p>Aborda la relación con la comida y el cuerpo desde un enfoque de salud mental y autocuidado.</p>
  <a href="generar_charla.php?tema=trastornoalimenticio">Programar Charla</a>
</div>

<div class="tema-card">
  <img src="imagen_psicologo/icono_adicciones-psicologo.jpg" alt="Adicciones">
  <h4>Adicciones</h4>
  <p>Comprende los mecanismos de la adicción y descubre estrategias efectivas para la prevención y recuperación.</p>
  <a href="generar_charla.php?tema=adicciones">Programar Charla</a>
</div>

  </div>
</section>




    <section class="estadisticas-graficas">
      <h3>📊 Charlas por Mes (<?= date('Y') ?>)</h3>
      <canvas id="graficoCharlas" width="600" height="250"></canvas>
    </section>

    <div class="alerta">
      <i class="fas fa-bell"></i> <?= $prox_charla ? "Tienes una charla el " . $prox_charla['fecha'] . " a las " . substr($prox_charla['hora_inicio'], 0, 5) : "Sin charlas programadas mañana" ?>
    </div>

    <section class="charlas">
      <h3>Charlas Próximas</h3>
      <?php while ($charla = $proximas_charlas->fetch_assoc()): ?>
        <div class="charla-box">
          <h4><?= htmlspecialchars($charla['titulo']) ?></h4>
          <p><strong>Fecha:</strong> <?= $charla['fecha'] ?></p>
          <p><strong>Hora:</strong> <?= substr($charla['hora_inicio'], 0, 5) ?></p>
          <p><strong>Cupo Máximo:</strong> <?= htmlspecialchars($charla['cupo_maximo']) ?></p>
          <a class="ver-detalles" href="detalle-charla.php?id=<?= $charla['id'] ?>">Ver Detalles</a>
        </div>
      <?php endwhile; ?>
    </section>


    <section class="recordatorio">
      <h3><i class="fas fa-bullhorn"></i> Recordatorio</h3>
      <p>No olvides actualizar tu perfil si cambias de número o correo institucional.</p>
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

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const datosCharlas = <?= json_encode(array_values($charlas_por_mes)) ?>;
    const etiquetas = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

    const ctx = document.getElementById('graficoCharlas').getContext('2d');
    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: etiquetas,
        datasets: [{
          label: 'Charlas impartidas',
          data: datosCharlas,
          backgroundColor: '#6b3fa0'
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          title: { display: true, text: 'Charlas por mes' }
        },
        scales: {
          y: {
            beginAtZero: true,
            precision: 0,
            stepSize: 1
          }
        }
      }
    });
  </script>
</body>
</html>
