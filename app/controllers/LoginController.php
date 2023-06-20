<?php

 require_once './models/Usuario.php';

 class LoginController extends Usuario{


    public function verificarUsuario($request, $response, $args){
        $params = $request->getParsedBody();
        $usuario_nombre = $params['usuario_nombre'];
        $clave = $params['clave'];
        
        $usuario = Usuario::getUsuarioPorNombre($usuario_nombre);
        $payload = json_encode(array('estado' => 'Usuario invalido'));
        
        if(!is_null($usuario)){
            if($clave === $usuario->clave){
                $usuario_datos = array(
                    'id' => $usuario->id,
                    'usuario_nombre' => $usuario->usuario_nombre,
                    'clave' => $usuario->clave,
                    'esSocio' => $usuario->esSocio,
                    'usuario_tipo' => $usuario->usuario_tipo);
                
                    $payload = json_encode(array(
                    'Token' => AutentificadorJWT::CrearToken($usuario_datos), 
                    'response' => 'Usuario valido', 
                    'Tipo de usuario' => $usuario->usuario_tipo));
                $idLoginGuardado= Usuario::insertartHistorialLogin($usuario);

                if($idLoginGuardado > 0){
                    echo "Logueo guardado en el historial";
                }
            }
        }
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
 }
?>