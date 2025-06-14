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
    
    $parametros=Util::limpiarParametros($_POST);
    $parametros['sensor']=$id;

    $start=isset($_POST['start']) ? trim($_POST['start']) : 0;
    $count=isset($_POST['length']) ? trim($_POST['length']) : 10;
    
    $dataBD=new SensorDatoBD($sql);
	$total=$dataBD->getTotal($parametros);
    $datos=$dataBD->getListado($parametros,true,$start,$count);
    $nDatos=count($datos);

    $listado=array();
    for($i=0; $i<$nDatos; $i++) {
        $obj=$datos[$i];
        $listado[$i]['id']=$obj->getId();
        $listado[$i]['fecha']=$obj->getFecha();
        
        $listado[$i]['humedadPorcentaje1']=round($obj->getHumedadPorcentaje1(),2) . " (" . $obj->getHumedad1() . ")";
        $listado[$i]['humedadPorcentaje2']=round($obj->getHumedadPorcentaje2(),2) . " (" . $obj->getHumedad2() . ")";
        $listado[$i]['humedadPorcentaje3']=round($obj->getHumedadPorcentaje3(),2) . " (" . $obj->getHumedad3() . ")";
    }

    echo '{';
    echo '"recordsFiltered":' . $total . ",";
    echo '"recordsTotal":' . $total . ",";
    echo '"data":';
    echo json_encode($listado);
    echo '}';
    exit;