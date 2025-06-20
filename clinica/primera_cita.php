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
