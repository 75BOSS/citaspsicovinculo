<?php
include '../conexion.php';
session_start();

// Simulación de ID de psicólogo (cámbialo si ya tienes login)
$id_psicologo = 8;

// Validar campos requeridos
if (
  !isset($_POST['titulo'], $_POST['descripcion'], $_POST['id_auditorio'],
          $_POST['fecha'], $_POST['hora_inicio'], $_POST['hora_fin'], $_POST['cupo_maximo'])
) {
  header("Location: generar_charla.php?error=datos");
  exit;
}

// Obtener datos del formulario
$titulo = trim($_POST['titulo']);
$descripcion = trim($_POST['descripcion']);
$id_auditorio = intval($_POST['id_auditorio']);
$fecha = $_POST['fecha'];
$hora_inicio = $_POST['hora_inicio'];
$hora_fin = $_POST['hora_fin'];
$cupo_maximo = intval($_POST['cupo_maximo']);
$tags = isset($_POST['tags']) ? $_POST['tags'] : [];

// Validar cruce de horarios en mismo auditorio y día
$verificar_sql = "
  SELECT id FROM charlas 
  WHERE id_auditorio = ? AND fecha = ? 
    AND (
      (hora_inicio < ? AND hora_fin > ?) OR
      (hora_inicio < ? AND hora_fin > ?) OR
      (hora_inicio >= ? AND hora_fin <= ?)
    )
";
$verificar_stmt = $conn->prepare($verificar_sql);
$verificar_stmt->bind_param("isssssss", $id_auditorio, $fecha, $hora_fin, $hora_fin, $hora_inicio, $hora_inicio, $hora_inicio, $hora_fin);
$verificar_stmt->execute();
$resultado = $verificar_stmt->get_result();

if ($resultado->num_rows > 0) {
  $verificar_stmt->close();
  $conn->close();
  header("Location: generar_charla.php?error=cruce");
  exit;
}
$verificar_stmt->close();

// Insertar la charla
$insert_stmt = $conn->prepare("
  INSERT INTO charlas (id_psicologo, titulo, descripcion, fecha, hora_inicio, hora_fin, id_auditorio, cupo_maximo, creada_en)
  VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
");

$insert_stmt->bind_param("isssssii", $id_psicologo, $titulo, $descripcion, $fecha, $hora_inicio, $hora_fin, $id_auditorio, $cupo_maximo);

if ($insert_stmt->execute()) {
  $id_charla = $insert_stmt->insert_id;

  // Insertar tags (si hay)
  if (!empty($tags)) {
    $tag_stmt = $conn->prepare("INSERT INTO charla_tags (id_charla, id_tag) VALUES (?, ?)");
    foreach ($tags as $tag_id) {
      $tag_id = intval($tag_id); // Sanitizar por seguridad
      $tag_stmt->bind_param("ii", $id_charla, $tag_id);
      $tag_stmt->execute();
    }
    $tag_stmt->close();
  }

  $insert_stmt->close();
  $conn->close();

  // Redirigir con éxito
  header("Location: generar_charla.php?success=1");
  exit;
} else {
  $insert_stmt->close();
  $conn->close();
  // Error en la inserción
  header("Location: generar_charla.php?error=sql");
  exit;
}
?>
