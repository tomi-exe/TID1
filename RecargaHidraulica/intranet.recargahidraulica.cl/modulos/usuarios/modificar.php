<?php
    require_once('../../includeGeneral.php');
    require_once('usuarios/Usuario.php');
    require_once('usuarios/UsuarioBD.php');

    if(isset($_GET['id'])) $id=$_GET['id'];
        else Util::mensajeError(1,true);

    // Control pestanas
    $tab=isset($_GET['tab']) ? $_GET['tab'] : 1;
    $tpl->set('tab',$tab);

    // Instanciamos clases
    $objBD=new UsuarioBD($sql);
    $obj=new Usuario();

    // Cargamos registro y validamos
    $obj=$objBD->cargar($id);
    if(!$obj) Util::mensajeError(2,true);
    if($obj->isEliminado()) Util::mensajeError(3,true);

    // Preparamos plantilla
    $tpl->set('modificar',true,TRUE);
    $tpl->set('moduloAccion','Modificar');

    $tpl->set('id',$obj->getId());
    $tpl->set('nombreLista',$obj->getNombreLista());
    if(method_exists($obj,'getNombre')) $tpl->set('tituloModulo',$obj->getNombre());
    
    $tpl->set('nombres',$obj->getNombres());
    $tpl->set('apellidoPaterno',$obj->getApellidoPaterno());
    $tpl->set('apellidoMaterno',$obj->getApellidoMaterno());
    $tpl->set('perfilAcceso_nombreLista',$obj->getPerfilAcceso() ? $obj->getPerfilAcceso()->getNombreLista() : '');
$tpl->set('perfilAcceso',$obj->getPerfilAcceso() ? $obj->getPerfilAcceso()->getId() : '');
    $tpl->set('correo',$obj->getCorreo());
    $tpl->set('contrasena',$obj->getContrasena());
    $tpl->set('habilitado',Util::bool2check($obj->isHabilitado()));

    


    echo $tpl->fetch('plantillas/modificar.html');
    exit;
?>