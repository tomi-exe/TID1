<?php



class ComunaBD {
    var $sql;


    public function ComunaBD($sql) {
        $this->sql=$sql;

    }


    public function cargar($id) {
        $query="SELECT id,nombre,eliminado FROM general_comunas WHERE id='" . $this->sql->str($id) . "'";

        $res=$this->sql->fastQuery($query);
        $obj=false;
        if($res && count($res)==1) {
            $obj=new Comuna();
            $obj->setId($res[0]['id']);

            $obj->setNombre($res[0]['nombre']);
            $obj->setEliminado((bool)$res[0]['eliminado']);

        }

        return($obj);
    }

    public function guardar($obj) {
        if($obj->getId()=="") $obj->setId($this->sql->getUUID());

        $query="INSERT into general_comunas (id,nombre,eliminado) values (" .
            "'" . $this->sql->str($obj->getId()) . "'," .
            
            "'" . $this->sql->str($obj->getNombre()) . "'," .
            "0" .
            ") ON DUPLICATE KEY UPDATE " .
            
            "nombre='" . $this->sql->str($obj->getNombre()) . "'," .
            "eliminado=eliminado"; // Autocontenido

        if($this->sql->iQuery($query)) {

            return($obj);
        }
        return(false);
    }

    public function eliminar($id) {
        $query="UPDATE general_comunas SET eliminado=1 WHERE id='" . $this->sql->str($id) . "'";

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

            if(isset($parametros['nombre'])) $query.=" AND t.nombre LIKE '%" . $parametros['nombre'] . "%' " ;

        }

        return($query);
    }

    public function getTotal($parametros) {
        $query="SELECT COUNT(t.id) AS total FROM general_comunas AS t WHERE t.eliminado=0 ";
        $query.=$this->getParametros($parametros);
    
        $total=0;
        $res=$this->sql->fastQuery($query);
        if($res && count($res)==1) $total=$res[0]['total'];
        return($total);
    }

    public function getListado($parametros=false,$limitar=false,$inicio=0,$dx=0) {
        $query="SELECT t.id FROM general_comunas AS t WHERE t.eliminado=0 ";
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
            $query.=" ORDER BY t.nombre ASC"; // TODO: pendiente lógica
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