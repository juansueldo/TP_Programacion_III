<?php


require_once './db/AccesoDatos.php';
require_once './models/Empleado.php';

class Usuario{
    
    //--- Artributos ---//
    public $id;
    public $usuario_nombre;
    public $clave;
    public $esSocio;
    public $usuario_tipo;
    public $estado;
    public $fecha_inicio;
    public $fecha_fin;

    public function __construct(){}

   
    public static function crearUsuario($usuario_nombre, $clave, $esSocio, $usuario_tipo, $estado, $fecha_inicio){
        $usuario = new Usuario();
        $usuario->usuario_nombre = $usuario_nombre;
        $usuario->clave = $clave;
        $usuario->esSocio = $esSocio;
        $usuario->usuario_tipo = $usuario_tipo;
        $usuario->estado = $estado;
        $usuario->fecha_inicio = $fecha_inicio;

        return $usuario;
    }
    
    public static function MostrarDatos($usuario){
        echo "Nombre de usuario: ". $usuario['usuario_nombre']. "\n";
        echo "Clave: ". $usuario['clave']. "\n";
        echo "Es administrador? ". ($usuario['esSocio'] == 1?"Si \n":"No \n");
        echo "Fecha de inicio " . $usuario['fecha_inicio'] . "\n";
    }

    public static function insertartUsuario($usuario){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("INSERT INTO usuarios (usuario_nombre, clave, esSocio, usuario_tipo, estado, fecha_inicio) 
        VALUES (:usuario_nombre, :clave, :esSocio, :usuario_tipo, :estado, :fecha_inicio)");
        $query->bindValue(':usuario_nombre', $usuario->usuario_nombre);
        $query->bindValue(':clave', $usuario->clave);
        $query->bindValue(':esSocio', $usuario->esSocio);
        $query->bindValue(':usuario_tipo', $usuario->usuario_tipo);
        $query->bindValue(':estado', $usuario->estado);
        $query->bindValue(':fecha_inicio', $usuario->fecha_inicio);
        $query->execute();

        return $objDataAccess->obtenerUltimoId();
    }


    public static function getTodosUsuarios(){

        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("SELECT * FROM usuarios");
        $query->execute();
        
        return $query->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function getUsuario($empleado){

        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("SELECT * FROM usuarios AS u
        JOIN empleados AS e
        ON :id = u.id");
        $query->bindValue(':id', $empleado->usuario_id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchObject('Usuario');
    }

    
    public static function getUsuarioPorId($id){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("SELECT * FROM usuarios WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $usuario = $query->fetchObject('Usuario');
        if(is_null($usuario)){
            throw new Exception("Usuario no encontrado");
        }
        return $usuario;
    }

 
    public static function modificarUsuario($usuario){

        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("UPDATE usuarios SET usuario_nombre = :usuario_nombre, clave = :clave WHERE id = :id");
        try {
            $query->bindValue(':usuario_nombre', $usuario->usuario_nombre, PDO::PARAM_STR);
            $query->bindValue(':clave', $usuario->clave, PDO::PARAM_STR);
            $query->bindValue(':id', $usuario->id, PDO::PARAM_INT);
            $query->execute();
        } catch (Error $error) {
            echo $error->getMessage();
        }

        return $query;
    }

    public static function borrarUsuario($usuario){

        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("DELETE FROM usuarios WHERE id = :id");
        $query->bindValue(':id', $usuario->usario_id, PDO::PARAM_INT);
        $query->execute();

        return $query->getRowCount() > 0;
    }
    public static function insertartHistorialLogin($usuario){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("INSERT INTO historial_logins (usuario_id, usuario_nombre, fecha_login) 
        VALUES (:usuario_id, :usuario_nombre, :fecha_login)");
        $query->bindValue(':usuario_id', $usuario->id, PDO::PARAM_INT);
        $query->bindValue(':usuario_nombre', $usuario->usuario_nombre, PDO::PARAM_STR);
        $query->bindValue(':fecha_login', date("Y-m-d H:i:s"), PDO::PARAM_STR);
        $query->execute();

        return $objDataAccess->obtenerUltimoId();
    }
    public static function getUsuarioPorNombre($usuario_nombre){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("SELECT * FROM usuarios WHERE usuario_nombre = :usuario_nombre");
        $query->bindParam(':usuario_nombre', $usuario_nombre, PDO::PARAM_STR);
        $query->execute();
        $usuario = $query->fetchObject('Usuario');
        if(is_null($usuario)){
            throw new Exception("Usuario no encontrado");
        }
        return $usuario;
    }

}
?>