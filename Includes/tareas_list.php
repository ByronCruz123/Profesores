<?php

/**
 * Este archivo (clases_list.php) unicamente devolvera la lista completa de clases
 */

include "../Model/conexion.php";
$idclase = mysqli_real_escape_string($con, $_POST['clase']);
$sql2 = mysqli_query($con, "SELECT * FROM tareas WHERE id_clase = '$idclase'");

while ($tarea = mysqli_fetch_array($sql2)) { ?>
    <a href="detalletarea.php?tarea=<?php echo $tarea['id'] ?>&clase=<?php echo $_POST['clase'] ?>" class="tarealink">
        <div class="alert alert-light" role="alert">
            <img src="resources/img/homework2.png" alt="icon" class="homework2_icon">
            <?php echo $tarea['titulo'] ?>
        </div>
    </a>

<?php
}
?>