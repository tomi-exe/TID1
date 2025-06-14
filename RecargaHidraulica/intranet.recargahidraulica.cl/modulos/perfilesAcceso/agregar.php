<?php
    require_once('../../includeGeneral.php');
    require_once('base/autenticacion/PerfilAcceso.php');
    require_once('base/autenticacion/PerfilAccesoBD.php');
 
    // Control pestanas
    $tab=isset($_GET['tab']) ? $_GET['tab'] : 1;
    $tpl->set('tab',$tab);
 
    // Preparamos plantilla
    $tpl->set('modificar',false,TRUE);
    $tpl->set('moduloAccion','Agregar');

    $tpl->set('id',$sql->getUUID());
    
    $tpl->set('nombre','');
    $tpl->set('permisos','');
 
    $yaExiste=array();
    $listado=array();
    $cta=0;
    $menus=$menuFinal;
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
                    $sec[]=array("nombre"=>ucfirst($seguridad[$k]),"nivel"=>$seguridad[$k],"activo"=>$activo);
                }
 
                $items[$cta]=array("id"=>$MODCONF['id'],
                                   "nombre"=>Util::utf2html($MODCONF['nombre']),
                                   "seguridad"=>$sec);
                $cta++;
            }
        }
        if(count($items)>0) $listado[]=array("nombre"=>$nombre,"modulos"=>$items);
    }
 
    $tpl->set("modulos",$listado);
 
    echo $tpl->fetch('plantillas/modificar.html');
    exit;
?>
