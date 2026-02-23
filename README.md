# 👋🏻 Leonardo de Moura Fuseti

Estudante de Defesa Cibernetica no Polo Estacio Piumhi MG . Formação tecnica em Tecnico em Redes de Computadores no IFMG Bambui MG , intusiasta na programação gostando muito de Python e evoluindo dia a dia .

### Conecte-se comigo

[![Perfil DIO](https://img.shields.io/badge/-Meu%20Perfil%20na%20DIO-30A3DC?style=for-the-badge)](https://www.dio.me/users/mourafuseti)
[![E-mail](https://img.shields.io/badge/-Email-000?style=for-the-badge&logo=microsoft-outlook&logoColor=E94D5F)](mailto:mourafuseti@gmail.com)
[![LinkedIn](https://img.shields.io/badge/-LinkedIn-000?style=for-the-badge&logo=linkedin&logoColor=30A3DC)](https://www.linkedin.com/in/leonardo-moura-fuseti-4052b0359/)
[![Livro](https://img.shields.io/badge/Livro-Python_do_Básico_ao_Avançado-E94D5F?style=for-the-badge&logo=read-the-docs&logoColor=white)](https://github.com/mourafuseti)

### Habilidades

[![Python](https://img.shields.io/badge/Python-3776AB?style=for-the-badge&logo=python&logoColor=white)](https://www.python.org/)
![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)
![Kali Linux](https://img.shields.io/badge/Kali_Linux-557C94?style=for-the-badge&logo=kali-linux&logoColor=white)
![OpenCV](https://img.shields.io/badge/OpenCV-5C3EE8?style=for-the-badge&logo=opencv&logoColor=white)
![HTML](https://img.shields.io/badge/HTML-000?style=for-the-badge&logo=html5&logoColor=30A3DC)
![CSS3](https://img.shields.io/badge/CSS3-000?style=for-the-badge&logo=css3&logoColor=E94D5F)
![JavaScript](https://img.shields.io/badge/JavaScript-000?style=for-the-badge&logo=javascript&logoColor=F0DB4F)
![Sass](https://img.shields.io/badge/SASS-000?style=for-the-badge&logo=sass&logoColor=CD6799)
![Bootstrap](https://img.shields.io/badge/bootstrap-000?style=for-the-badge&logo=bootstrap&logoColor=553C7B)
[![Git](https://img.shields.io/badge/Git-000?style=for-the-badge&logo=git&logoColor=E94D5F)](https://git-scm.com/doc)
[![GitHub](https://img.shields.io/badge/GitHub-000?style=for-the-badge&logo=github&logoColor=30A3DC)](https://docs.github.com/)

### 📊 GitHub Stats

<p align="left">
  <img src="https://github-readme-stats-sigma-five.vercel.app/api?username=mourafuseti&show_icons=true&theme=tokyonight&bg_color=000&border_color=30A3DC&title_color=E94D5F&text_color=FFF" alt="GitHub Stats" />
</p>

```markdown
# 🚛 Sistema de Gestão de Mineradora & Logística



Sistema web completo para controle de frota, abastecimento, balança rodoviária e manutenção de oficinas, desenvolvido com
foco em automação via **QR Code**.

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



