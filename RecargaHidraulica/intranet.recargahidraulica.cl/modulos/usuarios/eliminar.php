<?php
    require_once('../../includeGeneral.php');
    require_once('usuarios/Usuario.php');
    require_once('usuarios/UsuarioBD.php');

    $id=isset($_POST['id']) ? trim($_POST['id']) : die('Error fatal');
    $url=isset($_POST['url']) ? trim($_POST['url']) : '';

    $objBD=new UsuarioBD($sql);
    $obj=$objBD->cargar($id);
    if(!$obj) die('No existe el registro');
    if($obj->isEliminado()) die('El registro ya se encuentra eliminado');

    if(!$objBD->eliminar($obj->getId())) die('Error eliminando el registro');

    if($url=='') $url='index.php';
    header('Location: ' . $url);
    exit;
?>