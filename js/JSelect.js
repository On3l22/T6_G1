// Espera a que todo el contenido HTML haya sido completamente cargado antes de ejecutar el script
document.addEventListener("DOMContentLoaded", () => {

  // Selecciona todos los inputs tipo radio que indican el sexo del paciente
  const radiosSexo = document.querySelectorAll('input[name="sexo"]');

  // Obtiene el contenedor de checkboxes exclusivos para pacientes masculinos
  const checksHombres = document.getElementById("checks-hombres");

  // Obtiene el contenedor de checkboxes exclusivos para pacientes femeninos
  const checksMujeres = document.getElementById("checks-mujeres");

  // Selecciona todos los inputs tipo radio que indican el tipo de atención médica
  const radioMedico = document.querySelectorAll('input[name="medico"]');

  // Variable bandera que indica si el tipo de atención seleccionado es "Especialista"
  let bandera = false;

  // Variable que almacena el sexo seleccionado ("masculino" o "femenino")
  let sexoSeleccionado = "";

  /**
   * Función que actualiza la visibilidad de los checkboxes según:
   * - Si se eligió "Especialista"
   * - Y el sexo seleccionado
   */
  function actualizarVisibilidad() {
    // Oculta ambos contenedores de checkboxes por defecto
    checksHombres.style.display = "none";
    checksMujeres.style.display = "none";

    // Si la atención es "Especialista" y se ha seleccionado un sexo válido
    if (bandera) {
      if (sexoSeleccionado === "masculino") {
        // Muestra opciones para hombres
        checksHombres.style.display = "block";
      } else if (sexoSeleccionado === "femenino") {
        // Muestra opciones para mujeres
        checksMujeres.style.display = "block";
      }
    }
  }

  // Asigna un listener a cada radio de tipo de atención médica
  radioMedico.forEach(radio => {
    radio.addEventListener("change", function() {
      // Si el valor es "Medicina general", desactiva la bandera y oculta todo
      if (this.value === "General") {
        bandera = false;
      }
      // Si es "Especializado", activa la bandera
      else if (this.value === "Especializado") {
        bandera = true;
      }

      // Actualiza visibilidad por si ya hay un sexo previamente seleccionado
      actualizarVisibilidad();
    });
  });

  // Asigna un listener a cada radio de sexo
  radiosSexo.forEach(radio => {
    radio.addEventListener("change", function() {
      // Guarda el sexo elegido
      sexoSeleccionado = this.value;

      // Actualiza visibilidad según tipo de atención y nuevo sexo
      actualizarVisibilidad();
    });
  });
});


// Espera a que todo el DOM esté completamente cargado antes de ejecutar el script
document.addEventListener("DOMContentLoaded", () => {

  // Se seleccionan todos los radio buttons del grupo "metodoPago"
  const radios = document.querySelectorAll('input[name="metodoPago"]');

  // Se almacenan las referencias a los contenedores de inputs específicos de cada método
  const inputsEfectivo = document.getElementById("inputsEfectivo");
  const inputsCredito = document.getElementById("inputsCredito");
  const inputsDebito = document.getElementById("inputsDebito");

  /**
   * Esta función oculta todos los contenedores de inputs de pago
   * y resetea los campos de tarjetas para evitar errores de validación
   */
  function ocultarTodos() {
    // Oculta todos los contenedores
    inputsEfectivo.classList.add("d-none");
    inputsCredito.classList.add("d-none");
    inputsDebito.classList.add("d-none");

    // Limpia los valores y elimina "required" de los inputs de tarjetas
    [inputsCredito, inputsDebito].forEach(container => {
      container.querySelectorAll("input").forEach(input => {
        input.required = false;   // Se quita el atributo required para evitar validaciones innecesarias
        input.value = "";         // Se limpia el contenido del input
      });
    });
  }

  // Se agrega un evento a cada radio button para reaccionar cuando cambia el método de pago
  radios.forEach(radio => {
    radio.addEventListener("change", () => {
      // Primero se ocultan todos los contenedores e inputs relacionados
      ocultarTodos();

      // Luego se muestra el contenedor correspondiente según el valor del radio seleccionado
      if (radio.value === "efectivo") {
        inputsEfectivo.classList.remove("d-none");
        // No se requiere información adicional para pago en efectivo

      } else if (radio.value === "tarjeta_credito") {
        inputsCredito.classList.remove("d-none");
        // Se aplica "required" a los campos del formulario de tarjeta de crédito
        inputsCredito.querySelectorAll("input").forEach(input => input.required = true);

      } else if (radio.value === "tarjeta_debito") {
        inputsDebito.classList.remove("d-none");
        // Se aplica "required" a los campos del formulario de tarjeta de débito
        inputsDebito.querySelectorAll("input").forEach(input => input.required = true);
      }
    });
  });

});
