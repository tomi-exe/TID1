<?php
    require_once('../../includeGeneral.php');
    require_once('general/Comuna.php');
    require_once('general/ComunaBD.php');

    // Obtenemos las variables del formulario
    $id=trim($_POST['id']);
    
    $nombre=Util::inputTexto(0,$_POST['nombre'],255);

    // Instanciamos el objeto
    $obj=new Comuna();

    $obj->setId($id);
    
    $obj->setNombre($nombre);


    // Guardamos el objeto en la base de datos
    $error=false;
    $mensaje="";
    $jid="";

    $objBD=new ComunaBD($sql);
    $obj2=$objBD->guardar($obj);
    if(!$obj2) {
        $error=100;
        $mensaje="Ocurrió un error guardando el registro. Por favor intente de nuevo.";
    } else {
        $error=0;
        $jid=$obj2->getId();
        
    }

    $accion=2;
    if($error) $accion=1;
    $json=array("codigo"=>(int)$error,"mensaje"=>$mensaje,"accion"=>$accion,"url"=>"ver.php?id=" . $jid);
    echo json_encode($json);
    exit;
?>