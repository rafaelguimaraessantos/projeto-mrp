<?php

/**
 * Script de teste para verificar encoding
 */

require_once 'src/backend/utils/EncodingUtils.php';

echo "🧪 Testando Encoding...\n\n";

// Teste com strings problemáticas
$testStrings = [
    'GuidÃµes' => 'Guidões',
    'Placas-mÃ£e' => 'Placas-mãe',
    'MemÃ³rias RAM' => 'Memórias RAM',
    'Rodas' => 'Rodas',
    'Quadros' => 'Quadros',
    'Gabinetes' => 'Gabinetes'
];

echo "📋 Testando correção de nomes de componentes:\n";
foreach ($testStrings as $problematic => $expected) {
    $fixed = EncodingUtils::fixComponentName($problematic);
    $status = ($fixed === $expected) ? '✅' : '❌';
    echo "$status '$problematic' -> '$fixed' (esperado: '$expected')\n";
}

echo "\n🔍 Testando detecção de problemas de encoding:\n";
foreach ($testStrings as $problematic => $expected) {
    $hasIssues = EncodingUtils::hasEncodingIssues($problematic);
    $status = $hasIssues ? '⚠️' : '✅';
    echo "$status '$problematic' - Tem problemas: " . ($hasIssues ? 'SIM' : 'NÃO') . "\n";
}

echo "\n🛠️ Testando normalização de strings:\n";
$testNormalize = [
    '  Guidões  ' => 'Guidões',
    'Placas-mãe' => 'Placas-mãe',
    'Memórias RAM' => 'Memórias RAM'
];

foreach ($testNormalize as $input => $expected) {
    $normalized = EncodingUtils::normalizeString($input);
    $status = ($normalized === $expected) ? '✅' : '❌';
    echo "$status '$input' -> '$normalized' (esperado: '$expected')\n";
}

echo "\n🚀 Teste de encoding concluído!\n";
echo "Para aplicar as correções, reinicie os containers:\n";
echo "docker-compose down && docker-compose up -d\n";
?> 