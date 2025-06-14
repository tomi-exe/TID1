<?php
    require_once('../../includeGeneral.php');
    ob_clean();
    ob_end_clean();

    require_once('base/autenticacion/PerfilAcceso.php');
    require_once('base/autenticacion/PerfilAccesoBD.php');

    $objBD=new PerfilAccesoBD($sql);


    $datos=$objBD->getListado($parametros,false,0,0);
    $nDatos=count($datos);

    $nombreArchivo="[" . date('Y-m-d') . "] Perfiles De Acceso.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $nombreArchivo . '"');
    header('Cache-Control: max-age=0');

    $fout=fopen("php://output","w");
    $cabeceras=array(
        
        utf8_decode('Nombre'),
        utf8_decode('Permisos'),
    );
    fputcsv($fout,$cabeceras,';','"');

    for($i=0; $i<$nDatos; $i++) {
        $obj=$datos[$i];
        $fila=array(
            
            utf8_decode(Util::mascara(0,$obj->getNombre(),'',0)),
            utf8_decode(Util::mascara(0,$obj->getPermisos(),'',0)),
        );
        fputcsv($fout,$fila,';','"');
    }

    fclose($fout);
    exit;
?>