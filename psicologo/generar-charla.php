<?php
session_start();
include '../conexion.php';

// Verificar sesión de psicólogo
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'psicologo') {
  echo "Acceso denegado.";
  exit;
}

// Cargar auditorios
$aud_result = $conexion->query("SELECT id, nombre FROM auditorios");
$hay_auditorios = $aud_result && $aud_result->num_rows > 0;

// Cargar tags
$tags_result = $conexion->query("SELECT id, nombre FROM tags");
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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<?php include 'header-psicologo.php'; ?>

<main>
  <form action="procesar-charla.php" method="POST">
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
    <input type="time" name="hora_inicio" required>

    <label>Hora de fin:</label>
    <input type="time" name="hora_fin" required>

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

<?php include 'footer-psicologo.php'; ?>

<?php if (isset($_GET['success'])): ?>
<script>
  Swal.fire({
    icon: 'success',
    title: '¡Charla creada!',
    text: 'La charla fue registrada exitosamente.',
    confirmButtonText: 'OK'
  }).then(() => {
    window.location.href = 'index-psicologo.php';
  });
</script>
<?php elseif (isset($_GET['error'])): ?>
<script>
  Swal.fire({
    icon: 'error',
    title: 'Error',
    text:
      <?= json_encode(
        $_GET['error'] === 'cruce' ? 'Ya existe una charla en ese auditorio y horario.' :
        ($_GET['error'] === 'datos' ? 'Faltan datos del formulario.' : 'Error al guardar la charla.')
      ) ?>,
    confirmButtonText: 'OK'
  });
</script>
<?php endif; ?>

</body>
</html>
