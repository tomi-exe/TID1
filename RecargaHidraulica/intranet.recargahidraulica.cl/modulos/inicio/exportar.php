<?php
    require_once('../../includeGeneral.php');
    ob_clean();
    ob_end_clean();
    
	require_once('sensores/Sensor.php');
    require_once('sensores/SensorBD.php');
    require_once('sensores/SensorDato.php');
    require_once('sensores/SensorDatoBD.php');
    
    $pid=isset($_GET['pid']) ? $_GET['pid'] : die(1);
    
    $sensorBD=new SensorBD($sql);
    $sensor=$sensorBD->cargar($pid);
    
    if(!$sensor) die(2);
    
    $parametros=Util::limpiarParametros($_GET);
    $parametros['sensor']=$pid;
    
    $dataBD=new SensorDatoBD($sql);
	$total=$dataBD->getTotal($parametros);
    $datos=$dataBD->getListado($parametros,false);
    $nDatos=count($datos);
    
    $nombreArchivo="[" . date('Y-m-d') . "] Datos.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $nombreArchivo . '"');
    header('Cache-Control: max-age=0');

	$fout=fopen("php://output","w");
    $cabeceras=array(
        utf8_decode('Tipo'),
        utf8_decode('Número'),
        utf8_decode('Referencia'),
        utf8_decode('Latitud'),
        utf8_decode('Longitud'),
        utf8_decode('Dirección'),
        utf8_decode('Comuna'),
        utf8_decode('Fecha registro'),
        utf8_decode('Humedad 1 (%)'),
        utf8_decode('Humedad 2 (%)'),
        utf8_decode('Humedad 3 (%)')
    );
    fputcsv($fout,$cabeceras,';','"');

    $listado=array();
    for($i=0; $i<$nDatos; $i++) {
        $obj=$datos[$i];
        
        $data=array(
        	$sensor->getTipoStr(),
        	$sensor->getNumero(),
        	$sensor->getNombre(),
        	$sensor->getLatitud(),
        	$sensor->getLongitud(),
        	$sensor->getDireccion(),
        	$sensor->getComuna() ? $sensor->getComuna()->getNombre() : '',
        	$obj->getFecha(),
        	round($obj->getHumedadPorcentaje1(),2),
        	round($obj->getHumedadPorcentaje2(),2),
        	round($obj->getHumedadPorcentaje3(),2)
        	);
        	
        for($j=0; $j<count($data); $j++) $data[$j]=utf8_decode($data[$j]);
        
        fputcsv($fout,$data,';','"');
    }
    fclose($fout);

    exit;