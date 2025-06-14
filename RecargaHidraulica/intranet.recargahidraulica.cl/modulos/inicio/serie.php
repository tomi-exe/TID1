<?php
    require_once('../../includeGeneral.php');
	require_once('sensores/Sensor.php');
    require_once('sensores/SensorBD.php');
    require_once('sensores/SensorDato.php');
    require_once('sensores/SensorDatoBD.php');
    
    $id=isset($_GET['id']) ? $_GET['id'] : die(1);
    
    $sensorBD=new SensorBD($sql);
    $sensor=$sensorBD->cargar($id);
    
    if(!$sensor) die(2);
    
    $tpl->set('id',$sensor->getId());
    $tpl->set('tipo',$sensor->getTipoStr());
    $tpl->set('numero',$sensor->getNumero());
    $tpl->set('nombre',$sensor->getNombre());
    $tpl->set('direccion',$sensor->getDireccion());
    $tpl->set('comuna',$sensor->getComuna() ? $sensor->getComuna()->getNombre() : '');
    $tpl->set('latitud',$sensor->getLatitud());
    $tpl->set('longitud',$sensor->getLongitud());
    
    // Ultimos 24 eventos
    $datoBD=new SensorDatoBD($sql);
    $datos=$datoBD->getListado(array("sensor"=>$id,"sort"=>"-fecha"),true,0,24);
    $nDatos=count($datos);
    
    $etiquetas=array();
    $data1=array();
    $data2=array();
    $data3=array();
    $f1='';
    for($i=0; $i<$nDatos; $i++) {
    	$fecha=$datos[$i]->getFecha();
    	list($f,$h)=explode(" ",$fecha);
    	$etiquetas[]=substr($h,0,5);
    	$data1[]=round($datos[$i]->getHumedadPorcentaje1(),2);
    	$data2[]=round($datos[$i]->getHumedadPorcentaje2(),2);
    	$data3[]=round($datos[$i]->getHumedadPorcentaje3(),2);
    }
    $etiquetas=array_reverse($etiquetas);
    $data1=array_reverse($data1);
    $data2=array_reverse($data2);
    $data3=array_reverse($data3);
	
    $tpl->set('etiquetas',$etiquetas);
    $tpl->set('data1',implode(",",$data1));
    $tpl->set('data2',implode(",",$data2));
    $tpl->set('data3',implode(",",$data3));
    

    echo $tpl->fetch('plantillas/serie.html');
    exit;
?>