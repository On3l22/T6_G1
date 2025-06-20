/**
 * Procesa la información de una cita médica.
 *
 * Esta función verifica si el paciente existe en la base de datos utilizando su cédula.
 * Si el paciente no existe, lo inserta como un nuevo paciente.
 * Luego, registra una nueva cita con los datos proporcionados.
 *
 * @param array $datos Arreglo asociativo que contiene los datos del paciente y la cita.
 *                     Debe incluir al menos la clave 'cedula' y los datos necesarios para la cita.
 * @throws Exception Si ocurre un error durante el procesamiento de la cita.
 */
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
