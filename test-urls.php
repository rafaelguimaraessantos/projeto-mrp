<?php
/**
 * Script para testar as URLs /estoque e /mrp
 */

echo "=== TESTE DAS URLs ===\n\n";

// Teste 1: URL /estoque
echo "1. Testando URL /estoque:\n";
$url_estoque = 'http://localhost:8081/estoque';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_estoque);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status HTTP: $httpCode\n";
if ($httpCode == 200) {
    echo "✓ URL /estoque funcionando!\n";
    $data = json_decode($response, true);
    if (isset($data['data'])) {
        echo "Dados recebidos: " . count($data['data']) . " itens\n";
    }
} else {
    echo "❌ Erro na URL /estoque\n";
}
echo "\n";

// Teste 2: URL /mrp
echo "2. Testando URL /mrp:\n";
$url_mrp = 'http://localhost:8081/mrp';

$data = [
    'bicicletas' => 2,
    'computadores' => 1
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url_mrp);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status HTTP: $httpCode\n";
if ($httpCode == 200) {
    echo "✓ URL /mrp funcionando!\n";
    $data = json_decode($response, true);
    if (isset($data['data'])) {
        echo "Dados recebidos: " . count($data['data']) . " itens\n";
    }
} else {
    echo "❌ Erro na URL /mrp\n";
}

echo "\n=== INSTRUÇÕES ===\n";
echo "1. Acesse: http://localhost:8081/estoque\n";
echo "2. Acesse: http://localhost:8081/mrp\n";
echo "3. Teste o sistema pela interface principal: http://localhost:8081\n";
echo "\n=== FIM DO TESTE ===\n";
?> 