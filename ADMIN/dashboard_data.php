<?php
session_start();
include '../conexion.php';

$datos = [
    "usuarios" => 0,
    "psicologos" => 0,
    "charlas" => 0,
    "proxima_charla" => null,
    "ult_actualizacion" => null,
    "charlas_proximas" => []
];

// Total de usuarios
$res = $conexion->query("SELECT COUNT(*) as total FROM usuarios WHERE activo = 1");
$datos['usuarios'] = $res->fetch_assoc()['total'];

// Total de psicólogos
$res = $conexion->query("SELECT COUNT(*) as total FROM usuarios WHERE rol = 'psicologo' AND activo = 1");
$datos['psicologos'] = $res->fetch_assoc()['total'];

// Total de charlas
$res = $conexion->query("SELECT COUNT(*) as total FROM charlas");
$datos['charlas'] = $res->fetch_assoc()['total'];

// Última actualización
$res = $conexion->query("SELECT MAX(fecha_registro) as fecha FROM usuarios");
$datos['ult_actualizacion'] = $res->fetch_assoc()['fecha'] ?? '---';

// Próximas charlas
$sql = "SELECT titulo, fecha, hora, auditorio_id FROM charlas WHERE fecha >= CURDATE() ORDER BY fecha ASC, hora ASC LIMIT 5";
$res = $conexion->query($sql);
while ($row = $res->fetch_assoc()) {
    $auditorio = 'Desconocido';
    if ($row['auditorio_id']) {
        $aRes = $conexion->query("SELECT nombre FROM auditorios WHERE id = " . intval($row['auditorio_id']));
        if ($aRes && $aRes->num_rows > 0) {
            $auditorio = $aRes->fetch_assoc()['nombre'];
        }
    }
    $datos['charlas_proximas'][] = [
        "titulo" => $row['titulo'],
        "fecha" => $row['fecha'],
        "hora" => $row['hora'],
        "auditorio" => $auditorio
    ];
}

header('Content-Type: application/json');
echo json_encode($datos);