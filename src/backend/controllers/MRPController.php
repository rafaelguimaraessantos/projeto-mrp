<?php

require_once __DIR__ . '/../services/MRPService.php';

class MRPController {
    private $mrpService;
    
    public function __construct() {
        $this->mrpService = new MRPService();
    }
    
    /**
     * Calcular necessidades de materiais
     */
    public function calcular($data) {
        try {
            if (!isset($data['bicicletas']) && !isset($data['computadores'])) {
                return [
                    'status' => 'error',
                    'message' => 'Por favor, insira pelo menos uma quantidade para calcular o MRP.'
                ];
            }
            
            $bicicletas = isset($data['bicicletas']) ? (int)$data['bicicletas'] : 0;
            $computadores = isset($data['computadores']) ? (int)$data['computadores'] : 0;
            
            $resultado = $this->mrpService->calcularNecessidades($bicicletas, $computadores);
            
            return [
                'status' => 'success',
                'data' => $resultado
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Processar requisição HTTP
     */
    public function handleRequest($method, $data = null) {
        switch ($method) {
            case 'POST':
                return $this->calcular($data);
                
            default:
                return [
                    'status' => 'error',
                    'message' => 'Método não permitido'
                ];
        }
    }
}
?> 