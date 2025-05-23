<?php
function mostrarResumenCita(
    string $nombre,
    string $apellido,
    string $cedula,
    string $fecha,
    string $tipo_servicio,
    array $examenes_seleccionados,
    float $totalbt,
    float $descuento_jubilado,
    float $descuento_octubre,
    float $precio_final,
    string $hora
) {
    echo "<h2>Resumen de la Cita</h2>";

    echo "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th colspan='2' style='background-color: #f2f2f2;'>Datos del paciente</th></tr>";
    echo "<tr><td><strong>Nombre:</strong></td><td>" . htmlspecialchars($nombre) . " " . htmlspecialchars($apellido) . "</td></tr>";
    echo "<tr><td><strong>Cédula:</strong></td><td>" . htmlspecialchars($cedula) . "</td></tr>";
    echo "<tr><td><strong>Fecha y hora de la cita:</strong></td><td>" . htmlspecialchars($fecha) . " a las " . htmlspecialchars($hora) . "</td></tr>";
    echo "<tr><td><strong>Servicio:</strong></td><td>" . htmlspecialchars($tipo_servicio) . "</td></tr>";

    echo "<tr><th colspan='2' style='background-color: #f2f2f2;'>Exámenes seleccionados</th></tr>";
    if (!empty($examenes_seleccionados)) {
        echo "<tr><td colspan='2'>";
        echo "<table border='1' cellpadding='5' cellspacing='0' style='width: 100%; border-collapse: collapse;'>";
        echo "<tr><th>Nombre del examen</th><th>Precio</th></tr>";
        foreach ($examenes_seleccionados as $nombre_examen => $precio_examen) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($nombre_examen) . "</td>";
            echo "<td>$" . number_format((float)$precio_examen, 2) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</td></tr>";
    } else {
        echo "<tr><td colspan='2'>No se seleccionaron exámenes.</td></tr>";
    }

    echo "<tr><th colspan='2' style='background-color: #f2f2f2;'>Resumen de costos</th></tr>";
    echo "<tr><td><strong>Subtotal (cita + exámenes):</strong></td><td>$" . number_format($totalbt, 2) . "</td></tr>";

    if ($descuento_octubre > 0) {
        echo "<tr><td><strong>Descuento por promoción de octubre (10% de exámenes):</strong></td><td>-$" . number_format($descuento_octubre, 2) . "</td></tr>";
    } else {
        echo "<tr><td><strong>Descuento por octubre:</strong></td><td>No aplica</td></tr>";
    }
    if ($descuento_jubilado > 0) {
        echo "<tr><td><strong>Descuento por jubilado (10% del subtotal):</strong></td><td>-$" . number_format($descuento_jubilado, 2) . "</td></tr>";
    } else {
        echo "<tr><td><strong>Descuento por jubilado:</strong></td><td>No aplica</td></tr>";
    }


    echo "<tr><td><strong>Total a pagar:</strong></td><td><strong>$" . number_format($precio_final, 2) . "</strong></td></tr>";
    echo "</table>";
    echo "<br>";
}
?>
