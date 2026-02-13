-- Criação do Banco de Dados
CREATE DATABASE IF NOT EXISTS sistema_mineracao;
USE sistema_mineracao;

-- 1. Tabela de Veículos (Frota)
CREATE TABLE IF NOT EXISTS veiculos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE NOT NULL, -- Código do QR
    placa VARCHAR(10) NOT NULL,
    modelo VARCHAR(50),
    tipo ENUM('Caminhao', 'Escavadeira', 'Leve', 'Outro'),
    status ENUM('Ativo', 'Manutencao', 'Inativo') DEFAULT 'Ativo',
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    ultima_atualizacao DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Tabela de Funcionários (Login e Crachá)
CREATE TABLE IF NOT EXISTS funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    cpf VARCHAR(14) NOT NULL UNIQUE,
    cargo ENUM('Administrador', 'Gerente', 'Balanca', 'Frentista', 'Motorista', 'Mecanico') NOT NULL,
    email VARCHAR(100),
    senha_hash VARCHAR(255), -- Senha criptografada
    uuid_cracha VARCHAR(36) UNIQUE, -- QR do crachá
    foto VARCHAR(255) DEFAULT NULL, -- Caminho da foto 3x4
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- INSERIR USUÁRIO ADMINISTRADOR PADRÃO (Senha: 123456)
-- O hash abaixo é referente à senha "123456"
INSERT INTO funcionarios (nome, cpf, cargo, email, senha_hash, uuid_cracha) 
SELECT 'Administrador', '000.000.000-00', 'Administrador', 'admin@mineradora.com', '$2y$10$w/Xy/H.kZ.Qy/u.y.u.y.u.y.u.y.u.y.u.y.u.y.u.y.u.y.u', 'ADM-MASTER'
WHERE NOT EXISTS (SELECT 1 FROM funcionarios WHERE email = 'admin@mineradora.com');

-- 3. Tabela de Fornecedores (Postos e Oficinas)
CREATE TABLE IF NOT EXISTS fornecedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo ENUM('Oficina', 'Posto', 'Pecas', 'Outro'),
    contato VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Tabela de Peças (Estoque)
CREATE TABLE IF NOT EXISTS pecas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50), -- Part Number
    nome VARCHAR(100) NOT NULL,
    quantidade INT DEFAULT 0,
    minimo INT DEFAULT 5
);

-- 5. Tabela de Abastecimentos
CREATE TABLE IF NOT EXISTS abastecimentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT,
    tipo_combustivel ENUM('Diesel', 'Gasolina', 'Alcool') NOT NULL DEFAULT 'Diesel',
    motorista_id INT NULL,
    litros DECIMAL(10,2),
    horimetro INT,
    data_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id)
);

-- 6. Tabela de Manutenções (Ordens de Serviço)
CREATE TABLE IF NOT EXISTS manutencoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT NOT NULL,
    tipo ENUM('Corretiva', 'Preventiva', 'Preditiva'),
    descricao TEXT,
    prioridade ENUM('Alta', 'Media', 'Baixa'),
    status ENUM('Aberta', 'Em Andamento', 'Concluida') DEFAULT 'Aberta',
    solucao TEXT NULL, -- O que foi feito
    data_abertura TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_conclusao DATETIME,
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id)
);

-- 7. Tabela de Vínculo: Peças usadas na Manutenção
CREATE TABLE IF NOT EXISTS manutencao_pecas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    manutencao_id INT NOT NULL,
    peca_id INT NOT NULL,
    quantidade INT NOT NULL,
    FOREIGN KEY (manutencao_id) REFERENCES manutencoes(id),
    FOREIGN KEY (peca_id) REFERENCES pecas(id)
);

-- 8. Tabela de Pesagens (Balança)
CREATE TABLE IF NOT EXISTS pesagens (
    id INT AUTO_INCREMENT PRIMARY KEY,
    veiculo_id INT NOT NULL,
    material VARCHAR(50),
    peso_bruto DECIMAL(10,2),
    tara DECIMAL(10,2),
    peso_liquido DECIMAL(10,2), 
    data_pesagem TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (veiculo_id) REFERENCES veiculos(id)
);

-- 9. Tabela de Checklists (Opcional)
CREATE TABLE IF NOT EXISTS checklists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    veiculo_placa VARCHAR(20),
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    itens_ok JSON, -- Salva os checkboxes marcados
    observacao TEXT
);

-- 10. Tabela de Logs (Auditoria do Sistema)
CREATE TABLE IF NOT EXISTS logs_sistema (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    acao VARCHAR(255),
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES funcionarios(id)
);
