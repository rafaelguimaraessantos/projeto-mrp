<?php

require_once '../config/database.php';
require_once 'Estoque.php';
require_once '../utils/EncodingUtils.php';

class MRP {
    private $conn;
    private $estoque;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->estoque = new Estoque();
    }

    // Calcular necessidades de MRP
    public function calcularMRP($bicicletas, $computadores) {
        $resultado = [];
        
        // Buscar receitas dos produtos
        $receitas = $this->getReceitas();
        
        // Calcular para Bicicletas
        if ($bicicletas > 0) {
            $resultado = array_merge($resultado, $this->calcularNecessidades(1, 'Bicicleta', $bicicletas, $receitas));
        }
        
        // Calcular para Computadores
        if ($computadores > 0) {
            $resultado = array_merge($resultado, $this->calcularNecessidades(2, 'Computador', $computadores, $receitas));
        }
        
        return $resultado;
    }

    // Calcular necessidades para um produto específico
    private function calcularNecessidades($produto_id, $nome_produto, $quantidade, $receitas) {
        $necessidades = [];
        
        foreach ($receitas as $receita) {
            if ($receita['produto_id'] == $produto_id) {
                $necessario = $receita['quantidade_necessaria'] * $quantidade;
                $em_estoque = $this->estoque->getByComponente($receita['componente_id']);
                $a_comprar = max(0, $necessario - $em_estoque);
                
                $necessidades[] = [
                    'produto' => $nome_produto,
                    'componente' => EncodingUtils::fixComponentName($receita['componente']),
                    'componente_id' => $receita['componente_id'],
                    'necessario' => $necessario,
                    'em_estoque' => $em_estoque,
                    'a_comprar' => $a_comprar,
                    'status' => $a_comprar > 0 ? '⚠️' : '✅'
                ];
            }
        }
        
        return $necessidades;
    }

    // Buscar receitas dos produtos
    private function getReceitas() {
        $query = "SELECT r.produto_id, r.componente_id, r.quantidade_necessaria, 
                         c.nome as componente, p.nome as produto
                  FROM receitas r
                  INNER JOIN componentes c ON r.componente_id = c.id
                  INNER JOIN produtos p ON r.produto_id = p.id
                  ORDER BY p.id, c.nome";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar produtos disponíveis
    public function getProdutos() {
        $query = "SELECT id, nome, descricao FROM produtos ORDER BY nome";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar componentes disponíveis
    public function getComponentes() {
        $query = "SELECT id, nome, descricao FROM componentes ORDER BY nome";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?> 