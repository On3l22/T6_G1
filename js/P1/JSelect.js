// Espera a que todo el contenido HTML se haya cargado y parseado antes de ejecutar el script
document.addEventListener("DOMContentLoaded", () => {
  
  // Selecciona todos los elementos <input> tipo radio con nombre "sexo"
  const radiosSexo = document.querySelectorAll('input[name="sexo"]');
  
  // Obtiene el contenedor de checkboxes para hombres
  const checksHombres = document.getElementById("checks-hombres");
  
  // Obtiene el contenedor de checkboxes para mujeres
  const checksMujeres = document.getElementById("checks-mujeres");

  // Recorre cada radio button para añadir un listener al evento "change"
  radiosSexo.forEach(radio => {
    radio.addEventListener("change", function() {
      
      // Si el valor seleccionado es "masculino"
      if (this.value === "masculino") {
        // Muestra el contenedor de pruebas para hombres
        checksHombres.style.display = "block";
        // Oculta el contenedor de pruebas para mujeres
        checksMujeres.style.display = "none";

      // Si el valor seleccionado es "femenino"
      } else if (this.value === "femenino") {
        // Oculta el contenedor de pruebas para hombres
        checksHombres.style.display = "none";
        // Muestra el contenedor de pruebas para mujeres
        checksMujeres.style.display = "block";
      }
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
