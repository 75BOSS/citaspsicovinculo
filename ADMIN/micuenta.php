<?php
include '../conexion.php';
session_start();

// Verifica si hay sesión activa
if (!isset($_SESSION['id'])) {
  echo "No has iniciado sesión.";
  exit;
}

$id_usuario = $_SESSION['id'];

// Si se envió el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nuevo_nombre = trim($_POST['nombre'] ?? '');

  if ($nuevo_nombre !== '') {
    $stmt = $conn->prepare("UPDATE usuarios SET nombre = ? WHERE id = ?");
    $stmt->bind_param("si", $nuevo_nombre, $id_usuario);
    $stmt->execute();
    $stmt->close();
  }
}

// Obtener datos del usuario
$stmt = $conn->prepare("SELECT nombre, correo, rol FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$res = $stmt->get_result();
$usuario = $res->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mi Cuenta - Administrador</title>
  <link rel="stylesheet" href="css/mi_perfil.css">
</head>
<body>
  <header class="header">
    <a href="index.php">
      <img src="imagen/logo.png" alt="Logo Psicovínculo" class="logo">
    </a>
    <h1>Mi Cuenta</h1>
  </header>

  <main class="perfil">
    <form method="POST" class="formulario">
      <label>Nombre:
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
      </label>

      <p><strong>Correo:</strong> <?php echo htmlspecialchars($usuario['correo']); ?></p>
      <p><strong>Rol:</strong> <?php echo htmlspecialchars($usuario['rol']); ?></p>

      <button type="submit">Guardar cambios</button>
    </form>
  </main>
</body>
</html>
