<?php
// Incluye el archivo de conexión en la cabecera 
$servername = "localhost";
$username = "root";
$password = "Kasino";
$database = "sakila";

try {
    // Intenta crear la conexión 
    $conn = new mysqli($servername, $username, $password, $database);

    // Si hay un error de conexión, se lanzará una excepción 
    if ($conn->connect_error) {
        throw new Exception("No se pudo conectar a la base de datos: " . $conn->connect_error);
    }

    // Si la conexión es exitosa, muestra un mensaje 
    //echo "¡Conexión exitosa a la base de datos!"; 

    // Realiza aquí las operaciones con la base de datos 

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    } else {
        echo "¡Conectado correctamente a MySQL y la base de datos sakila!";
    }
    // Cierra la conexión 
    // $conn->close();
} catch (Exception $e) {
    // Captura cualquier excepción y muestra un mensaje de error 
    echo "Error: " . $e->getMessage();
}
?>