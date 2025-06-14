<?php


require_once('general/Comuna.php');

class Sensor {
    var $id;
    
    var $numero;
    var $nombre;
    var $tipo;
    var $tipoArr;
    var $ultimoEvento;
    var $latitud;
    var $longitud;
    var $direccion;
    var $comuna;
    var $referencia;
    var $periodo;
    var $contrasena;
    var $habilitado;
    var $maximo1;
    var $profundidad1;
    var $maximo2;
    var $profundidad2;
    var $maximo3;
    var $profundidad3;

    var $eliminado;

    public function Sensor() {
        
        $this->tipoArr=array();
        $this->tipoArr[]=array("id"=>"0","nombre"=>"GSM");
        $this->tipoArr[]=array("id"=>"1","nombre"=>"Logger");
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

    /* @param numero */
    public function setNumero($numero) {
        $this->numero=$numero;
    }

    /* @return numero */
    public function getNumero() {
        return($this->numero);
    }
    /* @param nombre */
    public function setNombre($nombre) {
        $this->nombre=$nombre;
    }

    /* @return nombre */
    public function getNombre() {
        return($this->nombre);
    }
    /* @param tipo */
    public function setTipo($tipo) {
        $this->tipo=$tipo;
    }

    /* @return tipo */
    public function getTipo() {
        return($this->tipo);
    }

    /* @return tipoArr */
    public function getTipoArr() {
        return($this->tipoArr);
    }

    /* @return tipoStr */
    public function getTipoStr() {
        for($i=0; $i<count($this->tipoArr); $i++) {
            if($this->tipoArr[$i]['id']==$this->tipo) return($this->tipoArr[$i]['nombre']);
        }
        return("");
    }
    /* @param ultimoEvento */
    public function setUltimoEvento($ultimoEvento) {
        $this->ultimoEvento=$ultimoEvento;
    }

    /* @return ultimoEvento */
    public function getUltimoEvento() {
        return($this->ultimoEvento);
    }
    /* @param latitud */
    public function setLatitud($latitud) {
        $this->latitud=$latitud;
    }

    /* @return latitud */
    public function getLatitud() {
        return($this->latitud);
    }
    /* @param longitud */
    public function setLongitud($longitud) {
        $this->longitud=$longitud;
    }

    /* @return longitud */
    public function getLongitud() {
        return($this->longitud);
    }
    /* @param direccion */
    public function setDireccion($direccion) {
        $this->direccion=$direccion;
    }

    /* @return direccion */
    public function getDireccion() {
        return($this->direccion);
    }
    /* @param comuna */
    public function setComuna($comuna) {
        $this->comuna=$comuna;
    }

    /* @return comuna */
    public function getComuna() {
        return($this->comuna);
    }
    /* @param referencia */
    public function setReferencia($referencia) {
        $this->referencia=$referencia;
    }

    /* @return referencia */
    public function getReferencia() {
        return($this->referencia);
    }
    /* @param periodo */
    public function setPeriodo($periodo) {
        $this->periodo=$periodo;
    }

    /* @return periodo */
    public function getPeriodo() {
        return($this->periodo);
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
    /* @param maximo1 */
    public function setMaximo1($maximo1) {
        $this->maximo1=$maximo1;
    }

    /* @return maximo1 */
    public function getMaximo1() {
        return($this->maximo1);
    }
    /* @param profundidad1 */
    public function setProfundidad1($profundidad1) {
        $this->profundidad1=$profundidad1;
    }

    /* @return profundidad1 */
    public function getProfundidad1() {
        return($this->profundidad1);
    }
    /* @param maximo2 */
    public function setMaximo2($maximo2) {
        $this->maximo2=$maximo2;
    }

    /* @return maximo2 */
    public function getMaximo2() {
        return($this->maximo2);
    }
    /* @param profundidad2 */
    public function setProfundidad2($profundidad2) {
        $this->profundidad2=$profundidad2;
    }

    /* @return profundidad2 */
    public function getProfundidad2() {
        return($this->profundidad2);
    }
    /* @param maximo3 */
    public function setMaximo3($maximo3) {
        $this->maximo3=$maximo3;
    }

    /* @return maximo3 */
    public function getMaximo3() {
        return($this->maximo3);
    }
    /* @param profundidad3 */
    public function setProfundidad3($profundidad3) {
        $this->profundidad3=$profundidad3;
    }

    /* @return profundidad3 */
    public function getProfundidad3() {
        return($this->profundidad3);
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