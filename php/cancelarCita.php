<?php
require_once __DIR__ . '/../conexion/configurar.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_cita'])) {
    $id = (int)$_POST['id_cita'];

    try {
        $stmt = $conn->prepare("UPDATE citas SET activo = 0 WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cita no encontrada o ya cancelada.']);
        }

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    } finally {
        $conn->close();
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Solicitud no válida.']);
}
