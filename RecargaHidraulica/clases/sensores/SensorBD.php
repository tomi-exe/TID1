<?php


require_once('general/ComunaBD.php');

class SensorBD {
    var $sql;

    var $comunaBD;

    public function SensorBD($sql) {
        $this->sql=$sql;

        $this->comunaBD=new ComunaBD($sql);
    }

    /* Actualizar fecha / hora de último evento */
    public function ultimoEvento($sensor,$fecha) {
        $sensor=$this->sql->str($sensor);
        $fecha=$this->sql->str($fecha);
        $query="UPDATE sensores_sensores SET ultimoEvento='$fecha' WHERE id='$sensor'";
    		
        return($this->sql->iQuery($query));
    }
    /* Autenticación */
    public function autenticar($numero,$contrasena) {
        $numero=$this->sql->str($numero);
        $contrasena=$this->sql->str($contrasena);
        $query="SELECT id FROM sensores_sensores WHERE numero='$numero' AND contrasena='$contrasena' AND eliminado=0 AND habilitado=1";
    
        $res=$this->sql->fastQuery($query);
        if($res && count($res)==1) return($this->cargar($res[0]['id']));
    
        return(false);
    }
    /* Carga el siguiente numero secuencial disponible para numero */
    public function cargarSecuencialNumero() {
        $query="SELECT MAX(numero)+1 AS siguiente FROM sensores_sensores";
        $res=$this->sql->fastQuery($query);

        $numero='';
        if($res && count($res)==1) $numero=$res[0]['siguiente']==null ? '' : $res[0]['siguiente'];
        return($numero);
    }

    public function cargar($id) {
        $query="SELECT id,numero,nombre,tipo,ultimoEvento,latitud,longitud,direccion,comuna,referencia,periodo,contrasena,habilitado,maximo1,profundidad1,maximo2,profundidad2,maximo3,profundidad3,eliminado FROM sensores_sensores WHERE id='" . $this->sql->str($id) . "'";

        $res=$this->sql->fastQuery($query);
        $obj=false;
        if($res && count($res)==1) {
            $obj=new Sensor();
            $obj->setId($res[0]['id']);

            $obj->setNumero($res[0]['numero']);
            $obj->setNombre($res[0]['nombre']);
            $obj->setTipo($res[0]['tipo']);
            $obj->setUltimoEvento($res[0]['ultimoEvento']);
            $obj->setLatitud($res[0]['latitud']);
            $obj->setLongitud($res[0]['longitud']);
            $obj->setDireccion($res[0]['direccion']);
            $obj->setComuna($this->comunaBD->cargar($res[0]['comuna']));
            $obj->setReferencia($res[0]['referencia']);
            $obj->setPeriodo($res[0]['periodo']);
            $obj->setContrasena($res[0]['contrasena']);
            $obj->setHabilitado((bool)$res[0]['habilitado']);
            $obj->setMaximo1($res[0]['maximo1']);
            $obj->setProfundidad1($res[0]['profundidad1']);
            $obj->setMaximo2($res[0]['maximo2']);
            $obj->setProfundidad2($res[0]['profundidad2']);
            $obj->setMaximo3($res[0]['maximo3']);
            $obj->setProfundidad3($res[0]['profundidad3']);
            $obj->setEliminado((bool)$res[0]['eliminado']);

        }

        return($obj);
    }

    public function guardar($obj) {
        if($obj->getId()=="") $obj->setId($this->sql->getUUID());

        $query="INSERT into sensores_sensores (id,numero,nombre,tipo,ultimoEvento,latitud,longitud,direccion,comuna,referencia,periodo,contrasena,habilitado,maximo1,profundidad1,maximo2,profundidad2,maximo3,profundidad3,eliminado) values (" .
            "'" . $this->sql->str($obj->getId()) . "'," .
            
            "'" . $this->sql->str($this->cargarSecuencialNumero()) . "'," .
            "'" . $this->sql->str($obj->getNombre()) . "'," .
            "'" . $this->sql->str($obj->getTipo()) . "'," .
            "'" . $this->sql->str($obj->getUltimoEvento()) . "'," .
            "'" . $this->sql->str($obj->getLatitud()) . "'," .
            "'" . $this->sql->str($obj->getLongitud()) . "'," .
            "'" . $this->sql->str($obj->getDireccion()) . "'," .
            "'" . $this->sql->str($obj->getComuna()) . "'," .
            "'" . $this->sql->str($obj->getReferencia()) . "'," .
            "'" . $this->sql->str($obj->getPeriodo()) . "'," .
            "'" . $this->sql->str($obj->getContrasena()) . "'," .
            "'" . $this->sql->str((int)$obj->isHabilitado()) . "'," .
            "'" . $this->sql->str($obj->getMaximo1()) . "'," .
            "'" . $this->sql->str($obj->getProfundidad1()) . "'," .
            "'" . $this->sql->str($obj->getMaximo2()) . "'," .
            "'" . $this->sql->str($obj->getProfundidad2()) . "'," .
            "'" . $this->sql->str($obj->getMaximo3()) . "'," .
            "'" . $this->sql->str($obj->getProfundidad3()) . "'," .
            "0" .
            ") ON DUPLICATE KEY UPDATE " .
            
            "numero=numero," .
            "nombre='" . $this->sql->str($obj->getNombre()) . "'," .
            "tipo='" . $this->sql->str($obj->getTipo()) . "'," .
            //"ultimoEvento='" . $this->sql->str($obj->getUltimoEvento()) . "'," .
            "latitud='" . $this->sql->str($obj->getLatitud()) . "'," .
            "longitud='" . $this->sql->str($obj->getLongitud()) . "'," .
            "direccion='" . $this->sql->str($obj->getDireccion()) . "'," .
            "comuna='" . $this->sql->str($obj->getComuna()) . "'," .
            "referencia='" . $this->sql->str($obj->getReferencia()) . "'," .
            "periodo='" . $this->sql->str($obj->getPeriodo()) . "'," .
            "contrasena='" . $this->sql->str($obj->getContrasena()) . "'," .
            "habilitado='" . $this->sql->str((int)$obj->isHabilitado()) . "'," .
            "maximo1='" . $this->sql->str($obj->getMaximo1()) . "'," .
            "profundidad1='" . $this->sql->str($obj->getProfundidad1()) . "'," .
            "maximo2='" . $this->sql->str($obj->getMaximo2()) . "'," .
            "profundidad2='" . $this->sql->str($obj->getProfundidad2()) . "'," .
            "maximo3='" . $this->sql->str($obj->getMaximo3()) . "'," .
            "profundidad3='" . $this->sql->str($obj->getProfundidad3()) . "'," .
            "eliminado=eliminado"; // Autocontenido

        if($this->sql->iQuery($query)) {

            return($obj);
        }
        return(false);
    }

    public function eliminar($id) {
        $query="UPDATE sensores_sensores SET eliminado=1 WHERE id='" . $this->sql->str($id) . "'";

        $st=false;
        if($this->sql->iQuery($query)) {
            $st=true;

        }
        return($st);
    }

    private function getParametros($parametros) {
        $query='';

        if($parametros) {
            foreach($parametros AS $param=>$val) $parametros[$param]=$this->sql->str($val); // SQLi

            if(isset($parametros['id'])) $query.=" AND t.id='" . $parametros['id'] . "' ";

            if(isset($parametros['numero'])) $query.=" AND t.numero" . $parametros['numero'] . " " ;
            if(isset($parametros['nombre'])) $query.=" AND t.nombre LIKE '%" . $parametros['nombre'] . "%' " ;
            if(isset($parametros['tipo'])) $query.=" AND t.tipo IN (" . $this->sql->arrToIN($parametros['tipo']) . ") ";
            if(isset($parametros['habilitado'])) $query.=" AND t.habilitado='" . $parametros['habilitado'] . "' " ;
            /* Búsqueda rápida múltiple */
            if(isset($parametros['_busqueda'])) {
                $val=$parametros['_busqueda'];
                $query.=" AND (";
                $query.=" nombre LIKE '%$val%' ";
                $query.=" OR numero LIKE '$val%' ";
                $query.=" OR comuna IN (SELECT id FROM general_comunas WHERE nombre LIKE '%$val%' AND eliminado=0) ";
                $query.=" )";
            }

        }

        return($query);
    }

    public function getTotal($parametros) {
        $query="SELECT COUNT(t.id) AS total FROM sensores_sensores AS t WHERE t.eliminado=0 ";
        $query.=$this->getParametros($parametros);
    
        $total=0;
        $res=$this->sql->fastQuery($query);
        if($res && count($res)==1) $total=$res[0]['total'];
        return($total);
    }

    public function getListado($parametros=false,$limitar=false,$inicio=0,$dx=0) {
        $query="SELECT t.id FROM sensores_sensores AS t WHERE t.eliminado=0 ";
        $query.=$this->getParametros($parametros);

        // Procesamos ordenamiento
        if(isset($parametros['sort'])) {
            $sort=$this->sql->str($parametros['sort']);
            $order="ASC";
            if(substr($sort,0,1)=="-") {
                $sort=substr($sort,1,strlen($sort));
                $order="DESC";
            }
            $query.=" ORDER BY t." . $sort . " " . $order;
        } else {
            $query.=" ORDER BY t.numero ASC"; // TODO: pendiente lógica
        }

        if($limitar==true) {
            $inicio=(int)$inicio;
            $dx=(int)$dx;
            $query.=" LIMIT $inicio,$dx";
        }

        // Procesamos listado
        $res=$this->sql->fastQuery($query);
        $nRes=$res ? count($res) : 0;
        $listado=array();
        for($i=0; $i<$nRes; $i++) {
            $obj=$this->cargar($res[$i]['id']);
            if($obj) $listado[]=$obj;
        }
        return($listado);
    }

    
}
?>