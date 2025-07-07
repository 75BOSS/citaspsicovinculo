<?php
$host = 'localhost';
$usuario = 'u240362798_citas12';
$contrasena = 'Citas1234567';
$basededatos = 'u240362798_citas';

$conn = new mysqli($host, $usuario, $contrasena, $basededatos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres
$conn->set_charset("utf8mb4");
?>
