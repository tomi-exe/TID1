<?php
    require_once('../../includeGeneral.php');
    require_once('sensores/Sensor.php');

    
    $obj=new Sensor();
    $tpl->set('tipoArr',Util::cmbSelLoop($obj->getTipoArr(),-1));

    echo $tpl->fetch('plantillas/buscar.html');
    exit;
?>