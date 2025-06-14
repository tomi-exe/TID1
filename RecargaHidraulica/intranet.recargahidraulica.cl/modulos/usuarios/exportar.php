<?php
    require_once('../../includeGeneral.php');
    ob_clean();
    ob_end_clean();

    require_once('usuarios/Usuario.php');
    require_once('usuarios/UsuarioBD.php');

    $objBD=new UsuarioBD($sql);


    $datos=$objBD->getListado($parametros,false,0,0);
    $nDatos=count($datos);

    $nombreArchivo="[" . date('Y-m-d') . "] Usuarios.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $nombreArchivo . '"');
    header('Cache-Control: max-age=0');

    $fout=fopen("php://output","w");
    $cabeceras=array(
        
        utf8_decode('Nombres'),
        utf8_decode('Apellido Paterno'),
        utf8_decode('Apellido Materno'),
        utf8_decode('Perfil'),
        utf8_decode('Correo'),
        utf8_decode('Contraseña'),
        utf8_decode('Habilitado'),
    );
    fputcsv($fout,$cabeceras,';','"');

    for($i=0; $i<$nDatos; $i++) {
        $obj=$datos[$i];
        $fila=array(
            
            utf8_decode(Util::mascara(0,$obj->getNombres(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getApellidoPaterno(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getApellidoMaterno(),'',0)),
            utf8_decode($obj->getPerfilAcceso() ? $obj->getPerfilAcceso()->getNombreLista() : ''),
            utf8_decode(Util::mascara(0,$obj->getCorreo(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getContrasena(),'',0)),
            utf8_decode((int)$obj->isHabilitado()),
        );
        fputcsv($fout,$fila,';','"');
    }

    fclose($fout);
    exit;
?>