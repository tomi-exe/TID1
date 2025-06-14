<?php
class Util {
    public function Util() {

    }

    public function inputNumero($tipo,$data) {
        $data=trim($data);

        if($tipo==1) return((double)$data);

        // Forzamos siempre que sea numero entero en caso
        return((int)$data);
    }

    public function inputTexto($tipo,$data,$largo=0) {
        $data=trim($data);

        if($largo>0) $data=substr($data,0,$largo);

        if($tipo==0) return(ucwords(strtolower($data)));
        if($tipo==1) return(strtolower($data));
        if($tipo==2) return(strtoupper($data));

        return($data);
    }

    public function mascara($tipo,$str,$extra='',$extra2='') {
        $mask='';
        if($tipo==0) {
            $mask=$str;
        } else if($tipo==1) { // Correo
            $mask='<a href="mailto:' . $str . '">' . $str . '</a>';
        } else if($tipo==2) { //URL
            $mask='<a TARGET="_blank" href="' . $str . '">' . $str . '</a>';
        } else if($tipo==3) { // Telefono
            $mask='<a href="tel://' . $str . '">' . $str . '</a>';
        } else if($tipo==4) { //Numero normal
            if($extra2=='') $extra2=0;
            $mask=number_format($str,$extra2,',','.');
        } else if($tipo==5) { //Numero - moneda
            if($extra2=='') $extra2=0;
            $mask=$extra . " " . number_format($str,$extra2,',','.');
        } else if($tipo==6) { // RUT
            list($b,$d)=explode("-",$str);
            $mask=number_format($b,0,'','.') . "-" . $d;
        } else if($tipo==7) { // PAD con extra o extra2
            $mask=str_pad($str,$extra,$extra2,STR_PAD_LEFT);
        } else if($tipo==8) {
            $mask=str_pad($str,$extra,$extra2,STR_PAD_RIGHT);
        } else if($tipo==9) { // prefijo - sufijo
            $mask=$extra . $str . $extra2;
        } else if($tipo==10) {
            $mask='********';
        } else $mask=$str;

        return($mask);
    }

	public function pestanas($sel,$total,&$tpl) {
		for($i=0; $i<$total; $i++) $tpl->set("tab" . ($i+1),($i+1)==$sel ? "true" : "false");
	}

	public function nombreMes($mes) {
        $meses=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
		return($meses[$mes-1]);
	}

    public function meses() {
        $meses=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
        
        return($meses);
    }

    public function mesesHTML() {
        $meses=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");

        $listado=array();
        for($i=0; $i<count($meses); $i++) {
            $listado[$i]['id']=$i;
            $listado[$i]['nombre']=$meses[$i];
        }
        return($listado);
    }
    
    public function anosHTML($inicio) {
        $listado=array();
        for($i=$inicio; $i<=date("Y"); $i++) {
            $listado[$i]['id']=$i;
            $listado[$i]['nombre']=$i;
        }
        
        return($listado);
    }

    public function correoSimple($CONF,$destinatario,$tema,$mensaje) {
        $cabeceras='From: ' . $CONF['general']['correoRemitente'] . "\r\n" .
                  'X-Mailer: ' . $CONF['nombreCortoAplicacion'] . "/" . $CONF['versionAplicacion'];

        mail(utf8_decode($destinatario),utf8_decode($tema),utf8_decode($mensaje),$cabeceras);
    }
    
    public function correoSimple2($CONF,$remitente,$destinatario,$tema,$mensaje) {
        $cabeceras='From: ' . utf8_decode($remitente) . "\r\n" .
                  'X-Mailer: ' . $CONF['nombreCortoAplicacion'] . "/" . $CONF['versionAplicacion'];

        mail(utf8_decode($destinatario),utf8_decode($tema),utf8_decode($mensaje),$cabeceras);
    }

	public function descargarArchivoCKEditor($modulo,$id,$url='descargar.php',$ckfn,$descarga=true,$raiz="/") {
        if($descarga) {
    		$link='<a href="javascript:window.opener.CKEDITOR.tools.callFunction(' . $ckfn . ',\'' . $raiz . '/modulos/' . $modulo . '/' . $url . '?id=' . $id . '\');window.close();"><img src="' . $raiz . '/imagenes/iconos/acciones/adjunto.png"></a>';
        } else {
    		$link='window.parent.CKEDITOR.tools.callFunction(' . $ckfn . ',\'' . $raiz . '/modulos/' . $modulo . '/' . $url . '?id=' . $id . '\',\'\');';
        }
		return($link);
	}


	public function descargarArchivo($modulo,$id,$url='descargar.php',$texto=false,$raiz="/") {
		if($texto) {
			$link='<a href="javascript:location.replace(\'' . $raiz . '/modulos/' . $modulo . '/' . $url . '?id=' . $id . '\');">' . $texto . '</a>';
		} else {
			$link='<a href="javascript:location.replace(\'' . $raiz . '/modulos/' . $modulo . '/' . $url . '?id=' . $id . '\');"><img src="' . $raiz . '/imagenes/iconos/acciones/adjunto.png"></a>';
		}
		return($link);
	}

	public function abrirExternoConfirmacion($modulo,$accion,$id,$icono,$url=false) {
		if(!$url) {
			$link='<a href="javascript:abrirExternoConfirmacion(\'' . $modulo . "','" . $accion . "','" . $id . '\');"><img src="imagenes/iconos/acciones/' . $icono . '"></a>';
		} else {
			$link='<a href="javascript:abrirExternoConfirmacion(\'' . $modulo . "','" . $accion . "','" . $id . "','" . $url . '\');"><img src="imagenes/iconos/acciones/' . $icono . '"></a>';
		}
		
		return($link);
	}

	public function abrirWidget($titulo,$modulo,$accion,$id,$url=false,$icono='abrir.png',$texto=false) {
		if(!$texto) $link='<img src="imagenes/iconos/acciones/' . $icono . '">';
			else $link=$texto;
			
		if(!$url) {
			$link='<a href="javascript:abrirExternoWidget(\'' . $titulo . "','" . $modulo . "','" . $accion . "','" . $id . '\');">' . $link . '</a>';
		} else {
			$link='<a href="javascript:abrirExternoWidget(\'' . $titulo . "','" . $modulo . "','" . $accion . "','" . $id . '\',\'' . $url . '\');">' . $link . '</a>';
		}
		return($link);
	}

	public function abrirRegistro($modulo,$id,$texto=false,$extra=false) {
        $link='../' . $modulo . '/ver.php?id=' . $id;
		if($extra) $link.="&" . $extra;
        
		if(!$texto) $texto='<i class="fa fa-edit"></i>';
        
        $url='<a href="' . $link . '">' . $texto . '</a>';
			
		return($url);
	}

    public function bool2html($bool,$cruz=true) {
        if($cruz) $str=$bool ? "&#x2713;" : "&#x2717;";
            else $str=$bool ? "&#x2713;" : "";

        return($str);
    }

	public function bool2check($bool) {
		$str=$bool ? "checked" : "";
		return($str);
	}

    /*public function monedaCLP($valor,$decimal=0) {
        return("$ " . number_format($valor,$decimal,",","."));
    }*/
    public function monedaCLP($valor,$decimales=0,$simbolo='$') {
        return($simbolo . ' ' . number_format($valor,$decimales,",","."));
    }

    public function monedaUSD($valor,$decimales=2,$simbolo='$') {
        return($simbolo . ' ' . number_format($valor,$decimales,",","."));
    }


    public function porcentaje($valor,$decimales=2) {
        return(number_format($valor,$decimales,",",".") . "%");
    }

    public function utf2html($string) {
        return(htmlentities(utf8_decode($string),ENT_QUOTES,'ISO-8859-1')); //por compatibilidad mantenemos ISO-8859-1
    }

    public function mensajeError($codigo,$salir=false) {
        $mensaje="";
        if($codigo==0) $mensaje="La operación fue realizada con éxito.";
        if($codigo==1) $mensaje="¡Error: Parámetros inválidos!";
        if($codigo==2) $mensaje="¡Error: Registro inválido!";
        if($codigo==3) $mensaje="¡Error: Problema en consulta a BBDD!";
        if($codigo==4) $mensaje="¡Debe completar todos los campos!";
        if($codigo==5) $mensaje="¡Error: La solicitud no ha sido enviada a los proveedores!";
        if($codigo==6) $mensaje="¡Formato de archivo no válido!";
        if($codigo==7) $mensaje="Al menos debe seleccionar un registro.";
        if($codigo==98) $mensaje="¡Error: El numero ya ha sido asignado!";
        if($codigo==99) $mensaje="¡Error: No tiene permisos suficientes!";

        if($salir) {
            echo $mensaje;
            exit;
        }

        return($mensaje);
    }

    /* Limpia parámetros para búsqueda de Dijit datagrid a SQL */
    public function busquedaHistorica($parametros) {
		$parametros=Util::limpiarParametros($parametros);
    	$query='';
		foreach($parametros AS $var=>$val) {
			if($val!='') $query.='&' . $var . '=' . $val;
		}
		$query="?1" . $query;

        return($query);
    }

    /* Limpia parámetros para búsqueda de Dijit datagrid a SQL */
    public function limpiarParametros($data) {
        $especiales=array("start","length","order","columns","search");

        $parametros=array();

        // Ordenamiento por columna y direccion
        $order=isset($data['order']) ? $data['order'] : false;
        $sort=isset($data['columns']) ? $data['columns'][$order[0]['column']]['data'] : false;
        if($order && $sort) {
            if($order[0]['dir']=='desc') $sort="-" . $sort;
            $parametros['sort']=$sort;
        }

        // Busqueda rápida
        $search=isset($data['search']) ? $data['search'] : false;
        if($search && $search['value']!='') $parametros['_busqueda']=$search['value'];

        foreach($data as $var=>$val) {
            if(!in_array($var,$especiales)) $parametros[$var]=trim($val);
        }
        
        // Búsqueda por columna
        if(isset($data['columns'])) {
	        $columnas=$data['columns'];
	        for($i=0; $i<count($columnas); $i++) {
	            $col=$columnas[$i]['data'];
	            $valor=trim($columnas[$i]['search']['value']);
	            if($valor!='') {
	                if(strpos($valor,",")) $valor=explode(',',$valor);
	                $parametros[$col]=$valor;
	            }
	        }
        }

        return($parametros);
    }


    /* Para un dato tipo DATETIME de SQL en formato "YYYY-MM-DD HH:MM:SS" devuelve solo la fecha */
    public function extraerFechaSQL($fecha) {
        @list($fecha,$hora)=explode(" ",$fecha);
        return($fecha);
    }

    public function fechaSQLChile($fecha,$mostrarHora=false) {
        @list($fecha,$hora)=explode(" ",$fecha);

        if($fecha=='0000-00-00') return('');

        @list($ano,$mes,$dia)=explode("-",$fecha);

		if($mostrarHora) {
			$hora=isset($hora) ? " " . $hora : "";
		} else $hora="";

        return($dia . "-" . $mes . "-" . $ano . $hora);
    }
    
    public function fechaChileSQL($fecha) {
        list($dia,$mes,$ano)=explode("/",$fecha);
        return($ano . "-" . $mes . "-" . $dia);
    }

    /* Para un dato tipo DATETIME de SQL en formato "YYYY-MM-DD HH:MM:SS" devuelve solo la fecha */
    public function fechaHoraSQL($fecha) {
        @list($fecha,$hora)=explode(" ",$fecha);
        @list($ano,$mes,$dia)=explode("-",$fecha);

        $fecha=$dia . "-" . $mes . "-" . $ano . " " . $hora;
        return($fecha);
    }

    public function fechaAtime($fecha) {
        list($ano,$mes,$dia)=explode("-",$fecha);
        return(gmmktime(0,0,0,$mes,$dia,$ano));
    }

    /* Valida un RUT */
    /* formato xxxxxxxxx-x o xxx.xxx.xxx-x o xxx,xxx,xxx-x */
    public function validarRut($sUsr) {
        $sUsr=str_replace(".","",$sUsr); //removemos puntos si los trae
        $sUsr=str_replace(",","",$sUsr); //removemos comas si los trae (se ha visto de todo)
        $sUsr=str_replace(" ","",$sUsr); //removemos espacios si los trae (se ha visto de todo)
        if(!preg_match("/(\d{7,8})-([\dK])/", strtoupper($sUsr), $aMatch)) {
            return(false); //invalido de inmeadito (formato)
        }
        $sRutBase = substr(strrev($aMatch[1]) , 0, 8 );
        $sCodigoVerificador = $aMatch[2];
        $iCont = 2;
        $iSuma = 0;
        for($i=0;$i<strlen($sRutBase);$i++) {
            if ($iCont>7) {
                $iCont = 2;
            }
            $iSuma+= ($sRutBase{$i}) *$iCont;
            $iCont++;
        }
        $iDigito=11-($iSuma%11);
        $sCaracter=substr("-123456789K0", $iDigito, 1);
        return($sCaracter==$sCodigoVerificador);
    }

    /* Valida un correo electrónico */
    function validarCorreo($email) {
        $patron="/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/";
        if(preg_match($patron,$email)) {
            return(true);
        }
        return(false);
    }

    public function int2bool($int) {
        if($int==0) return(false);
        return(true);
    }

    public function bool2int($bool) {
        if($bool==false) return(0);
        return(1);
    }

    /* genera una lista para bTemplate con una serie de incio a fin por dx incrementos */
    public function cmbSelEscala($inicio,$fin,$sel,$dx=1,$extra='') {
        $arreglo=array();
        for($i=$inicio; $i<=$fin; $i+=$dx) {
            $arreglo[]=array("id"=>$i,"nombre"=>$i . $extra,"selected"=>$i==$sel ? "selected" : "");
        }
        return($arreglo);
    }

    /* Genera una lista para bTemplate con un arreglo de forma id, nombre */
    public function cmbSelLoop($arreglo,$idSel,$excluir=array()) {
    	if(!is_array($idSel)) $idSel=array($idSel);
    	
    	$cta=0;
    	$narreglo=array();
        for($i=0; $i<count($arreglo); $i++) {
			if(in_array($arreglo[$i]['id'],$excluir)) continue;

			$narreglo[$cta]['id']=$arreglo[$i]['id'];
			$narreglo[$cta]['nombre']=$arreglo[$i]['nombre'];
			
            if(in_array($arreglo[$i]['id'],$idSel)) $narreglo[$cta]['selected']='selected';
                else $narreglo[$cta]['selected']='';
                
            $cta++;
        }
        return($narreglo);
    }

    /* Genera una lista para bTemplate con un arreglo de forma id, nombre */
    public function cmbSelLoop2($arreglo,$idSel) {   
        for($i=0; $i<count($arreglo); $i++) {
            if(in_array($arreglo[$i]['id'],$idSel)) $arreglo[$i]['selected']='selected';
                else $arreglo[$i]['selected']='';
        }
        return($arreglo);
    }

    /* Genera una lista para bTemplate con un arreglo de objetos */
    public function cmbObjSelLoop($arreglo,$idSel) {
        $listado=array();
        for($i=0; $i<count($arreglo); $i++) {
            $obj=$arreglo[$i];

            $listado[$i]['id']=$obj->getId();
            $listado[$i]['nombre']=$obj->getNombre();
            $sel="";
            if($obj->getId()==$idSel) {
                $sel="selected";
            }
            $listado[$i]['selected']=$sel;
        }
        return($listado);
    }

    /* Construye una URL con los parámetros de $parametro */
    public function urlBusqueda($parametros) {
        $url="";
        if($parametros && is_array($parametros)) {
            foreach($parametros AS $p=>$val) {
                $url.='&' . $p . '=' . $val;
            }
        }
        return($url);
    }

    /* Valida un parámetro en un arreglo tipo $_GET o $_POST */
    public function getURL($var,$URL) {
        if(isset($URL[$var])) return($URL[$var]);
        return(false);
    }

	public function stripTags($string,$tags) {
		foreach($tags as $tag) {
			$string=preg_replace("/<\/?" . $tag . "(.|\s)*?>/",'',$string);
		}  
		return($string);
	}

    /* Genera una contraseña al azar */
    public function generarContrasena($length=6) {
      $password="";

      $possible="0123456789ABCDEFGHIJ0123456789KLMNOPQRST0123456789UVWXYZabcd0123456789efghijklmn0123456789opqrstuvwxyz";
      $i=0; 

      while($i<$length) {
        $char=substr($possible, mt_rand(0,strlen($possible)-1),1);

        if(!strstr($password,$char)) { 
          $password.=$char;
          $i++;
        }
      }
      return($password);
    }
    
    function br2nl($string) {
		return(preg_replace('/\<br(\s*)?\/?\>/i', "\n", $string));
	}

    /* Función que solo deja caracteres ASCII */    
    public function normalizarCaracteres($string,$excluir=array(),$camel=true,$lower=true) {
        $string=trim($string);
        if($lower) $string=strtolower($string);

        $string=str_replace('á','a',$string);
        $string=str_replace('é','e',$string);
        $string=str_replace('í','i',$string);
        $string=str_replace('ó','o',$string);
        $string=str_replace('ú','u',$string);
        $string=str_replace('ñ','n',$string);
        
        $string=str_replace('Á','a',$string);
        $string=str_replace('É','e',$string);
        $string=str_replace('Í','i',$string);
        $string=str_replace('Ó','o',$string);
        $string=str_replace('Ú','u',$string);
        $string=str_replace('Ñ','n',$string);
        $string=str_replace('\(',' ',$string);
        $string=str_replace('\)',' ',$string);
        
        if(!in_array("'",$excluir)) $string=str_replace("'",'',$string); // Comilla simple
        if(!in_array('"',$excluir)) $string=str_replace('"','',$string); // Comilla doble

        $string=preg_replace('/\(|\)/','',$string); // Parentesis
        
        $string=preg_replace( '/[^[:print:]]/','',$string); // Caracteres no ASCII
        
        if($camel) $string=ucwords(strtolower($string)); // Realiza camel case
        
        return(trim($string));
    }
}
?>
