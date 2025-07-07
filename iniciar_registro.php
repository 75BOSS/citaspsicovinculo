<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrarse - Psicovínculo</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
    }

    header {
      background-color: #fff;
      padding: 10px 40px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo img {
      height: 40px;
    }

    .logo span {
      font-size: 18px;
      font-weight: bold;
      color: #4c2a77;
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 25px;
      margin: 0;
      padding: 0;
    }

    nav a {
      text-decoration: none;
      color: #222;
      font-weight: 500;
    }

    .contenedor {
      display: flex;
      justify-content: center;
      align-items: center;
      height: calc(100vh - 150px);
    }

    .contenedor__todo {
      background-color: #b694b7;
      width: 900px;
      display: flex;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .caja__trasera {
      width: 50%;
      padding: 40px;
      color: white;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .caja__trasera h3 {
      margin-bottom: 10px;
    }

    .caja__trasera p {
      margin-bottom: 20px;
    }

    .caja__trasera button {
      padding: 10px 20px;
      background: white;
      color: #b694b7;
      border: 2px solid white;
      border-radius: 20px;
      cursor: pointer;
      font-weight: bold;
    }

    .contenedor__login-register {
      width: 50%;
      background-color: white;
      padding: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .formulario__register {
      display: flex;
      flex-direction: column;
      width: 100%;
      max-width: 300px;
    }

    .formulario__register h2 {
      color: #4c2a77;
      margin-bottom: 20px;
      text-align: center;
    }

    .formulario__register input,
    .formulario__register select {
      margin-bottom: 15px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 10px;
      font-size: 14px;
    }

    #codigo_estudiante_div {
      display: none;
    }

    .formulario__register button {
      background: linear-gradient(to right, #b694f6, #6b46c1);
      border: none;
      padding: 12px;
      color: white;
      border-radius: 20px;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s ease;
    }

    .formulario__register button:hover {
      background: linear-gradient(to right, #a77df4, #5a39af);
    }

    footer {
      background-color: #6b46c1;
      padding: 20px;
      text-align: center;
      color: white;
    }
  </style>
</head>
<body>

  <header>
    <div class="logo">
      <img src="imagen/logo.png" alt="Psicovínculo">
      <span>Psicovínculo</span>
    </div>
    <nav>
      <ul>
        <li><a href="#">Inicio</a></li>
        <li><a href="#">Tests</a></li>
        <li><a href="#">Servicios</a></li>
        <li><a href="#">Eventos</a></li>
        <li><a href="#">Contacto</a></li>
      </ul>
    </nav>
  </header>

  <div class="contenedor">
    <div class="contenedor__todo">

      <!-- Columna izquierda -->
      <div class="caja__trasera">
        <h3>¿Ya tienes una cuenta?</h3>
        <p>Inicia sesión para entrar en la página</p>
        <a href="login.php"><button type="button">Iniciar Sesión</button></a>
      </div>

      <!-- Columna derecha (formulario) -->
      <div class="contenedor__login-register">
        <form action="registro.php" method="POST" class="formulario__register">
          <h2>Registrarse</h2>

          <select name="rol" id="rol" required>
            <option value="">Selecciona tu rol</option>
            <option value="paciente">Paciente</option>
            <option value="psicologo">Psicólogo</option>
          </select>

          <input type="text" name="nombre" placeholder="Nombre completo" required>
          <input type="email" name="correo" placeholder="Correo Electrónico" required>
          <input type="password" name="pass" placeholder="Contraseña" required>
          <input type="text" name="cedula" placeholder="Cédula" required>
          <input type="text" name="telefono" placeholder="Teléfono (opcional)">

          <div id="codigo_estudiante_div">
            <input type="text" name="codigo_estudiante" placeholder="Código de estudiante">
          </div>

          <button type="submit">Registrarse</button>
        </form>
      </div>
    </div>
  </div>

  <footer>
    &copy; 2025 Psicovínculo. Todos los derechos reservados.
  </footer>

  <script>
    document.getElementById('rol').addEventListener('change', function () {
      const div = document.getElementById('codigo_estudiante_div');
      div.style.display = this.value === 'psicologo' ? 'block' : 'none';
    });
  </script>

</body>
</html>
