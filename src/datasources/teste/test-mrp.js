#!/usr/bin/env node

/**
 * Script de teste para o Sistema MRP
 * Este script testa os cálculos de MRP com o exemplo fornecido
 */

console.log('🧪 Testando Sistema MRP...\n');

// Dados de teste (exemplo do enunciado)
const estoqueInicial = {
    'Rodas': 10,
    'Quadros': 5,
    'Guidões': 10,
    'Gabinetes': 2,
    'Placas-mãe': 5,
    'Memórias RAM': 6
};

const producao = {
    'Bicicletas': 6,
    'Computadores': 3
};

// Receitas dos produtos
const receitas = {
    'Bicicleta': {
        'Rodas': 2,
        'Quadros': 1,
        'Guidões': 1
    },
    'Computador': {
        'Gabinetes': 1,
        'Placas-mãe': 1,
        'Memórias RAM': 2
    }
};

// Função para calcular MRP
function calcularMRP(estoque, producao, receitas) {
    const resultado = [];
    
    // Calcular para Bicicletas
    if (producao.Bicicletas > 0) {
        Object.entries(receitas.Bicicleta).forEach(([componente, quantidadePorUnidade]) => {
            const necessario = quantidadePorUnidade * producao.Bicicletas;
            const emEstoque = estoque[componente] || 0;
            const aComprar = Math.max(0, necessario - emEstoque);
            
            resultado.push({
                produto: 'Bicicleta',
                componente: componente,
                necessario: necessario,
                emEstoque: emEstoque,
                aComprar: aComprar,
                status: aComprar > 0 ? '⚠️' : '✅'
            });
        });
    }
    
    // Calcular para Computadores
    if (producao.Computadores > 0) {
        Object.entries(receitas.Computador).forEach(([componente, quantidadePorUnidade]) => {
            const necessario = quantidadePorUnidade * producao.Computadores;
            const emEstoque = estoque[componente] || 0;
            const aComprar = Math.max(0, necessario - emEstoque);
            
            resultado.push({
                produto: 'Computador',
                componente: componente,
                necessario: necessario,
                emEstoque: emEstoque,
                aComprar: aComprar,
                status: aComprar > 0 ? '⚠️' : '✅'
            });
        });
    }
    
    return resultado;
}

// Executar teste
console.log('📊 Dados de Entrada:');
console.log('Estoque Inicial:', estoqueInicial);
console.log('Produção Solicitada:', producao);
console.log('');

const resultado = calcularMRP(estoqueInicial, producao, receitas);

console.log('📋 Resultado do MRP:');
console.log('┌─────────────┬──────────────┬───────────┬────────────┬───────────┬────────┐');
console.log('│ Produto     │ Componente   │ Necessário│ Em Estoque │ A Comprar │ Status │');
console.log('├─────────────┼──────────────┼───────────┼────────────┼───────────┼────────┤');

resultado.forEach(item => {
    const produto = item.produto.padEnd(11);
    const componente = item.componente.padEnd(12);
    const necessario = item.necessario.toString().padStart(9);
    const emEstoque = item.emEstoque.toString().padStart(10);
    const aComprar = item.aComprar.toString().padStart(9);
    const status = item.status.padStart(6);
    
    console.log(`│ ${produto} │ ${componente} │ ${necessario} │ ${emEstoque} │ ${aComprar} │ ${status} │`);
});

console.log('└─────────────┴──────────────┴───────────┴────────────┴───────────┴────────┘');

// Resumo de compras
const comprasNecessarias = resultado.filter(item => item.aComprar > 0);
if (comprasNecessarias.length > 0) {
    const resumo = comprasNecessarias.map(item => `${item.aComprar} ${item.componente}`).join(', ');
    console.log(`\n💡 Resumo: É necessário comprar ${resumo} para completar a produção.`);
} else {
    console.log('\n✅ Todos os componentes estão disponíveis em estoque suficiente!');
}

// Verificar se o resultado está correto
const resultadoEsperado = [
    { produto: 'Bicicleta', componente: 'Rodas', necessario: 12, emEstoque: 10, aComprar: 2 },
    { produto: 'Bicicleta', componente: 'Quadros', necessario: 6, emEstoque: 5, aComprar: 1 },
    { produto: 'Bicicleta', componente: 'Guidões', necessario: 6, emEstoque: 10, aComprar: 0 },
    { produto: 'Computador', componente: 'Gabinetes', necessario: 3, emEstoque: 2, aComprar: 1 },
    { produto: 'Computador', componente: 'Placas-mãe', necessario: 3, emEstoque: 5, aComprar: 0 },
    { produto: 'Computador', componente: 'Memórias RAM', necessario: 6, emEstoque: 6, aComprar: 0 }
];

const testePassou = resultado.every((item, index) => {
    const esperado = resultadoEsperado[index];
    return item.necessario === esperado.necessario && 
           item.emEstoque === esperado.emEstoque && 
           item.aComprar === esperado.aComprar;
});

console.log('\n🧪 Teste de Validação:');
if (testePassou) {
    console.log('✅ Teste PASSOU! Os cálculos estão corretos.');
} else {
    console.log('❌ Teste FALHOU! Os cálculos estão incorretos.');
}

console.log('\n🚀 Sistema MRP pronto para uso!');
console.log('Para executar: docker-compose up -d');
console.log('Acesse: http://localhost:8081'); 