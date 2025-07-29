<?php
/**
 * Script para testar a conexão com o banco de dados
 */

echo "=== TESTE DE CONEXÃO COM BANCO ===\n\n";

try {
    // Carregar configuração do banco
    require_once __DIR__ . '/src/backend/config/database.php';
    
    echo "1. Testando conexão com banco...\n";
    $database = new Database();
    $conn = $database->getConnection();
    
    if ($conn) {
        echo "✓ Conexão estabelecida\n";
        
        // Testar query simples
        echo "\n2. Testando query...\n";
        $query = "SELECT COUNT(*) as total FROM estoque";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "Total de itens no estoque: " . $result['total'] . "\n";
        
        // Testar query de estoque completo
        echo "\n3. Testando query de estoque completo...\n";
        $query = "SELECT e.id, c.nome as componente, e.quantidade, c.id as componente_id
                  FROM estoque e
                  INNER JOIN componentes c ON e.componente_id = c.id
                  ORDER BY c.nome";
        
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $estoque = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Itens encontrados: " . count($estoque) . "\n";
        foreach ($estoque as $item) {
            echo "- " . $item['componente'] . ": " . $item['quantidade'] . " unidades\n";
        }
        
    } else {
        echo "❌ Falha na conexão\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
}

echo "\n=== FIM DO TESTE ===\n";
?> 