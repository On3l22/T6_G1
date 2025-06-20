<?php
// Incluir la configuración de conexión
include(__DIR__ . '/../conexion/configurar.php');

// Inicializar arrays
$MG_Urologicos = [];
$MG_Ginecologicos = [];

try {

    $sql = "SELECT id, nombre, precio, tipo FROM examenes";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $id = (int)$row['id'];
        $item = ['nombre' => $row['nombre'], 'precio' => (float)$row['precio']];

        if ($row['tipo'] === 'Urológico') {
            $MG_Urologicos[$id] = $item;
        } elseif ($row['tipo'] === 'Ginecológico') {
            $MG_Ginecologicos[$id] = $item;
        }
    }

} catch (Exception $e) {
    // Mensaje visible en el formulario
    echo "<div style='color:red;'>Error al cargar el formulario.</div>";
    // Stack trace en la consola del navegador
    echo "<script>console.error(" . json_encode($e->getMessage() . '\n' . $e->getTraceAsString()) . ");</script>";
} finally {
    // Cerrar conexión si existe
    if ($conn) {
        $conn->close();
    }
}