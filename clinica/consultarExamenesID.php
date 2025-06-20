<?php
function obtenerExamenesPorID() {
include(__DIR__ . '/../conexion/configurar.php');

    $examenes = [];

    $sql = "SELECT id, nombre, precio FROM examenes";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $id = (int)$row['id'];
        $examenes[$id] = [
            'nombre' => $row['nombre'],
            'precio' => (float)$row['precio']
        ];
    }

    $conn->close();
    return $examenes;
}

?>