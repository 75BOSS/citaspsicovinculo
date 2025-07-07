<?php
session_start();
echo "<h1>Bienvenido, paciente " . $_SESSION['correo'] . "</h1>";
?>
