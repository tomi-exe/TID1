<?php
/* Servicios e Inversiones GAEA E.I.R.L.
 * Revisión: 20100612 
 *           20080110
 * Revisor : Francisco Guidi <fguidi@gaea.cl>
 */

/* Clase para conectividad y operaciones con motor MySQL */
class MySQL {
    var $conn; //parametros conexion
    var $bbdd; //numero base de datos
    var $dbh; //recurso base de datos

    /* Recibe arreglo de parametros conectividad y nombre BBDD */
    function MySQL($conn,$bbdd) {
        $this->conn=$conn;
        $this->bbdd=$bbdd;

        return($this->conectar()); //intentamos la conexion
    }

    /* Establece conexion con el motor de BBDD */
    private function conectar() {
        $this->dbh=mysqli_connect($this->conn['host'],$this->conn['usuario'],$this->conn['contrasena']);

        if($this->dbh) {
            $idx='bbdd' . $this->bbdd;
            if(mysqli_select_db($this->dbh,$this->conn[$idx])) {
                //$this->dbh->set_charset('iso-8859-1');
                //para normalizar encoding y evitar defaults
                //$query="SET NAMES 'iso-8859-1'";
                $query="SET NAMES UTF8";
                $this->iQuery($query);
                return(true);
            }
        }
        return(false);
    }

    /* Devuelve el recurso de BBDD utilizado; sirve para operaciones que necesitan
     * como parametro el recurso (preparar algunos queries, escapar caracteres, etc)
     */
    public function getLink() {
        return($this->dbh);
    }

    public function pString($str) {
        return(mysqli_real_escape_string($this->dbh,$str));    
    }

    public function str($str) {
        if(is_array($str)) return($str);

        return($this->pString($str));
    }

    public function arrToIN($arr) {
        $str="";
        if(is_array($arr)) {
            for($i=0; $i<count($arr); $i++) {
                $str.="'" . $this->str(trim($arr[$i])) . "'";
                if($i<count($arr)-1) $str.=",";
            }
        } else $str="'" . $this->str(trim($arr)) . "'";
        return($str);
    }

    /* Desconecta el recurso BBDD */
    public function desconectar() {
        mysqli_close($this->dbh);
        $this->dbh=null;
        return(true);
    }

    /* Método para insertar, actualizar o eliminar registros;
     * si el query es exitoso y se trata de una operacion insert $id contiene el nuevo ID;
     * si el query falla $id contiene el codigo de error (se obtiene por referencia) */
    public function iQuery($dbquery, &$id=null) {
        if(!$this->dbh) {
            $this->conectar();
        }
        $sqlres=mysqli_query($this->dbh,$dbquery);
        if($sqlres!=false) {
            $id=@mysqli_insert_id($this->dbh); //devolvemos el id de insercion (si existe)
            return(true);
        } else $id=mysqli_errno($this->dbh); //devolvemos el error
        return(false);
    }

    /* Método que genera un UUID según ISO/IEC 11578:1996 desde el motor MySQL */
    public function getUUID() {
        $query="SELECT UUID() AS uid";
        $res=$this->fastQuery($query);
        return($res[0]['uid']);
    }

    /* Método para consulta base de datos. Devuelve un arreglo con los datos
     * de estructura $datos[$fila][$campo]
     */
    public function fastQuery($dbquery) {
        $error=0;
        if(!$this->dbh) {
            $this->conectar();
        }
        $sqlres=mysqli_query($this->dbh,$dbquery);

        if($sqlres!=false) {
            if(mysqli_num_rows($sqlres)<=0) {
                $error=1; //conjunto vacio
            }
        } else $error=2; //error query

        if($error==0) {
            $data=array();
            while($row=mysqli_fetch_assoc($sqlres)) {
                $data[]=$row;
            }
            mysqli_free_result($sqlres);
            return($data);
        }
        return(false);
    }
}
?>
