<?php
include '../conexion.php';
session_start();

if (!isset($_SESSION['id'])) {
  echo "No has iniciado sesión.";
  exit;
}
$id_empresa = $_SESSION['empresa_id'];

// Si enviaron cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nuevo_nombre = trim($_POST['nombre'] ?? '');

  if ($nuevo_nombre !== '') {
    $stmt = $conexion->prepare("UPDATE empresas SET nombre = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_nombre, $id_empresa);
    $stmt->execute();
    $stmt->close();
  }
}

// Obtener datos actualizados
$stmt = $conexion->prepare("SELECT nombre, correo, logo, plan FROM empresas WHERE id = ?");
$stmt->bind_param("i", $id_empresa);
$stmt->execute();
$res = $stmt->get_result();
$empresa = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Perfil</title>
  <link rel="stylesheet" href="css/mi_perfil.css">
</head>
<body>
  <header class="header">
    <a href="index.php">
      <img src="imagen/logo.png" alt="Logo Psicovínculo" class="logo">
    </a>
    <h1>Mi Perfil</h1>
  </header>

  <main class="perfil">
    <div class="logo-empresa">
      <?php if ($empresa['logo']) : ?>
        <img src="logos/<?php echo $empresa['logo']; ?>" alt="Logo de la empresa">
      <?php else : ?>
        <p>Sin logo cargado.</p>
      <?php endif; ?>
    </div>

    <form method="POST" class="formulario">
      <label>Nombre:
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($empresa['nombre']); ?>" required>
      </label>

      <p><strong>Correo:</strong> <?php echo htmlspecialchars($empresa['correo']); ?></p>
      <p><strong>Plan actual:</strong> <?php echo htmlspecialchars($empresa['plan']); ?></p>

      <button type="submit">Guardar cambios</button>
    </form>
  </main>
</body>
</html>