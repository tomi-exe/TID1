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
    $tpl->set('id',$obj->getId());
    $tpl->set('nombreLista',$obj->getNombreLista());
    if(method_exists($obj,'getNombre')) $tpl->set('tituloModulo',substr($obj->getNombre(),0,40));
    
    $tpl->set('nombres',Util::mascara(0,$obj->getNombres(),'',0));
    $tpl->set('apellidoPaterno',Util::mascara(0,$obj->getApellidoPaterno(),'',0));
    $tpl->set('apellidoMaterno',Util::mascara(0,$obj->getApellidoMaterno(),'',0));
    $tpl->set('perfilAcceso',$obj->getPerfilAcceso() ? $obj->getPerfilAcceso()->getNombreLista() : '');
    $tpl->set('perfilAccesoId',$obj->getPerfilAcceso() ? $obj->getPerfilAcceso()->getId() : '');
    $tpl->set('correo',Util::mascara(1,$obj->getCorreo(),'',0));
    $tpl->set('habilitado',Util::bool2html($obj->isHabilitado()));

    


    echo $tpl->fetch('plantillas/ver.html');
    exit;
?>