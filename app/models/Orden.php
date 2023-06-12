<?php

require_once './db/AccesoDatos.php';
require_once './models/Empleado.php';
require_once './models/Table.php';

class Orden{

    public $id;
    public $mesa_id;
    public $orden_estado;
    public $cliente_nombre;
    public $orden_costo;

    public function __construct($mesa_id, $orden_estado, $cliente_nombre, $orden_costo = 0){
        $this->mesa_id = $mesa_id;
        $this->orden_estado = $orden_estado;
        $this->cliente_nombre = $cliente_nombre;
        $this->orden_costo = $orden_costo;

    }

    public static function insertarOrden($orden){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('INSERT INTO ordenes (mesa_id, orden_estado, cliente_nombre, orden_costo) 
        VALUES (:mesa_id, :orden_estado, :cliente_nombre, :orden_costo)');
        $query->bindValue(':mesa_id', $orden->mesa_id);
        $query->bindValue(':orden_estado', $orden->orden_estado);
        $query->bindValue(':cliente_nombre', $orden->cliente_nombre);
        $query->bindValue(':orden_costo', $orden->orden_costo);
        $query->execute();

        return $objDataAccess->obtenerUltimoId();
    }


    public static function getTodos(){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('SELECT * FROM ordenes');
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, 'Orden');
    }


    public static function getOrdenPorId($id){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('SELECT * FROM ordenes WHERE id = :id');
        $query->bindParam(':id', $id);
        $query->execute();

        return $query->fetchObject('Orden');
    }


    public static function getOrdernesPorMesa($mesa){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('SELECT * FROM ordenes WHERE mesa_id = :mesa_id');
        $query->bindParam(':mesa_id', $mesa);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC, 'Orden');
    }

    public static function getOrdenPorEmpleado($empleado){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('SELECT o.id, o.mesa_id, o.orden_estado 
        FROM ordenes AS o
        LEFT JOIN mesas AS t ON o.mesa_id = t.id
        LEFT JOIN empleados AS e ON t.empleado_id = :id');
        $query->bindValue(':id', $empleado->id);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC, 'Orden');
    }

    public static function getOrdernPorTipoUsuario($tipo){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('SELECT o.id, o.mesa_id, o.orden_estado 
        FROM ordenes AS o
        LEFT JOIN mesas AS t ON o.mesa_id = t.id
        LEFT JOIN empleados AS e ON t.empleado_id = e.id
        LEFT JOIN usuarios AS u ON e.usuario_id = u.id
        WHERE u.tipo_usuario = :type;');
        $query->bindParam(':tipo', $tipo);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC, 'Order');
    }


    public static function actualizarOrden($orden){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('UPDATE ordenes 
        SET orden_estado = :orden_estado, orden_costo = :orden_costo 
        WHERE id = :id');
        $query->bindValue(':id', $orden->id);
        $query->bindValue(':orden_estado', $orden->orden_estado);
        $query->bindValue(':orden_costo', $orden->orden_costo);
        $query->execute();

        return $query;
    }


    public static function borrarOrdenPorId($id){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('DELETE FROM ordenes WHERE id = :id');
        $query->bindParam(':id', $id);
        $query->execute();
        
        return $query;
    }

  
    public static function getPorMesaId($mesa_id){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('SELECT * FROM ordenes WHERE mesa_id = :mesa_id');
        $query->bindParam(':mesa_id', $mesa_id);
        $query->execute();

        return $query->fetchObject('Orden');
    }

    
}
?>