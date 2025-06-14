<?php
    require_once('../../includeGeneral.php');
    require_once('base/autenticacion/PerfilAcceso.php');
    require_once('base/autenticacion/PerfilAccesoBD.php');

    $id=isset($_POST['id']) ? trim($_POST['id']) : die('Error fatal');
    $url=isset($_POST['url']) ? trim($_POST['url']) : '';

    $objBD=new PerfilAccesoBD($sql);
    $obj=$objBD->cargar($id);
    if(!$obj) die('No existe el registro');

    if(!$objBD->eliminar($obj->getId())) die('Error eliminando el registro');

    if($url=='') $url='index.php';
    header('Location: ' . $url);
    exit;
?>
