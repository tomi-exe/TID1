<?php
    require_once('../../includeGeneral.php');
    require_once('usuarios/Usuario.php');
    require_once('usuarios/UsuarioBD.php');

    // Obtenemos las variables del formulario
    $id=trim($_POST['id']);
    
    $nombres=Util::inputTexto(0,$_POST['nombres'],255);
    $apellidoPaterno=Util::inputTexto(0,$_POST['apellidoPaterno'],255);
    $apellidoMaterno=Util::inputTexto(0,$_POST['apellidoMaterno'],255);
    $perfilAcceso=trim($_POST['perfilAcceso']);
    $correo=Util::inputTexto(1,$_POST['correo'],255);
    $contrasena=Util::inputTexto(3,$_POST['contrasena'],255);
    $habilitado=isset($_POST['habilitado']);

    // Instanciamos el objeto
    $obj=new Usuario();

    $obj->setId($id);
    
    $obj->setNombres($nombres);
    $obj->setApellidoPaterno($apellidoPaterno);
    $obj->setApellidoMaterno($apellidoMaterno);
    $obj->setPerfilAcceso($perfilAcceso);
    $obj->setCorreo($correo);
    $obj->setContrasena($contrasena);
    $obj->setHabilitado($habilitado);


    // Guardamos el objeto en la base de datos
    $error=false;
    $mensaje="";
    $jid="";

    $objBD=new UsuarioBD($sql);
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