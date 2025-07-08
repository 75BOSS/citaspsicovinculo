<?php
session_start();
include '../conexion.php';
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'paciente') {
    echo "Acceso denegado.";
    exit;
}
// Procesar registro a charla
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_charla'])) {
    $id_charla = intval($_POST['id_charla']);
    $id_paciente = $_SESSION['id'];
    $verificar = $conn->prepare("SELECT id FROM reservas WHERE id_charla = ? AND id_paciente = ?");
    $verificar->bind_param("ii", $id_charla, $id_paciente);
    $verificar->execute();
    $resultado = $verificar->get_result();
    if ($resultado->num_rows === 0) {
        $insertar = $conn->prepare("INSERT INTO reservas (id_charla, id_paciente, estado) VALUES (?, ?, 'confirmada')");
        $insertar->bind_param("ii", $id_charla, $id_paciente);
        $insertar->execute();
        $insertar->close();
    }
    $verificar->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Charlas Disponibles</title>
  <style>
    body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f7f7f7; color: #333; }
    header { background: white; padding: 20px 50px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 5px rgba(0,0,0,0.1); }
    header img { height: 40px; }
    header nav a { margin: 0 15px; text-decoration: none; color: #333; font-weight: bold; }
    .contenedor { padding: 40px; }
    .titulo { font-size: 28px; margin-bottom: 20px; color: #6b3fa0; }
    .tarjetas { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .tarjeta { background: white; padding: 20px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .tarjeta h3 { color: #6b3fa0; margin-top: 0; }
    .tarjeta p { margin: 5px 0; }
    .etiquetas { margin-top: 10px; }
    .etiqueta { display: inline-block; background: #e0d7f5; color: #6b3fa0; padding: 4px 8px; margin: 3px; border-radius: 8px; font-size: 13px; }
    .tarjeta form { margin-top: 15px; }
    .tarjeta button {
      padding: 8px 15px;
      background-color: #6b3fa0;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }
    .tarjeta button:hover {
      background-color: #502d85;
    }
  </style>
</head>
<body>
  <header>
    <div style="display:flex; align-items:center;">
      <img src="../imagen/logo.png" alt="Logo Psicovínculo">
      <span style="margin-left:10px; font-size: 20px; color: #6b3fa0; font-weight: bold;">Psicovínculo</span>
    </div>
    <nav>
      <a href="index_paciente.php">Inicio</a>
      <a href="#">Mi cuenta</a>
      <a href="#">Charlas</a>
      <a href="logout.php">Cerrar sesión</a>
    </nav>
  </header>

  <div class="contenedor">
    <h1 class="titulo">Charlas Disponibles</h1>
    <div class="tarjetas">
      <?php while ($charla = $charlas->fetch_assoc()): ?>
        <div class="tarjeta">
          <h3><?= htmlspecialchars($charla['titulo']) ?></h3>
          <p><strong>Fecha:</strong> <?= $charla['fecha'] ?> <?= substr($charla['hora_inicio'], 0, 5) ?></p>
          <p><strong>Auditorio:</strong> <?= $charla['auditorio'] ?></p>
          <p><strong>Psicólogo:</strong> <?= $charla['psicologo'] ?></p>

          <div class="etiquetas">
            <?php
              $id_charla = $charla['id'];
              $etiquetas = $conn->query("
                SELECT t.nombre FROM charla_tags ct
                JOIN tags t ON ct.id_tag = t.id
                WHERE ct.id_charla = $id_charla
              ");
              while ($etiqueta = $etiquetas->fetch_assoc()):
            ?>
              <span class="etiqueta"><?= htmlspecialchars($etiqueta['nombre']) ?></span>
            <?php endwhile; ?>
          </div>

          <!-- Botón Registrarme -->
          <form method="POST" method="GET">
            <input type="hidden" name="id_charla" value="<?= $charla['id'] ?>">
            <button type="submit">Registrarme</button>
          </form>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>
