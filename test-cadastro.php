<?php
/**
 * Script de teste para funcionalidade de cadastro de estoque
 * 
 * Este script testa a API de cadastro de novos itens no estoque
 */

echo "=== TESTE DE CADASTRO DE ESTOQUE ===\n\n";

// URL da API
$url = 'http://localhost:8081/estoque';

// Teste 1: Tentar cadastrar um componente que já existe
echo "1. Testando cadastro de componente que já existe (deve falhar):\n";
$data = [
    'action' => 'create',
    'componente_id' => 1, // Rodas (já existe no banco)
    'quantidade' => 5
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
echo "Resposta: " . $response . "\n\n";

// Teste 2: Listar estoque atual
echo "2. Listando estoque atual:\n";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status HTTP: $httpCode\n";
$estoque = json_decode($response, true);
if ($estoque['status'] === 'success') {
    echo "Itens no estoque:\n";
    foreach ($estoque['data'] as $item) {
        echo "- {$item['componente']}: {$item['quantidade']} unidades\n";
    }
} else {
    echo "Erro ao listar estoque: " . $response . "\n";
}

echo "\n=== INSTRUÇÕES PARA TESTE MANUAL ===\n";
echo "1. Acesse: http://localhost:8081\n";
echo "2. Vá para a aba 'Estoque'\n";
echo "3. Clique no botão 'Cadastrar Novo Item' no final da tabela\n";
echo "4. Selecione um componente e digite uma quantidade\n";
echo "5. Clique em 'Cadastrar'\n";
echo "6. Verifique se o item aparece na tabela\n\n";

echo "=== NOTAS IMPORTANTES ===\n";
echo "- O sistema só permite cadastrar componentes que ainda não têm estoque\n";
echo "- Se um componente já tem estoque, use a opção 'Editar' para atualizar\n";
echo "- Todos os dados são persistidos no banco MySQL\n";
echo "- O sistema usa UTF-8 para caracteres especiais (acentos)\n";
?> 