<?php
include './clinica/consultarExamenes.php';
include './clinica/recuperarCitas.php';

$mensaje = '';
$cedulaIngresada = $_POST['cedula'] ?? '';
$paciente        = $pacientes[$cedulaIngresada] ?? null;

if ($paciente) {
    $info = $paciente['info'];
    foreach ($info as $k => $v) { $$k = $v; }
} else {
    $nombre = $apellido = $edad = $sexo = $provincia = $distrito = $ciudad = $direccion = $telefono = '';
}

$indiceCitaSel = $_POST['cita_id'] ?? 0;
$listaCitas    = $paciente['citas'] ?? [];
$citaActual    = $listaCitas[$indiceCitaSel] ?? null;

$tipo_medico   = $citaActual['tipo_medico']   ?? 'General';
$primera_cita  = $citaActual['primera_cita']  ?? 'No';
$fecha_cita    = $citaActual['fecha']         ?? date('Y-m-d\TH:i');
$pruebas_sel   = $citaActual['pruebas']       ?? [];

$catalogoPruebas = ($sexo === 'Femenino') ? $MG_Ginecologicos : $MG_Urologicos;
$fechaSolo = date('Y-m-d', strtotime($fecha_cita));
$horaSolo  = date('H:i',    strtotime($fecha_cita));

// Guardar cambios (simulado)
if (isset($_POST['guardar']) && $cedulaIngresada && isset($_POST['cita_id'])) {
    $idx = $_POST['cita_id'];
    $listaCitas[$idx]['tipo_medico'] = $_POST['tipo_medico'] ?? 'General';
    $listaCitas[$idx]['fecha'] = $_POST['fecha'] ?? $fechaSolo;
    $listaCitas[$idx]['hora'] = $_POST['hora'] ?? $horaSolo;
    $listaCitas[$idx]['pruebas'] = $_POST['tipo_prueba'] ?? [];
    $mensaje = "Cita actualizada correctamente.";
}
?>

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

    <div class="col-md-6">
      <label class="form-label">Cédula</label>
      <div class="input-group">
        <input type="text" name="cedula" value="<?= htmlspecialchars($cedulaIngresada) ?>" class="form-control" required>
        <button class="btn btn-outline-secondary" name="buscar" type="submit">Buscar</button>
      </div>
    </div>

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

<<<<<<< Updated upstream
    <div class="col-md-6">
      <label class="form-label d-block">Primera cita</label>
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="primera-cita" <?= $primera_cita === 'Sí' ? 'checked' : '' ?> disabled>
        <label class="form-check-label" for="primera-cita">¿Es su primera cita?</label>
        <input type="hidden" name="primera_cita" value="<?= $primera_cita ?>">
      </div>
    </div>

=======
    <!-- Selección del tipo de médico -->
>>>>>>> Stashed changes
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

    <div class="col-12">
      <button class="btn btn-primary" name="guardar">Guardar</button>
    </div>
  </form>
</div>

<script>
function togglePruebas(habilitar) {
  const seccion = document.getElementById('seccion-pruebas');
  if (!seccion) return;

  seccion.style.display = habilitar ? 'block' : 'none';

  document.querySelectorAll('.prueba-checkbox').forEach(cb => {
    cb.disabled = !habilitar;
    if (!habilitar) cb.checked = false;
  });
}

window.onload = () => {
  const hayCedula = document.querySelector('input[name="cedula"]').value.trim() !== '';
  const esEspecializado = document.getElementById('especializado').checked;
  togglePruebas(hayCedula && esEspecializado);
};
</script>

