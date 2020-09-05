<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesores | Inicio</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="Resources/css/buscador.css">
    <link rel="stylesheet" href="Resources/css/styles.css">


</head>

<body style="background-color: white;">
    <nav class="navbar navbar-white fixed-top bg-white border">
        <?php
        include 'Model/conexion.php';
        if (isset($_GET['clase']) && !empty($_GET['clase'])) {

            $idclase = mysqli_real_escape_string($con, $_GET['clase']);

            $sql = mysqli_query($con, "SELECT * FROM clases WHERE id =  '$idclase'");

            $clase = mysqli_fetch_assoc($sql);

            if (mysqli_num_rows($sql) > 0) {
        ?>
                <a class="navbar-brand seccion2" href="tareas.php?clase=<?php echo $clase['id'] ?>">
                <?php
            } else {
                ?>
                    <a class="navbar-brand seccion2" href="inicio.php">
                    <?php
                }
            } else {
                    ?>
                    <a class="navbar-brand seccion2" href="inicio.php">
                    <?php
                }
                    ?>
                    <span> <?php echo isset($clase['nombre']) ? $clase['nombre'] : 'Esta tarea no existe :('; ?></span>
                    <span>
                        <?php
                        if (isset($clase['seccion'])) {
                            if (!empty($clase['seccion'])) {
                                echo $clase['seccion'];
                            } else {
                                echo 1;
                            }
                        }
                        ?>
                    </span>
                    </a>

                    <div>
                            <div class="d-inline-block mr-2 btnlabel">
                                <!-- <input type="checkbox" id="toggle" class="toggle-btn"> -->
                                <label id="btn-search" for="search-tag">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 26" class="svg-icon iconlupa">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M16.3347 17.7381C14.6099 19.1517 12.404 20 10 20C4.47715 20 0 15.5228 0 10C0 4.47715 4.47715 0 10 0C15.5228 0 20 4.47715 20 10C20 12.3985 19.1556 14.5998 17.7478 16.3228L25.6796 24.2546C26.0702 24.6451 26.0702 25.2783 25.6796 25.6688C25.2891 26.0593 24.6559 26.0593 24.2654 25.6688L16.3347 17.7381ZM18 10C18 14.4183 14.4183 18 10 18C5.58172 18 2 14.4183 2 10C2 5.58172 5.58172 2 10 2C14.4183 2 18 5.58172 18 10Z"></path>
                                    </svg>
                                </label>
                                <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 408 408" class="search-tag__come-back svg-icon s-mr-05"><path d="M408,178.5H96.9L239.7,35.7L204,0L0,204l204,204l35.7-35.7L96.9,229.5H408V178.5z"></path></svg> -->
                                <input id="search-tag" placeholder="¿Qué buscaremos hoy?" type="search" class="search-tag__input s-bg-white z-normal" autocomplete="off">
                            </div>

                            <div class="d-inline-block pl-3 pl-lg-4 border-left">
                                <a href="detalletarea.php?tarea=<?php echo $_GET['tarea'] ?>&clase=<?php echo $_GET['clase'] ?>" class="btn btn-secondary">Instrucciones</a>
                                <a href="calificaciones.php?tarea=<?php echo $_GET['tarea'] ?>&clase=<?php echo $_GET['clase'] ?>" class="btn btn-secondary">Calificaciones</a>
                            </div>
                        </div>
    </nav>

    <main class="container tareas">
        <a href="" class="btn btn-info mb-3" data-toggle="modal" data-target="#creartareamodal">Nueva tarea</a>

        <div class="tarea">
            <!-- Contenido Dinamico -->
        </div>

    </main>



    <div class="modal fade px-2" id="creartareamodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="nuevatareaform">
                    <div class="modal-body">
                        <h6 class="modal-title" id="exampleModalLabel">Nueva tarea</h6>
                        <input type="hidden" name="accion" value="crear">
                        <input type="hidden" name="idclase" value="<?php echo $_GET['clase'] ?>">
                        <div class="form-group pt-3">
                            <input type="text" class="form-control" name="titulo" placeholder="Titulo" required>
                        </div>

                        <div class="form-group">
                            <textarea class="form-control" rows="3" name="instrucciones" placeholder="Instrucciones"></textarea>
                        </div>
                        <span class="fecha_entrega">Fecha de entrega</span>
                        <div class="form-inline">
                            <input type="date" id="fecha" class="form-control mb-2 mr-sm-2" value="<?php echo date("Y-m-d"); ?>" name="f_entrega" required>
                            <input type="time" id="hora" class="form-control mb-2 mr-sm-2" value="23:59" name="h_entrega" required>
                        </div>
                        <span class="fecha_entrega">Puntos</span>
                        <div class="form-inline">
                            <input type="number" class="form-control" name="puntos" value="100">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear tarea</button>
                    </div>
                </form>
            </div>
        </div>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js"></script>
<script src="resources/js/bootstrap-notify-3.1.3/bootstrap-notify.js"></script>
<script src="resources/js/functions.js"></script>
<script>
    cargartareas(<?php echo $_GET['clase'] ?>);
</script>

</html>