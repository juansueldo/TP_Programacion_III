<?php
require_once './models/HistorialLogin.php';
require_once './models/Encuesta.php';
    class ArchivosController extends HistorialLogin{
        public function Leer($request, $response, $args){
            $filename = './Reportes/historial_login.csv';
            $dataToRead = HistorialLogin::LeerCSV($filename);
            $payload = json_encode(array("Error" => 'Error en la lectura del archivo'));
            if(!is_null($dataToRead)){
                $payload = json_encode(array("Success" => 'Archvio cargado en la tabla: ', "historial_login" => $dataToRead));
            }
            
            $response->getBody()->write($payload);
            return $response
              ->withHeader('Content-Type', 'application/json');
        }
    
        public function Guardar($request, $response, $args){
            $loginsFromDb = HistorialLogin::getTodas();
            $filename = './Reportes/historial_login.csv';
            $payload = json_encode(array("Error" => 'Archivo no guardado'));
            if(HistorialLogin::GuardarCSV($loginsFromDb, $filename)){
    
                $payload = json_encode(array("Exito" => 'Archivo guardado como historial_login.csv',"Historical Logins" => $loginsFromDb));
            }
            $response->getBody()->write($payload);
            return $response
              ->withHeader('Content-Type', 'application/json');
        }

        public function DownloadPdf($request, $response, $args){
            $params = $request->getParsedBody();
            $directory = './Reportes/';
            $payload = json_encode(array("Error" => 'Archivo no guardado'));
            
            if($params['promedio']){
                $amountPolls = $params['promedio'];
                $payload = Encuesta::DownloadPdf($directory, $amountPolls);
                echo 'Archivo guardado en: '.$directory;
            }
            
            $response->getBody()->write($payload);
            return $response
              ->withHeader('Content-Type', 'application/json');
        }
    }
?>