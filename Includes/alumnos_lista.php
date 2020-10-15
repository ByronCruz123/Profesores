<?php
if (isset($_REQUEST['clase']) && !empty($_REQUEST['clase'])) {
    include "../Model/conexion.php";
    $idclase = mysqli_real_escape_string($con, $_REQUEST['clase']);

    $sql2 = mysqli_query($con, "SELECT ac.id_alumno, us.nombre, us.apellido, ac.id_usuario
                                                FROM alumnosdeclase AS ac 
                                                INNER JOIN usuario AS us ON ac.id_usuario = us.id 
                                                WHERE ac.id_clase = $idclase AND ac.acceso = true");
    $contador = 1;

    while ($alumno = mysqli_fetch_array($sql2)) { ?>
        <tr id="fila">
            <th scope="row"><?php echo $contador++; ?></th>
            <td class="text-capitalize"><?php echo $alumno['nombre'] . " " . $alumno["apellido"]; ?></td>
            <td>
                <button class="btn btn-danger btn_eliminar_alumno" role="button" data-id="<?php echo $alumno['id_alumno']; ?>" data-para="<?php echo $alumno['id_usuario']; ?>">
                    Eliminar
                </button>
            </td>
        </tr>
<?php

    }
}
?>