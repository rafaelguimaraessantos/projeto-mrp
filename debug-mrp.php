<?php
/**
 * Script para debugar o MRP
 */

// Ativar exibição de erros
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "=== DEBUG MRP ===\n\n";

try {
    // Testar carregamento dos arquivos
    echo "1. Testando carregamento de arquivos...\n";
    
    require_once __DIR__ . '/src/backend/config/Container.php';
    echo "✓ Container carregado\n";
    
    require_once __DIR__ . '/src/backend/services/EstoqueService.php';
    echo "✓ EstoqueService carregado\n";
    
    require_once __DIR__ . '/src/backend/services/MRPService.php';
    echo "✓ MRPService carregado\n";
    
    require_once __DIR__ . '/src/backend/controllers/MRPController.php';
    echo "✓ MRPController carregado\n";
    
    // Testar criação do controller
    echo "\n2. Testando criação do controller...\n";
    $controller = new MRPController();
    echo "✓ Controller criado\n";
    
    // Testar cálculo MRP
    echo "\n3. Testando cálculo MRP...\n";
    $data = [
        'bicicletas' => 2,
        'computadores' => 1
    ];
    
    $response = $controller->calcular($data);
    echo "✓ Cálculo realizado\n";
    echo "Status: " . $response['status'] . "\n";
    
    if (isset($response['data'])) {
        echo "Dados: " . count($response['data']) . " itens\n";
        foreach ($response['data'] as $item) {
            echo "- " . $item['componente'] . ": " . $item['necessario'] . " necessário\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== FIM DO DEBUG ===\n";
?> 