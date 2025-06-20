/**
 * Este script PHP carga y muestra una tabla de citas activas desde la base de datos.
 *
 * Funcionalidad:
 * - Obtiene los parámetros de paginación 'pagina' y 'limite' desde la URL (GET).
 * - Realiza una consulta SQL para obtener las citas activas, uniendo la información del paciente.
 * - Muestra los resultados en una tabla HTML, incluyendo nombre, cédula, tipo de médico, fecha, hora, método de pago y una opción para cancelar la cita.
 * - Si no hay citas activas, muestra un mensaje indicándolo.
 *
 * Parámetros de entrada (vía GET):
 * - pagina: Número de página para la paginación (opcional, por defecto 1).
 * - limite: Cantidad de registros por página (opcional, por defecto 5).
 *
 * Dependencias:
 * - Requiere el archivo de configuración de la base de datos ('configurar.php').
 * - Utiliza la extensión mysqli para la conexión y consulta a la base de datos.
 *
 * Seguridad:
 * - Utiliza consultas preparadas para evitar inyección SQL.
 * - Escapa los datos de salida con htmlspecialchars para prevenir XSS.
 *
 * Salida:
 * - Tabla HTML con las citas activas y un botón para cancelar cada cita.
 */
<?php
require_once __DIR__ . '/../conexion/configurar.php';

$pagina = isset($_GET['pagina']) ? max((int)$_GET['pagina'], 1) : 1;
$limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 5;
$offset = ($pagina - 1) * $limite;

$sql = "
    SELECT c.id, c.fecha, c.hora, c.tipo_medico, c.metodo_pago,
           p.nombre, p.apellido, p.cedula
    FROM citas c
    JOIN pacientes p ON c.cedula = p.cedula
    WHERE c.activo = 1
    ORDER BY c.fecha DESC, c.hora DESC
    LIMIT ? OFFSET ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limite, $offset);
$stmt->execute();
$result = $stmt->get_result();

echo "<table class='tabla-citas'>";
echo "<tr><th>Nombre</th><th>Cédula</th><th>Tipo</th><th>Fecha</th><th>Hora</th><th>Pago</th><th>Acción</th></tr>";

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['nombre'] . " " . $row['apellido']) . "</td>";
        echo "<td>" . htmlspecialchars($row['cedula']) . "</td>";
        echo "<td>" . htmlspecialchars($row['tipo_medico']) . "</td>";
        echo "<td>" . htmlspecialchars($row['fecha']) . "</td>";
        echo "<td>" . htmlspecialchars(substr($row['hora'], 0, 5)) . "</td>";
        echo "<td>" . htmlspecialchars($row['metodo_pago']) . "</td>";
        echo "<td><button class='btn btn-sm btn-danger' onclick='cancelarCita(" . (int)$row['id'] . ")'>Cancelar</button></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='7'>No hay citas activas.</td></tr>";
}
echo "</table>";

$conn->close();
