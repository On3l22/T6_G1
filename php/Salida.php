<?php
function mostrarResumenCita(
    string $nombre,
    string $apellido,
    string $cedula,
    string $fecha,
    string $tipo_servicio,
    array $examenes_seleccionados, // IDs de los exámenes
    float $totalbt,
    float $descuento_jubilado,
    float $descuento_octubre,
    float $precio_final,
    string $hora
) {
    require_once(__DIR__ . '/../clinica/consultarExamenesID.php');

    $todos_los_examenes = obtenerExamenesPorID(); // [id => ['nombre' => ..., 'precio' => ...]]

    $examenes_a_mostrar = [];

    foreach ($examenes_seleccionados as $id) {
        if (isset($todos_los_examenes[$id])) {
            $examenes_a_mostrar[] = [
                'nombre' => $todos_los_examenes[$id]['nombre'],
                'precio' => $todos_los_examenes[$id]['precio']
            ];
        }
    }

    echo "<h2 class='titulo-resumen'>Resumen de la Cita</h2>";

    echo "<table class='tabla-resumen'>";
    echo "<tr><th colspan='2'>Datos del paciente</th></tr>";
    echo "<tr><td><strong>Nombre:</strong></td><td>" . htmlspecialchars($nombre) . " " . htmlspecialchars($apellido) . "</td></tr>";
    echo "<tr><td><strong>Cédula:</strong></td><td>" . htmlspecialchars($cedula) . "</td></tr>";
    echo "<tr><td><strong>Fecha y hora de la cita:</strong></td><td>" . htmlspecialchars($fecha) . " a las " . htmlspecialchars($hora) . "</td></tr>";
    echo "<tr><td><strong>Servicio:</strong></td><td>" . htmlspecialchars($tipo_servicio) . "</td></tr>";

    echo "<tr><th colspan='2'>Exámenes seleccionados</th></tr>";
    if (!empty($examenes_a_mostrar)) {
        echo "<tr><td colspan='2'>";
        echo "<table class='tabla-secundaria'>";
        echo "<tr><th>Nombre del examen</th><th>Precio</th></tr>";
        foreach ($examenes_a_mostrar as $examen) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($examen['nombre']) . "</td>";
            echo "<td>$" . number_format((float)$examen['precio'], 2) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</td></tr>";
    } else {
        echo "<tr><td colspan='2'>No se seleccionaron exámenes.</td></tr>";
    }

    echo "<tr><th colspan='2'>Resumen de costos</th></tr>";
    echo "<tr><td><strong>Subtotal (cita + exámenes):</strong></td><td>$" . number_format($totalbt, 2) . "</td></tr>";

    echo "<tr><td><strong>Descuento por promoción de octubre:</strong></td><td>" .
        ($descuento_octubre > 0 ? "-$" . number_format($descuento_octubre, 2) : "No aplica") . "</td></tr>";

    echo "<tr><td><strong>Descuento por jubilado:</strong></td><td>" .
        ($descuento_jubilado > 0 ? "-$" . number_format($descuento_jubilado, 2) : "No aplica") . "</td></tr>";

    echo "<tr><td><strong>Total a pagar:</strong></td><td><strong>$" . number_format($precio_final, 2) . "</strong></td></tr>";
    echo "</table><br>";
}
