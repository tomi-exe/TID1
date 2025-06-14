<?php
    require_once('../../includeGeneral.php');
    ob_clean();
    ob_end_clean();

    require_once('sensores/Sensor.php');
    require_once('sensores/SensorBD.php');

    $objBD=new SensorBD($sql);


    $datos=$objBD->getListado($parametros,false,0,0);
    $nDatos=count($datos);

    $nombreArchivo="[" . date('Y-m-d') . "] Sensores.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $nombreArchivo . '"');
    header('Cache-Control: max-age=0');

    $fout=fopen("php://output","w");
    $cabeceras=array(
        
        utf8_decode('Número'),
        utf8_decode('Nombre'),
        utf8_decode('Tipo'),
        utf8_decode('Ult. Evento'),
        utf8_decode('Latitud'),
        utf8_decode('Longitud'),
        utf8_decode('Dirección'),
        utf8_decode('Comuna'),
        utf8_decode('Referencia'),
        utf8_decode('Período [seg]'),
        utf8_decode('Contraseña'),
        utf8_decode('Habilitado'),
        utf8_decode('Máximo S1'),
        utf8_decode('Profundidad 1 (cm)'),
        utf8_decode('Máximo S2'),
        utf8_decode('Profundidad 2 (cm)'),
        utf8_decode('Máximo S3'),
        utf8_decode('Profunidad 3 (cm)'),
    );
    fputcsv($fout,$cabeceras,';','"');

    for($i=0; $i<$nDatos; $i++) {
        $obj=$datos[$i];
        $fila=array(
            
            utf8_decode(Util::mascara(0,$obj->getNumero(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getNombre(),'',0)),
            utf8_decode($obj->getTipoStr()),
            utf8_decode($obj->getUltimoEvento()),
            utf8_decode(Util::mascara(0,$obj->getLatitud(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getLongitud(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getDireccion(),'',0)),
            utf8_decode($obj->getComuna() ? $obj->getComuna()->getNombreLista() : ''),
            utf8_decode(Util::mascara(0,$obj->getReferencia(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getPeriodo(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getContrasena(),'',0)),
            utf8_decode((int)$obj->isHabilitado()),
            utf8_decode(Util::mascara(0,$obj->getMaximo1(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getProfundidad1(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getMaximo2(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getProfundidad2(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getMaximo3(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getProfundidad3(),'',0)),
        );
        fputcsv($fout,$fila,';','"');
    }

    fclose($fout);
    exit;
?>