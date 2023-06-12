<?php


require_once './db/AccesoDatos.php';
require_once './models/Empleado.php';

class Usuario{
    
    //--- Attributes ---//
    public $id;
    public $usuario_nombre;
    public $password;
    public $esAdmin;
    public $usuario_tipo;
    public $estado;
    public $fecha_inicio;
    public $fecha_fin;


    public function __construct($usuario_nombre, $password, $esAdmin, $usuario_tipo, $estado, $fecha_inicio){
        $this->usuario_nombre = $usuario_nombre;
        $this->password = $password;
        $this->esAdmin = $esAdmin;
        $this->usuario_tipo = $usuario_tipo;
        $this->estado = $estado;
        $this->fecha_inicio = $fecha_inicio;
    }


    public static function insertartUsuario($usuario){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("INSERT INTO usuarios (usuario_nombre, password, esAdmin, usuario_tipo, estado, fecha_inicio) 
        VALUES (:usuario_nombre, :password, :esAdmin, :usuario_tipo, :estado, :fecha_inicio)");
        $passwordHash = password_hash($usuario->password, PASSWORD_DEFAULT);
        $query->bindValue(':usuario_nombre', $usuario->usuario_nombre, PDO::PARAM_STR);
        $query->bindValue(':password', $passwordHash);
        $query->bindValue(':esAdmin', $usuario->esAdmin, PDO::PARAM_INT);
        $query->bindValue(':usuario_tipo', $usuario->usuario_tipo, PDO::PARAM_STR);
        $query->bindValue(':estado', $usuario->estado, PDO::PARAM_STR);
        $query->bindValue(':fecha_inicio', $usuario->fecha_inicio, PDO::PARAM_STR);
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
        $query = $objDataAccess->prepararConsulta("UPDATE usuarios SET usuario_nombre = :usuario_nombre, password = :password WHERE id = :id");
        try {
            $query->bindValue(':usuario_nombre', $usuario->usuario_nombre, PDO::PARAM_STR);
            $query->bindValue(':password', $usuario->password, PDO::PARAM_STR);
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

        return $query;
    }
}
?>