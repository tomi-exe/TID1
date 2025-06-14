<?php
    require_once('../../includeGeneral.php');
    require_once('sensores/Sensor.php');
    require_once('sensores/SensorBD.php');

    // Obtenemos las variables del formulario
    $id=trim($_POST['id']);
    
    $nombre=Util::inputTexto(0,$_POST['nombre'],255);
    $tipo=trim($_POST['tipo']);
    $latitud=Util::inputTexto(1,$_POST['latitud']);
    $longitud=Util::inputTexto(1,$_POST['longitud']);
    $direccion=Util::inputTexto(0,$_POST['direccion'],255);
    $comuna=isset($_POST['comunaNA']) ? '' : trim($_POST['comuna']);
    $referencia=trim($_POST['referencia']);
    $periodo=Util::inputNumero(0,$_POST['periodo']);
    $contrasena=Util::inputTexto(3,$_POST['contrasena'],255);
    $habilitado=isset($_POST['habilitado']);
    $maximo1=Util::inputNumero(0,$_POST['maximo1']);
    $profundidad1=Util::inputNumero(0,$_POST['profundidad1']);
    $maximo2=Util::inputNumero(0,$_POST['maximo2']);
    $profundidad2=Util::inputNumero(0,$_POST['profundidad2']);
    $maximo3=Util::inputNumero(0,$_POST['maximo3']);
    $profundidad3=Util::inputNumero(0,$_POST['profundidad3']);

    // Instanciamos el objeto
    $obj=new Sensor();

    $obj->setId($id);
    
    $obj->setNombre($nombre);
    $obj->setTipo($tipo);
    $obj->setUltimoEvento(date('Y-m-d'));
    $obj->setLatitud($latitud);
    $obj->setLongitud($longitud);
    $obj->setDireccion($direccion);
    $obj->setComuna($comuna);
    $obj->setReferencia($referencia);
    $obj->setPeriodo($periodo);
    $obj->setContrasena($contrasena);
    $obj->setHabilitado($habilitado);
    $obj->setMaximo1($maximo1);
    $obj->setProfundidad1($profundidad1);
    $obj->setMaximo2($maximo2);
    $obj->setProfundidad2($profundidad2);
    $obj->setMaximo3($maximo3);
    $obj->setProfundidad3($profundidad3);


    // Guardamos el objeto en la base de datos
    $error=false;
    $mensaje="";
    $jid="";

    $objBD=new SensorBD($sql);
    $obj2=$objBD->guardar($obj);
    if(!$obj2) {
        $error=100;
        $mensaje="Ocurrió un error guardando el registro. Por favor intente de nuevo.";
    } else {
        $error=0;
        $jid=$obj2->getId();
        
    }

    $accion=2;
    if($error) $accion=1;
    $json=array("codigo"=>(int)$error,"mensaje"=>$mensaje,"accion"=>$accion,"url"=>"ver.php?id=" . $jid);
    echo json_encode($json);
    exit;
?>