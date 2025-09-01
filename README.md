## Aplicação web com banco de dados (AWS)

Uma aplicação web para gerenciamento de jogos do **Campeonato Brasileiro 2025**, desenvolvida em **PHP** e hospedada na **AWS** utilizando **EC2** para servidor web e **RDS** para banco de dados **MariaDB**.

### Sobre os arquivos do repositório

- **SamplePage.php**: Página principal da aplicação, responsável pelo formulário de cadastro e por carregar a lista de jogos (refletindo o banco de dados)
- **README.md**: Documentação do projeto.
- **ponderada-tutorial-aws.mp4**: Vídeo mostrando o funcionamento da aplicação e infraestrutura AWS.

### Tecnologias/componentes

- **Frontend:** HTML + CSS (inline) + JavaScript (para validação de formulário)
- **Backend:** PHP com mysqli
- **Servidor Web:** Apache HTTP Server
- **Banco de Dados:** MariaDB no Amazon RDS
- **Infraestrutura:** Amazon EC2 (Amazon Linux 2023)

### Recursos usados na AWS
- **EC2 Instance**
  - Amazon Linux 2023 configurado
  - Security Group com portas SSH e HTTP liberadas
  - Key Pair (.pem) configurado para acesso SSH

- **RDS Instance** (MariaDB)
  - Security Group permitindo conexão apenas da EC2
  - Endpoint, usuário e senha configurados corretamente

Fiz o setup e o deploy conectando via **SSH** utilizando o arquivo `.pem` com as chaves.

---

### Banco de dados

#### Tabela `GAMES`

| Campo       | Tipo        | Restrições                      | Descrição                          |
|-------------|------------|---------------------------------|------------------------------------|
| `GAME_ID`   | INT(11)    | PRIMARY KEY, AUTO_INCREMENT     | Identificador único do jogo        |
| `HOME_TEAM` | VARCHAR(60)| NOT NULL                        | Time mandante                      |
| `AWAY_TEAM` | VARCHAR(60)| NOT NULL                        | Time visitante                     |
| `MATCH_DATE`| DATE       | NOT NULL                        | Data do jogo (restrito a 2025)     |
| `STADIUM`   | VARCHAR(100)| NULL                           | Estádio (opcional)                 |
| `CREATED_AT`| TIMESTAMP  | DEFAULT CURRENT_TIMESTAMP       | Data de criação do registro        |

---
**Validações:**

- *Data obrigatória e em formato válido (YYYY-MM-DD)*
- *Ano restrito a 2025 (Brasileirão 2025)*  
- *Times obrigatórios: mandante e visitante devem ser preenchidos*  
- *Sanitização de inputs, contra SQL injection*  
- *Estádio opcional, aceitando valores NUL*

### Funcionalidades
- **Cadastro de jogos:** Formulário com validação para ano de 2025, suporte a estádio opcional e mensagens de erro claras.  
- **Lista de jogos:** Exibição organizada dos jogos cadastrados, ordenados automaticamente por data.  

---

### Demonstração
O vídeo mostrando a aplicação em funcionamento, junto com os recursos na AWS (EC2 e RDS), está disponível em **ponderada-tutorial-aws.mp4**.  

---

*Desenvolvido como parte do tutorial "Create a web server and an Amazon RDS DB instance"* para a ponderada da Semana 4.
