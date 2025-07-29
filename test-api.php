<?php

/**
 * Script para testar a API de estoque
 */

echo "🧪 Testando API de Estoque\n\n";

// Simular requisição GET
$url = 'http://localhost:8081/backend/api/estoque.php';

echo "📡 Fazendo requisição GET para: $url\n\n";

$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => 'Content-Type: application/json'
    ]
]);

$response = file_get_contents($url, false, $context);

if ($response === false) {
    echo "❌ Erro ao fazer requisição\n";
    echo "Verifique se o servidor está rodando em http://localhost:8081\n";
} else {
    echo "✅ Resposta recebida:\n";
    $data = json_decode($response, true);
    
    if ($data && isset($data['status']) && $data['status'] === 'success') {
        echo "Status: " . $data['status'] . "\n";
        echo "Dados encontrados: " . count($data['data']) . " itens\n\n";
        
        foreach ($data['data'] as $index => $item) {
            echo "Item " . ($index + 1) . ":\n";
            echo "  ID: " . $item['componente_id'] . "\n";
            echo "  Componente: '" . $item['componente'] . "'\n";
            echo "  Quantidade: " . $item['quantidade'] . "\n";
            echo "  Encoding OK: " . (mb_check_encoding($item['componente'], 'UTF-8') ? '✅' : '❌') . "\n\n";
        }
    } else {
        echo "❌ Erro na resposta:\n";
        echo $response . "\n";
    }
}

echo "🔍 Teste concluído!\n";
?> 