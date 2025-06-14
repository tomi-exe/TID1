<?php


require_once('base/autenticacion/PerfilAcceso.php');

class Usuario {
    var $id;
    
    var $nombres;
    var $apellidoPaterno;
    var $apellidoMaterno;
    var $perfilAcceso;
    var $correo;
    var $contrasena;
    var $habilitado;

    var $eliminado;

    public function Usuario() {
        
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
        return($this->getNombres() . " " . $this->getApellidoPaterno());
    }


    /* @param id */
    public function setId($id) {
        $this->id=$id;
    }

    /* @return id */
    public function getId() {
        return($this->id);
    }   

    /* @param nombres */
    public function setNombres($nombres) {
        $this->nombres=$nombres;
    }

    /* @return nombres */
    public function getNombres() {
        return($this->nombres);
    }
    /* @param apellidoPaterno */
    public function setApellidoPaterno($apellidoPaterno) {
        $this->apellidoPaterno=$apellidoPaterno;
    }

    /* @return apellidoPaterno */
    public function getApellidoPaterno() {
        return($this->apellidoPaterno);
    }
    /* @param apellidoMaterno */
    public function setApellidoMaterno($apellidoMaterno) {
        $this->apellidoMaterno=$apellidoMaterno;
    }

    /* @return apellidoMaterno */
    public function getApellidoMaterno() {
        return($this->apellidoMaterno);
    }
    /* @param perfilAcceso */
    public function setPerfilAcceso($perfilAcceso) {
        $this->perfilAcceso=$perfilAcceso;
    }

    /* @return perfilAcceso */
    public function getPerfilAcceso() {
        return($this->perfilAcceso);
    }
    /* @param correo */
    public function setCorreo($correo) {
        $this->correo=$correo;
    }

    /* @return correo */
    public function getCorreo() {
        return($this->correo);
    }
    /* @param contrasena */
    public function setContrasena($contrasena) {
        $this->contrasena=$contrasena;
    }

    /* @return contrasena */
    public function getContrasena() {
        return($this->contrasena);
    }
    public function setHabilitado($habilitado) {
        $this->habilitado=(bool)$habilitado;
    }

    /* @return habilitado */
    public function isHabilitado() {
        return((bool)$this->habilitado);
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