
<?php
/**
 * Inserta un nuevo paciente en la base de datos.
 *
 * Esta función recibe un arreglo asociativo con los datos del paciente y realiza
 * la inserción en la tabla 'pacientes' utilizando una consulta preparada para evitar
 * inyecciones SQL. Al finalizar, cierra la conexión a la base de datos.
 *
 * @param array $datos Arreglo asociativo con los siguientes campos:
 *   - 'cedula' (string): Cédula del paciente.
 *   - 'nombre' (string): Nombre del paciente.
 *   - 'apellido' (string): Apellido del paciente.
 *   - 'edad' (int): Edad del paciente.
 *   - 'sexo' (string): Sexo del paciente.
 *   - 'provincia' (string): Provincia de residencia.
 *   - 'distrito' (string): Distrito de residencia.
 *   - 'ciudad' (string): Ciudad de residencia.
 *   - 'calle' (string): Dirección o calle.
 *   - 'celular' (string): Teléfono móvil.
 *   - 'telefono' (string): Teléfono de casa.
 *
 * @throws Exception Si ocurre un error durante la inserción del paciente.
 */
function insertarNuevoPaciente($datos)
{

    include(__DIR__ . '/../conexion/configurar.php');

    try {
        $sql = "INSERT INTO pacientes 
            (cedula, nombre, apellido, edad, sexo, provincia, distrito, ciudad, direccion, telefono_movil, telefono_casa)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssisssssss",
            $datos['cedula'],
            $datos['nombre'],
            $datos['apellido'],
            $datos['edad'],
            $datos['sexo'],
            $datos['provincia'],
            $datos['distrito'],
            $datos['ciudad'],
            $datos['calle'],
            $datos['celular'],
            $datos['telefono']
        );
        $stmt->execute();
        $stmt->close();
    } catch (Exception $e) {
        throw new Exception("Error al insertar paciente: " . $e->getMessage());
    } finally {
        if ($conn) {
            $conn->close();
        }
    }
}
