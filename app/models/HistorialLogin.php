<?php


class HistorialLogin{
    public $id;
    public $usuario_id;
    public $usuario_nombre;
    public $fecha_login;

    public function __construct() {}

    public static function crearHistorialLogin($usuario_id, $usuario_nombre, $fecha_login){
        $historialLogin = new HistorialLogin();
        $historialLogin->usuario_id = $usuario_id;
        $historialLogin->usuario_nombre =  $usuario_nombre;
        $historialLogin->fecha_login = $fecha_login;
        
        return $historialLogin;
    }

    
    public static function LeerCSV($filename="./Reportes/historial_login.csv"){
        $file = fopen($filename, "r");
        $array = array();
        try {
            if (!is_null($file) && self::borrarTabla() > 0){
                
            }
            while (!feof($file)) {
                $line = fgets($file);
                
                if (!empty($line)) {
                    $line = str_replace(PHP_EOL, "", $line);
                    $loginsArray = explode(",", $line);
                    $hLogin = HistorialLogin::crearHistorialLogin($loginsArray[0], $loginsArray[1], $loginsArray[2]);
                    array_push($array, $hLogin);
                    HistorialLogin::insertarHistorialLogin($hLogin);
                }
            }
        } catch (\Throwable $th) {
            echo "Error en la lectura del archivo";
        }finally{
            fclose($file);
            return $array;
        }
    }


    public static function GuardarCSV($entitiesList, $filename = './Reportes/historial_login.csv'):bool{
        $success = false;
        $directory = dirname($filename, 1);
        
        try {
            if(!file_exists($directory)){
                mkdir($directory, 0777, true);
            }
            $file = fopen($filename, "w");
            if ($file) {
                foreach ($entitiesList as $entity) {
                    $line = $entity->usuario_id . "," . $entity->usuario_nombre . "," . $entity->fecha_login . PHP_EOL;
                    fwrite($file, $line);
                    $success = true;
                    
                }
            }
        } catch (\Throwable $th) {
            echo "Error al guardar el archivo";
        }finally{
            fclose($file);
        }

        return $success;
    }

   
    public static function insertarHistorialLogin($historicalLogin){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("INSERT INTO `historial_logins` (usuario_id, usuario_nombre, fecha_login) 
        VALUES (:usuario_id, :usuario_nombre, :fecha_login);");
        $query->bindValue(':usuario_id', $historicalLogin->usuario_id, PDO::PARAM_INT);
        $query->bindValue(':usuario_nombre', $historicalLogin->usuario_nombre, PDO::PARAM_STR);
        $query->bindValue(':fecha_login', $historicalLogin->fecha_login, PDO::PARAM_STR);
        $query->execute();

        return $objDataAccess->obtenerUltimoId();
    }

   
    public static function getHistorialLoginPorId($id){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("SELECT * FROM `historial_logins` WHERE id = :id;");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'HistorialLogin');
    }

   
    public static function getTodas(){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("SELECT * FROM `historial_logins`;");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_CLASS, 'HistorialLogin');
    }


    public static function borrarHistorialLoginPorId($id){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("DELETE FROM `historial_logins` WHERE id = :id;");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        return $query->rowCount() > 0;
    }


    public static function borrarTabla(){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("DELETE FROM `historial_logins` WHERE 1=1;");
        $query->execute();

        return $query->rowCount() > 0;
    }
}
?>