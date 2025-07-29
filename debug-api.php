<?php
/**
 * Script para debugar a API de estoque
 */

echo "=== DEBUG API ESTOQUE ===\n\n";

// Teste 1: Verificar se o arquivo existe
echo "1. Verificando arquivo da API:\n";
$api_file = __DIR__ . '/src/backend/api/estoque.php';
if (file_exists($api_file)) {
    echo "✓ Arquivo existe: $api_file\n";
} else {
    echo "❌ Arquivo não encontrado: $api_file\n";
    exit;
}

// Teste 2: Verificar se os includes funcionam
echo "\n2. Testando includes:\n";
try {
    require_once __DIR__ . '/src/backend/controllers/EstoqueController.php';
    echo "✓ EstoqueController carregado\n";
    
    require_once __DIR__ . '/src/backend/config/Container.php';
    echo "✓ Container carregado\n";
    
    require_once __DIR__ . '/src/backend/services/EstoqueService.php';
    echo "✓ EstoqueService carregado\n";
    
} catch (Exception $e) {
    echo "❌ Erro ao carregar: " . $e->getMessage() . "\n";
    exit;
}

// Teste 3: Testar a API diretamente
echo "\n3. Testando API diretamente:\n";
try {
    // Simular requisição GET
    $_SERVER['REQUEST_METHOD'] = 'GET';
    
    // Capturar output
    ob_start();
    include $api_file;
    $output = ob_get_clean();
    
    echo "Output da API:\n$output\n";
    
    // Verificar se é JSON válido
    $json = json_decode($output, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "✓ JSON válido\n";
        if (isset($json['status'])) {
            echo "Status: " . $json['status'] . "\n";
        }
        if (isset($json['data'])) {
            echo "Dados: " . count($json['data']) . " itens\n";
        }
    } else {
        echo "❌ JSON inválido: " . json_last_error_msg() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro ao executar API: " . $e->getMessage() . "\n";
}

echo "\n=== FIM DO DEBUG ===\n";
?> 