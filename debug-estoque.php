<?php

/**
 * Script de debug para verificar dados do estoque
 */

require_once 'src/backend/models/Estoque.php';

echo "🔍 Debug do Estoque\n\n";

$estoque = new Estoque();

echo "📊 Dados brutos do banco:\n";
$stmt = $estoque->getAll();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['componente_id']}, Componente: '{$row['componente']}', Quantidade: {$row['quantidade']}\n";
}

echo "\n📋 Dados com encoding corrigido:\n";
$dados = $estoque->getAllWithEncoding();
foreach ($dados as $item) {
    echo "ID: {$item['componente_id']}, Componente: '{$item['componente']}', Quantidade: {$item['quantidade']}\n";
}

echo "\n🔧 Teste de correção de nomes:\n";
$nomes = ['GuidÃµes', 'Placas-mÃ£e', 'MemÃ³rias RAM', 'Gabinetes', 'Rodas', 'Quadros'];
foreach ($nomes as $nome) {
    $corrigido = EncodingUtils::fixComponentName($nome);
    echo "'$nome' -> '$corrigido'\n";
}

echo "\n✅ Debug concluído!\n";
?> 