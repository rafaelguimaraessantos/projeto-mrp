<?php
/**
 * Script para testar o roteamento
 */

echo "=== TESTE DE ROTEAMENTO ===\n\n";

// Teste 1: URL /estoque
echo "1. Testando URL /estoque:\n";
$url = 'http://localhost:8081/estoque';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status HTTP: $httpCode\n";
echo "Resposta: $response\n\n";

if ($httpCode == 200) {
    $data = json_decode($response, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        echo "✓ JSON válido\n";
        if (isset($data['status'])) {
            echo "Status: " . $data['status'] . "\n";
        }
        if (isset($data['data'])) {
            echo "Dados: " . count($data['data']) . " itens\n";
        }
    } else {
        echo "❌ JSON inválido\n";
    }
} else {
    echo "❌ Erro HTTP: $httpCode\n";
}

echo "\n=== FIM DO TESTE ===\n";
?> 