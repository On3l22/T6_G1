<?php
// Incluir la configuración de conexión
include('./conexion/configurar.php');

$pacientes = [];

try {
    // Verificar si la conexión fue exitosa
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    // 1. Obtener todos los pacientes
    $queryPacientes = "SELECT * FROM pacientes";
    $resultPacientes = $conn->query($queryPacientes);
    
    if (!$resultPacientes) {
        throw new Exception("Error al consultar pacientes: " . $conn->error);
    }

    while ($pac = $resultPacientes->fetch_assoc()) {
        $cedula = $pac['cedula'];

        $pacientes[$cedula]['info'] = [
            'nombre'    => $pac['nombre'],
            'apellido'  => $pac['apellido'],
            'edad'      => $pac['edad'],
            'sexo'      => $pac['sexo'],
            'provincia' => $pac['provincia'],
            'distrito'  => $pac['distrito'],
            'ciudad'    => $pac['ciudad'],
            'direccion' => $pac['direccion'],
            'telefono'  => $pac['telefono_movil'] ?: $pac['telefono_casa']
        ];

        $pacientes[$cedula]['citas'] = [];

        // 2. Obtener citas activas con ID de exámenes
        $queryCitas = "
            SELECT c.id, c.fecha, c.hora, c.tipo_medico,
                   ce.examen_id, c.activo
            FROM citas c
            LEFT JOIN cita_examen ce ON ce.cita_id = c.id
            WHERE c.cedula = '$cedula'
              AND c.activo = 1
            ORDER BY c.fecha, c.hora
        ";

        $resultCitas = $conn->query($queryCitas);

        if (!$resultCitas) {
            throw new Exception("Error al consultar citas para $cedula: " . $conn->error);
        }

        while ($row = $resultCitas->fetch_assoc()) {
            $idCita = $row['id'];

            if (!isset($pacientes[$cedula]['citas'][$idCita])) {
                $fechaHora = $row['fecha'] . 'T' . substr($row['hora'], 0, 5);
                $pacientes[$cedula]['citas'][$idCita] = [
                    'id'           => "cita-" . $idCita,
                    'fecha'        => $fechaHora,
                    'tipo_medico'  => $row['tipo_medico'],
                    'pruebas'      => []
                ];
            }

            if ($row['examen_id']) {
                $pacientes[$cedula]['citas'][$idCita]['pruebas'][] = (int)$row['examen_id'];
            }
        }

        // Convertir las citas a array numerado
        $pacientes[$cedula]['citas'] = array_values($pacientes[$cedula]['citas']);
    }

} catch (Exception $e) {
    echo "<strong>Error:</strong> " . $e->getMessage();
} finally {
    if ($conn) {
        $conn->close();
    }
}
