<?php
    require_once('../../includeGeneral.php');
    require_once('base/autenticacion/PerfilAcceso.php');
    require_once('base/autenticacion/PerfilAccesoBD.php');
 

    if(isset($_GET['id'])) $id=$_GET['id'];
        else Util::mensajeError(1,true);
 
    // Control pestanas
    $tab=isset($_GET['tab']) ? $_GET['tab'] : 1;
    $tpl->set('tab',$tab);
 
    // Instanciamos clases
    $perfilAccesoBD=new PerfilAccesoBD($sql);
    
    // Cargamos registro y validamos
    $obj=$perfilAccesoBD->cargar($id);
    if(!$obj) Util::mensajeError(2,true);
 
    // Preparamos plantilla
    $tpl->set('id',$obj->getId());
    $tpl->set('nombre',$obj->getNombre());
 
    $menus=generarMenu($usuario,false);
    
    $yaExiste=array();
    for($i=0; $i<count($menus); $i++) {
        $nombre=$menus[$i]['nombre'];
        $modulos=$menus[$i]['modulos'];
 
        $items=array();
        for($j=0; $j<count($modulos); $j++) {
            if(in_array($modulos[$j]['id'],$yaExiste)) continue;
            $yaExiste[]=$modulos[$j]['id']; 
            $modconfig='../../modulos/' . $modulos[$j]['id'] . '/config.php';
            if(file_exists($modconfig)) {
                include($modconfig);
 
                $seguridad=$MODCONF['seguridad'];
                $sec=array();
                for($k=0; $k<count($seguridad); $k++) {
                    $activo="";
                    if($obj->tieneAcceso($MODCONF['id'],$seguridad[$k])) $sec[]=array("nombre"=>ucfirst($seguridad[$k]),"nivel"=>$seguridad[$k],"activo"=>Util::bool2html($obj->tieneAcceso($MODCONF['id'],$seguridad[$k])));
                }
 
                $items[]=array("id"=>$MODCONF['id'],
                               "nombre"=>Util::utf2html($MODCONF['nombre']),
                               "seguridad"=>$sec);
            }
        }
        if(count($items)>0) $listado[]=array("nombre"=>$nombre,"modulos"=>$items);
    }
    $tpl->set("modulos",$listado);
 
 
    echo $tpl->fetch('plantillas/ver.html');
    exit;
?>
