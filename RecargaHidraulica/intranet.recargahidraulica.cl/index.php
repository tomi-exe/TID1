<?php
    session_start();
    header('Content-Type: text/html; charset=UTF-8');
    
    include('config.php');
    include('base/web/bTemplate.php');

    if(isset($_SESSION['usuario'])) {
        header('Location: modulos/inicio/index.php');
        exit;
    }

    $msg=isset($_GET['msg']) ? trim($_GET['msg']) : false;

    $mensaje="";
    if($msg==1) $mensaje="La sesión ha caducado";
    $tpl=new bTemplate();
    $tpl->set('mensaje',$mensaje);
    $tpl->set('usuario',isset($_COOKIE['recordar']) ? $_COOKIE['recordar'] : '');
    $tpl->set('recordar',isset($_COOKIE['recordar']) ? "checked" : "");
    $tpl->set('nombreAplicacion',$CONF['app']['nombre']);

    echo $tpl->fetch("plantillas/index.html");
?>
