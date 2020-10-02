<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php?cod=1');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesores</title>
    <link rel="icon" href="Resources/img/homework.png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Resources/css/bootstrap.css">
    <link rel="stylesheet" href="Resources/css/styles.css">


</head>

<body>
    <nav class="navbar navbar-white fixed-top bg-white border-bottom">
        <a class="navbar-brand titulo">
            <img src="resources/img/homework.png" alt="icon" class="homework_icon"> Profesores
            <span class="seccion"> Clases</span>
        </a>


        <a class="btn btn_new_class" type="button" data-toggle="modal" data-target="#crearclase">
            <img src="resources/img/plus.png" alt="" width="20px">
        </a>
    </nav>

    <div>
        <ol class="clases_list">

            <!-- Contenido Dinamico -->

        </ol>
    </div>


    <div class="modal fade px-2" id="crearclase" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h6 class="modal-title" id="exampleModalLabel">Crear una clase</h6>
                    <form id="nuevaclaseform" class="pt-3">
                        <input type="hidden" name="accion" value="crear">
                        <div class="form-group">
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre de la clase (obligatorio)" required>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" name="seccion" placeholder="Sección">
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" name="materia" placeholder="Materia">
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" name="salon" placeholder="Salón">
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear</button>
                </div>
                </form>
            </div>
        </div>
    </div>

</body>
<script src="resources/js/jquery.js"></script>
<script src="resources/js/popper.min.js"></script>
<script src="resources/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js"></script>
<script src="resources/js/bootstrap-notify.js"></script>
<script src="resources/js/functions.js"></script>
<script>
    cargarclases();
</script>

</html>