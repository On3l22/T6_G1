<?php
require_once 'ProcesarDatos';
require_once 'Salida.php';

try {
    // Obtenemos los valores del formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Capturar datos del formulario del cliente
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $cedula = $_POST['cedula'];
        $edad = $_POST['edad'];
        $genero = isset($_POST['sexo']) ? $_POST['sexo'] : '';
        $provincia = $_POST['provincia'];
        $distrito = $_POST['distrito'];
        $ciudad = $_POST['ciudad'];
        $calle = $_POST['calle'];
        $celular = $_POST['celular'];
        $telefono = $_POST['telefonoCasa'];
        $metodo_pago = $_POST['metodoPago'];


        // Captura de los datos del formulario de servicios
        $tipo_servicio = $_POST['medico'];
        $primera_cita = isset($_POST['primera-cita']) ? $_POST['primera-cita'] : "No";
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];

        if ($genero == "masculino") {
            $examenes_seleccionados = isset($_POST['pruebasHombre']) ? $_POST['pruebasHombre'] : [];
        } else {
            $examenes_seleccionados = isset($_POST['pruebasMujer']) ? $_POST['pruebasMujer'] : [];
        }

        $dia = date('w', strtotime($fecha)); // 0=domingo, 6=sábado

        if ($dia >= 1 && $dia <= 5) {
            if (!(($hora >= "08:00" && $hora <= "12:00") || ($hora >= "13:00" && $hora <= "18:00"))) {
                throw new Exception("Las citas se agendan de lunes a viernes de 8:00am a 12:00md y de 1:00pm a 6:00pm");
            }
        } elseif ($dia == 6) {
            if (!($hora >= "08:00" && $hora <= "12:00")) {
                throw new Exception("Las citas del sábado se agendan de 8:00am a 12:00md");
            }
        } elseif ($dia == 0) {
            throw new Exception("No se agendan citas los domingos.");
        }


        $procesar = new ProcesarDatos($edad, $genero, $primera_cita, $tipo_servicio, $examenes_seleccionados, $fecha);

        $totalbt = $procesar->CalcularTotalBruto();

        // validacion de metodo de pago
        if ($metodo_pago == "tarjeta_credito") {
            if ($totalbt <= 20) {
                throw new Exception("El monto a pagar debe ser superior a $20.00 para poder pagar con tarjeta de crédito.");
            }
        }
        // Calculamos promedio a través de una función
        $descuento1 = $procesar->CalcularDescuentoJubilado();
        $descuento2 = $procesar->CalcularDescuentoOctubre();

        // Calculamos promedio a través de una función
        $precio = $procesar->CalcularPrecio($totalbt, $descuento1, $descuento2);

        mostrarResumenCita(
            $nombre,
            $apellido,
            $cedula,
            $fecha,
            $tipo_servicio,
            $examenes_seleccionados,
            $totalbt,
            $descuento1,
            $descuento2,
            $precio,
            $hora
        );
    }

} catch (Exception $e) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
        . htmlspecialchars($e->getMessage()) .
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
