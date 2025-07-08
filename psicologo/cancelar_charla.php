<?php
session_start();
include '../conexion.php';


if (isset($_GET['id'])) {
    $id_charla = intval($_GET['id']);

    // Eliminar la charla de forma directa (en modo público o de pruebas)
    $conn->query("DELETE FROM charlas WHERE id = $id_charla");
}

// Redirigir al panel
header("Location: index_psicologo.php");
exit();
?>
