<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión - Psicovínculo</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <!-- Barra de navegación -->
    <header class="header">
        <nav class="navbar">
            <div class="logo">
                <a href="index.html" class="logo-link">
                    <img src="imagen/logo.png" class="logo-psicovinculo" alt="Psicovínculo">
                </a>
                <span>Psicovínculo</span>
            </div>
            <ul class="menu">
                <li><a href="index.html">Inicio</a></li>
                <li><a href="test.html">Tests</a></li>
                <li> <a href="servicios.html">Servicios</a></li>
                <li><a href="eventos.html">Eventos</a></li>
                <li><a href="contacto.html">Contacto</a></li>
            </ul>
            <div class="auth-buttons">
                <a class="btn-outline btn" href="login.html">Reservar Cita</a>
                <a class="btn-primary btn" href="login.html">Iniciar Sesión</a>
            </div>
        </nav>
    </header>

    <!-- Página de login dividida en dos -->
    <div id="tservicios" class="contenedor">
        <div class="contenedor__todo">
            <div class="caja__trasera">
                <div class="caja__trasera-login">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Inicia sesión para entrar en la página</p>
                    <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                </div>
                  <!-- Botón de Registro (ahora redirige) -->
                  <div class="caja__trasera-register">
                    <h3>¿Aún no tienes una cuenta?</h3>
                    <p>Regístrate para que puedas iniciar sesión</p>
                    <a href="iniciar_registro.php"><button id="btn__registrarse" type="button">Registrarse</button></a>
                  </div>
            </div>
            <div class="contenedor__login-register">
                <!--Formulario de Login-->
                <form action="validar_login.php" method="POST" class="formulario__login">
                  <h2>Iniciar Sesión</h2>
                  <input type="email" placeholder="Correo Electrónico" name="correo" required>
                  <input type="password" placeholder="Contraseña" name="pass" required>
                  <button type="submit">Entrar</button>
                </form>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <div class="footer-info">
                <h4>INFORMACIÓN</h4>
                <p><i class="fas fa-map-marker-alt"></i><a href="https://maps.app.goo.gl/CJ5V1qExnY63Y8Xn7"
                        target="_blank">Av.
                        Isabel La Católica N. 23-52 y Madrid.</a></p>
                <p><i class="fas fa-phone"></i> 0960951729</p>
                <p><i class="fas fa-envelope"></i> fabian.carsola@ups.edu.ec</p>
            </div>

            <div class="footer-social">
                <h4>NUESTRAS REDES SOCIALES</h4>
                <div class="social-media">
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>

            <div class="footer-attention">
                <h4>ATENCIÓN</h4>
                <p>LUNES A VIERNES</p>
                <p>9:00 AM - 17:00 PM</p>
            </div>

            <div class="footer-newsletter">


                <h4>NUESTROS SERVICIOS</h4>
                <p class="hover-levanta">Tratamientos de Depresion</p>
                <p class="hover-levanta">Terapia para Ansiedad</p>
                <p class="hover-levanta">Mejora de Autoestima</p>
                <p class="hover-levanta">Terapia para Parejas</p>
            </div>
        </div>

    </footer>
    <script src="js/iniciar.js"></script>
</body>

</html>