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
    $("#ingresar").click(function () {
        var boton = $(this);
        var estilos1 = { 'background': '#007bff', 'border-color': '#007bff' };
        var estilos2 = { 'background': '#166087', 'border-color': '#166087' };

        var datos = $('#formulariologin').serialize();

        $.ajax({
            type: "POST",
            url: "Controllers/User.php",
            data: datos,
            beforeSend: function () {
                boton.html(loader);
                boton.css(estilos2);
            },
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


/**
 * Funciones Pagina Principal
 */
/**
 * Unicamente cargara la lista de libros
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
cargarclases();

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
