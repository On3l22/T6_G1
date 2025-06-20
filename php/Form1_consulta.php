<?php
// Incluye scripts auxiliares para obtener catálogos de exámenes y citas por paciente
include './clinica/consultarExamenes.php';
include './clinica/recuperarCitas.php';

$mensaje = '';
$cedulaIngresada = $_POST['cedula'] ?? '';
$paciente        = $pacientes[$cedulaIngresada] ?? null;

// Carga los datos del paciente si existe
if ($paciente) {
    $info = $paciente['info'];
    foreach ($info as $k => $v) { $$k = $v; } // Carga dinámica de variables
} else {
    // Valores vacíos si no se encontró el paciente
    $nombre = $apellido = $edad = $sexo = $provincia = $distrito = $ciudad = $direccion = $telefono = '';
}

$indiceCitaSel = $_POST['cita_id'] ?? 0; // Índice de cita seleccionada
$listaCitas    = $paciente['citas'] ?? [];
$citaActual    = $listaCitas[$indiceCitaSel] ?? null;

// Carga los valores de la cita seleccionada
$tipo_medico   = $citaActual['tipo_medico']   ?? 'General';
$primera_cita  = $citaActual['primera_cita']  ?? 'No';
$fecha_cita    = $citaActual['fecha']         ?? date('Y-m-d\TH:i');
$pruebas_sel   = $citaActual['pruebas']       ?? [];

// Catálogo dinámico de pruebas según sexo
$catalogoPruebas = ($sexo === 'Femenino') ? $MG_Ginecologicos : $MG_Urologicos;

// Separación de fecha y hora
$fechaSolo = date('Y-m-d', strtotime($fecha_cita));
$horaSolo  = date('H:i',    strtotime($fecha_cita));

// Procesamiento del formulario si se presiona "Guardar"
if (isset($_POST['guardar']) && $cedulaIngresada && isset($_POST['cita_id'])) {
    $idx = (int)$_POST['cita_id'];
    $tipo_medico_post = $_POST['tipo_medico'] ?? 'General';
    $fecha_post = $_POST['fecha'] ?? $fechaSolo;
    $hora_post = $_POST['hora'] ?? $horaSolo;
    $pruebas_post = $_POST['tipo_prueba'] ?? [];

    include './conexion/configurar.php';

    try {
        $cita_id_real = $listaCitas[$idx]['id'] ?? null;

        if (!$cita_id_real) {
            throw new Exception("No se encontró la cita para actualizar.");
        }

        $conn->begin_transaction();

        // Actualiza los datos principales de la cita
        $stmt = $conn->prepare("UPDATE citas SET tipo_medico = ?, fecha = ?, hora = ? WHERE id = ? AND cedula = ?");
        if (!$stmt) throw new Exception("Error en la preparación de la consulta: " . $conn->error);

        $stmt->bind_param("sssis", $tipo_medico_post, $fecha_post, $hora_post, $cita_id_real, $cedulaIngresada);
        if (!$stmt->execute()) throw new Exception("Error al ejecutar UPDATE: " . $stmt->error);
        $stmt->close();

        // Elimina las pruebas anteriores asociadas a la cita
        $stmtDel = $conn->prepare("DELETE FROM cita_examen WHERE cita_id = ?");
        if (!$stmtDel) throw new Exception("Error en la preparación DELETE: " . $conn->error);

        $stmtDel->bind_param("i", $cita_id_real);
        if (!$stmtDel->execute()) throw new Exception("Error al ejecutar DELETE: " . $stmtDel->error);
        $stmtDel->close();

        // Inserta las nuevas pruebas seleccionadas
        if (!empty($pruebas_post)) {
            $stmtIns = $conn->prepare("INSERT INTO cita_examen (cita_id, examen_id) VALUES (?, ?)");
            if (!$stmtIns) throw new Exception("Error en la preparación INSERT: " . $conn->error);

            foreach ($pruebas_post as $examen_id) {
                $examen_id = (int)$examen_id;
                $stmtIns->bind_param("ii", $cita_id_real, $examen_id);
                if (!$stmtIns->execute()) throw new Exception("Error al ejecutar INSERT: " . $stmtIns->error);
            }
            $stmtIns->close();
        }

        $conn->commit();
        $mensaje = "Cita actualizada correctamente.";
    } catch (Exception $e) {
        $conn->rollback();
        $mensaje = "Error al actualizar la cita: " . $e->getMessage();
    } finally {
        $conn->close();
    }
}
?>

<!-- HTML principal -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Citas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
  <h2 class="mb-4">Gestión de Citas del Paciente</h2>

  <?php if ($mensaje): ?>
    <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
  <?php endif; ?>

  <form method="POST" class="row g-3">

    <!-- Campo de cédula con botón Buscar -->
    <div class="col-md-6">
      <label class="form-label">Cédula</label>
      <div class="input-group">
        <input type="text" name="cedula" value="<?= htmlspecialchars($cedulaIngresada) ?>" class="form-control" required>
        <button class="btn btn-outline-secondary" name="buscar" type="submit">Buscar</button>
      </div>
    </div>

    <!-- Selector de citas del paciente -->
    <?php if ($paciente): ?>
      <div class="col-md-6">
        <label class="form-label">Citas del paciente</label>
        <select name="cita_id" class="form-select" onchange="this.form.submit()">
          <?php foreach ($listaCitas as $idx => $cita): ?>
            <?php $opLabel = date('d/m/Y H:i', strtotime($cita['fecha'])) . " - " . $cita['tipo_medico']; ?>
            <option value="<?= $idx ?>" <?= $idx == $indiceCitaSel ? 'selected' : '' ?>>
              <?= htmlspecialchars($opLabel) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    <?php endif; ?>

    <!-- Función para mostrar campos de solo lectura -->
    <?php
    function campo_ro($col, $label, $valor, $name) {
        echo <<<HTML
        <div class="col-md-$col">
          <label class="form-label">$label</label>
          <input type="text" class="form-control" value="{$valor}" disabled>
          <input type="hidden" name="$name" value="{$valor}">
        </div>
        HTML;
    }
    campo_ro(6,'Nombre',$nombre,'nombre');
    campo_ro(6,'Apellido',$apellido,'apellido');
    campo_ro(3,'Edad',$edad,'edad');
    campo_ro(3,'Sexo',$sexo,'sexo');
    campo_ro(3,'Provincia',$provincia,'provincia');
    campo_ro(3,'Distrito',$distrito,'distrito');
    campo_ro(3,'Ciudad',$ciudad,'ciudad');
    campo_ro(3,'Dirección',$direccion,'direccion');
    campo_ro(6,'Teléfono',$telefono,'telefono');
    ?>

    <!-- Campo de primera cita -->
    <div class="col-md-6">
      <label class="form-label d-block">Primera cita</label>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="primera-cita" <?= $primera_cita === 'Sí' ? 'checked' : '' ?> disabled>
        <label class="form-check-label" for="primera-cita">¿Es su primera cita?</label>
        <input type="hidden" name="primera_cita" value="<?= $primera_cita ?>">
      </div>
    </div>

    <!-- Selección del tipo de médico -->
    <div class="col-md-3">
      <label class="form-label">Tipo de Médico</label><br>
      <input type="radio" name="tipo_medico" id="general" value="General"
             <?= $tipo_medico === 'General' ? 'checked' : '' ?>
             onclick="togglePruebas(false)">
      <label for="general">General</label><br>
      <input type="radio" name="tipo_medico" id="especializado" value="Especializado"
             <?= $tipo_medico === 'Especializado' ? 'checked' : '' ?>
             onclick="togglePruebas(true)">
      <label for="especializado">Especializado</label>
    </div>

    <!-- Sección de pruebas médicas -->
    <div class="col-md-9" id="seccion-pruebas" style="display: none;">
      <label class="form-label">Tipo de Prueba</label>
      <div class="border p-3 rounded bg-white">
        <?php foreach ($catalogoPruebas as $id => $prueba): ?>
          <?php
            $checked = in_array($id, $pruebas_sel) ? 'checked' : '';
            $inputId = 'prueba_' . $id;
          ?>
          <div class="form-check">
            <input class="form-check-input prueba-checkbox"
                   type="checkbox"
                   name="tipo_prueba[]"
                   id="<?= $inputId ?>"
                   value="<?= $id ?>"
                   <?= $checked ?>
                   <?= $tipo_medico !== 'Especializado' ? 'disabled' : '' ?>>
            <label class="form-check-label" for="<?= $inputId ?>">
              <?= htmlspecialchars($prueba['nombre']) ?> ($<?= number_format($prueba['precio'], 2) ?>)
            </label>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Fecha y hora de la cita -->
    <fieldset class="col-md-6 border rounded p-3">
      <legend class="float-none w-auto px-2">Fecha y hora de la cita</legend>
      <div class="row">
        <div class="col-md-6">
          <label for="fecha" class="form-label">Fecha</label>
          <input type="date" class="form-control" id="fecha" name="fecha" value="<?= htmlspecialchars($fechaSolo) ?>" required>
        </div>
        <div class="col-md-6">
          <label for="hora" class="form-label">Hora</label>
          <input type="time" class="form-control" id="hora" name="hora" value="<?= htmlspecialchars($horaSolo) ?>" required>
        </div>
      </div>
    </fieldset>

    <!-- Botón para guardar cambios -->
    <div class="col-12">
      <button class="btn btn-primary" name="guardar">Guardar</button>
    </div>
  </form>
</div>

<!-- JavaScript para controlar pruebas -->
<script>
// Muestra u oculta la sección de pruebas médicas
function togglePruebas(habilitar) {
  const seccion = document.getElementById('seccion-pruebas');
  if (!seccion) return;

  seccion.style.display = habilitar ? 'block' : 'none';

  document.querySelectorAll('.prueba-checkbox').forEach(cb => {
    cb.disabled = !habilitar;
    if (!habilitar) cb.checked = false;
  });
}

// Inicializa estado de la sección de pruebas
window.onload = () => {
  const hayCedula = document.querySelector('input[name="cedula"]').value.trim() !== '';
  const esEspecializado = document.getElementById('especializado').checked;
  togglePruebas(hayCedula && esEspecializado);
};
</script>
</body>
</html>
