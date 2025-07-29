<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once __DIR__ . '/../controller/EstoqueController.php';

// Instanciar controller
$controller = new EstoqueController();

// Obter método HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Obter dados da requisição
$data = null;
if ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
}

// Processar requisição através do controller
$response = $controller->handleRequest($method, $data);

// Retornar resposta JSON
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?> 