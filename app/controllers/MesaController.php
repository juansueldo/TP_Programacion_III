<?php
require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
  public function CargarUno($request, $response, $args){

    $params = $request->getParsedBody();
    echo '<br>Datos de la Mesa a crear:<br>';
    var_dump($params);
    
    $mesa = Mesa::crearMesa(
      $params['numero_mesa'], 
      $params['empleado_id'], 
      $params['estado'], );
    if (Mesa::insertarMesa($mesa) > 0) {
        $payload = json_encode(array("mensaje" => "Mesa creada con exito"));
    }else{
        $payload = json_encode(array("mensaje" => "Error al crear la Mesa"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
}

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
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

        $nombre = $parametros['nombre'];
        Usuario::modificarUsuario($nombre);

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['usuarioId'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
