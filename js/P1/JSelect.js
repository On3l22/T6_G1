
document.addEventListener("DOMContentLoaded", function () {
    const radios = document.querySelectorAll('input[name="sexo"]');
    const selectHombres = document.getElementById("select-hombres");
    const selectMujeres = document.getElementById("select-mujeres");

    radios.forEach((radio) => {
        radio.addEventListener("change", function () {
            if (this.value === "masculino") {
                selectHombres.style.display = "block";
                selectMujeres.style.display = "none";
                selectHombres.required = true;
                selectMujeres.required = false;
            } else if (this.value === "femenino") {
                selectHombres.style.display = "none";
                selectMujeres.style.display = "block";
                selectHombres.required = false;
                selectMujeres.required = true;
            }
        });
    });
});

