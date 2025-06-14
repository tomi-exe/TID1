<?php
    require_once('../../includeGeneral.php');
    require_once('general/Comuna.php');
    require_once('general/ComunaBD.php');

    if(isset($_GET['id'])) $id=$_GET['id'];
        else Util::mensajeError(1,true);

    // Control pestanas
    $tab=isset($_GET['tab']) ? $_GET['tab'] : 1;
    $tpl->set('tab',$tab);

    // Instanciamos clases
    $objBD=new ComunaBD($sql);
    $obj=new Comuna();

    // Cargamos registro y validamos
    $obj=$objBD->cargar($id);
    if(!$obj) Util::mensajeError(2,true);
    if($obj->isEliminado()) Util::mensajeError(3,true);


    
    // Preparamos plantilla
    $tpl->set('id',$obj->getId());
    $tpl->set('nombreLista',$obj->getNombreLista());
    if(method_exists($obj,'getNombre')) $tpl->set('tituloModulo',substr($obj->getNombre(),0,40));
    
    $tpl->set('nombre',Util::mascara(0,$obj->getNombre(),'',0));

    


    echo $tpl->fetch('plantillas/ver.html');
    exit;
?>