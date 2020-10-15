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
/* Mostrar ocultar sidebar */
$(".btn-menu").on("click", function () {
    $("#navbar").toggleClass("abrir");
})


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

//MANIPULACION DE CLASES


/**el archivo (form_clase.php) esta preparado para proporcionar uno de dos formularios diferentes, segun se le
 * espeficique en el @param {} accion 
*/
$("body").on("click", ".btn-class-modal", function () {
    var accion = $(this).attr("data-accion");
    var attrid = $(this).attr("data-id");
    var id = attrid != undefined ? attrid : "";
    $.ajax({
        type: "POST",
        url: "Includes/form_clase.php",
        data: { accion: accion, id: id },
        beforeSend: function () {
            $(".modalformclase").html(`<p class="pl-3 pt-3">Cargando...</p>`);
        },
        success: function (res) {
            $(".modalformclase").html(res);
        }
    });

});


/**
     * El controlador Classes.php, puede ejecutar tres operaciones diferente @Crear , @Editar y @Eliminar , 
     * segun se le indique
     * 
     * Mediante el boton del formulario crear o editar se enviara un parametro llamado "accion", 
     * que se encarga de especificar la operacion requerida 
     * 
     * Para ejecutar la operacion @Eliminar , sera mediante el boton eliminar, que tambien enviara su
     * respectivo valor en el paremetro "accion"
    

 * FUNCION DE PARAMETROS
 * @param {requerido solo al editar o eliminar} id 
 * @param {informacion que insertar en la base de datos} form 
 * @param {funcion que el controlador debe ejecutar} accion 
 */
function managgement_class(id, form, accion) {
    $.ajax({
        type: "POST",
        url: "Controllers/Classes.php",
        data: form + '&accion=' + accion + '&id=' + id,
        success: function (res) {
            //posibles respuestas del servidor 
            if (res == "creado") {
                alertify.notify('Nueva clase agregada', 'success', 5);
                $('#formclasemodal').modal('hide');
                setTimeout(
                    function () {
                        cargarclases();
                    }, 200);
            } else if (res == "errorcrear") {
                alertify.error('Clase no agregada');
            } else if (res == "actualizado") {
                alertify.success('Clase actualizada');
                $('#formclasemodal').modal('hide');
                setTimeout(
                    function () {
                        cargarclases();
                    }, 200);
            } else if (res == "erroractualizar") {
                alertify.error('Clase no actualiza');
            } else if (res == "eliminado") {
                alertify.warning('Clase eliminada');
                $('#formclasemodal').modal('hide');
                setTimeout(
                    function () {
                        cargarclases();
                    }, 200);
            } else if (res == "erroreliminar") {
                alertify.error('Error al eliminar la clase');
            }else if (res == "claseabandonada") {
                alertify.success('Has abandonado la clase');
                setTimeout(
                    function () {
                        cargarclases();
                    }, 150);
            }else if (res == "errorabandonar") {
                alertify.error('Error abandonar clase');
            }
            else if (res == "error_desconocido") {
                alertify.error('No se ha ejecutado ninguna funcion');
            } else {
                alertify.success(res);
            }
        }
    });
}

/**
 * Manejador de eventos para utilizar la funcion managgement_class()
 */

$("body").on("click", ".class_management_btn", function () {

    var id = $(this).attr("data-id") != undefined || $(this).attr("data-id") != null ? $(this).attr("data-id") : "0";
    var form = $("#formularioclase").serialize();
    var accion = $(this).attr("data-accion");
    var nombreclase = $("input[name='nombre']").val();

    if (accion == "eliminar_clase") {
        alertify.confirm('Eliminar clase', 'Si elimina la clase se eliminaran tambien las tareas y los alumnos que le corresponden',
            function () {
                managgement_class(id, form, accion);
            },
            function () {

            }).setting({
                'labels': {
                    ok: 'Eliminar',
                    cancel: 'Cancelar'
                },
                'reverseButtons': true,
                'defaultFocusOff': true
            });
    } else {
        if (nombreclase != "") {
            managgement_class(id, form, accion);
        }
    }
});


$(".clases_list").on("click", ".abandonarclase", function (){
    var idalumno = $(this).attr("data-alumnoid");
    alertify.confirm('Abandonar clase', 'Si abandonas la clase se perderán todas tus notas',
            function () {
                managgement_class(idalumno, '', 'abandonarclase');
            },
            function () {

            }).setting({
                'labels': {
                    ok: 'Abandonar',
                    cancel: 'Cancelar'
                },
                'reverseButtons': true,
                'defaultFocusOff': true
            });
});

/**
 * Unicamente cargara la lista de alumnos
 */
function cargaralumnos() {
    var clase = $(".idclase").val();
    $.ajax({
        type: "POST",
        data: { clase: clase },
        url: "Includes/alumnos_lista.php",
        success: function (res) {
            $(".listaalumnos").html(res).fadeIn();
        }
    });
}

function managgement_alumnos(boton, id, para, clase, accion) {
    $.ajax({
        type: "POST",
        url: "Controllers/Alumnos.php",
        data: { id: id, para: para, clase: clase, accion: accion },
        success: function (res) {
            if (res == "eliminado") {
                alertify.warning('Alumno eliminado');
                boton.parent().parent().remove();
            } else if (res == "agregado") {
                alertify.success("Alumno agregado");
                setTimeout(
                    function () {
                        cargaralumnos();
                    }, 200);
            } else {
                alertify.error(res);
            }
        }
    });
}


/**
 * Funcion para eliminar alumno de una clase en especifico
 * requiere un parametro, que no se pasara mediante la funcion, sino mediante un atributo del boton que ejecute el evento
 * @param {id alumno} id 
 */
$(".listaalumnos").on("click", ".btn_eliminar_alumno", function () {
    var boton = $(this);
    var id = boton.attr("data-id");
    var para = boton.attr("data-para");
    var clase = $(".idclase").val();
    alertify.confirm('Eliminar alumno', 'Tambien se eliminaran las notas asignadas. Tenga en cuenta que esta operacion no se puede deshacer',
        function () {
            managgement_alumnos(boton, id, para, clase, "eliminar");
        },
        function () {

        }).setting({
            'labels': {
                ok: 'Eliminar',
                cancel: 'Cancelar'
            },
            'reverseButtons': true,
            'defaultFocusOff': true
        });
});



$(".btn_agregaralumno").click(function () {
    var clase = $(".idclase").val();
    alertify.prompt('Agregar alumno', 'Ingrese el codigo del alumno', ''
        , function (evt, val) {
            if(val != ""){
                managgement_alumnos('', val, '', clase, "agregar");
            }
        },
        function () {

        }).setting({
            'labels': {
                ok: 'Agregar',
                cancel: 'Cancelar'
            },
            'reverseButtons': true,
            'defaultFocusOff': true
        });
})








$("body").on('submit', '#formularioclase', function (evt) {
    evt.preventDefault();
});



function cargartareas() {
    var idclase = $("input[name='idclase']").val();
    $.ajax({
        type: "POST",
        data: { clase: idclase },
        url: "Includes/tareas_list.php",
        success: function (res) {
            $(".tarea").html(res).fadeIn();
        }
    });
}

function cargarsolicitudes(idclase) {
    $.ajax({
        type: "POST",
        data: { clase: idclase },
        url: "Includes/tareas_list.php",
        success: function (res) {
            $(".tarea").html(res).fadeIn();
        }
    });
}

function cargarnotificaciones() {
    $.ajax({
        type: "GET",
        url: "Includes/notificaciones_list.php",
        success: function (res) {
            $(".notificaciones").html(res).fadeIn();
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
                alertify.success('Nueva tarea asignada');
                $('#creartareamodal').modal('hide');
                setTimeout(
                    function () {
                        cargartareas();
                        formulario[0].reset();
                    }, 200);
            } else if (res == "errorcrear") {
                alertify.error('Tarea no asignada');
            } else if (res == "actualizado") {
                alertify.success('Tarea actualizada');
                setTimeout(
                    function () {
                        cargartareas();
                    }, 200);
            } else if (res == "erroractualizar") {
                alertify.error('Clase no actualiza');
            } else if (res == "error_desconocido") {
                alertify.warning('No se ha ejecutado ninguna funcion');
            } else {
                alertify.error('El servidor no responde');
            }
        }
    });
});

/**
 * Funciones Guardar Notas del apartado calificaciones 
 */

$("#guardarnotas").click(function () {
    var MyArray = [];
    var idtarea = $(this).attr("data-idtarea");

    $('.input_puntos').each(function () {//recorrer cada input
        var saved = $(this).attr("data-puntaje_actual");//valor predefinido (guardado en la db)
        var newx = $(this).val(); //valor real dentro del input 
        var idalumno = $(this).parents("#fila").find("#alumno").attr("data-id"); //id alumno
        if (saved != newx) {//si algun valor ( val ) se modifica
            if (saved == '' & newx != '') {
                /**
                 * si el valor predefinido esta vacio, se interpreta la intencion 
                 * de crear nueva nota
                 */

                MyArray.push(/*Agregar objeto al array */
                    { "idalumno": idalumno, "idtarea": idtarea, "puntos": newx, "accion": "crear" }
                );

                $(this).attr("data-puntaje_actual", newx);
                /**
                 * El valor predefinido en atributo se igual al valor ( val ) del input 
                 */
            } else if (newx == '' & saved != "") {
                /**
                 * si el valor predefinido no esta vacio, sin embargo el nuevo valor ( val )
                 * si se cuentra vacio, se interpreta la intencion 
                 * de elimnar la nota
                 */

                MyArray.push(/*Agregar objeto al array */
                    { "idalumno": idalumno, "idtarea": idtarea, "puntos": newx, "accion": "eliminar" }
                );

                $(this).attr("data-puntaje_actual", newx);
                /**
                * El valor predefinido en atributo se igual al valor ( val ) del input 
                 * El valor predefinido en atributo se igual al valor ( val ) del input 
                * El valor predefinido en atributo se igual al valor ( val ) del input 
                */

            } else if (saved != "" & newx != saved) {
                /**
                 * Si el valor predefinito en atributo no esta vacio y el nuevo 
                 * valor (val) del input es diferente de este, se considera la intencion de 
                 * actualizar la nota
                 */

                MyArray.push(/*Agregar objeto al array */
                    { "idalumno": idalumno, "idtarea": idtarea, "puntos": newx, "accion": "actualizar" }
                );
                $(this).attr("data-puntaje_actual", newx);
                /**
               * El valor predefinido en atributo se igual al valor ( val ) del input 
                 * El valor predefinido en atributo se igual al valor ( val ) del input 
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
        data: { "json": json },
        url: "Controllers/Notes.php",
        success: function (res) {
            console.log(res);
        }
    });

});

