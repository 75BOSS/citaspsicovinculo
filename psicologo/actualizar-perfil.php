<?php
session_start();
include '../conexion.php';




$id_usuario = $_SESSION['usuario_id'];

// Verificar si el formulario fue enviado correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger datos del formulario con validación mínima
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $telefono = trim($_POST['telefono'] ?? '');
    $foto = trim($_POST['foto'] ?? '');

    // Validación básica
    if (empty($nombre) || empty($correo)) {
        echo "Nombre y correo son obligatorios.";
        exit();
    }

    // Actualizar en la base de datos
    $stmt = $conexion->prepare("UPDATE usuarios SET nombre = ?, correo = ?, telefono = ?, foto = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $nombre, $correo, $telefono, $foto, $id_usuario);

    if ($stmt->execute()) {
        // Redirigir de vuelta al perfil o panel
        header("Location: mi-cuenta.php?actualizado=1");
        exit();
    } else {
        echo "Error al actualizar el perfil: " . $stmt->error;
    }
} else {
    echo "Acceso no permitido.";
}
?>
