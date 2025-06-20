<?php 
// Incluye el archivo de conexión en la cabecera 
$servername = "localhost"; 
$username = "root"; 

$password = "UTP.Guerrero2024"; 
$database = "sakila"; 
 
try { 
    // Intenta crear la conexión 
    $conn = new mysqli($servername, $username, $password, $database); 
 
    // Si hay un error de conexión, se lanzará una excepción 
    if ($conn->connect_error) { 
        throw new Exception("No se pudo conectar a la base de datos: " . $conn->connect_error); 
    } 
 
    // Si la conexión es exitosa, muestra un mensaje 
    echo "<div>¡Conexión exitosa a la base de datos!</div>"; 
 
    // Realiza aquí las operaciones con la base de datos 
 
    // Cierra la conexión 
    //$conn->close(); 
} catch (Exception $e) { 
    // Captura cualquier excepción y muestra un mensaje de error 
    echo "Error: " . $e->getMessage(); 
} 
?>