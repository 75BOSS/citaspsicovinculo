<?php
include 'conexion.php'; // tu archivo de conexión

$rol = $_POST['rol'];
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
$cedula = $_POST['cedula'];
$telefono = $_POST['telefono'] ?? null;
$codigo_estudiante = ($rol == 'psicologo') ? $_POST['codigo_estudiante'] : null;

// Por defecto, los psicólogos no estarán activos hasta que el admin lo apruebe
$activo = ($rol === 'psicologo') ? 0 : 1;

$sql = "INSERT INTO usuarios (nombre, correo, contraseña, rol, cedula, telefono, codigo_estudiante, activo) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssssssi", $nombre, $correo, $pass, $rol, $cedula, $telefono, $codigo_estudiante, $activo);

if ($stmt->execute()) {
    echo "Registro exitoso. Ahora puedes iniciar sesión.";
    // Redirigir a login o mostrar mensaje
} else {
    echo "Error en el registro: " . $stmt->error;
}
?>
