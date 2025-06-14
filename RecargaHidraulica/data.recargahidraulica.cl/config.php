<?php
    /* Todos los parámetros de configuración están contenidos en el arreglo
     * $CONF['componente']['paramtero']
     * fguidi@gaea.cl
     */

    /* Datos generales app */
    $CONF['app']['nombre']="UAI | Recarga Hidráulica";
    $CONF['app']['nombreCorto']="UAI RH";
    $CONF['app']['version']="1.0";
    $CONF['app']['revision']="20171207";
    $CONF['empresa']['nombre']="GAEA";

    /* Contectividad Motor Base de datos */
    $CONF['bbdd']['host']='localhost';
    $CONF['bbdd']['usuario']='root';
    $CONF['bbdd']['contrasena']='';
    $CONF['bbdd']['bbdd0']='uai_recargahidraulica';

    /* Parámetros Web */
    $CONF['web']['raiz']='';

    /* Locales y otros datos generales */
    $CONF['general']['path']='/projects/clases';
    $CONF['general']['locale']="es_CL";
    $CONF['general']['timezone']='America/Santiago';
    $CONF['general']['nombreRemitente']="UAI Recarga Hidraulica";
    $CONF['general']['correoRemitente']="uairh@gaea.cl";

	set_include_path(get_include_path() . PATH_SEPARATOR . $CONF['general']['path']);
?>
