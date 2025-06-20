<?php
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
