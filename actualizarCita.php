<?php
include './conexion/configurar.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Método no permitido');
    }

    // Datos recibidos y sanitizados
    $cedula      = $_POST['cedula'] ?? '';
    $fecha       = $_POST['fecha'] ?? '';
    $hora        = $_POST['hora'] ?? '';
    $tipoMedico  = $_POST['tipo_medico'] ?? 'General';
    $primeraVez  = (isset($_POST['primera_cita']) && $_POST['primera_cita'] === 'Sí') ? 1 : 0;
    $pruebas     = $_POST['tipo_prueba'] ?? []; // Array con nombres de pruebas
    $citaId      = $_POST['cita_id'] ?? null;

    if (!$cedula || !$fecha || !$hora) {
        throw new Exception('Faltan datos obligatorios');
    }

    // Comenzar transacción para mantener integridad
    $conn->begin_transaction();

    // Insertar o actualizar cita
    if ($citaId) {
        // Actualizar cita existente
        $stmt = $conn->prepare("UPDATE citas SET tipo_medico=?, primera_vez=?, fecha=?, hora=?, activo=1 WHERE id=? AND cedula=?");
        $stmt->bind_param('sissis', $tipoMedico, $primeraVez, $fecha, $hora, $citaId, $cedula);
        $stmt->execute();
        if ($stmt->affected_rows === 0) {
            throw new Exception("No se pudo actualizar la cita o no existe");
        }
        $stmt->close();
    } else {
        // Insertar nueva cita
        $stmt = $conn->prepare("INSERT INTO citas (cedula, tipo_medico, primera_vez, fecha, hora, metodo_pago, activo) VALUES (?, ?, ?, ?, ?, 'Efectivo', 1)");
        // Usamos 'Efectivo' por defecto, ajustar según necesidad
        $stmt->bind_param('ssiss', $cedula, $tipoMedico, $primeraVez, $fecha, $hora);
        $stmt->execute();
        $citaId = $stmt->insert_id;
        $stmt->close();
    }

    // Sincronizar exámenes (cita_examen)
    // 1. Borrar los antiguos
    $stmt = $conn->prepare("DELETE FROM cita_examen WHERE cita_id = ?");
    $stmt->bind_param('i', $citaId);
    $stmt->execute();
    $stmt->close();

    // 2. Insertar los nuevos si hay
    if (!empty($pruebas)) {
        // Obtener los ids de exámenes para los nombres dados
        $placeholders = implode(',', array_fill(0, count($pruebas), '?'));
        $types = str_repeat('s', count($pruebas));
        $sql = "SELECT id, nombre FROM examenes WHERE nombre IN ($placeholders)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param($types, ...$pruebas);
        $stmt->execute();
        $result = $stmt->get_result();

        $examenIds = [];
        while ($row = $result->fetch_assoc()) {
            $examenIds[] = $row['id'];
        }
        $stmt->close();

        if (!empty($examenIds)) {
            $stmtInsert = $conn->prepare("INSERT INTO cita_examen (cita_id, examen_id) VALUES (?, ?)");
            foreach ($examenIds as $examenId) {
                $stmtInsert->bind_param('ii', $citaId, $examenId);
                $stmtInsert->execute();
            }
            $stmtInsert->close();
        }
    }

    $conn->commit();

    echo "Cita guardada correctamente.";

} catch (Exception $e) {
    if ($conn->in_transaction) {
        $conn->rollback();
    }
    echo "Error: " . $e->getMessage();
} finally {
    $conn->close();
}
?>
