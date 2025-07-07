<?php
session_start();
include '../conexion.php';

// Validar acceso del psicólogo
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'psicologo') {
  echo "Acceso denegado.";
  exit;
}

$id_psicologo = $_SESSION['id'];

// Consulta segura
$sql = "
  SELECT c.id, c.titulo, c.fecha, c.hora_inicio, c.estado, a.nombre AS auditorio
  FROM charlas c
  INNER JOIN auditorios a ON c.id_auditorio = a.id
  WHERE c.id_psicologo = ?
  ORDER BY c.fecha DESC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
  die("Error en la consulta: " . $conn->error);
}
$stmt->bind_param("i", $id_psicologo);
$stmt->execute();
$resultado = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Charlas Impartidas</title>
  <link rel="stylesheet" href="estilos_psicologo/charlas-impartidas.css">
</head>
<body>

<header>
  <div class="logo-container">
    <img src="../imagen/logo.png" alt="Logo" class="logo-img">
    <div class="logo-text">Psicovínculo</div>
  </div>
  <div class="titulo-centro">Charlas Impartidas</div>
  <nav>
    <a href="index_psicologo.php">Inicio</a>
    <a href="logout.php">Cerrar sesión</a>
  </nav>
</header>

<main>
  <table class="tabla-charlas">
    <thead>
      <tr>
        <th>Título</th>
        <th>Fecha</th>
        <th>Hora</th>
        <th>Auditorio</th>
        <th>Estado</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($charla = $resultado->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($charla['titulo']) ?></td>
          <td><?= $charla['fecha'] ?></td>
          <td><?= substr($charla['hora_inicio'], 0, 5) ?></td>
          <td><?= htmlspecialchars($charla['auditorio']) ?></td>
          <td><span class="estado <?= strtolower($charla['estado']) ?>"><?= ucfirst($charla['estado']) ?></span></td>
          <td><a href="detalle-charla.php?id=<?= $charla['id'] ?>">Ver Detalles</a></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</main>