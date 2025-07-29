<?php
/**
 * Script para testar a API com método GET simulado
 */

echo "=== TESTE API COM GET ===\n\n";

// Simular variáveis de ambiente para uma requisição GET
$_SERVER['REQUEST_METHOD'] = 'GET';

// Incluir o arquivo da API
require_once __DIR__ . '/backend/api/estoque.php';

echo "\n=== FIM DO TESTE ===\n";
?> 