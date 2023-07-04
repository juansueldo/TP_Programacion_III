<?php
require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {

    $params = $request->getParsedBody();
    echo 'Datos de la Mesa a crear:';
    var_dump($params);

    $mesa = Mesa::crearMesa(
      $params['numero_mesa'],
      $params['empleado_id'],
      $params['estado'],
    );
    if (Mesa::insertarMesa($mesa) > 0) {
      $payload = json_encode(array("mensaje" =>"Creada con exito"));
    } else {
      $payload = json_encode(array("mensaje" => "Error al crear la Mesa"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    $id = $args['id'];
    $mesa = Mesa::getMesaPorId($id);
    $payload = json_encode($mesa);

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerTodos($request, $response, $args)
  {
    $lista = Mesa::getTodasMesas();
    $payload = json_encode(array("listaMesas" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUno($request, $response, $args)
  {

    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData);
    $mesa = Mesa::getMesaPorId($data->id);

    if(Mesa::getProductosListosParaServir($data->id, "listo para servir") === Mesa::getProductosMesa($data->id)){
      $mesa->estado = "con cliente comiendo";
      Mesa::actualizarMesa($mesa);
      $payload = json_encode(array("mensaje"=> $mesa));
    }
    else{
      $payload = json_encode(array("mensaje"=> "Todavia no estan todos los productos listos para servir"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function CobrarUno($request, $response, $args)
  {
    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData);
    $payload = json_encode(array("mensaje" => "Error al cobrar en la mesa"));

      $mesa = Mesa::getMesaPorId($data->id);
      $mesa->estado = "con cliente pagando";
      Mesa::actualizarMesa($mesa);
      
      if($mesa !== null){
        $payload = json_encode(array("mensaje" =>  "Mesa cobrada con exito"));
        $pedido = Pedido::getPedidoPorMesa($data->id);
        var_dump($pedido);
        $pedido->estado = "finalizado";
        Pedido::actualizarPedido($pedido);
      }
      


    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function CerrarMesa($request, $response, $args)
  {

    $jsonData = file_get_contents('php://input');
    $data = json_decode($jsonData);
    $payload = json_encode(array("mensaje" => "Error al cerrar la mesa"));
    $mesa = Mesa::getMesaPorId($data->id);
    $mesa->estado = "cerrada";
    Mesa::actualizarMesa($mesa);
      if($mesa !== null){
        $payload = json_encode(array("mensaje" =>  "Mesa cerrada"));
      }
      
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerDemoraPedidoMesa($request, $response, $args)
  {

    $nro_mesa = $args['mesa_id'];
    $nro_pedido = $args['nro_pedido'];
    $espera = Pedido::getMaxTimeOrderByTableCode($nro_pedido, $nro_mesa)[0]['tiempo_pedido'];
    if ($espera == 0) {
      $payload = json_encode(array("mensaje" => 'Mesa: ' . $nro_mesa . ' Tiempo de espera: ' . $espera . ' minutos
      Tu pedido ya esta siendo despachado'));
    } else {
      $payload = json_encode(array("mensaje" =>  'Mesa: ' . $nro_mesa . ' El tiempo de espera para tu pedido es de: ' . $espera . ' minutos'));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args){
    $parametros = $request->getParsedBody();
    $id = $parametros['id'];
    $mesa = Mesa::getMesaPorId($id);
    $payload = json_encode($mesa);
    if(isset($mesa) && Mesa::borrarMesa($id) > 0){
        $payload = json_encode(array("mensaje" => "Mesa eliminada con exito"));
    }else{
        $payload = json_encode(array("mensaje" => "Error al borrar la mesa"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
}
public function MesaMasUsada($request, $response, $args){
  $mesa = Mesa::getMesaMasUsada();
    $payload = json_encode(array("Mesa mas usada" => $mesa));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
}

}
