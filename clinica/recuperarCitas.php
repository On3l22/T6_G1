/**
 * Recupera la información de todos los pacientes y sus citas activas con exámenes asociados.
 *
 * Proceso:
 * 1. Incluye la configuración de conexión a la base de datos.
 * 2. Consulta todos los pacientes registrados en la base de datos.
 * 3. Para cada paciente:
 *    - Obtiene y almacena su información personal (nombre, apellido, edad, sexo, provincia, distrito, ciudad, dirección y teléfono).
 *    - Consulta todas sus citas activas (citas con campo 'activo' igual a 1), junto con los exámenes asociados a cada cita.
 *    - Organiza las citas y sus exámenes en un arreglo estructurado.
 * 4. Maneja errores de conexión y de consulta mediante excepciones.
 * 5. Cierra la conexión a la base de datos al finalizar el proceso.
 *
 * Variables:
 * - $pacientes: Arreglo asociativo que almacena la información de cada paciente y sus citas.
 * - $conn: Objeto de conexión a la base de datos.
 *
 * Notas:
 * - El teléfono mostrado será el móvil si está disponible, de lo contrario el de casa.
 * - Las citas se agrupan por paciente y se listan solo las activas.
 * - Cada cita puede tener múltiples exámenes asociados.
 */
<?php
// ---------------------------------------------
// Script: recuperarCitas.php
// Propósito: Recuperar los datos de todos los pacientes y sus citas activas con los exámenes asignados.
// ---------------------------------------------

// Incluir archivo de configuración para conectar a la base de datos
include('./conexion/configurar.php');

// Inicializa el array que contendrá toda la información de los pacientes
$pacientes = [];

try {
    // Verifica si hubo error de conexión con la base de datos
    if ($conn->connect_error) {
        throw new Exception("Error de conexión: " . $conn->connect_error);
    }

    // ---------------------------------------------------
    // 1. Consultar todos los pacientes en la base de datos
    // ---------------------------------------------------
    $queryPacientes = "SELECT * FROM pacientes";
    $resultPacientes = $conn->query($queryPacientes);

    if (!$resultPacientes) {
        throw new Exception("Error al consultar pacientes: " . $conn->error);
    }

    // Recorrer cada paciente encontrado
    while ($pac = $resultPacientes->fetch_assoc()) {
        $cedula = $pac['cedula']; // Se usa como clave única

        // Guardar información básica del paciente
        $pacientes[$cedula]['info'] = [
            'nombre'    => $pac['nombre'],
            'apellido'  => $pac['apellido'],
            'edad'      => $pac['edad'],
            'sexo'      => $pac['sexo'],
            'provincia' => $pac['provincia'],
            'distrito'  => $pac['distrito'],
            'ciudad'    => $pac['ciudad'],
            'direccion' => $pac['direccion'],
            // Se prioriza teléfono móvil; si no hay, se usa teléfono de casa
            'telefono'  => $pac['telefono_movil'] ?: $pac['telefono_casa']
        ];

        // Inicializar array de citas
        $pacientes[$cedula]['citas'] = [];

        // ------------------------------------------------------------
        // 2. Consultar citas activas del paciente junto con sus exámenes
        // ------------------------------------------------------------
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

        // Recorre las filas devueltas por la consulta (una fila por examen asociado)
        while ($row = $resultCitas->fetch_assoc()) {
            $idCita = $row['id'];

            // Si esta cita aún no ha sido agregada al arreglo
            if (!isset($pacientes[$cedula]['citas'][$idCita])) {
                $fechaHora = $row['fecha'] . 'T' . substr($row['hora'], 0, 5); // Formato datetime-local
                $pacientes[$cedula]['citas'][$idCita] = [
                    'id'           => $idCita,
                    'fecha'        => $fechaHora,
                    'tipo_medico'  => $row['tipo_medico'],
                    'pruebas'      => [] // Inicializa como array vacío
                ];
            }

            // Agrega examen si existe
            if ($row['examen_id']) {
                $pacientes[$cedula]['citas'][$idCita]['pruebas'][] = (int)$row['examen_id'];
            }
        }

        // Reindexa las citas con claves numéricas (0, 1, 2...) para facilitar su uso en formularios
        $pacientes[$cedula]['citas'] = array_values($pacientes[$cedula]['citas']);
    }

} catch (Exception $e) {
    // Captura errores de conexión o de ejecución de consultas
    echo "<strong>Error:</strong> " . $e->getMessage();
} finally {
    // Cierra conexión con la base de datos si fue establecida
    if ($conn) {
        $conn->close();
    }
}
?>
