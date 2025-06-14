<?php
    require_once('../../includeGeneral.php');
    require_once('sensores/Sensor.php');
    require_once('sensores/SensorBD.php');

    if(isset($_GET['id'])) $id=$_GET['id'];
        else Util::mensajeError(1,true);

    // Control pestanas
    $tab=isset($_GET['tab']) ? $_GET['tab'] : 1;
    $tpl->set('tab',$tab);

    // Instanciamos clases
    $objBD=new SensorBD($sql);
    $obj=new Sensor();

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
    
    $tpl->set('nombre',$obj->getNombre());
    $tpl->set('tipo',$obj->getTipo());
    $tpl->set('tipoArr',Util::cmbSelLoop($obj->getTipoArr(),$obj->getTipo()));
    $tpl->set('ultimoEvento',$obj->getUltimoEvento()=='0000-00-00 00:00:00' ? '' : $obj->getUltimoEvento());
    $tpl->set('latitud',$obj->getLatitud());
    $tpl->set('longitud',$obj->getLongitud());
    $tpl->set('direccion',$obj->getDireccion());
    $tpl->set('comuna_nombreLista',$obj->getComuna() ? $obj->getComuna()->getNombreLista() : '');
$tpl->set('comuna',$obj->getComuna() ? $obj->getComuna()->getId() : '');
    $tpl->set('referencia',$obj->getReferencia());
    $tpl->set('periodo',$obj->getPeriodo());
    $tpl->set('contrasena',$obj->getContrasena());
    $tpl->set('habilitado',Util::bool2check($obj->isHabilitado()));
    $tpl->set('maximo1',$obj->getMaximo1());
    $tpl->set('profundidad1',$obj->getProfundidad1());
    $tpl->set('maximo2',$obj->getMaximo2());
    $tpl->set('profundidad2',$obj->getProfundidad2());
    $tpl->set('maximo3',$obj->getMaximo3());
    $tpl->set('profundidad3',$obj->getProfundidad3());

    


    echo $tpl->fetch('plantillas/modificar.html');
    exit;
?>