<?php
// Mostrar errores para depuración (puedes quitar esto en producción)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Incluir la conexión
require_once 'conexion.php';

// Recoger y sanitizar datos
$rol = $_POST['rol'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$correo = $_POST['correo'] ?? '';
$pass = $_POST['pass'] ?? '';
$cedula = $_POST['cedula'] ?? '';
$telefono = $_POST['telefono'] ?? null;
$codigo_estudiante = ($rol === "psicologo") ? ($_POST['codigo_estudiante'] ?? null) : null;
$activo = ($rol === "psicologo") ? 0 : 1;

// Verificar si el correo ya está registrado
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Este correo ya está registrado. <a href='login.php'>Iniciar sesión</a>";
    exit;
}
$stmt->close();

// Encriptar contraseña
$passHash = password_hash($pass, PASSWORD_DEFAULT);

// Insertar usuario nuevo
$stmt = $conn->prepare("INSERT INTO usuarios (nombre, correo, contraseña, rol, cedula, telefono, codigo_estudiante, activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssi", $nombre, $correo, $passHash, $rol, $cedula, $telefono, $codigo_estudiante, $activo);

if ($stmt->execute()) {
    echo "Registro exitoso. <a href='login.php'>Iniciar sesión</a>";
} else {
    echo "Error al registrar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
