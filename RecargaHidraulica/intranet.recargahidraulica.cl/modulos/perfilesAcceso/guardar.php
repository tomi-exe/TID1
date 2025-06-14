<?php
    require_once('../../includeGeneral.php');
    require_once('base/autenticacion/PerfilAcceso.php');
    require_once('base/autenticacion/PerfilAccesoBD.php');
 
    // Obtenemos las variables del formulario
    $id=trim($_POST['id']);
    $nombre=Util::inputTexto(0,$_POST['nombre'],255);
 
    $permisos=$_POST['permisos'];
 
    // Procesamos los permisos
    $modulos=array();
    for($i=0; $i<count($permisos); $i++) {
        list($modulo,$permiso)=explode("_",$permisos[$i]);
 
        $modulos[$modulo][]=$permiso;
    }
 
    $permisos="";
    foreach($modulos as $modulo=>$mod) {
        $permisos.=$modulo . ":";
        for($i=0; $i<count($mod); $i++) {
            $permisos.=$mod[$i];
            if($i<count($mod)-1) $permisos.=",";
        }
        $permisos.="\n";
    }
 
    $permisos=trim($permisos);
 
    $perfilAccesoBD=new PerfilAccesoBD($sql);
 
    $obj=new PerfilAcceso();
 
    $obj->setId($id);
    $obj->setNombre($nombre);
    $obj->setPermisos($permisos);
 
 
    $error=false;
    $mensaje="";
    $nombre="";
    $jid="";
 
    $obj2=$perfilAccesoBD->guardar($obj);
    if(!$obj2) {
        $error=100;
        $mensaje="Ocurrió un error guardando el registro. Por favor intente de nuevo.";
    } else {
        $error=0;
        $jid=$obj2->getId();
        $nombre=$obj2->getNombre();
    }
    
    $accion=2;
    if($error) $accion=1;
    $json=array("codigo"=>(int)$error,"mensaje"=>$mensaje,"accion"=>$accion,"url"=>"ver.php?id=" . $jid);
    echo json_encode($json);
    exit;
?>
