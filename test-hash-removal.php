<?php
/**
 * Script para testar se o hash foi removido das URLs
 */

echo "=== TESTE DE REMOÇÃO DE HASH ===\n\n";

echo "Para testar se o hash foi removido:\n\n";

echo "1. Acesse: http://localhost:8081\n";
echo "2. Clique em 'Estoque' - URL deve ser: http://localhost:8081/estoque (SEM #)\n";
echo "3. Clique em 'MRP' - URL deve ser: http://localhost:8081/mrp (SEM #)\n";
echo "4. Use o botão voltar/avançar do navegador\n";
echo "5. Digite as URLs diretamente no navegador\n\n";

echo "URLs corretas (sem hash):\n";
echo "- http://localhost:8081/\n";
echo "- http://localhost:8081/estoque\n";
echo "- http://localhost:8081/mrp\n\n";

echo "URLs incorretas (com hash):\n";
echo "- http://localhost:8081/#/estoque\n";
echo "- http://localhost:8081/#/mrp\n\n";

echo "Se ainda aparecer # nas URLs, verifique:\n";
echo "1. Se o angular-route.min.js foi carregado\n";
echo "2. Se o HTML5 mode está configurado\n";
echo "3. Se o base href está definido\n";
echo "4. Se o $locationProvider está configurado\n\n";

echo "=== FIM DO TESTE ===\n";
?> 