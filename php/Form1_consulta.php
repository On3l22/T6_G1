<?php
// Definición de pruebas médicas según el sexo del paciente.
// Son arrays asociativos donde la clave es el nombre de la prueba
// y el valor es el precio (en dólares, por ejemplo).
$MG_Urologicos = [
    'Biopsia de próstata transperineal dirigida' => 60,
    'Cistoscopia' => 55,
    'Ecografía urológica' => 70,
    'Flujometría' => 70,
    'PSA (Antígeno Prostático Específico)' => 35,
    'Urodinamia' => 80
];
$MG_Ginecologicos = [
    'Biopsias de diferentes tejidos' => 60,
    'Ecografía ginecológica' => 40,
    'Histeroscopia' => 60,
    'Mamografía' => 50,
    'Ecografía mamaria' => 50,
    'Pruebas para el estudio de la fertilidad' => 100
];

// Recibimos el sexo y tipo de médico desde el formulario vía POST.
// Si no existe, asignamos valores por defecto para facilitar las pruebas.
$sexo = $_POST['sexo'] ?? 'Masculino';
$tipo_medico = $_POST['tipo_medico'] ?? 'General';

// Seleccionamos dinámicamente el conjunto de pruebas basado en el sexo.
// Esto permite que el formulario muestre solo las pruebas relevantes.
$pruebas = $sexo === 'Femenino' ? $MG_Ginecologicos : $MG_Urologicos;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Formulario con Pruebas Dinámicas</title>
  <!-- Bootstrap 5 para estilos responsivos y elegantes -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4">Registro de Paciente</h2>

  <!-- Formulario que se envía a sí mismo para mantener la información tras submit -->
  <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" class="row g-3" id="registroForm">

    <!-- Campos deshabilitados para evitar edición directa, pero sus valores se envían con inputs hidden -->
    <div class="col-md-6">
      <label class="form-label">Nombre</label>
      <input type="text" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? 'Juan') ?>" class="form-control" disabled>
      <input type="hidden" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? 'Juan') ?>" />
    </div>

    <div class="col-md-6">
      <label class="form-label">Apellido</label>
      <input type="text" name="apellido" value="<?= htmlspecialchars($_POST['apellido'] ?? 'Pérez') ?>" class="form-control" disabled>
      <input type="hidden" name="apellido" value="<?= htmlspecialchars($_POST['apellido'] ?? 'Pérez') ?>" />
    </div>

    <div class="col-md-6">
      <label class="form-label">Cédula</label>
      <input type="text" name="cedula" value="<?= htmlspecialchars($_POST['cedula'] ?? '12345678') ?>" class="form-control" disabled>
      <input type="hidden" name="cedula" value="<?= htmlspecialchars($_POST['cedula'] ?? '12345678') ?>" />
    </div>

    <div class="col-md-3">
      <label class="form-label">Edad</label>
      <input type="number" name="edad" value="<?= htmlspecialchars($_POST['edad'] ?? '30') ?>" class="form-control" disabled>
      <input type="hidden" name="edad" value="<?= htmlspecialchars($_POST['edad'] ?? '30') ?>" />
    </div>

    <div class="col-md-3">
      <label class="form-label">Sexo</label>
      <input type="text" name="sexo_display" value="<?= htmlspecialchars($sexo) ?>" class="form-control" disabled>
      <input type="hidden" name="sexo" value="<?= htmlspecialchars($sexo) ?>" />
    </div>

    <div class="col-md-3">
      <label class="form-label">Provincia</label>
      <input type="text" name="provincia" value="<?= htmlspecialchars($_POST['provincia'] ?? 'Provincia X') ?>" class="form-control" disabled>
      <input type="hidden" name="provincia" value="<?= htmlspecialchars($_POST['provincia'] ?? 'Provincia X') ?>" />
    </div>

    <div class="col-md-3">
      <label class="form-label">Distrito</label>
      <input type="text" name="distrito" value="<?= htmlspecialchars($_POST['distrito'] ?? 'Distrito Y') ?>" class="form-control" disabled>
      <input type="hidden" name="distrito" value="<?= htmlspecialchars($_POST['distrito'] ?? 'Distrito Y') ?>" />
    </div>

    <div class="col-md-3">
      <label class="form-label">Ciudad</label>
      <input type="text" name="ciudad" value="<?= htmlspecialchars($_POST['ciudad'] ?? 'Ciudad Z') ?>" class="form-control" disabled>
      <input type="hidden" name="ciudad" value="<?= htmlspecialchars($_POST['ciudad'] ?? 'Ciudad Z') ?>" />
    </div>

    <div class="col-md-3">
      <label class="form-label">Urbanización / Calle / Barriada</label>
      <input type="text" name="direccion" value="<?= htmlspecialchars($_POST['direccion'] ?? 'Calle 123') ?>" class="form-control" disabled>
      <input type="hidden" name="direccion" value="<?= htmlspecialchars($_POST['direccion'] ?? 'Calle 123') ?>" />
    </div>

    <div class="col-md-6">
      <label class="form-label">Teléfono</label>
      <input type="text" name="telefono" value="<?= htmlspecialchars($_POST['telefono'] ?? '555-1234') ?>" class="form-control" disabled>
      <input type="hidden" name="telefono" value="<?= htmlspecialchars($_POST['telefono'] ?? '555-1234') ?>" />
    </div>

    <!-- Selección activa de tipo de médico mediante radio buttons -->
    <div class="col-md-3">
      <label class="form-label">Tipo de Médico</label><br>

      <!-- Radio para Médico General -->
      <input type="radio" name="tipo_medico" id="general" value="General" <?= $tipo_medico === 'General' ? 'checked' : '' ?> onclick="togglePruebas(false)" required>
      <label for="general">General</label><br>

      <!-- Radio para Médico Especializado -->
      <input type="radio" name="tipo_medico" id="especializado" value="Especializado" <?= $tipo_medico === 'Especializado' ? 'checked' : '' ?> onclick="togglePruebas(true)" required>
      <label for="especializado">Especializado</label>
    </div>

    <!-- Listado dinámico de pruebas con checkboxes -->
    <div class="col-md-9">
      <label class="form-label">Tipo de Prueba</label>
      <div class="border p-3 rounded bg-white" id="pruebasContainer">
        <?php foreach($pruebas as $prueba => $precio): ?>
          <div class="form-check">
            <!-- Checkboxes deshabilitados si no es médico especializado -->
            <input class="form-check-input prueba-checkbox" type="checkbox" name="tipo_prueba[]" id="<?= md5($prueba) ?>" value="<?= htmlspecialchars($prueba) ?>" <?= ($tipo_medico !== 'Especializado') ? 'disabled' : '' ?>>
            <label class="form-check-label" for="<?= md5($prueba) ?>"><?= htmlspecialchars($prueba) ?> ($<?= $precio ?>)</label>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- Fecha y hora deshabilitada (se envía por hidden) -->
    <div class="col-md-6">
      <label class="form-label">Fecha y hora</label>
      <input type="datetime-local" name="fecha_display" value="<?= htmlspecialchars($_POST['fecha_display'] ?? '2025-06-19T12:00') ?>" class="form-control" disabled>
      <input type="hidden" name="fecha" value="<?= htmlspecialchars($_POST['fecha'] ?? '2025-06-19 12:00:00') ?>" />
    </div>

    <!-- Botón para enviar formulario -->
    <div class="col-12">
      <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
  </form>
</div>

<script>
  /**
   * Función para habilitar o deshabilitar checkboxes de pruebas
   * según el tipo de médico seleccionado.
   * @param {boolean} habilitar - true si es médico especializado
   */
  function togglePruebas(habilitar) {
    const checkboxes = document.querySelectorAll('.prueba-checkbox');
    checkboxes.forEach(cb => {
      cb.disabled = !habilitar;
      if (!habilitar) cb.checked = false; // desmarca si deshabilita
    });
  }

  // Al cargar la página, ajustamos el estado de los checkboxes según el médico seleccionado
  window.onload = function() {
    const especializado = document.getElementById('especializado').checked;
    togglePruebas(especializado);
  }
</script>

</body>
</html>
