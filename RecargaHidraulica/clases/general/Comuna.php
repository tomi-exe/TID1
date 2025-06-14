<?php



class Comuna {
    var $id;
    
    var $nombre;

    var $eliminado;

    public function Comuna() {
        
    }

    /* Obtiene los ID de las variables objeto y sobreescribe el objeto */
    public function objToId() {
        foreach($this AS $var=>$val) {
            if(is_object($val)) $this->$var=$val->getId();
        }
    }

    public function __toString() {
        return($this->getNombreLista());
    }

    public function getNombreLista() {
        return($this->getNombre());
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