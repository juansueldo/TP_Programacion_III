<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
  public function CargarUno($request, $response, $args)
  {

    $params = $request->getParsedBody();
    echo 'Datos del Usuario a crear: '. `\n`;
    Usuario::MostrarDatos($params);

    
    $usuario = Usuario::crearUsuario(
      $params['usuario_nombre'],
      $params['clave'],
      $params['esSocio'],
      $params['usuario_tipo'],
      $params['estado'],
      $params['fecha_inicio']
    );
    if (Usuario::insertartUsuario($usuario) > 0) {
      $payload = json_encode(array("mensaje" => "Usuario creado con éxito"));
    } else {
      $payload = json_encode(array("mensaje" => "Error al crear el usuario"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function TraerUno($request, $response, $args)
  {
    
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

    $payload = json_encode(array("mensaje" => "Usuario modificado con éxito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }

  public function BorrarUno($request, $response, $args)
  {
    $params = $request->getParsedBody();

    $usuarioId = $params['id'];
    Usuario::borrarUsuario($usuarioId);

    $payload = json_encode(array("mensaje" => "Usuario borrado con éxito"));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public function Login($request, $response, $args)
  {

    $params = $request->getParsedBody();

    if (isset($params['usuario_nombre']) && isset($params['clave'])) {
      $usuario_nombre = $params['usuario_nombre'];
      $clave = $params['clave'];
      $usuario_actual = Usuario::getUsuarioPorNombre($usuario_nombre);

      if ($usuario_actual != null && ($usuario_actual->usuario_nombre == $usuario_nombre && $usuario_actual->clave == $clave)) {
        $token = AutentificadorJWT::CrearToken($usuario_actual);
        $respuesta = $token;
        $payload = json_encode($respuesta);
      } else {
        $payload = json_encode(array("mensaje" => "Login fallido"));
      }
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }


  public static function GetInfoByToken($request)
  {
    $header = $request->getHeader('Authorization');
    $token = trim(str_replace("Bearer", "", $header[0]));
    $usuario = AutentificadorJWT::ObtenerData($token);

    return $usuario;
  }
}
