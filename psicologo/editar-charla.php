<?php
session_start();
include '../conexion.php';



$id_psicologo = $_SESSION['usuario_id'];

if (!isset($_GET['id'])) {
    echo "Charla no especificada.";
    exit();
}

$id_charla = intval($_GET['id']);

// Verifica que la charla pertenezca al psicólogo
$stmt = $conexion->prepare("SELECT * FROM charlas WHERE id = ? AND id_psicologo = ?");
$stmt->bind_param("ii", $id_charla, $id_psicologo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "No tienes permiso para editar esta charla.";
    exit();
}

$charla = $result->fetch_assoc();

// Obtener auditorios disponibles
$aud_result = $conexion->query("SELECT id, nombre FROM auditorios");
$auditorios = $aud_result->fetch_all(MYSQLI_ASSOC);

// Procesar actualización
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $titulo = $_POST['titulo'];
    $fecha = $_POST['fecha'];
    $hora_inicio = $_POST['hora_inicio'];
    $hora_fin = $_POST['hora_fin'];
    $cupo_maximo = $_POST['cupo_maximo'];
    $descripcion = $_POST['descripcion'];
    $id_auditorio = $_POST['id_auditorio'];

    $stmt_upd = $conexion->prepare("
        UPDATE charlas SET 
        titulo = ?, fecha = ?, hora_inicio = ?, hora_fin = ?, 
        cupo_maximo = ?, descripcion = ?, id_auditorio = ?
        WHERE id = ? AND id_psicologo = ?
    ");
    $stmt_upd->bind_param("ssssissii", 
        $titulo, $fecha, $hora_inicio, $hora_fin, 
        $cupo_maximo, $descripcion, $id_auditorio, 
        $id_charla, $id_psicologo
    );
    $stmt_upd->execute();

    header("Location: detalle-charla.php?id=$id_charla");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Charla</title>
    <link rel="stylesheet" href="estilos_psicologo/panel1-psicologo.css">
  <link rel="stylesheet" href="estilos_psicologo/index-psicologo.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<?php include 'header-psicologo.php'; ?>

<main>
  <form method="post">
    <label>Título:</label>
    <input type="text" name="titulo" required value="<?= htmlspecialchars($charla['titulo']) ?>">

    <label>Fecha:</label>
    <input type="date" name="fecha" required value="<?= $charla['fecha'] ?>">

    <label>Hora de Inicio:</label>
    <input type="time" name="hora_inicio" required value="<?= substr($charla['hora_inicio'], 0, 5) ?>">

    <label>Hora de Fin:</label>
    <input type="time" name="hora_fin" required value="<?= substr($charla['hora_fin'], 0, 5) ?>">

    <label>Cupo Máximo:</label>
    <input type="number" name="cupo_maximo" required min="1" value="<?= $charla['cupo_maximo'] ?>">

    <label>Auditorio:</label>
    <select name="id_auditorio" required>
      <?php foreach ($auditorios as $aud): ?>
        <option value="<?= $aud['id'] ?>" <?= $aud['id'] == $charla['id_auditorio'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($aud['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select>

    <label>Descripción:</label>
    <textarea name="descripcion" rows="5"><?= htmlspecialchars($charla['descripcion']) ?></textarea>

    <button type="submit">💾 Guardar Cambios</button>
  </form>

  <a href="detalle-charla.php?id=<?= $id_charla ?>" class="volver">← Volver</a>
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

</footer>s
</body>
</html>
