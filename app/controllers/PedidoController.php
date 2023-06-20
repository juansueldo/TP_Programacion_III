<?php


require_once './interfaces/IApiUsable.php';
require_once './models/Pedido.php';
require_once './models/Mesa.php';
//require_once './models/UploadManager.php';
require_once './controllers/UsuarioController.php';

class PedidoController extends Pedido implements IApiUsable{

 
    public function TraerUno($request, $response, $args){
        $params = $request->getParsedBody();
        $id = $params['id'];
        $pedido = Pedido::getPedidoPorId($id);
        $payload = json_encode($pedido);
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

   
	public function TraerTodos($request, $response, $args){
        $pedidos = Pedido::getTodos();

        echo 'Pedidos:';
        $payload = json_encode(array("Pedidos" => $pedidos));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


	public function TraerSegunArea($request, $response, $args){
        $usuario_tipo = UsuarioController::GetInfoByToken($request)->getUserType();

        $productos = Producto::getProductoPorTipoUsuario($usuario_tipo);

        echo 'Productos para: '.${$usuario_tipo}.'<br>';
        var_dump($productos);

        $payload = json_encode(array("Productos" => $productos));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


    public function TraerPedidosTiempo($request, $response, $args){
        $pedidos = Pedido::getPedidosConTiempo();
        echo 'Pedidos: <br>';
        var_dump($pedidos);

        $payload = json_encode(array("Pedidos" => $pedidos));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

 
	public function CargarUno($request, $response, $args){
        $imagenRuta = "./PedidoFoto/";
        $params = $request->getParsedBody();
        
        $mesa_id = $params['mesa_id'];
        
        $pedido = Pedido::crearPedido(
            $mesa_id, 
            $params['pedido_estado'], 
            $params['cliente_nombre'],
            $params['pedido_costo']
        );
        
        $payload = json_encode($pedido);
        $pedido_id = Pedido::insertarPedido($pedido);
        if($pedido_id > 0){
            $payload = json_encode(array("mensaje" => "Orden Creada con exito"));
            $response->getBody()->write("Order created successfully");
            $fileManager = new ManejadorCarga($imagenRuta, $pedido_id, $_FILES);
            $pedido = Pedido::getPedidoPorId($pedido_id);
            $pedido->pedido_foto = ManejadorCarga::getOrderImageNameExt($fileManager, $pedido_id);
            Pedido::actualizarFoto($pedido);
            echo 'Order Created: <br>';
            var_dump($pedido);
        }
        else{
            $response->getBody()->write("Something failed while creating the Order");
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }

 
	public function BorrarUno($request, $response, $args){
        $params = $request->getParsedBody();
        $id = $params['id'];
        $pedido = Pedido::getPedidoPorId($id);
        $payload = json_encode($pedido);
        if(Pedido::borrarPedidoPorId($id) > 0){
            $payload = json_encode(array("mensaje" => "Orden Eliminada con exito"));
            $response->getBody()->write("Order deleted successfully");
        }
        else{
            $response->getBody()->write("Something failed while deleting the Order");
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

	public function ModificarUno($request, $response, $args){

        $params = $request->getParsedBody();
        $id = $params['id'];
        
        $pedido = Pedido::getPedidoPorId($id);
        $pedido->estado = $params['estado'];
        $pedido->pedido_costo = Producto::getSumaProductosPorPedido($pedido->id);

        echo 'New order data: <br>';
        var_dump($pedido);

        if (Pedido::actualizarPedido($pedido) > 0) {
            $payload = json_encode(array("mensaje" => "Orden modificada con exito"));
            $response->getBody()->write("Order updated successfully");
        }else{
            $response->getBody()->write("Something failed while updating the Order");
        }
    }
}
?>