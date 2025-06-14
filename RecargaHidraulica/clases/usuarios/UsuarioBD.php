<?php


require_once('base/autenticacion/PerfilAccesoBD.php');

class UsuarioBD {
    var $sql;

    var $perfilAccesoBD;

    public function UsuarioBD($sql) {
        $this->sql=$sql;

        $this->perfilAccesoBD=new PerfilAccesoBD($sql);
    }

    /* Autenticación */
    /* Autenticación */
    public function autenticar($correo,$contrasena) {
        $correo=$this->sql->str($correo);
        $contrasena=$this->sql->str($contrasena);
        
        $query="SELECT id FROM usuarios_usuarios WHERE correo='$correo' AND contrasena='$contrasena' AND eliminado=0 AND habilitado=1";
        $res=$this->sql->fastQuery($query);
        if($res && count($res)==1) return($this->cargar($res[0]['id']));
        
        return(false);
    }

    public function cargar($id) {
        $query="SELECT id,nombres,apellidoPaterno,apellidoMaterno,perfilAcceso,correo,contrasena,habilitado,eliminado FROM usuarios_usuarios WHERE id='" . $this->sql->str($id) . "'";

        $res=$this->sql->fastQuery($query);
        $obj=false;
        if($res && count($res)==1) {
            $obj=new Usuario();
            $obj->setId($res[0]['id']);

            $obj->setNombres($res[0]['nombres']);
            $obj->setApellidoPaterno($res[0]['apellidoPaterno']);
            $obj->setApellidoMaterno($res[0]['apellidoMaterno']);
            $obj->setPerfilAcceso($this->perfilAccesoBD->cargar($res[0]['perfilAcceso']));
            $obj->setCorreo($res[0]['correo']);
            $obj->setContrasena($res[0]['contrasena']);
            $obj->setHabilitado((bool)$res[0]['habilitado']);
            $obj->setEliminado((bool)$res[0]['eliminado']);

        }

        return($obj);
    }

    public function guardar($obj) {
        if($obj->getId()=="") $obj->setId($this->sql->getUUID());

        $query="INSERT into usuarios_usuarios (id,nombres,apellidoPaterno,apellidoMaterno,perfilAcceso,correo,contrasena,habilitado,eliminado) values (" .
            "'" . $this->sql->str($obj->getId()) . "'," .
            
            "'" . $this->sql->str($obj->getNombres()) . "'," .
            "'" . $this->sql->str($obj->getApellidoPaterno()) . "'," .
            "'" . $this->sql->str($obj->getApellidoMaterno()) . "'," .
            "'" . $this->sql->str($obj->getPerfilAcceso()) . "'," .
            "'" . $this->sql->str($obj->getCorreo()) . "'," .
            "'" . $this->sql->str($obj->getContrasena()) . "'," .
            "'" . $this->sql->str((int)$obj->isHabilitado()) . "'," .
            "0" .
            ") ON DUPLICATE KEY UPDATE " .
            
            "nombres='" . $this->sql->str($obj->getNombres()) . "'," .
            "apellidoPaterno='" . $this->sql->str($obj->getApellidoPaterno()) . "'," .
            "apellidoMaterno='" . $this->sql->str($obj->getApellidoMaterno()) . "'," .
            "perfilAcceso='" . $this->sql->str($obj->getPerfilAcceso()) . "'," .
            "correo='" . $this->sql->str($obj->getCorreo()) . "'," .
            "contrasena='" . $this->sql->str($obj->getContrasena()) . "'," .
            "habilitado='" . $this->sql->str((int)$obj->isHabilitado()) . "'," .
            "eliminado=eliminado"; // Autocontenido

        if($this->sql->iQuery($query)) {

            return($obj);
        }
        return(false);
    }

    public function eliminar($id) {
        $query="UPDATE usuarios_usuarios SET eliminado=1 WHERE id='" . $this->sql->str($id) . "'";

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

            if(isset($parametros['nombres'])) $query.=" AND t.nombres LIKE '%" . $parametros['nombres'] . "%' " ;
            if(isset($parametros['apellidoPaterno'])) $query.=" AND t.apellidoPaterno LIKE '%" . $parametros['apellidoPaterno'] . "%' " ;
            if(isset($parametros['perfilAcceso'])) $query.=" AND (t.perfilAcceso='" . $parametros['perfilAcceso'] . "') ";
            if(isset($parametros['correo'])) $query.=" AND t.correo LIKE '%" . $parametros['correo'] . "%' " ;
            if(isset($parametros['habilitado'])) $query.=" AND t.habilitado='" . $parametros['habilitado'] . "' " ;

        }

        return($query);
    }

    public function getTotal($parametros) {
        $query="SELECT COUNT(t.id) AS total FROM usuarios_usuarios AS t WHERE t.eliminado=0 ";
        $query.=$this->getParametros($parametros);
    
        $total=0;
        $res=$this->sql->fastQuery($query);
        if($res && count($res)==1) $total=$res[0]['total'];
        return($total);
    }

    public function getListado($parametros=false,$limitar=false,$inicio=0,$dx=0) {
        $query="SELECT t.id FROM usuarios_usuarios AS t WHERE t.eliminado=0 ";
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
            $query.=" ORDER BY t.apellidoPaterno ASC"; // TODO: pendiente lógica
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