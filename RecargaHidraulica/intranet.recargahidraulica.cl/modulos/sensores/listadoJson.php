<?php
    require_once('../../includeGeneral.php');
    require_once('sensores/Sensor.php');
    require_once('sensores/SensorBD.php');

    $objBD=new SensorBD($sql);


    $total=$objBD->getTotal($parametros);
    $datos=$objBD->getListado($parametros,true,$start,$count);
    $nDatos=count($datos);

    $listado=array();
    for($i=0; $i<$nDatos; $i++) {
        $obj=$datos[$i];
        $listado[$i]['click']=Util::abrirRegistro($moduloActual,$obj->getId());
        $listado[$i]['id']=$obj->getId();
        $listado[$i]['nombre']=method_exists($obj,'getNombre') ? $obj->getNombre() : $obj->getNombreLista();
        $listado[$i]['nombreLista']=$obj->getNombreLista();
        
        $listado[$i]['numero']=Util::mascara(0,$obj->getNumero(),'',0);
        $listado[$i]['nombre']=Util::mascara(0,$obj->getNombre(),'',0);
        $listado[$i]['tipo']=$obj->getTipoStr();
        $listado[$i]['tipoId']=$obj->getTipo();
        $listado[$i]['ultimoEvento']=Util::fechaSQLChile($obj->getUltimoEvento(),true);
        $listado[$i]['latitud']=Util::mascara(0,$obj->getLatitud(),'',0);
        $listado[$i]['longitud']=Util::mascara(0,$obj->getLongitud(),'',0);
        $listado[$i]['comuna']=$obj->getComuna() ? $obj->getComuna()->getNombreLista() : '';
        $listado[$i]['comunaId']=$obj->getComuna() ? $obj->getComuna()->getId() : '';
        $listado[$i]['periodo']=Util::mascara(0,$obj->getPeriodo(),'',0);
        $listado[$i]['habilitado']=Util::bool2html($obj->isHabilitado());
    }

    echo '{';
    echo '"recordsFiltered":' . $total . ",";
    echo '"recordsTotal":' . $total . ",";
    echo '"data":';
    echo json_encode($listado);
    echo '}';
    exit;
?>