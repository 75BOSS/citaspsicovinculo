<?php
session_start();
include '../conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'paciente') {
  echo "Acceso denegado.";
  exit;
}

$id_charla = $_GET['id_charla'];
$id_paciente = $_SESSION['id'];

// Validar si ya está registrado
$verificar = $conn->prepare("SELECT * FROM asistencias WHERE id_charla = ? AND id_paciente = ?");
$verificar->bind_param("ii", $id_charla, $id_paciente);
$verificar->execute();
$verificacion = $verificar->get_result();

if ($verificacion->num_rows > 0) {
  echo "Ya estás registrado en esta charla.";
} else {
  $insertar = $conn->prepare("INSERT INTO asistencias (id_charla, id_paciente) VALUES (?, ?)");
  $insertar->bind_param("ii", $id_charla, $id_paciente);
  if ($insertar->execute()) {
    header("Location: index_paciente.php?registro=exito");
    exit;
  } else {
    echo "Error al registrarse.";
  }
}
?>
