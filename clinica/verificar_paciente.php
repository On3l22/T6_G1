
<?php
/**
 * Verifica si un paciente existe en la base de datos según su cédula.
 *
 * Esta función recibe una cédula como parámetro y consulta la base de datos
 * para determinar si existe un paciente registrado con dicha cédula.
 * 
 * @param string $cedula La cédula del paciente a verificar.
 * @return bool Retorna true si el paciente existe, false en caso contrario.
 * @throws Exception Lanza una excepción si ocurre un error durante la verificación.
 */

function pacienteExiste($cedula)
{
    include(__DIR__ . '/../conexion/configurar.php');

    $existe = false;

    try {
        $stmt = $conn->prepare("SELECT cedula FROM pacientes WHERE cedula = ?");
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $stmt->store_result();

        $existe = $stmt->num_rows > 0;

        $stmt->close();
    } catch (Exception $e) {
        throw new Exception("Error al verificar paciente: " . $e->getMessage());
    } finally {
        if ($conn) {
            $conn->close();
        }
    }

    return $existe;
}
