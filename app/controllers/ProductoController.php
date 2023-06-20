<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();;
        $pedido_asociado = $params['pedido_asociado'];
        $producto = Producto::crearProducto(
            $params['area_id'],
            $params['pedido_asociado'],
            $params['estado'],
            $params['descripcion'],
            $params['costo'],
            date("Y-m-d H:i:s")
        );

        echo 'Producto creado';
        var_dump($producto);

        $payload = json_encode($producto);
        if (Producto::insertarProducto($producto) > 0) {

            $pedido = Pedido::getPedidoPorId($pedido_asociado);
            $pedido_costo = Producto::getSumaProductosPorPedido($pedido->id);
            $pedido->pedido_costo = $pedido_costo;

            if (Pedido::actualizarPedido($pedido) > 0) {
                echo 'El total del precio del pedido ha sido actualizado';
                var_dump($pedido);
            }

            $payload = json_encode(array("mensaje" => "Producto creado con exito"));
            $response->getBody()->write("Producto creado con exito");
        } else {
            $response->getBody()->write("Error, algo salio mal al crear el producto");
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $producto = Producto::getProductoPorId($id);
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $empleado_tipo = UsuarioController::GetInfoByToken($request)->usuario_tipo;
        $empleado_tipo_id = Area::getAreaPorPuesto($empleado_tipo);
        switch($empleado_tipo_id){
            case 2 :
            case 5:
                $auxiliar = 3;
                break;
            case 3:
                $auxiliar = 2;
                break;
            case 4:
                $auxiliar = 1;
                break; 
        }

        $productos = Producto::getProductoPorTipoUsuario($auxiliar);
        $productosMostrar = array();

        foreach ($productos as $producto) {
            array_push($productosMostrar, $producto);
        }

        echo 'Pedido en preparacion por: [' . $empleado_tipo_id . ']' . "\n";


        $payload = json_encode(array("Productos" => $productos));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {


        //$this->TraerTodos($request, $response, $args);

        $params = $request->getParsedBody();
        $id_producto = $params['id'];
        echo $id_producto;
        $estado = $params['estado'];
        $producto = Producto::getProductoPorId($id_producto);
        $payload = json_encode(array("mensaje" => "Producto modificado"));


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();
        $id = $params['id'];
        $producto = Producto::getProductoPorId($id);
        $payload = json_encode($producto);
        if (Producto::borraProducto($id) > 0) {
            $payload = json_encode(array("mensaje" => "Producto eliminado con exito"));
            $response->getBody()->write("Producto eliminado con exito");
        } else {
            $response->getBody()->write("Error mientras se eliminaba el producto");
        }
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
