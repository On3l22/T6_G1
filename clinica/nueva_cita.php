/**
 * Inserta una nueva cita en la base de datos junto con los exámenes asociados.
 *
 * @param array $datos Arreglo asociativo que contiene los datos de la cita:
 *   - 'cedula' (string): Cédula del paciente.
 *   - 'tipo_servicio' (string): Tipo de servicio o médico.
 *   - 'fecha' (string): Fecha de la cita (formato 'YYYY-MM-DD').
 *   - 'hora' (string): Hora de la cita (formato 'HH:MM:SS').
 *   - 'metodo_pago' (string): Método de pago seleccionado.
 *   - 'examenes' (array, opcional): Lista de IDs de exámenes a asociar con la cita.
 *
 * @throws Exception Si ocurre un error al insertar la cita o los exámenes.
 *
 * Este método realiza la inserción de una nueva cita en la tabla 'citas' y,
 * si se proporcionan exámenes, los asocia en la tabla 'cita_examen'.
 * Utiliza sentencias preparadas para evitar inyecciones SQL.
 */
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
