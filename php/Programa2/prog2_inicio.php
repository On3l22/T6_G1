<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Prog2</title>

    <!-- Bootstrap CSS -->
    <link href="../../css/bootstrap.css" rel="stylesheet">

</head>

<body>
    <?php include("../../includesHTML/menu.html"); ?> <!-- Llama al menu  -->


    <!--Inicia contenedor-->
    <div class="container" id="contenedor">
        <!--Inicia panelestudiante-->
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="panel panel-default" id="panelEstudiante">
                <div class="panel-body">
                    <!-- Inicia formulario -->
                    <form action="" method="POST" role="form">
                        <legend>Datos del estudiante</legend>

                        <div class="form-group">
                            <!-- Primera columna datos estudiante -->
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <label for="">Nombre:</label>
                                <input type="text" class="form-control" id="" placeholder="Input field">
                            </div>
                            <!-- Segunda columna datos estudiante -->
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <label for="">Apellido:</label>
                                <input type="text" class="form-control" id="" placeholder="Input field">
                            </div>
                            <!-- Tercera columna datos estudiante -->
                            <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                                <label for="">Carnet:</label>
                                <input type="text" class="form-control" id="" placeholder="Input field">
                            </div>

                            col
                    </form>
                    <!-- Fin formulario -->

                </div>
            </div>
        </div>
        <!--Fin panelestudiante-->

    </div>
    <!--Fin contenedor-->


    <!-- Inlcude del footer -->
    <?php include("../../includesHTML/footer.html"); ?> <!-- Llama al menu  -->


    <!-- jQuery -->
    <script src="./js/jquery.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="./js/bootstrap.min.js"></script>
</body>

</html>