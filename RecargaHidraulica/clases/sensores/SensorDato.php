<?php



class SensorDato {
    var $id;
    
    var $sensor;
    var $fecha;
    var $humedad1;
    var $humedad2;
    var $humedad3;
    var $humedadMaximo1;
    var $humedadMaximo2;
    var $humedadMaximo3;
    var $humedadPorcentaje1;
    var $humedadPorcentaje2;
    var $humedadPorcentaje3;

    var $eliminado;

    public function SensorDato() {
        
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
        return($this->getFecha());
    }


    /* @param id */
    public function setId($id) {
        $this->id=$id;
    }

    /* @return id */
    public function getId() {
        return($this->id);
    }   

    /* @param sensor */
    public function setSensor($sensor) {
        $this->sensor=$sensor;
    }

    /* @return sensor */
    public function getSensor() {
        return($this->sensor);
    }
    /* @param fecha */
    public function setFecha($fecha) {
        $this->fecha=$fecha;
    }

    /* @return fecha */
    public function getFecha() {
        return($this->fecha);
    }
    /* @param humedad1 */
    public function setHumedad1($humedad1) {
        $this->humedad1=$humedad1;
    }

    /* @return humedad1 */
    public function getHumedad1() {
        return($this->humedad1);
    }
    /* @param humedad2 */
    public function setHumedad2($humedad2) {
        $this->humedad2=$humedad2;
    }

    /* @return humedad2 */
    public function getHumedad2() {
        return($this->humedad2);
    }
    /* @param humedad3 */
    public function setHumedad3($humedad3) {
        $this->humedad3=$humedad3;
    }

    /* @return humedad3 */
    public function getHumedad3() {
        return($this->humedad3);
    }
    /* @param humedadMaximo1 */
    public function setHumedadMaximo1($humedadMaximo1) {
        $this->humedadMaximo1=$humedadMaximo1;
    }

    /* @return humedadMaximo1 */
    public function getHumedadMaximo1() {
        return($this->humedadMaximo1);
    }
    /* @param humedadMaximo2 */
    public function setHumedadMaximo2($humedadMaximo2) {
        $this->humedadMaximo2=$humedadMaximo2;
    }

    /* @return humedadMaximo2 */
    public function getHumedadMaximo2() {
        return($this->humedadMaximo2);
    }
    /* @param humedadMaximo3 */
    public function setHumedadMaximo3($humedadMaximo3) {
        $this->humedadMaximo3=$humedadMaximo3;
    }

    /* @return humedadMaximo3 */
    public function getHumedadMaximo3() {
        return($this->humedadMaximo3);
    }
    /* @param humedadPorcentaje1 */
    public function setHumedadPorcentaje1($humedadPorcentaje1) {
        $this->humedadPorcentaje1=$humedadPorcentaje1;
    }

    /* @return humedadPorcentaje1 */
    public function getHumedadPorcentaje1() {
        return($this->humedadPorcentaje1);
    }
    /* @param humedadPorcentaje2 */
    public function setHumedadPorcentaje2($humedadPorcentaje2) {
        $this->humedadPorcentaje2=$humedadPorcentaje2;
    }

    /* @return humedadPorcentaje2 */
    public function getHumedadPorcentaje2() {
        return($this->humedadPorcentaje2);
    }
    /* @param humedadPorcentaje3 */
    public function setHumedadPorcentaje3($humedadPorcentaje3) {
        $this->humedadPorcentaje3=$humedadPorcentaje3;
    }

    /* @return humedadPorcentaje3 */
    public function getHumedadPorcentaje3() {
        return($this->humedadPorcentaje3);
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