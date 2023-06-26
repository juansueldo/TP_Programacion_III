<?php
    class Encuesta{
        public $id;
        public $nro_pedido;
        public $mesa_puntaje;
        public $restaurante_puntaje;
        public  $mozo_puntaje;
        public $cocinero_puntaje;
        public $promedio;
        public $comentarios;

        public function __construct() {}

        public static function crearEncuesta($nro_pedido, $mesa_puntaje, $restaurante_puntaje, $mozo_puntaje, $cocinero_puntaje, $comentarios){
            $encuesta = new Encuesta();

            $encuesta->nro_pedido = $nro_pedido;
            $encuesta->mesa_puntaje = $mesa_puntaje;
            $encuesta->restaurante_puntaje = $restaurante_puntaje;
            $encuesta->mozo_puntaje = $mozo_puntaje;
            $encuesta->cocinero_puntaje = $cocinero_puntaje;
            $encuesta->setPromedio();
            $encuesta->comentarios = $comentarios;

            return $encuesta;
        }

        public function setPromedio() {
            $auxPromedio = 0;
            $arraySum = array($this->mesa_puntaje, $this->restaurante_puntaje, $this->mozo_puntaje, $this->cocinero_puntaje);
            if(count($arraySum) > 0) {
                $auxPromedio = round(array_sum($arraySum) / count($arraySum), 2, PHP_ROUND_HALF_EVEN);
            }
            $this->promedio = $auxPromedio; 
        }
        public static function insertarEncuesta($encuesta){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta('INSERT INTO `encuesta` (nro_pedido, mesa_puntaje, restaurante_puntaje, mozo_puntaje, cocinero_puntaje,promedio,comentarios) 
            VALUES (:nro_pedido, :mesa_puntaje, :restaurante_puntaje, :mozo_puntaje, :cocinero_puntaje,:promedio, :comentarios)');
            $query->bindValue(':nro_pedido', $encuesta->nro_pedido);
            $query->bindValue(':mesa_puntaje', $encuesta->mesa_puntaje);
            $query->bindValue(':restaurante_puntaje', $encuesta->restaurante_puntaje);
            $query->bindValue(':mozo_puntaje', $encuesta->mozo_puntaje);
            $query->bindValue(':cocinero_puntaje', $encuesta->cocinero_puntaje);
            $query->bindValue(':promedio', $encuesta->promedio);
            $query->bindValue(':comentarios', $encuesta->comentarios);
            $query->execute();
    
            return $objDataAccess->obtenerUltimoId();
        }

        public static function getTodas(){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta('SELECT * FROM `encuesta`');
            $query->execute();
    
            return $query->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
        }
    

        public static function getEncuestaPorId($id){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta('SELECT * FROM `encuesta` WHERE id = :id');
            $query->bindParam(':id', $id);
            $query->execute();
    
            return $query->fetchObject('Encuesta');
        }
    
   
        public static function getMejorPromedio($limite){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta(
                'SELECT * FROM `encuesta` 
                ORDER BY promedio DESC 
                LIMIT :limite');
            $query->bindParam(':limite', $limite);
            $query->execute();
    
            return $query->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
        }
    }

?>