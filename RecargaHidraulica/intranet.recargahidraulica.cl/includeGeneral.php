<?php
    session_start();
    header('Content-Type: text/html; charset=UTF-8');

    /* Includes básicos */
    include('../../config.php');

    require_once('base/bd/MySQL.php');
    require_once('base/web/Util.php');
    require_once('base/web/Correo.php');
    require_once('base/web/bTemplate.php');
    require_once('base/autenticacion/PerfilAccesoBD.php');
    require_once($includeClaseAutenticacion);
    require_once($includeClaseAutenticacionBD);

    setlocale(LC_ALL, $CONF['general']['locale']);
	date_default_timezone_set($CONF['general']['timezone']);

    $tpl=new bTemplate();
    $tpl->set('raizWeb',$CONF['web']['raiz']);

    // JS Id para algunos widgets
    $tpl->set('jsId',uniqid());

    /* Instancia BBDD */
    $sql=new MySQL($CONF['bbdd'],0);
    
    $usuario=false;
    if(!isset($_SESSION['usuario'])) {
        header('Location: ' . $CONF['web']['raiz'] . '/index.php?msg=1');
        exit;
    } else {
        $autenticacionBD=new $claseAutenticacionBD($sql);
        $usuario=$autenticacionBD->cargar($_SESSION['usuario']);

        if(!$usuario) {
            header('Location: ' . $CONF['web']['raiz'] . '/index.php?msg=1');
            exit;
        }

        $th=new bTemplate();
        $th->set('nombreAplicacion',$CONF['app']['nombre']);
        $th->set('usuarioNombre',$usuario->getNombreLista());
        $th->set('revision',$CONF['app']['revision']);
        $tpl->set('header',$th->fetch('../../plantillas/header.html'));

        $tf=new bTemplate();
        $tf->set('nombreAplicacion',$CONF['app']['nombre']);
        $tf->set('revision',$CONF['app']['revision']);
        $tpl->set('footer',$tf->fetch('../../plantillas/footer.html'));


        // Ruta, módulo y acción (controlador)
        $queryPath=explode("/",$_SERVER["PHP_SELF"]);
        $moduloActual=$queryPath[count($queryPath)-2];
        $accionActual=$queryPath[count($queryPath)-1];


        // Configuración del modulo
        if(file_exists('../../modulos/' . $moduloActual . "/config.php")) {
            require_once('modulos/' . $moduloActual . "/config.php");
            $tpl->set('moduloNombre',$MODCONF['nombre']);
        }

        // Datos generales
		$tpl->set('moduloActual',$moduloActual);
		$tpl->set('usuario',$usuario->getId());
        
        $tpl->set('mostrarAgregar',$usuario->getPerfilAcceso()->tieneAcceso($moduloActual,'agregar'),TRUE);
        $tpl->set('mostrarModificar',$usuario->getPerfilAcceso()->tieneAcceso($moduloActual,'modificar'),TRUE);
        $tpl->set('mostrarEliminar',$usuario->getPerfilAcceso()->tieneAcceso($moduloActual,'eliminar'),TRUE);
        $tpl->set('mostrarInformes',$usuario->getPerfilAcceso()->tieneAcceso($moduloActual,'informes'),TRUE);

        // Parámetros para listas y exportación
        if($accionActual=='listadoJson.php' || $accionActual=='exportar.php') {
            $parametros=Util::limpiarParametros($_POST);

            $start=isset($_POST['start']) ? trim($_POST['start']) : 0;
            $count=isset($_POST['length']) ? trim($_POST['length']) : 10;
        }

        // Menú principal
        function ordenar_menu($a,$b) {
            if($a['posicion']==$b['posicion']) return(0);
            return($a['posicion']<$b['posicion'] ? -1 : 1);
        }
        
        function ordenar_menu2($a,$b) {
            return(strcmp($a['nombre'],$b['nombre']));
        }

        function generarMenu($usuario,$aplicarPermisos=true,$modulo=false) {
            $menu=array();
            $menuPrincipal=array(); // Especial, quedan arriba
            $modulosDisponibles=scandir('../../modulos');
            for($i=0; $i<count($modulosDisponibles); $i++) {
                if($modulosDisponibles[$i]=='..' || $modulosDisponibles[$i]=='.') continue;
                $modconfig='../../modulos/' . $modulosDisponibles[$i] . '/config.php';
                include($modconfig);


                if($aplicarPermisos) {
                    if(!$usuario->getPerfilAcceso()->tieneAcceso($MODCONF['id'],'leer')) continue;
                }
                
                $data=explode(":",$MODCONF['menu']);
                $nombreMenu=$data[0];
                $posicion=!isset($data[1]) ? 0 : $data[1];

                $menu[$nombreMenu]['nombre']=$nombreMenu=='.' ? "Inicio" : $nombreMenu;
                $menu[$nombreMenu]['modulos'][]=array(
                        'id'=>$MODCONF['id'],
                        'nombre'=>Util::utf2html($MODCONF['nombre']),
                        'posicion'=>$posicion,
                        'icono'=>$MODCONF['icono']
                );
                
            }

            $cta=0;
            $menuFinal=array();
            foreach($menu AS $var=>$val) {
                
                $submenu=$val['modulos'];
                usort($submenu,'ordenar_menu');
                
                $clases='';
                for($i=0; $i<count($submenu); $i++) {
                    if($submenu[$i]['id']==$modulo) {
                        $clases="active menu-open";
                        break;    
                    }
                }
                
                $menuFinal[$cta]['nombre']=$val['nombre'];
                $menuFinal[$cta]['modulos']=$submenu;
                $menuFinal[$cta]['clases']=$clases;
                $cta++;
            }
            
            usort($menuFinal,'ordenar_menu2');
            
            return($menuFinal);
        }
    
        // Obtenemos los menús
        $menu=generarMenu($usuario,true,$moduloActual);
        
        $tpl->set('menu',$menu);
    }
?>
