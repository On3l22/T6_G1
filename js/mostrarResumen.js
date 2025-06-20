// Función que se ejecuta al hacer clic en "Generar"
function mostrarResumen() {
    $.ajax({
        // Ruta al archivo PHP que procesará la opción seleccionada
        url: './php/validarDatos.php',

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