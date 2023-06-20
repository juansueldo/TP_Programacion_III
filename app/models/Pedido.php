<?php

require_once './db/AccesoDatos.php';
require_once './models/Empleado.php';
require_once './models/Mesa.php';

class Pedido{

    public $id;
    public $mesa_id;
    public $pedido_estado;
    public $cliente_nombre;
    public $pedido_foto;
    public $pedido_costo;

    public function __construct(){}

    public static function crearPedido($mesa_id, $orden_estado, $cliente_nombre,$pedido_foto, $pedido_costo = 0){
        $pedido = new Pedido();
        $pedido->mesa_id = $mesa_id;
        $pedido->pedido_estado = $orden_estado;
        $pedido->cliente_nombre = $cliente_nombre;
        $pedido->pedido_foto = $pedido_foto;
        $pedido->pedido_costo = $pedido_costo;

        return $pedido;
        
    }
    public static function insertarPedido($pedido){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('INSERT INTO pedidos (mesa_id, pedido_estado, cliente_nombre, pedido_foto, pedido_costo) 
        VALUES (:mesa_id, :pedido_estado, :cliente_nombre, :pedido_costo)');
        $query->bindValue(':mesa_id', $pedido->mesa_id);
        $query->bindValue(':pedido_estado', $pedido->pedido_estado);
        $query->bindValue(':cliente_nombre', $pedido->cliente_nombre);
        $query->bindValue(':pedido_foto', $pedido->pedido_foto);
        $query->bindValue(':pedido_costo', $pedido->pedido_costo);
        $query->execute();

        return $objDataAccess->obtenerUltimoId();
    }


    public static function getTodos(){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('SELECT * FROM pedidos');
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }


    public static function getPedidoPorId($id){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('SELECT * FROM pedidos WHERE id = :id');
        $query->bindParam(':id', $id);
        $query->execute();

        return $query->fetchObject('Pedido');
    }


    public static function getPedidoPorMesa($mesa){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('SELECT * FROM pedidos WHERE mesa_id = :mesa_id');
        $query->bindParam(':mesa_id', $mesa);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC, 'Pedido');
    }

    public static function getPedidoPorEmpleado($empleado){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('SELECT p.id, p.mesa_id, p.pedido_estado 
        FROM pedidos AS p
        LEFT JOIN mesas AS m ON m.mesa_id = m.id
        LEFT JOIN empleados AS e ON m.empleado_id = :id');
        $query->bindValue(':id', $empleado->id);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC, 'Pedido');
    }

    public static function getPedidoPorTipoUsuario($tipo){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('SELECT p.id, p.mesa_id, p.pedido_estado 
        FROM pedidos AS p
        LEFT JOIN mesas AS m ON p.mesa_id = m.id
        LEFT JOIN empleados AS e ON m.empleado_id = e.id
        LEFT JOIN usuarios AS u ON e.usuario_id = u.id
        WHERE u.usuario_tipo = :usuario_tipo;');
        $query->bindParam(':usuario_tipo', $tipo);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC, 'Pedido');
    }


    public static function actualizarPedido($pedido){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('UPDATE pedidos 
        SET pedido_estado = :pedido_estado, pedido_costo = :pedido_costo 
        WHERE id = :id');
        $query->bindValue(':id', $pedido->id);
        $query->bindValue(':pedido_estado', $pedido->pedido_estado);
        $query->bindValue(':pedido_costo', $pedido->pedido_costo);
        $query->execute();

        return $query;
    }


    public static function borrarPedidoPorId($id){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('DELETE FROM pedidos WHERE id = :id');
        $query->bindParam(':id', $id);
        $query->execute();
        
        return $query;
    }
    public static function getPedidosConTiempo(){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta(
            'SELECT 
            p.id,
            p.mesa_id,
            p.pedido_estado,
            p.cliente_nombre,
            p.pedido_foto,
            p.pedido_costo,
            MAX(pr.tiempo_para_finalizar) AS tiempo_espera
            FROM producto AS pr
            LEFT JOIN pedidos as p
            ON pr.pedido_asociado = p.id
            GROUP BY p.id
            order by tiempo_espera DESC;');
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, "stdClass");
    }

    public static function actualizarFoto($pedido){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta('UPDATE pedidos SET pedido_foto = :pedido_foto WHERE id = :id');
        $query->bindValue(':id', $pedido->getId());
        $query->bindValue(':pedido_foto', $pedido->getOrderPicture());
        $query->execute();

        return $query->rowCount() > 0;
    }
}
?>