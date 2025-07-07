<?php
session_start();
include 'conexion.php'; // Asegúrate de tener tu conexión aquí

$correo = $_POST['correo'];
$pass = $_POST['pass'];

$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();
    if (password_verify($pass, $usuario['contraseña'])) {
        $_SESSION['usuario'] = $usuario['correo'];
        $_SESSION['rol'] = $usuario['rol'];

        if ($usuario['rol'] === 'admin') {
            header("Location: ADMIN/index.php");
        } elseif ($usuario['rol'] === 'psicologo') {
            if ($usuario['activo'] == 1) {
                header("Location: psicologo/index_psicologo.php");
            } else {
                echo "Tu cuenta aún no ha sido activada por el administrador.";
            }
        } elseif ($usuario['rol'] === 'paciente') {
            header("Location: paciente/index_paciente.php");
        } else {
            echo "Rol no válido.";
        }
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Usuario no encontrado.";
}
?>
