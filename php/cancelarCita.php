/**
 * Script para cancelar una cita en la base de datos.
 *
 * Este archivo recibe una solicitud POST con el parámetro 'id_cita' y marca la cita correspondiente como inactiva (activo = 0).
 * 
 * Flujo del script:
 * 1. Incluye la configuración de la conexión a la base de datos.
 * 2. Establece el encabezado de respuesta como JSON.
 * 3. Verifica que la solicitud sea POST y que se haya enviado 'id_cita'.
 * 4. Prepara y ejecuta una consulta SQL para actualizar el estado de la cita.
 * 5. Devuelve una respuesta JSON indicando si la operación fue exitosa o si ocurrió algún error.
 * 
 * 
 * Parámetros de entrada:
 * - id_cita (int): ID de la cita a cancelar.
 * 
 * Respuestas posibles (JSON):
 * - { "success": true } si la cita fue cancelada exitosamente.
 * - { "success": false, "message": "Cita no encontrada o ya cancelada." } si no se encontró la cita o ya estaba cancelada.
 * - { "success": false, "message": "Solicitud no válida." } si la solicitud no es POST o falta el parámetro.
 * - { "success": false, "message": "Error message" } si ocurre una excepción.
 */
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
