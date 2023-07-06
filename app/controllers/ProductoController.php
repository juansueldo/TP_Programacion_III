<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $params = $request->getParsedBody();;
        $pedido_asociado = $params['pedido_asociado'];
        $area = $params['area'];
        switch($area){
            case "cocina":
                $area_id = 3;
                break;
            case "barra de tragos":
                $area_id = 1;
                break;
            case "barra de choperas":
                $area_id = 2;
                break;
            case "candy bar":
                $area_id = 4;
                break;
        }
        $producto = Producto::crearProducto(
            $area_id,
            $params['pedido_asociado'],
            $params['estado'],
            $params['descripcion'],
            $params['costo'],
            date("Y-m-d H:i:s")
        );

     

        if (Producto::insertarProducto($producto) > 0) {

            $pedido = Pedido::getPedidoPorNroPedido($pedido_asociado);
            if($pedido != null){
                $pedido_costo = Producto::getSumaProductosPorPedido($pedido->nro_pedido);
                $pedido->pedido_costo = $pedido_costo;
    
                if (Pedido::actualizarPedido($pedido) > 0) {
                    echo 'El total del precio del pedido ha sido actualizado';
                    echo $pedido->costo;
                }
            }
            else{
                if(Pedido::insertarNroPedido($pedido_asociado)>0){
                    $pedido = Pedido::getPedidoPorNroPedido($pedido_asociado);
            if($pedido != null){
                $pedido_costo = Producto::getSumaProductosPorPedido($pedido->nro_pedido);
                $pedido->pedido_costo = $pedido_costo;
    
                if (Pedido::actualizarPedido($pedido) > 0) {
                    echo 'El total del precio del pedido ha sido actualizado';
                    echo $pedido->costo;
                }
                }
            }
        }
            $payload = json_encode(array("mensaje" => "Producto creado: " . $producto->descripcion));
            $response->getBody()->write("Producto creado con exito");
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
        switch ($empleado_tipo_id) {
            case 2:
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
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData);


        $id = $data->id;
        $estado = $data->estado;
        $tiempo_para_finalizar = $data->tiempo_para_finalizar;

       
        $params = array(
            'id' => $id,
            'estado' => $estado,
            'tiempo_para_finalizar' => $tiempo_para_finalizar
        );

        $producto = Producto::getProductoPorId($id);
        $producto->estado = $estado;
        $producto->tiempo_para_finalizar = $tiempo_para_finalizar;
        $producto->calcularTiempoFinalizacion();

        Producto::actualizarProducto($producto);
        $payload = json_encode(array("mensaje" => $producto));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUnoDos($request, $response, $args)
    {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData);

        $id = $data->id;
        $estado = $data->estado;
       

        $producto = Producto::getProductoPorId($id);
        $producto->estado = $estado;
        $fechaActual = new DateTime(); // Crea un objeto DateTime con la fecha y hora actual
        $minutosASumar = 10; // Cantidad de minutos a sumar

        $fechaActual->add(new DateInterval('PT' . $minutosASumar . 'M')); // Agrega el intervalo de minutos

        echo $fechaActual->format('Y-m-d H:i:s');
        $producto->tiempo_fin = $fechaActual->format('Y-m-d H:i:s');
 

        Producto::actualizarProducto($producto);
        $payload = json_encode(array("mensaje" => $producto));
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
    public function TraerTodosTarde($request, $response, $args)
    {
        

        $productos = Producto::getTodosProductosTarde();
        

        $payload = json_encode(array("Productos tarde" => $productos));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
