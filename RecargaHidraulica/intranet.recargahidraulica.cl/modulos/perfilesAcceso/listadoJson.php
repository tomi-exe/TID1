<?php
    require_once('../../includeGeneral.php');
    require_once('base/autenticacion/PerfilAcceso.php');
    require_once('base/autenticacion/PerfilAccesoBD.php');

    $objBD=new PerfilAccesoBD($sql);


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
        
        $listado[$i]['nombre']=Util::mascara(0,$obj->getNombre(),'',0);
    }

    echo '{';
    echo '"recordsFiltered":' . $total . ",";
    echo '"recordsTotal":' . $total . ",";
    echo '"data":';
    echo json_encode($listado);
    echo '}';
    exit;
?>
