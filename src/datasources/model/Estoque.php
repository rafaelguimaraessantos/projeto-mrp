<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/EncodingUtils.php';

class Estoque {
    private $conn;
    private $table_name = "estoque";

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Buscar todo o estoque
    public function getAll() {
        $query = "SELECT e.id, c.nome as componente, e.quantidade, c.id as componente_id
                  FROM " . $this->table_name . " e
                  INNER JOIN componentes c ON e.componente_id = c.id
                  ORDER BY c.nome";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    // Buscar todo o estoque com encoding corrigido
    public function getAllWithEncoding() {
        $stmt = $this->getAll();
        $result = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row['componente'] = EncodingUtils::fixComponentName($row['componente']);
            $result[] = $row;
        }
        
        return $result;
    }

    // Atualizar quantidade de um componente
    public function update($componente_id, $quantidade) {
        $query = "UPDATE " . $this->table_name . " 
                  SET quantidade = :quantidade 
                  WHERE componente_id = :componente_id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":quantidade", $quantidade);
        $stmt->bindParam(":componente_id", $componente_id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Buscar quantidade de um componente específico
    public function getByComponente($componente_id) {
        $query = "SELECT quantidade FROM " . $this->table_name . " 
                  WHERE componente_id = :componente_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":componente_id", $componente_id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['quantidade'] : 0;
    }

    // Inserir novo componente no estoque
    public function create($componente_id, $quantidade) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (componente_id, quantidade) VALUES (:componente_id, :quantidade)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":componente_id", $componente_id);
        $stmt->bindParam(":quantidade", $quantidade);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Excluir item do estoque
    public function delete($componente_id) {
        $query = "DELETE FROM " . $this->table_name . " 
                  WHERE componente_id = :componente_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":componente_id", $componente_id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?> 