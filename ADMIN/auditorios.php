<?php
include '../conexion.php';
$accion = $_GET['accion'] ?? '';

if ($accion === 'listar' || $accion === 'crear' || $accion === 'eliminar' || $accion === 'actualizar') {
    header('Content-Type: application/json');
}

if ($accion === 'listar') {
    $res = $conexion->query("SELECT * FROM auditorios");
    $datos = [];
    while ($row = $res->fetch_assoc()) {
        $datos[] = $row;
    }
    echo json_encode($datos);
    exit;
}

if ($accion === 'crear') {
    $nombre = $_POST['nombre'] ?? '';
    $capacidad = $_POST['capacidad'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';

    if (!$nombre || !$capacidad) {
        echo json_encode("Faltan datos requeridos.");
        exit;
    }

    $stmt = $conexion->prepare("INSERT INTO auditorios (nombre, capacidad, ubicacion) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $nombre, $capacidad, $ubicacion);
    $stmt->execute();
    echo json_encode($stmt->affected_rows > 0 ? "ok" : "error");
    $stmt->close();
    exit;
}

if ($accion === 'eliminar') {
    $id = $_POST['id'] ?? null;
    if (!$id) {
        echo json_encode("ID no especificado");
        exit;
    }

    $stmt = $conexion->prepare("DELETE FROM auditorios WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo json_encode($stmt->affected_rows > 0 ? "ok" : "error");
    $stmt->close();
    exit;
}

if ($accion === 'actualizar') {
    $id = $_POST['id'] ?? null;
    $nombre = $_POST['nombre'] ?? '';
    $capacidad = $_POST['capacidad'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';

    if (!$id || !$nombre || !$capacidad) {
        echo json_encode("Faltan datos");
        exit;
    }

    $stmt = $conexion->prepare("UPDATE auditorios SET nombre = ?, capacidad = ?, ubicacion = ? WHERE id = ?");
    $stmt->bind_param("sisi", $nombre, $capacidad, $ubicacion, $id);
    $stmt->execute();
    echo json_encode($stmt->affected_rows > 0 ? "ok" : "error");
    $stmt->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Auditorios</title>
    <link rel="stylesheet" href="css/auditorios.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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


<form id="formAuditorio">
    <input type="hidden" name="id" id="auditorioId">
    <input type="text" name="nombre" id="nombre" placeholder="Nombre del auditorio" required>
    <input type="number" name="capacidad" id="capacidad" placeholder="Capacidad" required>
    <input type="text" name="ubicacion" id="ubicacion" placeholder="Ubicación (edificio, piso)">
    <button type="submit" id="btnSubmit">Crear</button>
</form>

<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Capacidad</th>
            <th>Ubicación</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="tablaAuditorios"></tbody>
</table>

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
    let modoEditar = false;

    document.addEventListener('DOMContentLoaded', () => {
        cargarAuditorios();

        const form = document.getElementById('formAuditorio');
        form.addEventListener('submit', e => {
            e.preventDefault();

            const datos = new FormData(form);
            const accion = modoEditar ? 'actualizar' : 'crear';

            fetch(`auditorios.php?accion=${accion}`, {
                method: 'POST',
                body: datos
            })
            .then(res => res.json())
            .then(r => {
                if (r === 'ok') {
                    alert(modoEditar ? 'Auditorio actualizado' : 'Auditorio creado');
                    form.reset();
                    document.getElementById('btnSubmit').textContent = 'Crear';
                    modoEditar = false;
                    cargarAuditorios();
                } else {
                    alert('Error: ' + r);
                }
            });
        });
    });

    function cargarAuditorios() {
        fetch('auditorios.php?accion=listar')
            .then(res => res.json())
            .then(data => {
                const tbody = document.getElementById('tablaAuditorios');
                tbody.innerHTML = '';
                data.forEach(a => {
                    const fila = document.createElement('tr');
                    fila.innerHTML = `
                        <td>${a.nombre}</td>
                        <td>${a.capacidad}</td>
                        <td>${a.ubicacion ?? '-'}</td>
                        <td>
                            <button class="editar" onclick="editarAuditorio(${a.id}, '${a.nombre}', ${a.capacidad}, '${a.ubicacion ?? ''}')">Editar</button>
                            <button class="eliminar" onclick="eliminarAuditorio(${a.id})">Eliminar</button>
                        </td>
                    `;
                    tbody.appendChild(fila);
                });
            });
    }

    function editarAuditorio(id, nombre, capacidad, ubicacion) {
        document.getElementById('auditorioId').value = id;
        document.getElementById('nombre').value = nombre;
        document.getElementById('capacidad').value = capacidad;
        document.getElementById('ubicacion').value = ubicacion;
        document.getElementById('btnSubmit').textContent = 'Actualizar';
        modoEditar = true;
    }

    function eliminarAuditorio(id) {
        if (!confirm("¿Eliminar este auditorio?")) return;

        const datos = new FormData();
        datos.append('id', id);

        fetch('auditorios.php?accion=eliminar', {
            method: 'POST',
            body: datos
        })
        .then(res => res.json())
        .then(r => {
            if (r === 'ok') cargarAuditorios();
            else alert('Error al eliminar');
        });
    }
</script>
</body>
</html>