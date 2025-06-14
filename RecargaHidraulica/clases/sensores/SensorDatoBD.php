<?php



class SensorDatoBD {
    var $sql;


    public function SensorDatoBD($sql) {
        $this->sql=$sql;

    }


    public function cargar($id) {
        $query="SELECT id,sensor,fecha,humedad1,humedad2,humedad3,humedadMaximo1,humedadMaximo2,humedadMaximo3,humedadPorcentaje1,humedadPorcentaje2,humedadPorcentaje3,eliminado FROM sensores_datos WHERE id='" . $this->sql->str($id) . "'";

        $res=$this->sql->fastQuery($query);
        $obj=false;
        if($res && count($res)==1) {
            $obj=new SensorDato();
            $obj->setId($res[0]['id']);

            $obj->setSensor($res[0]['sensor']);
            $obj->setFecha($res[0]['fecha']);
            $obj->setHumedad1($res[0]['humedad1']);
            $obj->setHumedad2($res[0]['humedad2']);
            $obj->setHumedad3($res[0]['humedad3']);
            $obj->setHumedadMaximo1($res[0]['humedadMaximo1']);
            $obj->setHumedadMaximo2($res[0]['humedadMaximo2']);
            $obj->setHumedadMaximo3($res[0]['humedadMaximo3']);
            $obj->setHumedadPorcentaje1($res[0]['humedadPorcentaje1']);
            $obj->setHumedadPorcentaje2($res[0]['humedadPorcentaje2']);
            $obj->setHumedadPorcentaje3($res[0]['humedadPorcentaje3']);
            $obj->setEliminado((bool)$res[0]['eliminado']);

        }

        return($obj);
    }

    public function guardar($obj) {
        if($obj->getId()=="") $obj->setId($this->sql->getUUID());

        $query="INSERT into sensores_datos (id,sensor,fecha,humedad1,humedad2,humedad3,humedadMaximo1,humedadMaximo2,humedadMaximo3,humedadPorcentaje1,humedadPorcentaje2,humedadPorcentaje3,eliminado) values (" .
            "'" . $this->sql->str($obj->getId()) . "'," .
            
            "'" . $this->sql->str($obj->getSensor()) . "'," .
            "'" . $this->sql->str($obj->getFecha()) . "'," .
            "'" . $this->sql->str($obj->getHumedad1()) . "'," .
            "'" . $this->sql->str($obj->getHumedad2()) . "'," .
            "'" . $this->sql->str($obj->getHumedad3()) . "'," .
            "'" . $this->sql->str($obj->getHumedadMaximo1()) . "'," .
            "'" . $this->sql->str($obj->getHumedadMaximo2()) . "'," .
            "'" . $this->sql->str($obj->getHumedadMaximo3()) . "'," .
            "'" . $this->sql->str($obj->getHumedadPorcentaje1()) . "'," .
            "'" . $this->sql->str($obj->getHumedadPorcentaje2()) . "'," .
            "'" . $this->sql->str($obj->getHumedadPorcentaje3()) . "'," .
            "0" .
            ") ON DUPLICATE KEY UPDATE " .
            
            "sensor='" . $this->sql->str($obj->getSensor()) . "'," .
            "fecha='" . $this->sql->str($obj->getFecha()) . "'," .
            "humedad1='" . $this->sql->str($obj->getHumedad1()) . "'," .
            "humedad2='" . $this->sql->str($obj->getHumedad2()) . "'," .
            "humedad3='" . $this->sql->str($obj->getHumedad3()) . "'," .
            "humedadMaximo1='" . $this->sql->str($obj->getHumedadMaximo1()) . "'," .
            "humedadMaximo2='" . $this->sql->str($obj->getHumedadMaximo2()) . "'," .
            "humedadMaximo3='" . $this->sql->str($obj->getHumedadMaximo3()) . "'," .
            "humedadPorcentaje1='" . $this->sql->str($obj->getHumedadPorcentaje1()) . "'," .
            "humedadPorcentaje2='" . $this->sql->str($obj->getHumedadPorcentaje2()) . "'," .
            "humedadPorcentaje3='" . $this->sql->str($obj->getHumedadPorcentaje3()) . "'," .
            "eliminado=eliminado"; // Autocontenido

        if($this->sql->iQuery($query)) {

            return($obj);
        }
        return(false);
    }

    public function eliminar($id) {
        $query="UPDATE sensores_datos SET eliminado=1 WHERE id='" . $this->sql->str($id) . "'";

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

            if(isset($parametros['sensor'])) $query.=" AND t.sensor LIKE '%" . $parametros['sensor'] . "%' " ;
            if(isset($parametros['fechaInicio'])) $query.=" AND t.fecha>='" . $parametros['fechaInicio'] . "' " ;
            if(isset($parametros['fechaTermino'])) $query.=" AND t.fecha<='" . $parametros['fechaTermino'] . "' " ;
            if(isset($parametros['humedad1'])) $query.=" AND t.humedad1" . $parametros['humedad1'] . " " ;
            if(isset($parametros['humedad2'])) $query.=" AND t.humedad2" . $parametros['humedad2'] . " " ;
            if(isset($parametros['humedad3'])) $query.=" AND t.humedad3" . $parametros['humedad3'] . " " ;
            if(isset($parametros['humedadMaximo1'])) $query.=" AND t.humedadMaximo1" . $parametros['humedadMaximo1'] . " " ;
            if(isset($parametros['humedadMaximo2'])) $query.=" AND t.humedadMaximo2" . $parametros['humedadMaximo2'] . " " ;
            if(isset($parametros['humedadMaximo3'])) $query.=" AND t.humedadMaximo3" . $parametros['humedadMaximo3'] . " " ;
            if(isset($parametros['humedadPorcentaje1'])) $query.=" AND t.humedadPorcentaje1" . $parametros['humedadPorcentaje1'] . " " ;
            if(isset($parametros['humedadPorcentaje2'])) $query.=" AND t.humedadPorcentaje2" . $parametros['humedadPorcentaje2'] . " " ;
            if(isset($parametros['humedadPorcentaje3'])) $query.=" AND t.humedadPorcentaje3" . $parametros['humedadPorcentaje3'] . " " ;
            /* Filtro por rango de fecha */
            if(isset($parametros['fecha'])) {
                list($desde,$hasta)=explode(":",$parametros['fecha']);
                if($desde=='') $desde=date('Y-m-d');
                if($hasta=='') $hasta=date('Y-m-d');
            				
                $query.=" AND t.fecha>='" . $desde . " 00:00:00' AND t.fecha<='" . $hasta . " 23:59:59' ";
            }

        }

        return($query);
    }

    public function getTotal($parametros) {
        $query="SELECT COUNT(t.id) AS total FROM sensores_datos AS t WHERE t.eliminado=0 ";
        $query.=$this->getParametros($parametros);
    
        $total=0;
        $res=$this->sql->fastQuery($query);
        if($res && count($res)==1) $total=$res[0]['total'];
        return($total);
    }

    public function getListado($parametros=false,$limitar=false,$inicio=0,$dx=0) {
        $query="SELECT t.id FROM sensores_datos AS t WHERE t.eliminado=0 ";
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
            $query.=" ORDER BY t.fecha ASC"; // TODO: pendiente lógica
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