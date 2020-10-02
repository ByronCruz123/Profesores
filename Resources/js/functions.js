/**
 * 
 * @param {*} mensaje este paremetro recibe el mensaje que se desea mostrar en la notificacion (alerta)
 * @param {*} tipo hace referencia al color de la alerta... el color es proporciona por bootstrap 
 */
function notificacion(mensaje, tipo) {
    $.notify('' + mensaje + '', {
        placement: {
            from: 'bottom',
            align: 'right'
        },
        type: '' + tipo + ''
    });
}

var loader = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
Loading...`;

$(document).ready(function () {
    /**
     * Metodo de verificacion de usario, (login)
     */
    $("#login-button").click(function () {
        var boton = $(this);

        var datos = $('#formulariologin').serialize();
        var action = "login";

        $.ajax({
            type: "POST",
            url: "Controllers/User.php",
            data: datos + '&action=' + action,

            error: function () {
                alert("No se pudo obtner informacion del servidor");
            },
            success: function (res) {
                if (res == 'correcto') {
                    setTimeout(
                        function () {
                            location.href = "inicio.php";
                        }, 1000);
                } else {
                    setTimeout(
                        function () {
                            location.href = "index.php";
                        }, 1500);
                }
            }
        });
    })
});

$(document).ready(function () {
    /**
     * Metodo de verificacion de usario, (login)
     */
    $("#signup-button").click(function () {
        var boton = $(this);

        var datos = $('#formulariosignup').serialize();
        var action = "signup";


        $.ajax({
            type: "POST",
            url: "Controllers/User.php",
            data: datos + '&action=' + action,

            error: function () {
                alert("No se pudo obtner informacion del servidor");
            },
            success: function (res) {
                alert(res);
            }
        });
    })
});


/**
 * Funciones Pagina Principal
 */

/**
 * Unicamente cargara la lista de clases 
 */
function cargarclases() {
    $.ajax({
        type: "GET",
        url: "Includes/clases_list.php",
        success: function (res) {
            $(".clases_list").html(res).fadeIn();
        }
    });
}

//CREAR NUEVA CLASE
$("#nuevaclaseform").on('submit', function (evt) {
    evt.preventDefault();
    var formulario = $(this);
    console.log(formulario);
    /**
     * El controlador Classes.php, puede ejecutar tres operaciones @Crear , @Editar y @Eliminar , segun se 
     * le indique
     * 
     * Mediante los formularios crear y editar se envia un parametro llamado "accion", 
     * que se encarga de especificar la operacion requerida 
     * 
     * Para ejecutar la operacion @Eliminar es requerida otra funcion
     */
    $.ajax({
        type: "POST",
        url: "Controllers/Classes.php",
        data: formulario.serialize(),
        success: function (res) {
            //posibles respuestas del servidor 
            if (res == "creado") {
                notificacion("Nueva clase agregada", "success");
                $('#crearclase').modal('hide');
                setTimeout(
                    function () {
                        cargarclases();
                        formulario[0].reset();
                    }, 200);
            } else if (res == "errorcrear") {
                notificacion("Clase no agregada", "danger");
            } else if (res == "actualizado") {
                notificacion("Clase actualizada", "success");
                setTimeout(
                    function () {
                        cargarclases();
                    }, 200);
            } else if (res == "erroractualizar") {
                notificacion("Clase no actualiza", "danger");
            } else if (res == "error_desconocido") {
                notificacion("No se ha ejecutado ninguna funcion", "danger");
            } else {
                notificacion("El servidor no responde", "danger");
            }
        }
    });
});



function cargartareas(idclase) {
    $.ajax({
        type: "POST",
        data: {clase : idclase},
        url: "Includes/tareas_list.php",
        success: function (res) {
            $(".tarea").html(res).fadeIn();
        }
    });
}



//CREAR NUEVA TAREA
$("#nuevatareaform").on('submit', function (evt) {
    evt.preventDefault();
    var formulario = $(this);
    var idclase = formulario.find("input[name='idclase']").val();
    /**
     * El controlador tarea.php, puede ejecutar tres operaciones @Crear , @Editar y @Eliminar , segun se 
     * le indique
     * 
     * Mediante los formularios crear y editar se envia un parametro llamado "accion", 
     * que se encarga de especificar la operacion requerida 
     * 
     * Para ejecutar la operacion @Eliminar es requerida otra funcion
     */
    $.ajax({
        type: "POST",
        url: "Controllers/Activities.php",
        data: formulario.serialize(),
        success: function (res) {
            //posibles respuestas del servidor 
            if (res == "creado") {
                notificacion("Nueva tarea asignada", "success");
                $('#creartareamodal').modal('hide');
                setTimeout(
                    function () {
                        cargartareas(idclase);
                        formulario[0].reset();
                    }, 200);
            } else if (res == "errorcrear") {
                notificacion("Tarea no asignada", "danger");
            } else if (res == "actualizado") {
                notificacion("Tarea actualizada", "success");
                setTimeout(
                    function () {
                        cargartareas();
                    }, 200);
            } else if (res == "erroractualizar") {
                notificacion("Clase no actualiza", "danger");
            } else if (res == "error_desconocido") {
                notificacion("No se ha ejecutado ninguna funcion", "danger");
            } else {
                notificacion("El servidor no responde", "danger");
            }
        }
    });
});

/**
 * Funciones en la pestania calificaciones 
 */

 $("#guardarnotas").click(function(){
    var MyArray = [];
    var idtarea = $(this).attr("data-idtarea");

    $('.input_puntos').each(function() {//recorrer cada input
        var saved = $(this).attr("data-puntaje_actual");//valor predefinido (guardado en la db)
        var newx = $(this).val(); //valor real dentro del input 
        var idalumno = $(this).parents("#fila").find("#alumno").attr("data-id"); //id alumno
        if(saved != newx){//si algun valor ( val ) se modifica
            if(saved == '' & newx != ''){ 
                /**
                 * si el valor predefinido esta vacio, se interpreta la intencion 
                 * de crear nueva nota
                 */

                MyArray.push(/*Agregar objeto al array */
                    {"idalumno" : idalumno, "idtarea" : idtarea, "puntos" : newx, "accion" : "crear"}
                );

                $(this).attr("data-puntaje_actual", newx);
                /**
                 * El valor predefinido en atributo se igual al valor ( val ) del input 
                 */
            }else if(newx == '' & saved != ""){
                /**
                 * si el valor predefinido no esta vacio, sin embargo el nuevo valor ( val )
                 * si se cuentra vacio, se interpreta la intencion 
                 * de elimnar la nota
                 */

                MyArray.push(/*Agregar objeto al array */
                    {"idalumno" : idalumno, "idtarea" : idtarea, "puntos" : newx, "accion" : "eliminar"}
                );

                $(this).attr("data-puntaje_actual", newx);
                 /**
                 * El valor predefinido en atributo se igual al valor ( val ) del input 
                 */

            }else if(saved != "" & newx != saved){
                /**
                 * Si el valor predefinito en atributo no esta vacio y el nuevo 
                 * valor (val) del input es diferente de este, se considera la intencion de 
                 * actualizar la nota
                 */

                MyArray.push(/*Agregar objeto al array */
                    {"idalumno" : idalumno, "idtarea" : idtarea, "puntos" : newx, "accion" : "actualizar"}
                );
                $(this).attr("data-puntaje_actual", newx);
                  /**
                 * El valor predefinido en atributo se igual al valor ( val ) del input 
                 */
            }

        }
    });

    var json = JSON.stringify(MyArray);
    /**
     * Convertir MyArray en un objeto json
     * para luego enviarlo mediante ajax y ser procesado por el servidor 
     */

    $.ajax({
        type: "POST",
        data: {"json" : json},
        url: "Controllers/Notes.php",
        success: function (res) {
            console.log(res);
        }
    });

 });