<?php
    require_once('../../includeGeneral.php');
	require_once('sensores/Sensor.php');
    require_once('sensores/SensorBD.php');
    
    $sensorBD=new SensorBD($sql);
    $sensores=$sensorBD->getListado();
    $nSensores=count($sensores);
    
    
    $listado=array();
    for($i=0; $i<$nSensores; $i++) {
    	$info='';
    	$info.='<strong>Tipo: </strong>' . $sensores[$i]->getTipoStr();
    	$info.='<br><strong>Número: </strong>' . $sensores[$i]->getNumero();
    	$info.='<br><strong>Nombre: </strong>' . $sensores[$i]->getNombre();
    	$info.='<br><strong>Lat/Lon: </strong>' . $sensores[$i]->getLatitud() . "," . $sensores[$i]->getLongitud();
    	$info.='<br><strong>Dirección: </strong>' . $sensores[$i]->getDireccion();
    	$info.='<br><strong>Comuna: </strong>' . ($sensores[$i]->getComuna() ? $sensores[$i]->getComuna()->getNombre() : '');
    	$info.='<br><strong>Ult. evento: </strong> ' . $sensores[$i]->getUltimoEvento();
    	$info.='<br><br><a href="serie.php?id=' . $sensores[$i]->getId() . '">Ver serie</a>';
    	
    	$listado[]=array(
    		'id'=>$sensores[$i]->getId(),
    		'tipo'=>$sensores[$i]->getTipoStr(),
    		'numero'=>$sensores[$i]->getNumero(),
    		'nombre'=>$sensores[$i]->getNombre(),
    		'latitud'=>$sensores[$i]->getLatitud(),
    		'longitud'=>$sensores[$i]->getLongitud(),
    		'info'=>$info
    		);
    }
    $tpl->set('sensores',$listado);
    $tpl->set('cantidad',count($listado));
    //$tpl->set('sensores2',$listado);

    echo $tpl->fetch('plantillas/index.html');
    exit;
?>