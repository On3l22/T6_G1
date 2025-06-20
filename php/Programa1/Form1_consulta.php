<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Formulario de Registro Médico</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
  <h2 class="mb-4">Registro de Paciente</h2>
  <form action="guardar_datos.php" method="POST" class="row g-3">
    <!-- Datos personales -->
    <div class="col-md-6">
      <label class="form-label">Nombre</label>
      <input type="text" name="nombre" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Apellido</label>
      <input type="text" name="apellido" class="form-control" required>
    </div>
    <div class="col-md-6">
      <label class="form-label">Cédula</label>
      <input type="text" name="cedula" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Edad</label>
      <input type="number" name="edad" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Sexo</label>
      <select name="sexo" class="form-select" required>
        <option selected disabled>Seleccione</option>
        <option>Masculino</option>
        <option>Femenino</option>
        <option>Otro</option>
      </select>
    </div>

    <!-- Dirección -->
    <div class="col-md-3">
      <label class="form-label">Provincia</label>
      <input type="text" name="provincia" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Distrito</label>
      <input type="text" name="distrito" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Ciudad</label>
      <input type="text" name="ciudad" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Urbanización / Calle / Barriada</label>
      <input type="text" name="direccion" class="form-control" required>
    </div>

    <!-- Contacto y prueba -->
    <div class="col-md-6">
      <label class="form-label">Teléfono</label>
      <input type="text" name="telefono" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Tipo de Prueba</label>
      <input type="text" name="tipo_prueba" class="form-control" required>
    </div>
    <div class="col-md-3">
      <label class="form-label">Tipo de Médico</label>
      <input type="text" name="tipo_medico" class="form-control" required>
    </div>

    <!-- Fecha y hora -->
    <div class="col-md-6">
      <label class="form-label">Fecha y hora</label>
      <input type="datetime-local" name="fecha" class="form-control" required>
    </div>

    <div class="col-12">
      <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
  </form>
</div>

</body>
</html>
