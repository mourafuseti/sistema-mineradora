

```markdown
# 🚛 Sistema de Gestão de Mineradora & Logística

![Status](https://img.shields.io/badge/Status-Em_Desenvolvimento-yellow)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-purple)

Sistema web completo para controle de frota, abastecimento, balança rodoviária e manutenção de oficinas, desenvolvido com foco em automação via **QR Code**.

---

## 🚀 Funcionalidades Principais

### 1. 🔐 Controle de Acesso e Segurança
* Login com níveis de permissão (Administrador, Gerente, Mecânico, Frentista, Operador de Balança).
* **Logs de Auditoria:** O sistema registra automaticamente todas as ações críticas (quem fez, o que fez e quando).

### 2. 🚛 Gestão de Frota
* Cadastro de Veículos (Caminhões, Escavadeiras, Leves).
* Geração automática de **QR Code** para cada veículo.
* Impressão de etiquetas de identificação.

### 3. ⛽ Controle de Abastecimento
* Leitura de QR Code via câmera (Webcam/Celular) ou digitação manual.
* Suporte a múltiplos combustíveis: **Diesel, Gasolina e Álcool**.
* Registro de horímetro/quilometragem para cálculo de média.
* Ticket de comprovante para o motorista.

### 4. ⚖️ Balança Rodoviária
* Pesagem de entrada (Tara) e saída (Bruto).
* Cálculo automático de peso líquido.
* Associação com o tipo de material transportado (Minério, Brita, Estéril).
* Impressão de Ticket de Pesagem.

### 5. 🛠️ Oficina Mecânica e Estoque
* **Abertura de O.S. (Ordem de Serviço):** Relato de problemas e prioridade.
* **Controle de Estoque:** Cadastro de peças com código (Part Number) e alerta de estoque mínimo.
* **Baixa Automática:** Ao utilizar uma peça na O.S., ela é descontada automaticamente do estoque.
* Histórico completo de manutenções por veículo.

---

## 💻 Tecnologias Utilizadas

* **Back-end:** PHP 8 (Nativo, sem frameworks pesados).
* **Banco de Dados:** MySQL / MariaDB.
* **Front-end:** HTML5, CSS3, Bootstrap 5 (Responsivo).
* **Bibliotecas:**
    * `html5-qrcode` (Leitura de QR Code via câmera).
    * `Google Charts` (Integração futura para Dashboards).

---

## ⚙️ Como Instalar e Rodar

### Pré-requisitos
* [XAMPP](https://www.apachefriends.org/) ou qualquer servidor com PHP e MySQL.
* Navegador Web (Chrome, Edge, Firefox).

### Passo a Passo

1.  **Clone o repositório:**
    ```bash
    git clone [https://github.com/mourafuseti/sistema-mineradora.git](https://github.com/mourafuseti/sistema-mineradora.git)
    ```
    Ou baixe o ZIP e extraia na pasta `htdocs` do seu XAMPP.

2.  **Configurar o Banco de Dados:**
    * Abra o **phpMyAdmin** (`http://localhost/phpmyadmin`).
    * Crie um banco de dados chamado `sistema_mineracao`.
    * Importe o arquivo `banco_dados.sql` que está na raiz do projeto.

3.  **Configurar Conexão (Se necessário):**
    * O arquivo `config/conexao.php` está configurado para o padrão do XAMPP:
        * Host: `localhost`
        * User: `root`
        * Pass: `` (vazio)
    * Se seu banco tiver senha, edite este arquivo.

4.  **Acessar:**
    * Abra o navegador e digite: `http://localhost/sistema-mineradora`
    * **Login Admin Padrão:**
        * Email: `admin@mineradora.com`
        * Senha: `123456`

---

## 📂 Estrutura de Pastas


```

sistema-mineradora/
├── assets/             # Imagens, CSS e JS globais
├── config/             # Conexão com banco e autenticação
├── modules/            # Módulos do sistema
│   ├── abastecimento/  # Lançamento e Histórico de Combustível
│   ├── admin/          # Dashboard e Logs
│   ├── balanca/        # Pesagem e Tickets
│   ├── cadastro/       # Veículos, Funcionários, Fornecedores
│   └── manutencao/     # O.S., Peças e Estoque
├── templates/          # Cabeçalho e Menu lateral
├── index.php           # Tela de Login
└── README.md           # Documentação

```

---

## 📄 Licença

Este projeto foi desenvolvido para fins acadêmicos e de portfólio. Sinta-se à vontade para contribuir!

**Desenvolvido por:** [MouraFuseti](https://github.com/mourafuseti)

```


```

Agora, quando alguém entrar no seu link do GitHub, verá essa capa profissional explicando tudo sobre o seu sistema! 🚀
