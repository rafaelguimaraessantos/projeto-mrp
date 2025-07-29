<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Teste simples
echo json_encode([
    'status' => 'success',
    'data' => [
        [
            'id' => 1,
            'componente' => 'Rodas',
            'quantidade' => 10,
            'componente_id' => 1
        ],
        [
            'id' => 2,
            'componente' => 'Quadros',
            'quantidade' => 5,
            'componente_id' => 2
        ],
        [
            'id' => 3,
            'componente' => 'Guidões',
            'quantidade' => 10,
            'componente_id' => 3
        ]
    ]
], JSON_UNESCAPED_UNICODE);
?> 