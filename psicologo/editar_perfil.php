<?php
session_start();
include '../conexion.php';

// Verificar que el usuario esté autenticado y sea psicólogo
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'psicologo') {
    die("Acceso no autorizado.");
}

$id_psicologo = $_SESSION['id'];

// Obtener los datos actuales del usuario
$stmt = $conn->prepare("SELECT nombre, correo, foto, telefono FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $id_psicologo);
$stmt->execute();
$usuario = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Perfil</title>
  <link rel="stylesheet" href="estilos_psicologo/editarpefil-psicologo.css">
  <link rel="stylesheet" href="estilos_psicologo/index-psicologo.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

</head>
<body>
<?php include 'header_psicologo.php'; ?>


<main>
  <section class="formulario-edicion">
    <form action="actualizar_perfil.php" method="POST">
      <label>Nombre:</label>
      <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>

      <label>Correo:</label>
      <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>

      <label>Teléfono:</label>
      <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>">

      <label>Foto de perfil (URL):</label>
      <input type="text" name="foto" value="<?= htmlspecialchars($usuario['foto']) ?>">

      <button type="submit">Guardar Cambios</button>
    </form>
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
</body>
</html>
