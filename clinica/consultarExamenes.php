
<?php
/**
 * Consulta los exámenes médicos almacenados en la base de datos y los clasifica en dos categorías:
 * Urológicos y Ginecológicos. Los resultados se almacenan en los arrays $MG_Urologicos y $MG_Ginecologicos,
 * respectivamente, usando el ID del examen como clave y un array asociativo con el nombre y precio como valor.
 *
 * Proceso:
 * - Incluye el archivo de configuración para la conexión a la base de datos.
 * - Ejecuta una consulta SQL para obtener todos los exámenes con sus respectivos campos.
 * - Itera sobre los resultados y clasifica cada examen según su tipo.
 * - Maneja posibles excepciones mostrando un mensaje de error en el formulario y el detalle en la consola.
 * - Cierra la conexión a la base de datos al finalizar.
 *
 * Variables:
 * @var array $MG_Urologicos      Lista de exámenes urológicos (clave: id, valor: ['nombre', 'precio']).
 * @var array $MG_Ginecologicos   Lista de exámenes ginecológicos (clave: id, valor: ['nombre', 'precio']).
 * @var mysqli $conn              Conexión a la base de datos.
 */

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