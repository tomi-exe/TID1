<?php
    /* Todos los parámetros de configuración están contenidos en el arreglo
     * $CONF['componente']['paramtero']
     * fguidi@gaea.cl
     */

    /* Datos generales app */
    $CONF['app']['nombre']="RH | Intranet";
    $CONF['app']['nombreCorto']="Intranet";
    $CONF['app']['version']="1.0";
    $CONF['app']['revision']="2018011001";
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
    $CONF['general']['nombreRemitente']="RH | Intranet";
    $CONF['general']['correoRemitente']="intranet@recargahidraulica.cl";

    /* Parametros autenticacion */
    $CONF['autenticacion']['path']='usuarios';
    $CONF['autenticacion']['clase']='Usuario'; // Debe tener clase BD

    /* No modificar */
    $includeClaseAutenticacion=$CONF['autenticacion']['path'] . "/" . $CONF['autenticacion']['clase'] . '.php';
    $includeClaseAutenticacionBD=$CONF['autenticacion']['path'] . "/" . $CONF['autenticacion']['clase'] . 'BD.php';
    $claseAutenticacion=$CONF['autenticacion']['clase'];
    $claseAutenticacionBD=$claseAutenticacion . "BD";

	set_include_path(get_include_path() . PATH_SEPARATOR . $CONF['general']['path']);
?>
