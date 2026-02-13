CREATE DATABASE sistema_mineracao;
USE sistema_mineracao;

-- Tabela de Usuários (Admin, Gerente, Operacional)
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    senha VARCHAR(255), -- Hash
    perfil ENUM('admin', 'operador') DEFAULT 'operador',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Usuário Padrão (Senha: 123456)
INSERT INTO usuarios (nome, email, senha, perfil) 
VALUES ('Administrador', 'admin@mineradora.com', '$2y$10$w/Xy/H.kZ.Qy/u.y.u.y.u.y.u.y.u.y.u.y.u.y.u.y.u.y.u', 'admin');

-- Tabela de Veículos
CREATE TABLE veiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL, -- Código do QR
    placa VARCHAR(10) NOT NULL,
    modelo VARCHAR(50),
    tipo VARCHAR(20),
    status ENUM('Ativo', 'Manutencao') DEFAULT 'Ativo'
);

-- Tabela de Abastecimentos (Fato)
CREATE TABLE abastecimentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT,
    litros DECIMAL(10,2),
    horimetro INT,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id)
);

-- Tabela de Manutenções
CREATE TABLE manutencoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT NOT NULL,
    tipo ENUM('Corretiva', 'Preventiva', 'Preditiva'),
    descricao TEXT,
    prioridade ENUM('Alta', 'Media', 'Baixa'),
    status ENUM('Aberta', 'Em Andamento', 'Concluida') DEFAULT 'Aberta',
    data_abertura TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_conclusao DATETIME,
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id)
);

-- Tabela de Pesagens (Balança)
CREATE TABLE pesagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT NOT NULL,
    material VARCHAR(50),
    peso_bruto DECIMAL(10,2),
    tara DECIMAL(10,2),
    peso_liquido DECIMAL(10,2) GENERATED ALWAYS AS (peso_bruto - tara) STORED,
    data_pesagem TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id)
);

-- 1. Tabela de Peças para a Oficina
CREATE TABLE pecas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    quantidade INT DEFAULT 0,
    minimo INT DEFAULT 5 -- Alerta se baixar disso
);

-- 2. Adicionar suporte a GPS na tabela de veículos
ALTER TABLE veiculos 
ADD COLUMN latitude DECIMAL(10, 8) NULL,
ADD COLUMN longitude DECIMAL(11, 8) NULL,
ADD COLUMN ultima_atualizacao DATETIME NULL;

-- 3. Tabela de Logs (Opcional, para auditoria futura)
CREATE TABLE logs_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    acao VARCHAR(255),
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Fornecedores
CREATE TABLE fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo ENUM('Oficina', 'Posto', 'Pecas', 'Outro'),
    contato VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela Simples de Checklist (Opcional, se quiser salvar histórico)
CREATE TABLE checklists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    veiculo_placa VARCHAR(20),
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    itens_ok JSON, -- Salva os checkboxes marcados
    observacao TEXT
);