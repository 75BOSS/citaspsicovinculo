
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'conexion.php';

if (!isset($_SESSION['id']) || $_SESSION['rol'] !== 'paciente') {
    echo "Acceso denegado.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_charla'])) {
    $id_charla = intval($_POST['id_charla']);
    $id_paciente = $_SESSION['id'];

    // Verificar si ya está registrado
    $verificar = $conn->prepare("SELECT id FROM reservas WHERE id_charla = ? AND id_paciente = ?");
    $verificar->bind_param("ii", $id_charla, $id_paciente);
    $verificar->execute();
    $resultado = $verificar->get_result();

    if ($resultado->num_rows === 0) {
        // Insertar nueva reserva
        $insertar = $conn->prepare("INSERT INTO reservas (id_charla, id_paciente, estado) VALUES (?, ?, 'confirmada')");
        $insertar->bind_param("ii", $id_charla, $id_paciente);
        if ($insertar->execute()) {
            header("Location: index_paciente 2.php?registro=exito");
            exit;
        } else {
            echo "Error al registrar: " . $insertar->error;
        }
        $insertar->close();
    } else {
        header("Location: index_paciente 2.php?registro=ya_registrado");
        exit;
    }

    $verificar->close();
} else {
    echo "Solicitud inválida.";
}
?>
