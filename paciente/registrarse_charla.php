<?php
// registro_exitoso.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro Exitoso</title>
  <style>
    body { margin: 0; font-family: 'Segoe UI', sans-serif; background: #f7f7f7; color: #333; }
    header { background: white; padding: 20px 50px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 5px rgba(0,0,0,0.1); }
    header img { height: 40px; }
    header nav a { margin: 0 15px; text-decoration: none; color: #333; font-weight: bold; }
    .contenedor { padding: 40px; text-align: center; }
    .titulo { font-size: 28px; margin-bottom: 20px; color: #6b3fa0; }
    .mensaje { font-size: 20px; margin-bottom: 30px; }
    .boton {
      padding: 10px 20px;
      background-color: #6b3fa0;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      text-decoration: none;
    }
    .boton:hover {
      background-color: #502d85;
    }
  </style>
</head>
<body>
  <header>
    <div style="display:flex; align-items:center;">
      <img src="../imagen/logo.png" alt="Logo Psicovínculo">
      <span style="margin-left:10px; font-size: 20px; color: #6b3fa0; font-weight: bold;">Psicovínculo</span>
    </div>
    <nav>
      <a href="#">Inicio</a>
      <a href="#">Mi cuenta</a>
      <a href="#">Charlas</a>
      <a href="logout.php">Cerrar sesión</a>
    </nav>
  </header>

  <div class="contenedor">
    <h1 class="titulo">Registro exitoso</h1>
    <p class="mensaje">¡Te has registrado correctamente en la charla!</p>
    <a href="paciente/index_paciente.php" class="boton">Volver a Charlas</a>
  </div>
</body>
</html>
