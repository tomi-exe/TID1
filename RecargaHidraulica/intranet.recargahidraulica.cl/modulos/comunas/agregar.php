<?php
    require_once('../../includeGeneral.php');
    require_once('general/Comuna.php');

    // Control pestanas
    $tab=isset($_GET['tab']) ? $_GET['tab'] : 1;
    $tpl->set('tab',$tab);

    // Instanciamos objeto
    $obj=new Comuna();

    // Preparamos plantilla
    $tpl->set('modificar',false,TRUE);
    $tpl->set('moduloAccion','Agregar');

    $tpl->set('id',$sql->getUUID());
    
    $tpl->set('nombre','');
    
    


    echo $tpl->fetch('plantillas/modificar.html');
    exit;
?>