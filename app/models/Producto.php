<?php
require_once './db/AccesoDatos.php';
require_once './models/Area.php';

class Producto
{

    public $id;
    public $producto_area;
    public $pedido_asociado;
    public $estado;
    public $descripcion;
    public $costo;
    public $tiempo_inicio;
    public $tiempo_fin;
    public $tiempo_para_finalizar;

    public function __construct()
    {
    }


    public static function crearProducto($producto_area, $pedido_asociado, $estado, $descripcion, $costo, $tiempo_inicio)
    {
        $producto = new Producto();
        $producto->producto_area = $producto_area;
        $producto->pedido_asociado = $pedido_asociado;
        $producto->estado = $estado;
        $producto->descripcion = $descripcion;
        $producto->costo = $costo;
        $producto->tiempo_inicio = $tiempo_inicio;
        $producto->tiempo_fin = null;
        $producto->tiempo_para_finalizar = null;

        return $producto;
    }

    public function calcularTiempoFinalizacion()
    {
        $newDate = new DateTime($this->tiempo_inicio);
        $newDate = $newDate->modify('+' . $this->tiempo_para_finalizar . ' minutes');
        $this->tiempo_fin = $newDate->format('Y-m-d H:i:s');
    }

    public static function filtrarProductosFinalizados($listadoProducto, $estado)
    {
        $arrayProducto = array();
        foreach ($listadoProducto as $producto) {
            if (strcmp($producto->estado, $estado) == 0) {
                array_push($arrayProducto, $producto);
            }
        }
        return $arrayProducto;
    }

    public static function insertarProducto($producto)
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("INSERT INTO producto (producto_area, pedido_asociado, estado, descripcion, costo, tiempo_inicio) 
                VALUES (:producto_area, :pedido_asociado, :estado, :descripcion, :costo, :tiempo_inicio)");
        $query->bindValue(':producto_area', $producto->producto_area);
        $query->bindValue(':pedido_asociado', $producto->pedido_asociado);
        $query->bindValue(':estado', $producto->estado);
        $query->bindValue(':descripcion', $producto->descripcion);
        $query->bindValue(':costo', $producto->costo);
        $query->bindValue(':tiempo_inicio', $producto->tiempo_inicio);
        $query->execute();

        return $objDataAccess->obtenerUltimoId();
    }


    public static function actualizarProducto($producto)
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("UPDATE producto
                SET estado = :estado, tiempo_fin = :tiempo_fin, tiempo_para_finalizar = :tiempo_para_finalizar 
                WHERE id = :id");
        $query->bindValue(':estado', $producto->estado);
        $query->bindValue(':tiempo_fin', $producto->tiempo_fin);
        $query->bindValue(':tiempo_para_finalizar', $producto->tiempo_para_finalizar);
        $query->bindValue(':id', $producto->id);
        $query->execute();

        return $query->rowCount();
    }

    public static function getTodosProductos()
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("SELECT * FROM producto");
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, "Producto");
    }

    public static function getProductoPorId($id)
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("SELECT * FROM producto WHERE id = :id");
        $query->bindParam(':id', $id);
        $query->execute();

        return $query->fetchObject("Producto");
    }


    public static function getProductoPorTipoUsuario($usuario_tipo)
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta(
            "SELECT 
            p.id,
            p.producto_area,
            p.pedido_asociado,
            p.estado,
            p.descripcion,
            p.costo,
            p.tiempo_inicio,
            p.tiempo_fin,
            p.tiempo_para_finalizar
        FROM producto AS p
        WHERE p.producto_area = :producto_area"
        );
        $query->bindParam(':producto_area', $usuario_tipo);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, "Producto");
    }
    public static function getProductoPorPedidoId($pedidoId)
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta(
            "SELECT 
                    p.id AS id,
                    p.producto_area AS producto_area,
                    p.pedido_asociado AS pedido_asociado,
                    p.estado AS estado,
                    p.descripcion AS descripcion,
                    p.costo AS costo,
                    p.tiempo_inicio AS tiempo_inicio,
                    p.tiempo_fin AS tiempo_fin,
                    p.tiempo_para_finalizar AS tiempo_para_finalizar
                    FROM producto AS p
                    WHERE p.pedido_asociado = :id;"
        );
        $query->bindParam(':id', $pedidoId);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_CLASS, "Producto");
    }

    public static function borraProducto($producto)
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("DELETE FROM producto WHERE id = :id");
        $query->bindValue(':id', $producto->id);
        $query->execute();

        return $query->rowCount() > 0;
    }
    public static function getSumaProductosPorPedido($pedido_id)
    {
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("SELECT SUM(p.costo) AS total FROM producto AS p WHERE pedido_asociado = :id");
        $query->bindParam(':id', $pedido_id);
        $query->execute();

        return $query->fetchObject()->total;
    }
}
