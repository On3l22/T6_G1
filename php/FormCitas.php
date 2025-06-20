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
           <?php include __DIR__ . '/../includesHTML/SeccionPaciente.html' ?>
        </div>
        <!-- FIN sección: Información del paciente -->





        <!-- INICIO sección 2 -->
        <div class="row">
            <div class="col-md-6 mb-4"><!-- INICIO lado izquierdo-->
                  <?php include __DIR__ . '/../includesHTML/SeccionCita.html' ?><!-- llamar a la sección de la cita -->
            </div><!-- FIN lado izquierdo-->

            <div class="col-md-6 mb-4"><!-- INICIO lado derecho-->
                 <?php include __DIR__ . '/../includesHTML/SeccionPago.html' ?><!-- llamar a la sección del pago -->
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

    <div class="row" id="salida"></div>

</div>
    <!-- FIN contenedor principal -->