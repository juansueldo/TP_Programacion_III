<?php
require_once './models/Empleado.php';
require_once './interfaces/IApiUsable.php';

class EmpleadoController extends Empleado implements IApiUsable
{
  public function CargarUno($request, $response, $args){

    $params = $request->getParsedBody();
    echo '<br>Datos de la Empleado a crear:<br>';
    
    
    $empleado = Empleado::crearEmpleado(
      $params['usuario_id'], 
      $params['area_id'], 
      $params['nombre'], 
      date("Y-m-d H:i:s")
    );
    Empleado::MostrarDatos($empleado);
    if (Empleado::insertarEmpleado($empleado) > 0) {
        $payload = json_encode(array("mensaje" => "Empleado creado con exito"));
    }else{
        $payload = json_encode(array("mensaje" => "Error al crear el Empleado"));
    }

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
}

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $empleado = Empleado::getEmpleadoPorId($id);
        $payload = json_encode($empleado);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Empleado::getTodosEmpleados();
        $payload = json_encode(array("listaEmpleados" => $lista));

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

        $usuarioId = $parametros['id'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
