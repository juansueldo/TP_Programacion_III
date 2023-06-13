<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public function CargarUno($request, $response, $args){

    $params = $request->getParsedBody();
    echo '<br>Datos del Usuario a crear:<br>';
    var_dump($params);
    
    // Creamos el User
    $user = Usuario::crearUsuario(
      $params['usuario_nombre'], 
      $params['clave'], 
      $params['esAdmin'], 
      $params['usuario_tipo'],
      $params['estado'], 
      $params['fecha_inicio']);
    if (Usuario::insertartUsuario($user) > 0) {
        $payload = json_encode(array("mensaje" => "User creado con exito"));
    }else{
        $payload = json_encode(array("mensaje" => "Error al crear el User"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
}

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $id = $args['id'];
        $usuario = Usuario::getUsuarioPorId($id);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::getTodosUsuarios();
        $payload = json_encode(array("listaUsuario" => $lista));

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
