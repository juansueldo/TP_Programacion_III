<?php

require_once './db/AccesoDatos.php';
    class Area {
        public $area_id;
        public $area_descripcion;

        public function __construct(){}

        public function insertarArea(){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $sql = "INSERT INTO area (area_descripcion) VALUES (:area_descripcion);";
            $query = $objDataAccess->prepararConsulta($sql);
            $query->bindValue(':area_descripcion', $this->area_descripcion);
            $query->execute();

            return $objDataAccess->obtenerUltimoId();
        }

        public static function actualizarArea($area){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $sql = "UPDATE area SET area_descripcion = ':area_descripcion' WHERE area_id = :area_id;";
            $query = $objDataAccess->prepararConsulta($sql);
            $query->bindValue(':area_id', $area->area_id);
            $query->bindValue(':area_descripcion', $area->area_descripcion);
            return $query->execute();
        }

        public static function eliminarArea($area){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $sql = "DELETE FROM area WHERE area_id = :area_id";
            $query = $objDataAccess->prepararConsulta($sql);
            $query->bindValue(':area_id', $area->area_id);
            return $query->execute();
        }

        public static function getAreaPorId($area_id){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta("SELECT * FROM area WHERE area_id = :area_id;");
            $query->bindParam(':area_id', $area_id);
            $query->execute();
            $area = $query->fetchObject('Area');
            if(is_null($area)){
                throw new Exception("El area no existe.");
            }
            
            return $area;
        }
      
        public static function getTodasAreas(){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $sql = "SELECT * FROM areas;";
            $query = $objDataAccess->prepararConsulta($sql);
            $query->execute();
            $areas = $query->fetchAll(PDO::FETCH_CLASS, 'Areas');
            return $areas;
        }

        public static function getAreaPorDescripcion($area_descripcion){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $sql = "SELECT * FROM area WHERE area_descripcion = ':area_descripcion';";
            $query = $objDataAccess->prepararConsulta($sql);
            $query->bindParam(':area_descripcion', $area_descripcion);
            $query->execute();
            $areas = $query->fetchAll(PDO::FETCH_CLASS, 'Area');
            return $areas;
        } 
 }
?>
?>