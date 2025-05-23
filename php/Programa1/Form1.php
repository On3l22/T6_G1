<?php
// Definir arrays de pruebas
$MG_Urologicos = [
    'Biopsia de próstata transperineal dirigida' => 60,
    'Cistoscopia' => 55,
    'Ecografía urológica' => 70,
    'Flujometría' => 70,
    'PSA (Antígeno Prostático Específico)' => 35,
    'Urodinamia' => 80
];

$MG_Ginecologicos = [
    'Biopsias de diferentes tejidos' => 60,
    'Ecografía ginecológica' => 40,
    'Histeroscopia' => 60,
    'Mamografía' => 50,
    'Ecografía mamaria' => 50,
    'Pruebas para el estudio de la fertilidad' => 100
];
?>

<!-- INICIO contenedor principal -->
<div class="container my-5">
    <h1 class="mb-4">Solicitar Cita Médica</h1>

    <!-- INICIO formulario -->
    <form name="formCita" method="POST" action="" id="formCita" onsubmit="mostrarResumen(); return false;">

        <!-- INICIO sección: Información del paciente -->
        <div class="row mb-4">
            <?php include("../../includesHTML/Programa1/SeccionPaciente.html") ?>
        </div>
        <!-- FIN sección: Información del paciente -->





        <!-- INICIO sección 2 -->
        <div class="row">
            <div class="col-md-6 mb-4"><!-- INICIO lado izquierdo-->
                <?php include("../../includesHTML/Programa1/SeccionCita.html") ?><!-- llamar a la sección de la cita -->
            </div><!-- FIN lado izquierdo-->

            <div class="col-md-6 mb-4"><!-- INICIO lado derecho-->
                <?php include("../../includesHTML/Programa1/SeccionPago.html") ?><!-- llamar a la sección del pago -->
            </div><!-- FIN lado derecho-->
        </div> <!-- FIN sección 2 -->


        <!-- INICIO botón de envío -->
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Enviar Solicitud</button>
        </div>
        <!-- FIN botón de envío -->

    </form>
    <!-- FIN formulario -->

    <!-- Salida del formulario -->

    <div class="row" id="salida">

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Función que se ejecuta al hacer clic en "Generar"
        function mostrarResumen() {
            $.ajax({
                // Ruta al archivo PHP que procesará la opción seleccionada
                url: 'validarDatos.php',

                // Método de envío
                type: 'POST',

                // Datos del formulario serializados (opciones)
                data: $('#formCita').serialize(),

                // Función que se ejecuta si la petición tiene éxito
                success: function (data) {
                    // Inserta la respuesta (tabla) dentro del div con id="salida"
                    $('#salida').html(data);
                },

                // Función que se ejecuta si ocurre un error
                error: function () {
                    alert('Error al obtener el resumen');
                }
            });
        }
    </script>
    <!-- FIN contenedor principal -->