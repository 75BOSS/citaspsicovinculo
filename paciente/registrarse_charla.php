<?php
session_start();
include '../conexion.php';

// Verificar si hay sesión activa y si es un paciente
if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'paciente') {
  echo "Acceso denegado.";
  exit;
}

// Obtener el ID de la charla desde el parámetro GET
$id_charla = $_GET['id_charla'] ?? null;

if ($id_charla) {
  // Verificar que la charla exista
  $stmt = $conexion->prepare("SELECT * FROM charlas WHERE id = ?");
  $stmt->bind_param("i", $id_charla);
  $stmt->execute();
  $result = $stmt->get_result();
  
  if ($result->num_rows > 0) {
    // La charla existe, registrar al paciente
    $id_paciente = $_SESSION['id'];
    
    // Insertar en la tabla de reservas o similar
    $stmt = $conexion->prepare("INSERT INTO reservas (id_usuario, id_charla) VALUES (?, ?)");
    $stmt->bind_param("ii", $id_paciente, $id_charla);
    
    if ($stmt->execute()) {
      // Redirigir con mensaje de éxito
      header("Location: index_paciente.php?success=1");
    } else {
      // Error al insertar
      echo "Hubo un error al registrar la charla.";
    }
  } else {
    echo "La charla no existe.";
  }
} else {
  echo "No se ha proporcionado un ID de charla.";
}
?>
