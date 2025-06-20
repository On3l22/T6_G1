<?php
function procesarCita($datos)
{
    try {

        require_once 'verificar_paciente.php';
        require_once 'nuevo_paciente.php';
        require_once 'nueva_cita.php';

        if (!pacienteExiste($datos['cedula'])) {
            insertarNuevoPaciente($datos);
        }

        // Insertar cita
        insertarNuevaCita($datos);
    } catch (Exception $e) {
        throw new Exception("Error al procesar la cita: " . $e->getMessage());
    } finally {
    }
}
