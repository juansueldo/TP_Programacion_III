<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {

    $params = $request->getParsedBody();
    echo '<br>Datos de la Mesa a crear:<br>';
    var_dump($params);

    $mesa = Mesa::crearMesa(
      $params['numero_mesa'],
      $params['empleado_id'],
      $params['estado'],
    );
    if (Mesa::insertarMesa($mesa) > 0) {
      $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
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

    $parametros = $request->getParsedBody();

    $this->TraerTodos($request, $response, $args);

    if (isset($parametros['id']) && isset($parametros['estado']) && isset($params['empleado_id'])) {
      $mesa_id = $parametros['id'];
      $empleado_id = $parametros['empleado_id'];

      $estado_mesa = $parametros['estado'];

      $empleado = Empleado::getEmpleadoPorId($empleado_id);

      if (isset($empleado) && isset($mesa_id) && strcmp($estado_mesa, "Cerrada") != 0) {
        $mesa = Mesa::getMesaPorId($mesa_id);
        $mesa->setState($estado_mesa);
        $mesa->empleado_id = $parametros['empleado_id'];
        echo 'Mesa elegida: <br>';
        var_dump($mesa);
      } else {
        echo 'Error<br>';
      }
    }

    if (isset($mesa) && Mesa::actualizarMesa($mesa) > 0) {
      $payload = json_encode(array("mensaje" => "Mesa actualizada"));
    } else {
      $payload = json_encode(array("mensaje" => "Error al actualizar la mesa"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function CobrarUno($request, $response, $args)
  {
    $parametros = $request->getParsedBody();
    $payload = json_encode(array("mensaje" => "Error al cobrar en la mesa"));

    if (!isset($parametros['id']) && !isset($parametros['estado'])) {
      $mesas = Mesa::getTodasMesas();
    }

    if (isset($params['id']) && isset($params['estado'])) {
      $mesa_id = $parametros['id'];
      $mesa_estado = $parametros['estado'];
      $mesa = Mesa::getMesaPorId($mesa_id);

      echo 'Mesa a cobrar: <br>';
      var_dump($mesa);

      if (isset($mesa)) {
        $mesa->estado = $mesa_estado;

        if (Mesa::actualizarMesa($mesa) > 0) {
          $payload = json_encode(array("mensaje" => "Mesa cobrada con exito"));
        }
      }
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function ModificarUnoAdmin($request, $response, $args)
  {

    $parametros = $request->getParsedBody();

    $this->TraerTodos($request, $response, $args);

    if (isset($parametros['id']) && isset($parametros['estado'])) {
      $mesa_id = $parametros['id'];
      $mesa_estado = $parametros['estado'];

      if (isset($mesa_id)) {
        $mesa = Mesa::getMesaPorId($mesa_id);

        if (
          strcmp($mesa->estado, "Cerrada") == 0
          && strcmp($mesa_estado, "Cerrada") == 0
        ) {
          echo 'La mesa esta cerrada';
        } else {
          $mesa->estado = $mesa_estado;
          echo 'Estado de la mesa modificado';
        }
      } else {
        echo 'Error';
      }
    }

    if (isset($mesa) && Mesa::actualizarMesa($mesa) > 0) {
      $payload = json_encode(array("mensaje" => "Mesa actualizada"));
    } else {
      $payload = json_encode(array("mensaje" => "Error al actualizar"));
    }
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  /*public function TraerDemoraPedidoMesa($request, $response, $args)
  {

    $table_code = $args['table_code'];
    $order_id = $args['order_id'];
    $delay = Pedido::getMaxTimeOrderByTableCode($order_id, $table_code)[0]['time_order'];
    if ($delay == 0) {
      echo '<h2>Table Code: ' . $table_code . '<br>Waiting Time: ' . $delay . ' minutes.</h2>
          <h2>Your order is ready, it will be dispatched shortly. Thanks for choosing us, Bon Appetit</h2><br>';
    } else {
      echo '<h2>Table Code: ' . $table_code . '<br>Order Will be ready in aprox: ' . $delay . ' minutes.</h2><br>';
    }
    $payload = json_encode(array("mensaje" => "Waiting Time: " . $delay . " minutes"));
    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }*/

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

}
