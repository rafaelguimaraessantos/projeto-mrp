<?php
/**
 * Script para testar a API de MRP
 */

echo "=== TESTE DA API MRP ===\n\n";

// URL da API
$url = 'http://localhost:8081/mrp';

// Teste 1: Calcular MRP com dados válidos
echo "1. Testando cálculo MRP:\n";
$data = [
    'bicicletas' => 2,
    'computadores' => 1
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status HTTP: $httpCode\n";
echo "Resposta bruta: " . $response . "\n\n";

// Decodificar JSON para verificar se é válido
$decoded = json_decode($response, true);
if (json_last_error() === JSON_ERROR_NONE) {
    echo "JSON válido!\n";
    if (isset($decoded['status'])) {
        echo "Status: " . $decoded['status'] . "\n";
        if (isset($decoded['data'])) {
            echo "Dados encontrados: " . count($decoded['data']) . " itens\n";
            foreach ($decoded['data'] as $item) {
                echo "- " . $item['componente'] . ": " . $item['necessario'] . " necessário, " . $item['a_comprar'] . " a comprar\n";
            }
        }
    }
} else {
    echo "Erro no JSON: " . json_last_error_msg() . "\n";
}

echo "\n=== FIM DO TESTE ===\n";
?> 