<?php

require_once __DIR__ . '/../service/EstoqueService.php';

class EstoqueController {
    private $estoqueService;
    
    public function __construct() {
        $this->estoqueService = new EstoqueService();
    }
    
    /**
     * Listar todo o estoque
     */
    public function index() {
        try {
            $data = $this->estoqueService->listarEstoque();
            
            return [
                'status' => 'success',
                'data' => $data
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Atualizar estoque existente
     */
    public function update($data) {
        try {
            if (!isset($data['componente_id']) || !isset($data['quantidade'])) {
                return [
                    'status' => 'error',
                    'message' => 'Dados inválidos'
                ];
            }
            
            $this->estoqueService->atualizarEstoque($data['componente_id'], $data['quantidade']);
            
            return [
                'status' => 'success',
                'message' => 'Estoque atualizado com sucesso'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Criar novo item no estoque
     */
    public function create($data) {
        try {
            if (!isset($data['componente_id']) || !isset($data['quantidade'])) {
                return [
                    'status' => 'error',
                    'message' => 'Dados inválidos'
                ];
            }
            
            $this->estoqueService->cadastrarEstoque($data['componente_id'], $data['quantidade']);
            
            return [
                'status' => 'success',
                'message' => 'Estoque cadastrado com sucesso'
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Excluir item do estoque
     */
    public function delete($data) {
        try {
            if (!isset($data['componente_id'])) {
                return [
                    'status' => 'error',
                    'message' => 'ID do componente é obrigatório'
                ];
            }
            
            $this->estoqueService->excluirEstoque($data['componente_id']);
            
            return [
                'status' => 'success',
                'message' => 'Item excluído do estoque com sucesso'
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
            case 'GET':
                return $this->index();
                
            case 'POST':
                if (isset($data['action'])) {
                    switch ($data['action']) {
                        case 'create':
                            return $this->create($data);
                        case 'delete':
                            return $this->delete($data);
                        default:
                            return $this->update($data);
                    }
                } else {
                    return $this->update($data);
                }
                
            default:
                return [
                    'status' => 'error',
                    'message' => 'Método não permitido'
                ];
        }
    }
}
?> 