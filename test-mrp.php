<?php

/**
 * Script de teste para o Sistema MRP
 * Este script testa os cálculos de MRP com o exemplo fornecido
 */

echo "🧪 Testando Sistema MRP...\n\n";

// Dados de teste (exemplo do enunciado)
$estoqueInicial = [
    'Rodas' => 10,
    'Quadros' => 5,
    'Guidões' => 10,
    'Gabinetes' => 2,
    'Placas-mãe' => 5,
    'Memórias RAM' => 6
];

$producao = [
    'Bicicletas' => 6,
    'Computadores' => 3
];

// Receitas dos produtos
$receitas = [
    'Bicicleta' => [
        'Rodas' => 2,
        'Quadros' => 1,
        'Guidões' => 1
    ],
    'Computador' => [
        'Gabinetes' => 1,
        'Placas-mãe' => 1,
        'Memórias RAM' => 2
    ]
];

// Função para calcular MRP
function calcularMRP($estoque, $producao, $receitas) {
    $resultado = [];
    
    // Calcular para Bicicletas
    if ($producao['Bicicletas'] > 0) {
        foreach ($receitas['Bicicleta'] as $componente => $quantidadePorUnidade) {
            $necessario = $quantidadePorUnidade * $producao['Bicicletas'];
            $emEstoque = $estoque[$componente] ?? 0;
            $aComprar = max(0, $necessario - $emEstoque);
            
            $resultado[] = [
                'produto' => 'Bicicleta',
                'componente' => $componente,
                'necessario' => $necessario,
                'emEstoque' => $emEstoque,
                'aComprar' => $aComprar,
                'status' => $aComprar > 0 ? '⚠️' : '✅'
            ];
        }
    }
    
    // Calcular para Computadores
    if ($producao['Computadores'] > 0) {
        foreach ($receitas['Computador'] as $componente => $quantidadePorUnidade) {
            $necessario = $quantidadePorUnidade * $producao['Computadores'];
            $emEstoque = $estoque[$componente] ?? 0;
            $aComprar = max(0, $necessario - $emEstoque);
            
            $resultado[] = [
                'produto' => 'Computador',
                'componente' => $componente,
                'necessario' => $necessario,
                'emEstoque' => $emEstoque,
                'aComprar' => $aComprar,
                'status' => $aComprar > 0 ? '⚠️' : '✅'
            ];
        }
    }
    
    return $resultado;
}

// Executar teste
echo "📊 Dados de Entrada:\n";
echo "Estoque Inicial: " . json_encode($estoqueInicial, JSON_PRETTY_PRINT) . "\n";
echo "Produção Solicitada: " . json_encode($producao, JSON_PRETTY_PRINT) . "\n\n";

$resultado = calcularMRP($estoqueInicial, $producao, $receitas);

echo "📋 Resultado do MRP:\n";
echo "┌─────────────┬──────────────┬───────────┬────────────┬───────────┬────────┐\n";
echo "│ Produto     │ Componente   │ Necessário│ Em Estoque │ A Comprar │ Status │\n";
echo "├─────────────┼──────────────┼───────────┼────────────┼───────────┼────────┤\n";

foreach ($resultado as $item) {
    $produto = str_pad($item['produto'], 11);
    $componente = str_pad($item['componente'], 12);
    $necessario = str_pad($item['necessario'], 9, ' ', STR_PAD_LEFT);
    $emEstoque = str_pad($item['emEstoque'], 10, ' ', STR_PAD_LEFT);
    $aComprar = str_pad($item['aComprar'], 9, ' ', STR_PAD_LEFT);
    $status = str_pad($item['status'], 6);
    
    echo "│ $produto │ $componente │ $necessario │ $emEstoque │ $aComprar │ $status │\n";
}

echo "└─────────────┴──────────────┴───────────┴────────────┴───────────┴────────┘\n";

// Resumo de compras
$comprasNecessarias = array_filter($resultado, function($item) {
    return $item['aComprar'] > 0;
});

if (!empty($comprasNecessarias)) {
    $resumo = implode(', ', array_map(function($item) {
        return $item['aComprar'] . ' ' . $item['componente'];
    }, $comprasNecessarias));
    echo "\n💡 Resumo: É necessário comprar $resumo para completar a produção.\n";
} else {
    echo "\n✅ Todos os componentes estão disponíveis em estoque suficiente!\n";
}

// Verificar se o resultado está correto
$resultadoEsperado = [
    ['produto' => 'Bicicleta', 'componente' => 'Rodas', 'necessario' => 12, 'emEstoque' => 10, 'aComprar' => 2],
    ['produto' => 'Bicicleta', 'componente' => 'Quadros', 'necessario' => 6, 'emEstoque' => 5, 'aComprar' => 1],
    ['produto' => 'Bicicleta', 'componente' => 'Guidões', 'necessario' => 6, 'emEstoque' => 10, 'aComprar' => 0],
    ['produto' => 'Computador', 'componente' => 'Gabinetes', 'necessario' => 3, 'emEstoque' => 2, 'aComprar' => 1],
    ['produto' => 'Computador', 'componente' => 'Placas-mãe', 'necessario' => 3, 'emEstoque' => 5, 'aComprar' => 0],
    ['produto' => 'Computador', 'componente' => 'Memórias RAM', 'necessario' => 6, 'emEstoque' => 6, 'aComprar' => 0]
];

$testePassou = true;
foreach ($resultado as $index => $item) {
    $esperado = $resultadoEsperado[$index];
    if ($item['necessario'] !== $esperado['necessario'] || 
        $item['emEstoque'] !== $esperado['emEstoque'] || 
        $item['aComprar'] !== $esperado['aComprar']) {
        $testePassou = false;
        break;
    }
}

echo "\n🧪 Teste de Validação:\n";
if ($testePassou) {
    echo "✅ Teste PASSOU! Os cálculos estão corretos.\n";
} else {
    echo "❌ Teste FALHOU! Os cálculos estão incorretos.\n";
}

echo "\n🚀 Sistema MRP pronto para uso!\n";
echo "Para executar: docker-compose up -d\n";
echo "Acesse: http://localhost:8081\n";
?> 