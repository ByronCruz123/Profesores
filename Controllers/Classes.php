<?php
    include "../Model/conexion.php";

    /**
     * Este archivo se encargara de ejecutar una u otra funcion, dependiendo 
     * del valor que traiga el paremetro "accion"
     */

     //filtrado de caracteres extraños en los parametros POST
    $nombre = mysqli_real_escape_string($con, $_POST['nombre']);
    $seccion = mysqli_real_escape_string($con, $_POST['seccion']);
    $materia = mysqli_real_escape_string($con, $_POST['materia']);
    $salon = mysqli_real_escape_string($con, $_POST['salon']);
    if($_REQUEST['accion'] == "crear"){
        /**
         * @Crear clase
         * Se ejecutara siempre y cuando el valor del parametro "accion" sea => "crear"
         * Esta funcion ejecutara una sentencia SQL de insercion con los valores POST obtenidos
         */
        $sql = mysqli_query($con, "INSERT INTO clases (nombre, seccion, materia, salon)
                                   VALUES ('$nombre', '$seccion', '$materia', '$salon')");
        mysqli_close($con);
        
        if($sql){
            echo "creado";
        }else{
            echo "errorcrear";
        }
    }elseif($_REQUEST['accion'] == "actualizar"){
        /**
         * @Actualizar Lector
         * Se ejecutara siempre y cuando el valor del parametro "accion" sea => "actualizar"
         * Esta funcion ejecutara una sentencia SQL update con los valores POST obtenidos
         */
        $idlibro = $_REQUEST['id'];
        $sql = mysqli_query($con,  "UPDATE libros
                                    SET titulo = '$titulo', subtitulo = '$subitutlo', autor = '$autor', 
                                    publicacion = '$publicacion', editorial = '$editorial', edicion = '$edicion', idioma = '$idioma',
                                    ejemplares = '$ejemplares', id_tipo_libro = '$categoria' 
                                    WHERE id = '$idlibro'");
        
        mysqli_close($con);
        
        if($sql){
            echo "actualizado";
        }else{
            echo "erroractualizar";
        }
    }else{
         /**
         * Parametro accion @Desconocido
         * Se ejecutara siempre y cuando el valor del parametro "accion" no sea => "nuevo" o "actualizar"
         * Unicamente devolvera un mensaje
         */
        echo "error_desconocido";
    }
?>