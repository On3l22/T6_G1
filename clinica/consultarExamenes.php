<?php
// Incluir la configuración de conexión
include('./conexion/configurar.php');

// Inicializar arrays
$MG_Urologicos = [];
$MG_Ginecologicos = [];

try {
    // Realizar la consulta
    $sql = "SELECT nombre, precio, tipo FROM examenes";
    $result = $conn->query($sql);

    if (!$result) {
        throw new Exception("Error Al Cargar el Formulario: " . $conn->error);
    }

    while ($row = $result->fetch_assoc()) {
        $nombre = $row['nombre'];
        $precio = (float)$row['precio'];
        $tipo = $row['tipo'];

        if ($tipo === 'Urológico') {
            $MG_Urologicos[$nombre] = $precio;
        } elseif ($tipo === 'Ginecológico') {
            $MG_Ginecologicos[$nombre] = $precio;
        }
    }

} catch (Exception $e) {
    echo "Ha ocurrido un error: " . $e->getMessage();
} finally {
    // Cerrar conexión si existe
    if ($conn) {
        $conn->close();
    }
}
