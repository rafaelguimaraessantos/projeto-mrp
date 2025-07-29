-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS mrp_db;
USE mrp_db;

-- Tabela de componentes
CREATE TABLE componentes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de estoque
CREATE TABLE estoque (
    id INT AUTO_INCREMENT PRIMARY KEY,
    componente_id INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (componente_id) REFERENCES componentes(id) ON DELETE CASCADE
);

-- Tabela de produtos
CREATE TABLE produtos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de receitas (componentes necessários para cada produto)
CREATE TABLE receitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    produto_id INT NOT NULL,
    componente_id INT NOT NULL,
    quantidade_necessaria INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (produto_id) REFERENCES produtos(id) ON DELETE CASCADE,
    FOREIGN KEY (componente_id) REFERENCES componentes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_produto_componente (produto_id, componente_id)
);

-- Inserir dados iniciais
INSERT INTO componentes (nome, descricao) VALUES
('Rodas', 'Rodas para bicicleta'),
('Quadros', 'Quadros para bicicleta'),
('Guidões', 'Guidões para bicicleta'),
('Gabinetes', 'Gabinetes para computador'),
('Placas-mãe', 'Placas-mãe para computador'),
('Memórias RAM', 'Pentes de memória RAM para computador');

INSERT INTO produtos (nome, descricao) VALUES
('Bicicleta', 'Bicicleta completa'),
('Computador', 'Computador completo');

-- Inserir receitas (componentes necessários para cada produto)
INSERT INTO receitas (produto_id, componente_id, quantidade_necessaria) VALUES
-- Bicicleta (1 unidade requer: 2 rodas, 1 quadro, 1 guidão)
(1, 1, 2), -- 2 rodas
(1, 2, 1), -- 1 quadro
(1, 3, 1), -- 1 guidão

-- Computador (1 unidade requer: 1 gabinete, 1 placa-mãe, 2 memórias RAM)
(2, 4, 1), -- 1 gabinete
(2, 5, 1), -- 1 placa-mãe
(2, 6, 2); -- 2 memórias RAM

-- Inserir estoque inicial (dados de exemplo)
INSERT INTO estoque (componente_id, quantidade) VALUES
(1, 10), -- 10 rodas
(2, 5),  -- 5 quadros
(3, 10), -- 10 guidões
(4, 2),  -- 2 gabinetes
(5, 5),  -- 5 placas-mãe
(6, 6);  -- 6 memórias RAM 