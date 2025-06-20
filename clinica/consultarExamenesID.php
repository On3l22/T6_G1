
<?php
/**
 * Obtiene una lista de exámenes desde la base de datos.
 *
 * Conecta a la base de datos utilizando la configuración incluida,
 * ejecuta una consulta para recuperar todos los exámenes y sus respectivos
 * identificadores, nombres y precios. Devuelve un arreglo asociativo donde
 * la clave es el ID del examen y el valor es un arreglo con el nombre y precio.
 *
 * @return array Arreglo asociativo de exámenes, donde la clave es el ID y el valor es un arreglo con 'nombre' y 'precio'.
 */

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