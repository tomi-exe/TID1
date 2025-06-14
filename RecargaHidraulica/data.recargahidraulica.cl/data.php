<?php
    require_once('config.php');
    setlocale(LC_ALL, $CONF['general']['locale']);
	date_default_timezone_set($CONF['general']['timezone']);
    
    require_once('base/bd/MySQL.php');
    require_once('sensores/Sensor.php');
    require_once('sensores/SensorBD.php');
    require_once('sensores/SensorDato.php');
    require_once('sensores/SensorDatoBD.php');

    // Momento de la medición (+- diferencia de comunicación)
    $fecha=date("Y-m-d H:i:s");

    // Parámetros y control mínimo
    $id=isset($_GET['i']) ? trim($_GET['i']) : die('1');
    $contrasena=isset($_GET['c']) ? trim($_GET['c']) : die('1');
    $data=isset($_GET['d']) ? trim($_GET['d']) : die('1');


    // Instancia SQL
    $sql=new MySQL($CONF['bbdd'],0);
    $sensorBD=new SensorBD($sql);
    $datoBD=new SensorDatoBD($sql);

    // Autenticamos
    $sensor=$sensorBD->autenticar($id,$contrasena);
    if(!$sensor) die("2");

    if(strpos($data,",")!==false) {
        $humedad=explode(",",$data);
        
        if(count($humedad)==3) {
            $humedadPorcentaje=array();
            $maximos=array($sensor->getMaximo1(),$sensor->getMaximo2(),$sensor->getMaximo3());
            for($i=0; $i<count($humedad); $i++) {
                // Valor corregido del sensor análogo a porcentaje
                $final=$humedad[$i];
                if($final<0) $final=0;
                if($final>$maximos[$i]) $final=$maximos[$i];
                $humedadPorcentaje[$i]=$final*100/$maximos[$i];
            }
        
            $dato=new SensorDato();
            $dato->setId($sql->getUUID());
            $dato->setSensor($sensor->getId());
            $dato->setFecha($fecha);
            
            // Valor análogo leido
            $dato->setHumedad1($humedad[0]);
            $dato->setHumedad2($humedad[1]);
            $dato->setHumedad3($humedad[2]);
            
            // Trazabilidad (máximos al momento de la medición)
            $dato->setHumedadMaximo1($maximos[0]);
            $dato->setHumedadMaximo2($maximos[1]);
            $dato->setHumedadMaximo3($maximos[2]);
            
            // Valor calculado de porcentaje de humedad=humedad/maximo
            $dato->setHumedadPorcentaje1($humedadPorcentaje[0]);
            $dato->setHumedadPorcentaje2($humedadPorcentaje[1]);
            $dato->setHumedadPorcentaje3($humedadPorcentaje[2]);
            
            if(!$datoBD->guardar($dato)) {
            	die("5");
            } else {
            	// Actualizamos timestamp ultimo evento
            	$sensorBD->ultimoEvento($sensor->getId(),$fecha);
            	
            	// Todo OK, informamos periodo de actualizacion
            	die("$" . $sensor->getPeriodo() . "!");
            }
        } else die("4");
    } else die("3");

    die("0");
?>
