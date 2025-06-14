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
    $tpl->set('id',$obj->getId());
    $tpl->set('nombreLista',$obj->getNombreLista());
    if(method_exists($obj,'getNombre')) $tpl->set('tituloModulo',substr($obj->getNombre(),0,40));
    
    $tpl->set('numero',Util::mascara(0,$obj->getNumero(),'',0));
    $tpl->set('nombre',Util::mascara(0,$obj->getNombre(),'',0));
    $tpl->set('tipo',$obj->getTipoStr());
    $tpl->set('tipoId',$obj->getTipo());
    $tpl->set('ultimoEvento',Util::fechaSQLChile($obj->getUltimoEvento(),true));
    $tpl->set('latitud',Util::mascara(0,$obj->getLatitud(),'',0));
    $tpl->set('longitud',Util::mascara(0,$obj->getLongitud(),'',0));
    $tpl->set('direccion',Util::mascara(0,$obj->getDireccion(),'',0));
    $tpl->set('comuna',$obj->getComuna() ? $obj->getComuna()->getNombreLista() : '');
    $tpl->set('comunaId',$obj->getComuna() ? $obj->getComuna()->getId() : '');
    $tpl->set('referencia',Util::mascara(0,$obj->getReferencia(),'',0));
    $tpl->set('periodo',Util::mascara(0,$obj->getPeriodo(),'',0));
    $tpl->set('contrasena',Util::mascara(0,$obj->getContrasena(),'',0));
    $tpl->set('habilitado',Util::bool2html($obj->isHabilitado()));
    $tpl->set('maximo1',Util::mascara(0,$obj->getMaximo1(),'',0));
    $tpl->set('profundidad1',Util::mascara(0,$obj->getProfundidad1(),'',0));
    $tpl->set('maximo2',Util::mascara(0,$obj->getMaximo2(),'',0));
    $tpl->set('profundidad2',Util::mascara(0,$obj->getProfundidad2(),'',0));
    $tpl->set('maximo3',Util::mascara(0,$obj->getMaximo3(),'',0));
    $tpl->set('profundidad3',Util::mascara(0,$obj->getProfundidad3(),'',0));

    


    echo $tpl->fetch('plantillas/ver.html');
    exit;
?>