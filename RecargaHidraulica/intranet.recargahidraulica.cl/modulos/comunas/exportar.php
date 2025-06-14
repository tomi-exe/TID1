<?php
    require_once('../../includeGeneral.php');
    ob_clean();
    ob_end_clean();

    require_once('general/Comuna.php');
    require_once('general/ComunaBD.php');

    $objBD=new ComunaBD($sql);


    $datos=$objBD->getListado($parametros,false,0,0);
    $nDatos=count($datos);

    $nombreArchivo="[" . date('Y-m-d') . "] Comunas.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $nombreArchivo . '"');
    header('Cache-Control: max-age=0');

    $fout=fopen("php://output","w");
    $cabeceras=array(
        
        utf8_decode('Nombre'),
    );
    fputcsv($fout,$cabeceras,';','"');

    for($i=0; $i<$nDatos; $i++) {
        $obj=$datos[$i];
        $fila=array(
            
            utf8_decode(Util::mascara(0,$obj->getNombre(),'',0)),
        );
        fputcsv($fout,$fila,';','"');
    }

    fclose($fout);
    exit;
?>