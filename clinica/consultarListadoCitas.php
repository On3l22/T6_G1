<?php
function listarCitasRecientes(int $limite = 10) {
    require_once __DIR__ . '/../conexion/configurar.php';

    try {
        $sql = "
            SELECT 
                c.fecha, 
                c.hora,
                c.tipo_medico, 
                c.metodo_pago,
                p.nombre,
                p.apellido,
                p.cedula
            FROM citas c
            JOIN pacientes p ON c.cedula = p.cedula
            WHERE c.activo = 1
            ORDER BY c.fecha DESC, c.hora DESC
            LIMIT ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $limite);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h3>Citas recientes activas</h3>";
        echo "<table class='tabla-citas' border='1' cellpadding='8' cellspacing='0'>";
        echo "<tr>
                <th>Nombre</th>
                <th>Cédula</th>
                <th>Tipo de Médico</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Método de Pago</th>
              </tr>";

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['nombre']) . " " . htmlspecialchars($row['apellido']) . "</td>";
                echo "<td>" . htmlspecialchars($row['cedula']) . "</td>";
                echo "<td>" . htmlspecialchars($row['tipo_medico']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fecha']) . "</td>";
                echo "<td>" . htmlspecialchars(substr($row['hora'], 0, 5)) . "</td>";
                echo "<td>" . htmlspecialchars($row['metodo_pago']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No hay citas activas registradas.</td></tr>";
        }

        echo "</table>";

    } catch (Exception $e) {
        echo "<div style='color:red;'>Error al obtener las citas: " . htmlspecialchars($e->getMessage()) . "</div>";
    } finally {
        $conn->close();
    }
}
