<?php
class PerfilAcceso {
    var $id;
    
    var $nombre;
    var $permisos;

    var $eliminado;

    public function PerfilAcceso() {
        
    }

    public function getNombreLista() {
        return($this->getNombre());
    }

    /* Procesa los permisos (Lista a elementos) */
    public function getPermisosModulo($modulo) {
        $permisos=explode("\n",$this->getPermisos());
        $nPermisos=count($permisos);
             
        for($i=0; $i<$nPermisos; $i++) {
            list($mod,$niveles)=explode(":",$permisos[$i]);
                 
            if($mod==$modulo) return($niveles);
        }
        return(false);
    }
    /* Determina si se tiene cierto nivel de acceso a un módulo */
    public function tieneAcceso($modulo,$nivel) {
        $permisos=explode("\n",$this->getPermisos());
     
        $nPermisos=count($permisos);
        for($i=0; $i<$nPermisos; $i++) {
            list($mod,$niveles)=explode(":",$permisos[$i]);
     
            if($mod==$modulo) {
                $niveles=explode(",",$niveles);
                for($j=0; $j<count($niveles); $j++) {
                    if($niveles[$j]==$nivel) return(true);
                }
            }
        }
        return(false);
    }

    /* @param id */
    public function setId($id) {
        $this->id=$id;
    }

    /* @return id */
    public function getId() {
        return($this->id);
    }   

    /* @param nombre */
    public function setNombre($nombre) {
        $this->nombre=$nombre;
    }

    /* @return nombre */
    public function getNombre() {
        return($this->nombre);
    }
    /* @param permisos */
    public function setPermisos($permisos) {
        $this->permisos=$permisos;
    }

    /* @return permisos */
    public function getPermisos() {
        return($this->permisos);
    }

    /* @param eliminado */
    public function setEliminado($eliminado) {
        $this->eliminado=(bool)$eliminado;
    }

    /* @return eliminado */
    public function isEliminado() {
        return((bool)$this->eliminado);
    }
}
?>
