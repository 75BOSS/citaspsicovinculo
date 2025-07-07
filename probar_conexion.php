<?php
$host = '127.0.0.1:3307';
$usuario = 'root';
$contrasena = '131121';
$basededatos = 'citastrp';

$conn = new mysqli($host, $usuario, $contrasena, $basededatos);

if ($conn->connect_error) {
    die("❌ Conexión fallida: " . $conn->connect_error);
}
echo "✅ Conexión exitosa a la base de datos.";
$conn->close();
?>
