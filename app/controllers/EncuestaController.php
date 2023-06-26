<?php
include_once 'models/Encuesta.php';
class EncuestaController{
    public function RealizarEncuesta($request, $response, $args){
    
        $params = $request->getParsedBody();
        $payload = json_encode(array("message" => "Error al crear la encuesta"));
        if (isset($params['mesa_puntaje']) && isset($params['cocinero_puntaje'])
        && isset($params['mozo_puntaje']) && isset($params['restaurante_puntaje'])
        && isset($params['nro_pedido']) && isset($params['comentarios'])) {
            $nro_pedido = $params['nro_pedido'];
            $mesa_puntaje = $params['mesa_puntaje'];
            $restaurante_puntaje = $params['restaurante_puntaje'];
            $mozo_puntaje = $params['mozo_puntaje'];
            $cocinero_puntaje = $params['cocinero_puntaje'];
            $comentarios = $params['comentarios'];

            $encuesta = Encuesta::crearEncuesta($nro_pedido, $mesa_puntaje, $restaurante_puntaje, $mozo_puntaje, $cocinero_puntaje, $comentarios);
  
            if(Encuesta::insertarEncuesta($encuesta) > 0){
                echo 'Gracias por elegirnos!!!';
                $payload = json_encode(array("Encuesta" => $encuesta, "mensaje" => "Encuesta creada con exito"));
            }
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function ObtenerMejores($request, $response, $args){
        $params = $request->getParsedBody();
        $payload = json_encode(array("message" => 'Error al cargar las encuestas'));
        if (isset($params['limite'])){
            $limite = $params['limite'];
            $encuestas = Encuesta::getMejorPromedio($limite);
            $payload = json_encode(array("Encuestas" => $encuestas));
        }
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}
?>