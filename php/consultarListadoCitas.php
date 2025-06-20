<h2>Citas recientes activas</h2>
<div id="contenedor-citas"></div>
<div class="paginacion">
    <button class="btn btn-primary btn-sm me-2" onclick="cargarCitas(paginaActual - 1)">Anterior</button>
    <span id="pagina-actual" class="fw-bold me-2">1</span>
    <button class="btn btn-primary btn-sm" onclick="cargarCitas(paginaActual + 1)">Siguiente</button>
</div>

<script>
    let paginaActual = 1;
    const citasPorPagina = 5;

    function cargarCitas(pagina) {
        if (pagina < 1) return;
        fetch(`./php/cargarCitas.php?pagina=${pagina}&limite=${citasPorPagina}`)
            .then(res => res.text())
            .then(html => {
                document.getElementById("contenedor-citas").innerHTML = html;
                paginaActual = pagina;
                document.getElementById("pagina-actual").textContent = pagina;
            });
    }

    function cancelarCita(idCita) {
        if (!confirm("¿Desea cancelar esta cita?")) return;

        fetch('./php/cancelarCita.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id_cita=${idCita}`
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("Cita cancelada.");
                    cargarCitas(paginaActual);
                } else {
                    alert("Error: " + data.message);
                }
            });
    }

    // Cargar al inicio
    cargarCitas(paginaActual);
</script>