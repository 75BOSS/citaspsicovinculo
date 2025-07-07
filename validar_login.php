<?php
session_start();
require_once 'conexion.php';

// Activar errores en pantalla
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Obtener datos del formulario
$correo = $_POST['correo'] ?? '';
$pass = $_POST['pass'] ?? '';

// Verificar si el usuario existe
$stmt = $conn->prepare("SELECT * FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $usuario = $result->fetch_assoc();

    if (password_verify($pass, $usuario['contraseña'])) {
        if ($usuario['rol'] === 'psicologo' && $usuario['activo'] == 0) {
            echo "Tu cuenta de psicólogo aún no ha sido activada por el administrador.";
            exit;
        }

        // Guardar sesión
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['correo'] = $usuario['correo'];
        $_SESSION['rol'] = $usuario['rol'];

        // Redirigir por rol
        switch ($usuario['rol']) {
            case 'admin':
                header("Location: ADMIN/index.php");
                break;
            case 'psicologo':
                header("Location: psicologo/index_psicologo.php");
                break;
            case 'paciente':
                header("Location: paciente/index_paciente.php");
                break;
            default:
                echo "Rol no reconocido.";
        }
    } else {
        echo "Contraseña incorrecta.";
    }
} else {
    echo "Correo no registrado.";
}

$stmt->close();
$conn->close();
?>
