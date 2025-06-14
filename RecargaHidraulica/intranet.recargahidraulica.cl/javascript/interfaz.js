function cuadroDialogo(titulo,mensaje) {
    $('#cuadroDialogoTitulo').html(titulo);
    $('#cuadroDialogoMensaje').html(mensaje);

    $('#cuadroDialogo').modal();
}

function cuadroEliminacion(modulo,id,url) {
    url=typeof(url)!='undefined' ? url : "";

    $('#cuadroConfirmacionTitulo').html('Eliminar registro');
    $('#cuadroConfirmacionMensaje').html('¿Está seguro que desea eliminar el registro?');
    $('#cuadroConfirmacionFormulario').attr('action','../' + modulo + '/eliminar.php');
    $('#cuadroConfirmacionId').val(id);
    $('#cuadroConfirmacionURL').val(url);

    $('#cuadroConfirmacion').modal();
}

function abrirWidget(titulo,url) {
    $('#cuadroDialogo').load(url);

    $('#cuadroDialogo').modal();
}

//USAGE: $("#form").serializefiles();
(function($) {
    $.fn.serializeFiles = function() {
        var obj = $(this);
        /* ADD FILE TO PARAM AJAX */
        var formData = new FormData();
        $.each($(obj).find("input[type='file']"), function(i, tag) {
            $.each($(tag)[0].files, function(i, file) {
                formData.append(tag.name, file);
            });
        });
        var params = $(obj).serializeArray();
        $.each(params, function (i, val) {
            formData.append(val.name, val.value);
        });
        return formData;
    };
})(jQuery);

function procesarFormulario(formularioID) {
    var formulario=$('#' + formularioID);

    $('#formularioMensaje').addClass('bg-blue');
    $('#formularioMensaje').html("Procesando...");

    
    $.ajax({
        type:"POST",
        url:formulario.attr('action'),
        dataType: "json",
        data:new FormData(document.getElementById(formularioID)),
        processData: false,
        cache:false,
        contentType: false,
        success: function(response) {
            if(response.accion=="0") { // Mensaje en campo
                $('#formularioMensaje').removeClass('bg-blue');
                $('#formularioMensaje').addClass('bg-red');
                $('#formularioMensaje').html(response.mensaje);
            } else if(response.accion=="1") { // Dialogo
                var titulo="Error";
                if(response.codigo==0) titulo="Información";
                cuadroDialogo(titulo,response.mensaje);
            } else if(response.accion=="2") { // Redirect
                location.replace(response.url);
            }
        }
    });
}

// http://stackoverflow.com/questions/105034/create-guid-uuid-in-javascript/2117523#2117523
function generateUUID () { // Public Domain/MIT
    var d = new Date().getTime();
    if(typeof performance!=='undefined' && typeof performance.now==='function') {
        d+=performance.now(); //use high-precision timer if available
    }
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        var r=(d+Math.random()*16)%16|0;
        d=Math.floor(d/16);
        return(c==='x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
}

function menu() {
    if($('body').hasClass('sidebar-collapse')) {
        $('body').removeClass('sidebar-collapse');
    } else {
        $('body').addClass('sidebar-collapse');
    }
}
