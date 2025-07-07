<?php
include '../conexion.php';

$accion = $_GET['accion'] ?? '';

if ($accion === 'listar' || $accion === 'filtrar') {
    header('Content-Type: application/json');
    $datos = [];

    if ($accion === 'listar') {
        $sql = "SELECT
                    c.titulo,
                    c.descripcion,
                    c.fecha,
                    c.hora_inicio,
                    c.hora_fin,
                    c.cupo_maximo,
                    u.nombre AS psicologo,
                    a.nombre AS auditorio
                FROM charlas c
                INNER JOIN usuarios u ON c.id_psicologo = u.id
                INNER JOIN auditorios a ON c.id_auditorio = a.id
                ORDER BY c.fecha DESC";

        $res = $conexion->query($sql);
        while ($row = $res->fetch_assoc()) {
            $datos[] = $row;
        }
    }

    if ($accion === 'filtrar') {
        $desde = $_GET['desde'] ?? '';
        $hasta = $_GET['hasta'] ?? '';
        if (!$desde || !$hasta) {
            echo json_encode([]);
            exit;
        }

        $sql = "SELECT
                    c.titulo,
                    c.descripcion,
                    c.fecha,
                    c.hora_inicio,
                    c.hora_fin,
                    c.cupo_maximo,
                    u.nombre AS psicologo,
                    a.nombre AS auditorio
                FROM charlas c
                INNER JOIN usuarios u ON c.id_psicologo = u.id
                INNER JOIN auditorios a ON c.id_auditorio = a.id
                WHERE c.fecha BETWEEN ? AND ?
                ORDER BY c.fecha DESC";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ss", $desde, $hasta);
        $stmt->execute();
        $res = $stmt->get_result();
        while ($row = $res->fetch_assoc()) {
            $datos[] = $row;
        }
    }

    echo json_encode($datos);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Charlas Disponibles</title>
    <link rel="stylesheet" href="css/auditorios.css"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<header class="header">
    <nav class="navbar">
     <div class="logo">
    <a href="index.php">
        <img src="imagen/logo.png" alt="Psicovínculo" class="logo-psicovinculo" />
    </a>
    <span>Psicovínculo</span>
</div>

        <ul class="nav-centrado">
            <li><a href="index.php">Inicio</a></li>
            <li><a href="usuarios.php">Usuarios</a></li>
            <li><a href="auditorios.php">Auditorios</a></li>
            <li><a href="charlas.php">Charlas</a></li>
        </ul>

        <div class="perfil">
            <a href="micuenta.php" title="Mi Cuenta">
                <img src="imagen/perfil.png" alt="Perfil" class="icono-perfil">
            </a>
        </div>
    </nav>
</header>

<main>
    <section class="filtros">
        <label>Desde: <input type="date" id="fechaInicio"></label>
        <label>Hasta: <input type="date" id="fechaFin"></label>
        <button onclick="filtrarPorFechas()">Filtrar</button>
        <button onclick="cargarCharlas()">Mostrar Todo</button>
    </section>

    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Psicólogo</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Auditorio</th>
                <th>Cupo Máximo</th>
            </tr>
        </thead>
        <tbody id="tablaCharlas"></tbody>
    </table>
</main>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-section">
            <h3>INFORMACIÓN</h3>
            <p><i class="fas fa-map-marker-alt"></i> Av. Isabel La Católica N. 23-52 y Madrid.</p>
            <p><i class="fas fa-phone"></i> <a href="tel:0960951729">0960951729</a></p>
            <p><i class="fas fa-envelope"></i> <a href="mailto:fabian.carsoia@ups.edu.co">fabian.carsoia@ups.edu.co</a></p>
        </div>

        <div class="footer-section">
            <h3>ATENCIÓN</h3>
            <p><i class="far fa-clock"></i> LUNES A VIERNES</p>
            <p>9:00 AM - 17:00 PM</p>
        </div>

        <div class="footer-section">
            <h3>NUESTROS SERVICIOS</h3>
            <ul class="services-list">
                <li>Tratamientos de Ansiedad</li>
                <li>Terapia para Depresión</li>
                <li>Manejo del Estrés</li>
                <li>Terapia para Crisis de Pánico</li>
            </ul>
        </div>

        <div class="footer-section">
            <h3>REDES SOCIALES</h3>
            <div class="social-icons">
                <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
            </div>
            <div class="footer-link">
                <a href="https://www.deservicios.es/plos/muestras-redes-sociales-deservicios-totamacion"
                   target="_blank">
                  Más información sobre nuestros servicios
                </a>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 Psicovínculo. Todos los derechos reservados.</p>
    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
      cargarCharlas();
    });

    function cargarCharlas() {
      fetch('charlas.php?accion=listar')
        .then(res => res.json())
        .then(renderizarCharlas);
    }

    function filtrarPorFechas() {
      const inicio = document.getElementById('fechaInicio').value;
      const fin = document.getElementById('fechaFin').value;

      fetch(`charlas.php?accion=filtrar&desde=${inicio}&hasta=${fin}`)
        .then(res => res.json())
        .then(renderizarCharlas);
    }

    function renderizarCharlas(data) {
      const tbody = document.getElementById('tablaCharlas');
      tbody.innerHTML = '';
      data.forEach(c => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
          <td>${c.titulo}</td>
          <td>${c.psicologo}</td>
          <td>${c.descripcion}</td>
          <td>${c.fecha}</td>
          <td>${c.hora_inicio}</td>
          <td>${c.hora_fin}</td>
          <td>${c.auditorio}</td>
          <td>${c.cupo_maximo}</td>
        `;
        tbody.appendChild(fila);
      });
    }
</script>
</body>
</html>