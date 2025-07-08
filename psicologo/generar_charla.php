<?php
session_start();
include '../conexion.php';

session_start();
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'psicologo') {
    header("Location: login.php");
    exit;
}

$id_psicologo = $_SESSION['id'];


// Cargar auditorios
$aud_result = $conn->query("SELECT id, nombre FROM auditorios");
$hay_auditorios = $aud_result && $aud_result->num_rows > 0;

// Cargar tags
$tags_result = $conn->query("SELECT id, nombre FROM tags");
$hay_tags = $tags_result && $tags_result->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Generar Charla</title>
    <link rel="stylesheet" href="estilos_psicologo/panel1-psicologo.css">
<link rel="stylesheet" href="estilos_psicologo/index-psicologo.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php if (isset($_GET['success'])): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      Swal.fire({
        icon: 'success',
        title: '¡Charla creada!',
        text: 'La charla se registró exitosamente.',
        confirmButtonText: 'Aceptar'
      }).then(() => {
        window.location.href = 'index_psicologo.php';
      });
    });
  </script>
<?php elseif (isset($_GET['error'])): ?>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      Swal.fire({
        icon: 'error',
        title: '¡Error!',
        text: `<?= $_GET['error'] === 'cruce' ? 'Ya hay una charla en ese horario y auditorio.' : 'Error al crear la charla.' ?>`,
        confirmButtonText: 'Aceptar'
      });
    });
  </script>
<?php endif; ?>


<?php include 'header_psicologo.php'; ?>

<main>
  <form action="procesar_charla.php" method="POST" id="form-charla">
    <label>Título:</label>
    <input type="text" name="titulo" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios.">

    <label>Descripción:</label>
    <textarea name="descripcion" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+" title="Solo se permiten letras y espacios."></textarea>

    <label>Auditorio:</label>
    <select name="id_auditorio" required>
      <option value="">Seleccione un auditorio</option>
      <?php if ($hay_auditorios): ?>
        <?php while ($a = $aud_result->fetch_assoc()): ?>
          <option value="<?= $a['id'] ?>"><?= htmlspecialchars($a['nombre']) ?></option>
        <?php endwhile; ?>
      <?php else: ?>
        <option disabled>No hay auditorios disponibles</option>
      <?php endif; ?>
    </select>

    <label>Fecha:</label>
    <input type="date" name="fecha" required min="<?= date('Y-m-d') ?>">

    <label>Hora de inicio:</label>
    <input type="time" name="hora_inicio" id="hora_inicio" required>

    <label>Hora de fin:</label>
    <input type="time" name="hora_fin" id="hora_fin" required>

    <label>Tags relacionados:</label>
    <div class="tags-container">
      <?php if ($hay_tags): ?>
        <?php while ($tag = $tags_result->fetch_assoc()): ?>
          <label>
            <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>">
            <?= htmlspecialchars($tag['nombre']) ?>
          </label>
        <?php endwhile; ?>
      <?php else: ?>
        <p style="color: red; font-weight: bold;">⚠️ No hay tags disponibles en el sistema.</p>
      <?php endif; ?>
    </div>

    <label>Cupo máximo:</label>
    <input type="text" name="cupo_maximo" pattern="^[0-9]{1,3}$" maxlength="3" title="Solo se permiten números del 0 al 999" required>

    <button type="submit">Crear Charla</button>
  </form>
</main>

<script>
  // Validación hora fin > hora inicio
  const form = document.getElementById("form-charla");
  const horaInicio = document.getElementById("hora_inicio");
  const horaFin = document.getElementById("hora_fin");

  form.addEventListener("submit", function (e) {
    if (horaInicio.value && horaFin.value && horaFin.value <= horaInicio.value) {
      alert("⚠️ La hora de fin debe ser mayor a la hora de inicio.");
      e.preventDefault();
    }
  });

  // Opcional: ajustar el mínimo de hora_fin automáticamente
  horaInicio.addEventListener("change", function () {
    horaFin.min = horaInicio.value;
  });
</script>

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

  <!-- SweetAlert2 de respuesta -->
  <?php if (isset($_GET['success'])): ?>
    <script>
      Swal.fire({
        icon: 'success',
        title: '¡Charla creada!',
        text: 'La charla fue registrada exitosamente.',
        confirmButtonText: 'OK'
      });
    </script>
  <?php elseif (isset($_GET['error'])): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text:
          <?php
            switch ($_GET['error']) {
              case 'cruce': echo "'Ya existe una charla en ese auditorio y horario.'"; break;
              case 'datos': echo "'Faltan datos del formulario.'"; break;
              case 'sql': default: echo "'Error al guardar la charla.'"; break;
            }
          ?>,
        confirmButtonText: 'OK'
      });
    </script>
  <?php endif; ?>

</body>
</html>
