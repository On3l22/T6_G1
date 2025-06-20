<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FORMATIVO BASE DE DATOS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Links de archivos custom - toda ruta se empezara tomar desde la carpeta raiz, T6_G1 -->
    <base href="/T6_G1/">
    <link rel="stylesheet" href="css/estilos.css">
     <link rel="stylesheet" href="css/salidaPruebas.css">

    <!-- por si rompe -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!--------------------------Scripts Custom----------------------------------------->

    <script src="./js/cargar.js"></script>
    <script src="./js/JSelect.js"></script>
    <script src="./js/mostrarResumen.js"></script>
</head>

<body>



    <!--------------------------Contenido----------------------------------------->
    <div class="container mt-4" style="flex-grow: 1;">
        <h3 class="mb-4">Hospital Aquilino T. <span class="badge text-white"
                style="background-color: #1299B7;">Formulario de Citas</span>
        </h3>

        <!-- Botones o Tabs -->
        <?php include './includesHTML/menu.html' ?>
        <?php include './php/FormCitas.php' ?>
    </div>


</body>

    <!--------------------------Footer----------------------------------------->
    <?php include './php/footer.php' ?>

</html>