<?php
    require_once('../../includeGeneral.php');
    require_once('sensores/Sensor.php');

    // Control pestanas
    $tab=isset($_GET['tab']) ? $_GET['tab'] : 1;
    $tpl->set('tab',$tab);

    // Instanciamos objeto
    $obj=new Sensor();

    // Preparamos plantilla
    $tpl->set('modificar',false,TRUE);
    $tpl->set('moduloAccion','Agregar');

    $tpl->set('id',$sql->getUUID());
    
    $tpl->set('nombre','');
    $tpl->set('tipo',0);
    $tpl->set('tipoArr',Util::cmbSelLoop($obj->getTipoArr(),0));
    $tpl->set('ultimoEvento',date('Y-m-d'));
    $tpl->set('latitud',0);
    $tpl->set('longitud',0);
    $tpl->set('direccion','');
    $tpl->set('comuna','');
    $tpl->set('referencia','');
    $tpl->set('periodo',3600);
    $tpl->set('contrasena','Qrt162_7');
    $tpl->set('habilitado','');
    $tpl->set('maximo1',600);
    $tpl->set('profundidad1',30);
    $tpl->set('maximo2',600);
    $tpl->set('profundidad2',60);
    $tpl->set('maximo3',600);
    $tpl->set('profundidad3',90);
    
    


    echo $tpl->fetch('plantillas/modificar.html');
    exit;
?>