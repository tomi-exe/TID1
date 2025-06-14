<?php
    require_once('../../includeGeneral.php');
    require_once('usuarios/Usuario.php');

    // Control pestanas
    $tab=isset($_GET['tab']) ? $_GET['tab'] : 1;
    $tpl->set('tab',$tab);

    // Instanciamos objeto
    $obj=new Usuario();

    // Preparamos plantilla
    $tpl->set('modificar',false,TRUE);
    $tpl->set('moduloAccion','Agregar');

    $tpl->set('id',$sql->getUUID());
    
    $tpl->set('nombres','');
    $tpl->set('apellidoPaterno','');
    $tpl->set('apellidoMaterno','');
    $tpl->set('perfilAcceso','');
    $tpl->set('correo','');
    $tpl->set('contrasena',Util::generarContrasena(8));
    $tpl->set('habilitado','');
    
    


    echo $tpl->fetch('plantillas/modificar.html');
    exit;
?>