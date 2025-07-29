<?php

require_once __DIR__ . '/../models/Estoque.php';

class EstoqueService {
    private $estoque;
    
    public function __construct() {
        $this->estoque = new Estoque();
    }
    
    /**
     * Buscar todo o estoque com encoding corrigido
     */
    public function listarEstoque() {
        try {
            return $this->estoque->getAllWithEncoding();
        } catch (Exception $e) {
            throw new Exception('Erro ao buscar estoque: ' . $e->getMessage());
        }
    }
    
    /**
     * Atualizar quantidade de um componente no estoque
     */
    public function atualizarEstoque($componente_id, $quantidade) {
        try {
            // Validações de negócio
            if (!$this->validarComponente($componente_id)) {
                throw new Exception('Componente não encontrado');
            }
            
            if (!$this->validarQuantidade($quantidade)) {
                throw new Exception('Quantidade deve ser um número positivo');
            }
            
            $success = $this->estoque->update($componente_id, $quantidade);
            
            if (!$success) {
                throw new Exception('Erro ao atualizar estoque no banco de dados');
            }
            
            return true;
        } catch (Exception $e) {
            throw new Exception('Erro ao atualizar estoque: ' . $e->getMessage());
        }
    }
    
    /**
     * Cadastrar novo item no estoque
     */
    public function cadastrarEstoque($componente_id, $quantidade) {
        try {
            // Validações de negócio
            if (!$this->validarComponente($componente_id)) {
                throw new Exception('Componente não encontrado');
            }
            
            if (!$this->validarQuantidade($quantidade)) {
                throw new Exception('Quantidade deve ser um número positivo');
            }
            
            // Verificar se já existe estoque para este componente
            $quantidade_atual = $this->estoque->getByComponente($componente_id);
            
            if ($quantidade_atual > 0) {
                throw new Exception('Este componente já possui estoque cadastrado. Use a opção "Editar" para atualizar.');
            }
            
            $success = $this->estoque->create($componente_id, $quantidade);
            
            if (!$success) {
                throw new Exception('Erro ao cadastrar estoque no banco de dados');
            }
            
            return true;
        } catch (Exception $e) {
            throw new Exception('Erro ao cadastrar estoque: ' . $e->getMessage());
        }
    }
    
    /**
     * Buscar quantidade de um componente específico
     */
    public function buscarQuantidadeComponente($componente_id) {
        try {
            if (!$this->validarComponente($componente_id)) {
                throw new Exception('Componente não encontrado');
            }
            
            return $this->estoque->getByComponente($componente_id);
        } catch (Exception $e) {
            throw new Exception('Erro ao buscar quantidade do componente: ' . $e->getMessage());
        }
    }
    
    /**
     * Validar se o componente existe
     */
    private function validarComponente($componente_id) {
        // Aqui você pode adicionar validação mais robusta
        // Por exemplo, verificar se o componente existe na tabela componentes
        return is_numeric($componente_id) && $componente_id > 0;
    }
    
    /**
     * Validar quantidade
     */
    private function validarQuantidade($quantidade) {
        return is_numeric($quantidade) && $quantidade >= 0;
    }
    
    /**
     * Verificar se há estoque suficiente para um componente
     */
    public function verificarDisponibilidade($componente_id, $quantidade_necessaria) {
        try {
            $quantidade_atual = $this->estoque->getByComponente($componente_id);
            return $quantidade_atual >= $quantidade_necessaria;
        } catch (Exception $e) {
            throw new Exception('Erro ao verificar disponibilidade: ' . $e->getMessage());
        }
    }
    
    /**
     * Calcular quantidade a comprar
     */
    public function calcularQuantidadeComprar($componente_id, $quantidade_necessaria) {
        try {
            $quantidade_atual = $this->estoque->getByComponente($componente_id);
            $quantidade_faltante = $quantidade_necessaria - $quantidade_atual;
            
            return max(0, $quantidade_faltante);
        } catch (Exception $e) {
            throw new Exception('Erro ao calcular quantidade a comprar: ' . $e->getMessage());
        }
    }

    /**
     * Excluir item do estoque
     */
    public function excluirEstoque($componente_id) {
        try {
            // Validações de negócio
            if (!$this->validarComponente($componente_id)) {
                throw new Exception('Componente não encontrado');
            }
            
            // Verificar se existe estoque para este componente
            $quantidade_atual = $this->estoque->getByComponente($componente_id);
            
            if ($quantidade_atual == 0) {
                throw new Exception('Este componente não possui estoque cadastrado.');
            }
            
            $success = $this->estoque->delete($componente_id);
            
            if (!$success) {
                throw new Exception('Erro ao excluir estoque no banco de dados');
            }
            
            return true;
        } catch (Exception $e) {
            throw new Exception('Erro ao excluir estoque: ' . $e->getMessage());
        }
    }
}
?> 