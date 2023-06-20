<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
require_once './models/Usuario.php';
class MWAccesos{
    private $usuario_tipo = [
        "Socio", "Bartender", "Cervecero", "Cocinero", "Mozo"
    ];
    public function validarToken($request, $rHandler)
    {
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            AutentificadorJWT::VerificarToken($token);
            $response = $rHandler->handle($request);
        } else {
            $response->getBody()->write(json_encode(array("Token error" => "Es necesario un token")));
            $response = $response->withStatus(401);
        }
        return  $response->withHeader('Content-Type', 'application/json');
    }
    public function esSocio($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            $datos = AutentificadorJWT::ObtenerData($token);
            if ($datos->usuario_tipo == 'Socio') {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("Error" => "Solo los socios tienen acceso")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("Socio Error" => "Necesitas un token de administrador")));
            $response = $response->withStatus(401);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
    public function esEmpleado($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        try {
            if (!empty($header)) {
                $token = trim(explode("Bearer", $header)[1]);
                $data = AutentificadorJWT::ObtenerData($token);
                if (in_array($data->usuario_tipo, $this->usuario_tipo)) {
                    if ($data->usuario_tipo != "Socio") {
                        $response = $handler->handle($request);
                    }
                } else {
                    $response->getBody()->write(json_encode(array("error" => "Solo el personal registrado tiene acceso")));
                    $response = $response->withStatus(401);
                }
            } else {
                $response->getBody()->write(json_encode(array("error" => "Debes tener un token valido")));
                $response = $response->withStatus(401);
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function esBartender($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->usuario_tipo == "Bartender" 
            || $data->usuario_tipo == "Socio") {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "Solo los socios o bartender tienen acceso")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("Socio error" => "Es necesario un token de socio o bartender")));
            $response = $response->withStatus(401);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }


    public function esCocinero($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->usuario_tipo == "Cocinero"
            || $data->usuario_tipo == "Socio") {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "Solo los cocineros o socios tienen acceso")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("Socio error" => "Es necesario un token de cocineros o socios")));
            $response = $response->withStatus(401);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

  
    public function esMozo($request, $handler)
    {
        $header = $request->getHeaderLine('authorization');
        $response = new Response();
        if (!empty($header)) {
            $token = trim(explode("Bearer", $header)[1]);
            $data = AutentificadorJWT::ObtenerData($token);
            if ($data->usuario_tipo == "Mozo"
            || $data->usuario_tipo == "Socio") {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write(json_encode(array("error" => "Solo los mozos o socios tienen acceso")));
                $response = $response->withStatus(401);
            }
        } else {
            $response->getBody()->write(json_encode(array("Socio error" => "Es necesario un token de mozos o socios")));
            $response = $response->withStatus(401);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
   

}
