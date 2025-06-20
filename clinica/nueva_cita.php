<?php
function insertarNuevaCita($datos)
{

    include(__DIR__ . '/../conexion/configurar.php');

    try {
        // Insertar cita
        $sql = "INSERT INTO citas (cedula, tipo_medico, fecha, hora, metodo_pago)
        VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssss",
            $datos['cedula'],
            $datos['tipo_servicio'],
            $datos['fecha'],
            $datos['hora'],
            $datos['metodo_pago']
        );

        $stmt->execute();
        $cita_id = $stmt->insert_id;
        $stmt->close();

        // Insertar exámenes
        if (!empty($datos['examenes'])) {
            $stmtInsert = $conn->prepare("INSERT INTO cita_examen (cita_id, examen_id) VALUES (?, ?)");

            foreach ($datos['examenes'] as $examen_id) {
                $stmtInsert->bind_param("ii", $cita_id, $examen_id);
                $stmtInsert->execute();
            }

            $stmtInsert->close();
        }
    } catch (Exception $e) {
        throw new Exception("Error al insertar cita: " . $e->getMessage());
    } finally {
        if ($conn) {
            $conn->close();
        }
    }
}
