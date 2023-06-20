<?php


require_once './models/Pedido.php';
require_once './db/AccesoDatos.php';

 class Empleado{

    public $id;
    public $usuario_id;
    public $area_id;
    public $nombre;
    public $fecha_inicio;
    public $fecha_fin;

    public function __construct(){}
    
    public static function crearEmpleado($usuario_id, $area_id, $nombre, $fecha_inicio){
        $empleado = new Empleado();
        $empleado->usuario_id = $usuario_id;
        $empleado->area_id = $area_id;
        $empleado->nombre = $nombre;
        $empleado->fecha_inicio = $fecha_inicio;
        
        return $empleado;
    }

    public static function MostrarDatos($empleado){
        echo "Usuario ID: " . $empleado->usuario_id . "<br>";
        echo "Área ID: " . $empleado->area_id . "<br>";
        echo "Nombre: " . $empleado->nombre . "<br>";
        echo "Fecha de inicio: " . $empleado->fecha_inicio . "<br>";
    }
    
    public static function insertarEmpleado($empleado){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("INSERT INTO empleados (usuario_id, area_id, nombre, fecha_inicio)
        VALUES (:usario_id, :area_id, :nombre, :fecha_inicio);");
        $query->bindValue(':usario_id', $empleado->usuario_id);
        $query->bindValue(':area_id', $empleado->area_id);
        $query->bindValue(':nombre', $empleado->nombre);
        $query->bindValue(':fecha_inicio', $empleado->fecha_inicio);
        try {
            $query->execute();
        } catch (Error $error) {
            echo $error->getMessage();
        }
        
        return $objDataAccess->obtenerUltimoId();
    }

    
    public static function getEmpleadoPorId($id){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("SELECT * FROM `empleados` WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        
        return $query->fetchObject('Empleado');
    }

    public static function getTodosEmpleados(){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("SELECT * FROM `empleados`");
        $query->execute();
        $empleados = $query->fetchAll(PDO::FETCH_CLASS, 'Empleado');

        return $empleados;
    }

    public static function borrarEmpleado($id){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("DELETE FROM `empleados` WHERE id = :id");
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        return $query;
    }


    public static function actualizarEmpleado($empleado){
        $objDataAccess = AccesoDatos::obtenerInstancia();
        $query = $objDataAccess->prepararConsulta("UPDATE `empleados` SET usuario_id = :usuario_id, area_id = :area_id, nombre = :nombre WHERE id = :id");
        $query->bindValue(':usario_id', $empleado->usuario_id);
        $query->bindValue(':area_id', $empleado->area_id);
        $query->bindValue(':nombre', $empleado->nombre);
        $query->bindValue(':id', $empleado->id);
        $query->execute();

        return $query;
    }

 }
