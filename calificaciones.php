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
    <link rel="icon" href="Resources/img/homework.png">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Resources/css/bootstrap.css">
    <link rel="stylesheet" href="Resources/css/styles.css">


</head>

<body style="background-color: white;">
<nav class="navbar navbar-expand-lg fixed-top navbar-light bg-white border-bottom">

        <?php
        include 'Model/conexion.php';
        if (isset($_GET['clase']) && !empty($_GET['clase'])) {//si existe un valor en la variable clase de la url

                $idclase = mysqli_real_escape_string($con, $_GET['clase']);  //escapar de caracteres extraños
                $sql = mysqli_query($con, "SELECT * FROM clases WHERE id =  '$idclase'"); //consultar clase en BD para obtener titulo

                $clase = mysqli_fetch_assoc($sql);

                if (mysqli_num_rows($sql) > 0) {//si la consulta arroja un resulta la clase si existe y por tanto...
                ?>
                    <a class="navbar-brand seccion2" href="tareas.php?clase=<?php echo $clase['id'] ?>"><!-- Se usa su id en url para redireccionar a la lista de tareas de esa clase -->
                <?php
                } else {//si la clase no existe, solo existira un enlace a la pagina principal (clases)
                    ?>
                        <a class="navbar-brand seccion2" href="inicio.php">
                    <?php
                    }
            } else {//si la variable clase de la url no existe o esta vacia, el unico enlace mostrado sera a la pagina principal
                ?>
                <a class="navbar-brand seccion2" href="inicio.php">
                <?php
            }
                ?>
                <span> 
                    <?php echo isset($clase['nombre']) ? $clase['nombre'] : 'Esta tarea no existe :('; //si la variable nombre contiene valor, se mostrara?>
                </span>
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
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
    </nav>

    <main class="container tareas">
    <div class="botones d-block">
            <a class="nav-link btn-blanco d-inline-block" href="detalletarea.php?tarea=<?php echo $_GET['tarea'] ?>&clase=<?php echo $_GET['clase'] ?>">Instrucciones</a>
            <a class="nav-link btn-blanco active d-inline-block" href="calificaciones.php?tarea=<?php echo $_GET['tarea'] ?>&clase=<?php echo $_GET['clase'] ?>">Calificar tarea</a>
        </div>
        <br>
        <span class="pt-4">
            <img src="resources/img/homework2.png" alt="icon" class="homework2_icon">
            <?php
            include "Model/conexion.php";
            include "Includes/fecha.php";
            $idtarea = $_GET['tarea'];
            $sql = mysqli_query($con,  "SELECT * FROM tareas WHERE id = '$idtarea'");
            $datostarea = mysqli_fetch_assoc($sql);

            ?>

            Fecha de entrega: <?php echo fecha($datostarea['fecha_entrega']) . substr($datostarea['fecha_entrega'], 11, 5) ?>
        </span>
        <br>
        <h4 class="titulotarea mt-3"><?php echo $datostarea['titulo']; ?></h4>
        <span class="pt-4">
            <?php echo $datostarea['puntos']; ?> puntos
            <button id="guardarnotas" data-idtarea="<?php echo $_GET['tarea']?>">Actualizar notas</button>
        </span>
        <div class="tarea mt-2">
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Alumno</th>
                        <th scope="col">Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql2 = mysqli_query($con, "SELECT ac.id_alumno, us.nombre, us.apellido, nt.puntos 
                                                FROM alumnosdeclase AS ac 
                                                INNER JOIN usuario AS us ON ac.id_usuario = us.id 
                                                INNER JOIN notas AS nt ON ac.id_alumno = nt.id_alumno
                                                WHERE ac.id_clase = $idclase AND ac.acceso = true");
                    $contador = 1;

                    while ($alumno = mysqli_fetch_array($sql2)) { ?>
                        <tr id="fila">
                            <th scope="row"><?php echo $contador++; ?></th>
                            <td id="alumno" data-id="<?php echo $alumno['id_alumno'];?>"><?php echo $alumno['nombre'] . " ". $alumno["apellido"];?></td>
                            <td>
                                <div class="form-group">

                                    <input  type="number" 
                                            data-puntaje_actual="<?php echo $alumno['puntos'];?>" 
                                            max="<?php echo $datostarea['puntos']; ?>" 
                                            value="<?php echo $alumno['puntos']; ?>"  
                                            class="form-control input_puntos">
                                </div>
                            </td>
                        </tr>
                    <?php
                    }
                    ?>

                </tbody>
            </table>
        </div>

    </main>



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

</html>