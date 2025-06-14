<?php
    session_start();

    require_once('config.php');
    require_once('base/bd/MySQL.php');
    require_once('base/web/bTemplate.php');
    require_once('base/autenticacion/PerfilAccesoBD.php');
    require_once($includeClaseAutenticacion);
    require_once($includeClaseAutenticacionBD);


    $usuario=$_POST['usuario'];
    $contrasena=$_POST['contrasena'];
    $recordar=isset($_POST['recordar']);
    
    $mensaje=array("codigo"=>"1","accion"=>"0","mensaje"=>"Las credenciales no son válidas");
    if(!strlen($usuario)>0 || !strlen($contrasena)>0) {
        header('Location: index.php?msg=1&usuario=' . $usuario);
    } else {
        $sql=new MySQL($CONF['bbdd'],0);
        $perfilAccesoBD=new PerfilAccesoBD($sql);
        $autenticacionBD=new $claseAutenticacionBD($sql);

        $obj=false;
        if(($obj=$autenticacionBD->autenticar($usuario,$contrasena))) {
            $_SESSION['usuario']=$obj->getId();

            // Recordar
            if($recordar) setcookie('recordar',$usuario,time()+31536000);
                else setcookie('recordar','',1);

            //  Audit
            $ipActual=$_SERVER['REMOTE_ADDR'];
            $perfilAccesoBD->audit($ipActual,$obj->getId(),'login','login','');

            $mensaje=array("codigo"=>"0","accion"=>"2","url"=>"modulos/inicio/index.php");
        }
    }

    echo json_encode($mensaje);
    exit;
?>
