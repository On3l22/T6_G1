<?php
function insertarNuevaCita($datos)
{

    include(__DIR__ . '/../conexion/configurar.php');

    try {
        // Insertar cita
        $sql = "INSERT INTO citas (cedula, tipo_medico, primera_vez, fecha, hora, metodo_pago)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $primera = ($datos['primera_cita'] == "Si") ? 1 : 0;
        $stmt->bind_param(
            "ssisss",
            $datos['cedula'],
            $datos['tipo_servicio'],
            $primera,
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
