<?php

 class Mesa {
        public $id;
        public $numero_mesa;
        public $empleado_id;
        public $estado;

        public function __construct($numero_mesa, $empleado_id, $estado) {
            $this->numero_mesa = $numero_mesa;
            $this->empleado_id = $empleado_id;
            $this->estado = $estado;
        }

        public static function getMesasPorEmpleadoId($empleado_id){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta('SELECT * FROM mesas WHERE empleado_id = :empleado_id');
            $query->bindParam(':empleado_id', $empleado_id);
            $query->execute();

            return $query->fetchAll(PDO::FETCH_CLASS, 'Mesa');
        }

        public static function getTodasMesas(){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta('SELECT * FROM mesas');
            $query->execute();

            return $query->fetchAll(PDO::FETCH_CLASS, 'Mesa');
        }

        public static function getMesaPorId($id){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta('SELECT * FROM mesas WHERE id = :id');
            $query->bindParam(':id', $id);
            $query->execute();

            return $query->fetchObject('Mesa');
        }

        public static function insertarMesa($mesa){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta('INSERT INTO mesas (numero_mesa, empleado_id, estado) 
            VALUES (:numero_mesa, :empleado_id, :estado)');
            $query->bindValue(':numero_mesa', $mesa->getMesaNumero());
            $query->bindValue(':empleado_id', $mesa->getEmpleadoId());
            $query->bindValue(':estado', $mesa->getEstado());
            $query->execute();

            return $objDataAccess->obtenerUltimoId();
        }

        
 }
?>