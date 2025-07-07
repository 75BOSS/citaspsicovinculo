<?php
$host = '127.0.0.1:3307';
$usuario = 'root';
$contrasena = '131121';
$basededatos = 'citastrp';

$conn = new mysqli($host, $usuario, $contrasena, $basededatos);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Opcional: establecer el conjunto de caracteres
$conn->set_charset("utf8mb4");
?>
