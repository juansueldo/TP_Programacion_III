<?php
    require('./fpdf185/fpdf.php');
    class Encuesta{
        public $id;
        public $nro_pedido;
        public $mesa_puntaje;
        public $restaurante_puntaje;
        public  $mozo_puntaje;
        public $cocinero_puntaje;
        public $promedio;
        public $comentarios;

        public function __construct() {}

        public static function crearEncuesta($nro_pedido, $mesa_puntaje, $restaurante_puntaje, $mozo_puntaje, $cocinero_puntaje, $comentarios){
            $encuesta = new Encuesta();

            $encuesta->nro_pedido = $nro_pedido;
            $encuesta->mesa_puntaje = $mesa_puntaje;
            $encuesta->restaurante_puntaje = $restaurante_puntaje;
            $encuesta->mozo_puntaje = $mozo_puntaje;
            $encuesta->cocinero_puntaje = $cocinero_puntaje;
            $encuesta->setPromedio();
            $encuesta->comentarios = $comentarios;

            return $encuesta;
        }

        public static function DownloadPdf($directory, $amountPolls){
            $polls = self::getMejorPromedio($amountPolls);
            if ($polls) {
                if(!file_exists($directory)){
                    mkdir($directory, 0777, true);
                }
    
    
                $pdf = new FPDF();
                $pdf->AddPage();
    
                // Letter type size
                $pdf->SetFont('Arial', 'B', 25);
    
                // Main title of the pdf
                $pdf->Cell(160, 15, 'La Comanda BAR', 1, 3, 'L');
                $pdf->Ln(3);
    
                $pdf->SetFont('Arial', '', 15);
    
                // Secondary title of the pdf
                $pdf->Cell(60, 4, 'TP Programacion III', 0, 1, 'L');
                $pdf->Cell(60, 0, '', 'T');
                $pdf->Ln(3);
                
                // Title of the table
                $pdf->Cell(60, 4, 'Juan Sueldo', 0, 1, 'L');
                $pdf->Cell(40, 0, '', 'T');
                $pdf->Ln(5);
    
                // Columns of Poll Class
                $header = array('ID', 'PEDIDO', 'MESA', 'RESTO', 'MOZO', 'COCINERO', 'PROMEDIO', 'COMENTARIOS');
                
                // RGB colors of the table
                $pdf->SetFillColor(236, 98, 0);
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetDrawColor(50, 0, 0);
                $pdf->SetLineWidth(.3);
                $pdf->SetFont('Arial', 'B', 8);
                $w = array(10, 12, 15, 15, 15, 15, 15, 92);
                
                // Writes the header of the columns except the last one
                for ($i = 0; $i < count($header); $i++) {
                    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
                }
                $pdf->Ln();
    
                // Set the color of the text
                $pdf->SetFillColor(215, 209, 235);
                $pdf->SetTextColor(0);
                $pdf->SetFont('');
                // Data
                $fill = false;
    
                foreach ($polls as $encuesta) {
                    //* Every column except the last one
                    $pdf->Cell($w[0], 6, $encuesta->id, 'LR', 0, 'C', $fill);
                    $pdf->Cell($w[1], 6, $encuesta->nro_pedido, 'LR', 0, 'C', $fill);
                    $pdf->Cell($w[2], 6, $encuesta->mesa_puntaje, 'LR', 0, 'C', $fill);
                    $pdf->Cell($w[3], 6, $encuesta->restaurante_puntaje, 'LR', 0, 'C', $fill);
                    $pdf->Cell($w[4], 6, $encuesta->mozo_puntaje, 'LR', 0, 'C', $fill);
                    $pdf->Cell($w[5], 6, $encuesta->cocinero_puntaje , 'LR', 0, 'C', $fill);
                    $pdf->Cell($w[6], 6, $encuesta->promedio, 'LR', 0, 'C', $fill);
                    $pdf->Cell($w[7], 6, $encuesta->comentarios, 'LR', 0, 'C', $fill);
                    $pdf->Ln();
                    $fill = !$fill;
                }
    
                $pdf->Cell(array_sum($w), 0, '', 'T');
    
                $newFilename = $directory.'Encuesta' . date('Y_m_d_His') .'.pdf';
                $pdf->Output('F', $newFilename, 'I');
    
                $payload = json_encode(array("message" => 'pdf creado ' . $newFilename));
            } else {
                $payload = json_encode(array("error" => 'error al crear el pdf'));
            }
            
            return $payload;
        }

        public function setPromedio() {
            $auxPromedio = 0;
            $arraySum = array($this->mesa_puntaje, $this->restaurante_puntaje, $this->mozo_puntaje, $this->cocinero_puntaje);
            if(count($arraySum) > 0) {
                $auxPromedio = round(array_sum($arraySum) / count($arraySum), 2, PHP_ROUND_HALF_EVEN);
            }
            $this->promedio = $auxPromedio; 
        }
        public static function insertarEncuesta($encuesta){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta('INSERT INTO `encuesta` (nro_pedido, mesa_puntaje, restaurante_puntaje, mozo_puntaje, cocinero_puntaje,promedio,comentarios) 
            VALUES (:nro_pedido, :mesa_puntaje, :restaurante_puntaje, :mozo_puntaje, :cocinero_puntaje,:promedio, :comentarios)');
            $query->bindValue(':nro_pedido', $encuesta->nro_pedido);
            $query->bindValue(':mesa_puntaje', $encuesta->mesa_puntaje);
            $query->bindValue(':restaurante_puntaje', $encuesta->restaurante_puntaje);
            $query->bindValue(':mozo_puntaje', $encuesta->mozo_puntaje);
            $query->bindValue(':cocinero_puntaje', $encuesta->cocinero_puntaje);
            $query->bindValue(':promedio', $encuesta->promedio);
            $query->bindValue(':comentarios', $encuesta->comentarios);
            $query->execute();
    
            return $objDataAccess->obtenerUltimoId();
        }

        public static function getTodas(){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta('SELECT * FROM `encuesta`');
            $query->execute();
    
            return $query->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
        }
    

        public static function getEncuestaPorId($id){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta('SELECT * FROM `encuesta` WHERE id = :id');
            $query->bindParam(':id', $id);
            $query->execute();
    
            return $query->fetchObject('Encuesta');
        }
    
   
        public static function getMejorPromedio($limite){
            $objDataAccess = AccesoDatos::obtenerInstancia();
            $query = $objDataAccess->prepararConsulta(
                'SELECT * FROM `encuesta` 
                ORDER BY promedio DESC 
                LIMIT :limite');
            $query->bindParam(':limite', $limite);
            $query->execute();
    
            return $query->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
        }
    }

?>