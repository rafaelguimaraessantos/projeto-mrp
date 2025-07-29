<?php
/**
 * Script para testar a navegação e URLs
 */

echo "=== TESTE DE NAVEGAÇÃO ===\n\n";

// URLs para testar
$urls = [
    'http://localhost:8081/',
    'http://localhost:8081/estoque',
    'http://localhost:8081/mrp'
];

foreach ($urls as $url) {
    echo "Testando: $url\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo "Status HTTP: $httpCode\n";
    
    if ($httpCode == 200) {
        // Verificar se é HTML (página principal) ou JSON (API)
        if (strpos($response, '<!DOCTYPE html>') !== false || strpos($response, '<html>') !== false) {
            echo "✓ Página HTML carregada\n";
        } else {
            $json = json_decode($response, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                echo "✓ API JSON respondendo\n";
                if (isset($json['status'])) {
                    echo "Status: " . $json['status'] . "\n";
                }
            } else {
                echo "❌ Resposta inválida\n";
            }
        }
    } else {
        echo "❌ Erro HTTP: $httpCode\n";
    }
    
    echo "\n";
}

echo "=== INSTRUÇÕES PARA TESTE MANUAL ===\n";
echo "1. Acesse: http://localhost:8081\n";
echo "2. Clique em 'Estoque' - URL deve mudar para /estoque\n";
echo "3. Clique em 'MRP' - URL deve mudar para /mrp\n";
echo "4. Use o botão voltar/avançar do navegador\n";
echo "5. Digite as URLs diretamente no navegador\n";
echo "\n=== FIM DO TESTE ===\n";
?> 