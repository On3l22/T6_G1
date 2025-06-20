<?php
require_once 'ProcesarDatos.php';
require_once 'Salida.php';
require_once __DIR__ . '/../clinica/zProceso_Citas.php';
require_once __DIR__ . '/../clinica/primera_cita.php';

try {
    // Verifica si la solicitud proviene de un formulario por método POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // -----------------------------
        // Captura de datos personales
        // -----------------------------
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

        // -----------------------------
        // Captura de datos del servicio
        // -----------------------------
        $tipo_servicio = $_POST['medico'];
        $esPrimera = esPrimeraCita($cedula);
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];

        // Selección de exámenes según género
        if ($genero == "masculino") {
            $examenes_seleccionados = isset($_POST['pruebasHombre']) ? $_POST['pruebasHombre'] : [];
        } else {
            $examenes_seleccionados = isset($_POST['pruebasMujer']) ? $_POST['pruebasMujer'] : [];
        }


        // -----------------------------
        // Validación de horario
        // -----------------------------
        $dia = date('w', strtotime($fecha)); // 0=domingo, 6=sábado

        if ($dia >= 1 && $dia <= 5) {
            // Lunes a viernes: 8:00–12:00 y 13:00–18:00
            if (!(($hora >= "08:00" && $hora <= "12:00") || ($hora >= "13:00" && $hora <= "18:00"))) {
                throw new Exception("Las citas se agendan de lunes a viernes de 8:00am a 12:00md y de 1:00pm a 6:00pm");
            }
        } elseif ($dia == 6) {
            // Sábado: solo de 8:00–12:00
            if (!($hora >= "08:00" && $hora <= "12:00")) {
                throw new Exception("Las citas del sábado se agendan de 8:00am a 12:00md");
            }
        } elseif ($dia == 0) {
            // Domingo no se agenda
            throw new Exception("No se agendan citas los domingos.");
        }

        // -----------------------------
        // Cálculo de precios y descuentos
        // -----------------------------
        $procesar = new ProcesarDatos(
            $edad,
            $genero,
            $esPrimera ? "Si" : "No",
            $tipo_servicio,
            $examenes_seleccionados,
            $fecha
        );

        $precio = $procesar->CalcularPrecio(); // Precio final tras aplicar descuentos

        // -----------------------------
        // Validación de método de pago
        // -----------------------------
        if ($metodo_pago == "tarjeta_credito") {
            if ($precio <= 20) {
                throw new Exception("El monto a pagar debe ser superior a $20.00 para poder pagar con tarjeta de crédito.");
            }
        }

        // -----------------------------
        // Mostrar resumen final de la cita
        // -----------------------------
        mostrarResumenCita(
            $nombre,
            $apellido,
            $cedula,
            $fecha,
            $tipo_servicio,
            $examenes_seleccionados,
            $procesar->totalbrt,
            $procesar->descjubilado,
            $procesar->descuento_octubre,
            $precio,
            $hora
        );



        $datos = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'cedula' => $cedula,
            'edad' => $edad,
            'sexo' => $genero,
            'provincia' => $provincia,
            'distrito' => $distrito,
            'ciudad' => $ciudad,
            'calle' => $calle,
            'celular' => $celular,
            'telefono' => $telefono,
            'tipo_servicio' => $tipo_servicio,
            'fecha' => $fecha,
            'hora' => $hora,
            'metodo_pago' => $metodo_pago,
            'examenes' => $examenes_seleccionados
        ];

        procesarCita($datos);
    }
} catch (Exception $e) {
    // Mostrar mensaje de error personalizado
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">'
        . htmlspecialchars($e->getMessage()) .
        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
