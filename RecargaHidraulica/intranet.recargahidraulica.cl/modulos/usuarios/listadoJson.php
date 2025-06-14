<?php
    require_once('../../includeGeneral.php');
    require_once('usuarios/Usuario.php');
    require_once('usuarios/UsuarioBD.php');

    $objBD=new UsuarioBD($sql);


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
        
        $listado[$i]['nombres']=Util::mascara(0,$obj->getNombres(),'',0);
        $listado[$i]['apellidoPaterno']=Util::mascara(0,$obj->getApellidoPaterno(),'',0);
        $listado[$i]['perfilAcceso']=$obj->getPerfilAcceso() ? $obj->getPerfilAcceso()->getNombreLista() : '';
        $listado[$i]['perfilAccesoId']=$obj->getPerfilAcceso() ? $obj->getPerfilAcceso()->getId() : '';
        $listado[$i]['correo']=Util::mascara(1,$obj->getCorreo(),'',0);
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