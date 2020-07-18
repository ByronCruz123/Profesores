<?php

/**
 * Este archivo (clases_list.php) unicamente devolvera la lista completa de clases
 */

include "../Model/conexion.php";
$resultado = mysqli_query($con, "SELECT * FROM clases");

while ($fila = mysqli_fetch_array($resultado)) { $idclase = $fila['id']?>
    <li>
        <div class="card">
            <div class="card-body header_card">
                <a href="tareas.php?clase=<?php echo $idclase; ?>" class="card-title">
                    <h5 class="text-truncate"><?php echo $fila['nombre']; ?></h5>
                </a>
                <p class="card-text"><?php
                    if(isset($fila['seccion']) && !empty($fila['seccion'])){
                        echo $fila['seccion'];
                    }else{
                        echo 1;
                    }
                ?></p>
            </div>
            <div class="card-body body_card">
                <?php   $query2 = mysqli_query($con, "SELECT COUNT(id) as total FROM tareas WHERE id_clase = $idclase");
                        $cantidadtareas = mysqli_fetch_assoc($query2)['total'];
                        echo '<p class="card-text">' . $cantidadtareas . ' '; 
                            if($cantidadtareas == 1){
                                echo 'Actividad';
                            }else{
                                echo 'Actividades';
                            }
                        echo '</p>';
                ?>
                
                <br>
            </div>
            <div class="card-footer-morado">
                    <a><i class="far fa-edit"></i></a>
                    <a><i class="far fa-trash-alt"></i></a>
            </div>
        </div>
    </li>
<?php
}
?>