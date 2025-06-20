/**
 * Verifica si una persona está agendando su primera cita.
 *
 * Esta función consulta la base de datos para determinar si la cédula proporcionada
 * ya tiene citas registradas. Si no existen citas previas, retorna true, indicando
 * que es la primera cita; de lo contrario, retorna false.
 *
 * @param string $cedula La cédula de la persona a verificar.
 * @return bool true si es la primera cita, false en caso contrario.
 * @throws Exception Si ocurre un error al consultar la base de datos.
 */
<?php
function esPrimeraCita($cedula) {
     include(__DIR__ . '/../conexion/configurar.php');

    try {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM citas WHERE cedula = ?");
        $stmt->bind_param("s", $cedula);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        return $total == 0;

    } catch (Exception $e) {
        throw new Exception("Error al verificar si es primera cita: " . $e->getMessage());
    } finally {
        if ($conn) {
            $conn->close();
        }
    }
}
