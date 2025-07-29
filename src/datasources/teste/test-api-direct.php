<?php
/**
 * Script para testar a API diretamente
 */

echo "=== TESTE DIRETO DA API ===\n\n";

// Teste 1: API de estoque
echo "1. Testando API de estoque:\n";
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
            foreach ($data['data'] as $item) {
                echo "- " . $item['componente'] . ": " . $item['quantidade'] . " unidades\n";
            }
        }
    } else {
        echo "❌ JSON inválido: " . json_last_error_msg() . "\n";
    }
} else {
    echo "❌ Erro HTTP: $httpCode\n";
}

echo "\n=== FIM DO TESTE ===\n";
?> 