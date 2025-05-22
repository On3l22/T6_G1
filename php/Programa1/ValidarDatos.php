<?php
require_once 'clases/ProcesarDatos.php';
// Obtenemos los valores del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar datos del formulario del cliente
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $tomo = $_POST['tomo'];
    $folio = $_POST['folio'];
    $asiento = $_POST['asiento'];
    $edad = $_POST['edad'];
    $genero = isset($_POST['radiogenero']) ? $_POST['radiogenero'] : '';
    $provincia = $_POST['provincia'];
    $distrito = $_POST['distrito'];
    $calle = $_POST['calle'];
    $celular = $_POST['celular'];

    // Captura de los datos del formulario de servicios
    $tipo_servicio = $_POST['tiposervicio'];
    $primera_cita = isset($_POST['check13']) ? $_POST['check13'] : "No";
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    // Array para guardar los exámenes seleccionados
    $examenes_seleccionados = [];
    // Instanciamos el objeto ProcesarDatos
    $procesar = new ProcesarDatos($edad, $genero, $primera_cita, $tipo_servicio, $total, $fecha);

    // Calculamos el total bruto
    $totalbt = $procesar->CalcularTotalBruto();

    // Calculamos promedio a través de una función
    $descuento1 = $procesar->CalcularDescuentoJubilado();
    $descuento2 = $procesar->CalcularDescuentoOctubre();

    // Calculamos promedio a través de una función
    $precio = $procesar->CalcularPrecio();

}
