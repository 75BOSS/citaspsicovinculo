<?php
session_start();
include '../conexion.php';



if (isset($_GET['id'])) {
    $id_charla = intval($_GET['id']);
    $conexion->query("DELETE FROM charlas WHERE id = $id_charla");
}

header("Location: index-psicologo.php");
exit();
?>
