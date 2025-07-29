<?php

require_once __DIR__ . '/../models/MRP.php';
require_once __DIR__ . '/EstoqueService.php';

class MRPService {
    private $mrp;
    private $estoqueService;
    
    public function __construct() {
        $this->mrp = new MRP();
        $this->estoqueService = new EstoqueService();
    }
    
    /**
     * Calcular necessidades de materiais para produção
     */
    public function calcularNecessidades($bicicletas, $computadores) {
        try {
            // Validações de negócio
            if (!$this->validarQuantidades($bicicletas, $computadores)) {
                throw new Exception('Quantidades devem ser números positivos');
            }
            
            if ($bicicletas == 0 && $computadores == 0) {
                throw new Exception('Por favor, insira pelo menos uma quantidade para calcular o MRP.');
            }
            
            // Calcular necessidades usando o model
            $resultado = $this->mrp->calcularMRP($bicicletas, $computadores);
            
            // Enriquecer resultado com informações de disponibilidade
            $resultado = $this->enriquecerResultadoComDisponibilidade($resultado);
            
            return $resultado;
        } catch (Exception $e) {
            throw new Exception('Erro ao calcular MRP: ' . $e->getMessage());
        }
    }
    
    /**
     * Enriquecer resultado com informações de disponibilidade
     */
    private function enriquecerResultadoComDisponibilidade($resultado) {
        foreach ($resultado as &$item) {
            // O model já retorna 'em_estoque' e 'a_comprar', então vamos usar esses valores
            // e apenas adicionar informações extras se necessário
            $item['disponivel'] = $item['a_comprar'] == 0;
            $item['status'] = $item['a_comprar'] == 0 ? 'Disponível' : 'Insuficiente';
        }
        
        return $resultado;
    }
    
    /**
     * Obter produtos disponíveis
     */
    public function getProdutos() {
        try {
            return $this->mrp->getProdutos();
        } catch (Exception $e) {
            throw new Exception('Erro ao buscar produtos: ' . $e->getMessage());
        }
    }
    
    /**
     * Obter componentes disponíveis
     */
    public function getComponentes() {
        try {
            return $this->mrp->getComponentes();
        } catch (Exception $e) {
            throw new Exception('Erro ao buscar componentes: ' . $e->getMessage());
        }
    }
    
    /**
     * Validar quantidades de produção
     */
    private function validarQuantidades($bicicletas, $computadores) {
        return is_numeric($bicicletas) && is_numeric($computadores) && 
               $bicicletas >= 0 && $computadores >= 0;
    }
    
    /**
     * Gerar relatório de MRP
     */
    public function gerarRelatorio($bicicletas, $computadores) {
        try {
            $necessidades = $this->calcularNecessidades($bicicletas, $computadores);
            
            $relatorio = [
                'data_calculo' => date('Y-m-d H:i:s'),
                'quantidades_producao' => [
                    'bicicletas' => $bicicletas,
                    'computadores' => $computadores
                ],
                'necessidades' => $necessidades,
                'resumo' => $this->gerarResumoCompras($necessidades)
            ];
            
            return $relatorio;
        } catch (Exception $e) {
            throw new Exception('Erro ao gerar relatório: ' . $e->getMessage());
        }
    }
    
    /**
     * Gerar resumo de compras necessárias
     */
    private function gerarResumoCompras($necessidades) {
        $compras = [];
        $total_compras = 0;
        
        foreach ($necessidades as $item) {
            if ($item['a_comprar'] > 0) {
                $compras[] = $item['a_comprar'] . ' ' . $item['componente'];
                $total_compras += $item['a_comprar'];
            }
        }
        
        return [
            'itens_para_comprar' => $compras,
            'total_itens' => count($compras),
            'total_quantidade' => $total_compras,
            'mensagem' => count($compras) > 0 
                ? 'É necessário comprar: ' . implode(', ', $compras) . ' para completar a produção.'
                : 'Todos os componentes estão disponíveis em estoque suficiente!'
        ];
    }
}
?> 