<?php


require_once './interfaces/IApiUsable.php';
require_once './models/Pedido.php';
require_once './models/Mesa.php';
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
        echo 'Pedidos:';

        $payload = json_encode(array("Pedidos" => $pedidos));
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

 
	public function CargarUno($request, $response, $args){
        $params = $request->getParsedBody();
        
        $mesa_id = $params['mesa_id'];
        $nombre_cliente = $params['cliente_nombre'];

        if(isset($_FILES['pedido_foto']) && $_FILES['pedido_foto']['error'] === UPLOAD_ERR_OK) {
            $rutaArchivoTemporal = $_FILES['pedido_foto']['tmp_name'];
        
            $directorioDestino = 'PedidoFoto/';
            $nombreArchivo = $nombre_cliente .'.png'; 

            $rutaArchivoDestino = $directorioDestino . $nombreArchivo;
            if(move_uploaded_file($rutaArchivoTemporal, $rutaArchivoDestino)) {
                echo "El archivo se ha guardado en: " . $rutaArchivoDestino;
            } else {

                echo "Error al guardar el archivo.";
            }
        }
        $auxiliar = Pedido::getPedidoPorNroPedido($params['nro_pedido']);
        if($auxiliar == null){
            $pedido = Pedido::crearPedido(
                $mesa_id, 
                $params['nro_pedido'],
                $params['pedido_estado'], 
                $nombre_cliente,
                $rutaArchivoDestino
            );
            Pedido::insertarPedido($pedido);
            $payload = json_encode(array("mensaje" => $pedido));
        }
        else{
            $auxiliar->mesa_id = $mesa_id;
            $auxiliar->pedido_estado = $params['pedido_estado'];
            $auxiliar->cliente_nombre = $nombre_cliente;
            $auxiliar->pedido_foto = $rutaArchivoDestino;

            Pedido::actualizarPedidoDatos($auxiliar);
            $payload = json_encode(array("mensaje" => $auxiliar));
        }
        $mesa_actual = Mesa::getMesaPorId($mesa_id);
        $mesa_actual->estado = $params['pedido_estado'];
        Mesa::actualizarMesa($mesa_actual);
        $response->getBody()->write("Pedido creado con exito");
        
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

        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData);

        $id = $data->id;
        $pedido_estado = $data->pedido_estado;
        
        $params = array(
            'id' => $id,
            'pedido_estado' => $pedido_estado
        );

        $pedido = Pedido::getPedidoPorId($id);
        $pedido->pedido_estado = $pedido_estado;
        $pedido->pedido_costo = Producto::getSumaProductosPorPedido($pedido->nro_pedido);


        Pedido::actualizarPedido($pedido);
        $payload = json_encode(array("Pedido Modificado" => $pedido));
        $response->getBody()->write($payload);
        
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodosTarde($request, $response, $args)
    {
        

        $pedidos = Pedido::getTodosPedidosTarde();
        

        $payload = json_encode(array("Pedidos tarde" => $pedidos));
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}
?>