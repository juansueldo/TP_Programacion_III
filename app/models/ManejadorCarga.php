<?php

require_once './Pedido.php';

class ManejadorCarga{

    private $_directorio_guardado;
    private $_archivo_extension;
    private $_archivo_nombre;
    private $_ruta_guardado;

    public function __construct($directorio, $pedido_id, $array)
    {
        self::crearDirectorio($directorio);
        $this->setDirectorioGuardado($directorio);
        $this->guardarImagen($pedido_id, $array);
    }
    

    public function setDirectorioGuardado($directorio){
        $this->_directorio_guardado = $directorio;
    }

 
    public function setArchivoExtension($extension = 'png'){
        $this->_archivo_extension = $extension;
    }


    public function setAchivoNombre($nuevoNombre){
        $this->_archivo_nombre = $nuevoNombre;
    }

 
    public function setRutaGuardadoArchivo(){
        $this->_ruta_guardado = $this->getDirectorioGuardado().'Pedido'.$this->getAchivoNombre().'.'.$this->getArchivoExtension();
    }
    

    public function getArchivoExtension(){
        return $this->_archivo_extension;
    }


    public function getAchivoNombre(){
        return $this->_archivo_nombre;
    }

    public function getRutaGuardadoArchivo(){
        return $this->_ruta_guardado;
    }

    
    public function getDirectorioGuardado(){
        return $this->_directorio_guardado;
    }

  
    public static function getOrderImageNameExt($archivo, $id){
        $rutaCompleta = $archivo->getRutaGuardadoArchivo();
        return $rutaCompleta;
    }

    private static function crearDirectorio($directorio){
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }
    }

    public function guardarImagen($pedido_id, $array):bool{
        $success = false;
        
        try {
            $this->setAchivoNombre($pedido_id);
            $this->setArchivoExtension();
            $this->setRutaGuardadoArchivo();
            if ($this->moverImagen($array['pedido_foto']['tmp_name'])) {
                $success = true;
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
        }finally{
            return $success;
        }
    }


    public function moverImagen($tmpArchivo){
        return move_uploaded_file($tmpArchivo, $this->getRutaGuardadoArchivo());
    }

}
?>