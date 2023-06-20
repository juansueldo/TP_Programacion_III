<?php

 class Mesa {
        public $id;
        public $numero_mesa;
        public $empleado_id;
        public $estado;

        public function __construct() {}

        public static function crearMesa($numero_mesa, $empleado_id, $estado){
            $mesa = new Mesa();
            $mesa->numero_mesa = $numero_mesa;
            $mesa->empleado_id = $empleado_id;
            $mesa->estado = $estado;

            return $mesa;
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
            $query->bindValue(':numero_mesa', $mesa->numero_mesa);
            $query->bindValue(':empleado_id', $mesa->empleado_id);
            $query->bindValue(':estado', $mesa->estado);
            $query->execute();

            return $objDataAccess->obtenerUltimoId();
        }
        public static function actualizarMesa($mesa){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta('UPDATE mesas SET empleado_id = :empleado_id, estado = :estado WHERE id = :id');
            $query->bindValue(':empleado_id', $mesa->empleado_id, PDO::PARAM_INT);
            $query->bindValue(':estado', $mesa->estado, PDO::PARAM_STR);
            $query->bindValue(':id', $mesa->id, PDO::PARAM_INT);
            $query->execute();

            return  $query->rowCount() > 0;
        }
        public static function borrarMesa($mesa){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta('DELETE FROM mesas WHERE id = :id');
            $query->bindValue(':id', $mesa->id);
            $query->execute();

            return $query->rowCount() > 0;
        }
        public static function getMesaPorPedidoId($order_id){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta(
                'SELECT * FROM mesas
                WHERE id = (SELECT id FROM pedidos WHERE id = :id)');
            $query->bindParam(':id', $order_id);
            $query->execute();
        
            return $query->fetchObject('Mesa');
        }
        
 }
?>