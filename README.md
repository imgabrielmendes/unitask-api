<h1 align="center">
  <br>
  📋 UniTask — Backend API
  <br>
</h1>

<p align="center">
  REST API para gerenciamento de tarefas em equipe, construída com <strong>Laravel 12</strong>.
  <br>
  Autenticação via Sanctum, WebSockets com Reverb, banco PostgreSQL e deploy via Docker.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white"/>
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=flat-square&logo=laravel&logoColor=white"/>
  <img src="https://img.shields.io/badge/PostgreSQL-16-4169E1?style=flat-square&logo=postgresql&logoColor=white"/>
  <img src="https://img.shields.io/badge/Redis-alpine-DC382D?style=flat-square&logo=redis&logoColor=white"/>
  <img src="https://img.shields.io/badge/Docker-Compose-2496ED?style=flat-square&logo=docker&logoColor=white"/>
  <img src="https://img.shields.io/badge/WebSockets-Reverb-6c3483?style=flat-square"/>
  <img src="https://img.shields.io/badge/Auth-Sanctum-FF2D20?style=flat-square"/>
</p>

---

## Sobre o Projeto

**UniTask** é uma API RESTful de gerenciamento colaborativo de tarefas. Usuários podem criar times, organizar projetos dentro desses times e atribuir tarefas a membros, com suporte a comentários, anexos e atualizações em tempo real via WebSocket.

O projeto foi desenvolvido com foco em boas práticas de arquitetura de software — separação clara de responsabilidades, uso de DTOs, Services e Resources, além de containerização completa com Docker.

---

## Funcionalidades

| Módulo | Recursos |
|---|---|
| **Autenticação** | Registro, Login e Logout com tokens Sanctum |
| **Times (Teams)** | CRUD completo de equipes com membros |
| **Projetos** | Projetos vinculados a times |
| **Tarefas** | CRUD com atribuição a usuários, status e datas |
| **Comentários** | Comentários aninhados por tarefa |
| **Anexos** | Upload e gerenciamento de arquivos por tarefa |
| **Tempo Real** | Eventos via WebSocket com Laravel Reverb |
| **Filas** | Processamento assíncrono com Redis |

---

## Stack Tecnológica

- **PHP 8.2+** com **Laravel 12**
- **PostgreSQL** — banco de dados relacional
- **Redis** — cache e filas (Queue Worker)
- **Laravel Sanctum** — autenticação stateless via Bearer Token
- **Laravel Reverb** — servidor WebSocket nativo do Laravel
- **Docker + Docker Compose** — containerização completa (app, db, redis, reverb, nginx)
- **PHPUnit** — testes automatizados

---

## Arquitetura

O projeto segue uma arquitetura em camadas inspirada em Clean Architecture e DDD leve:

```
HTTP Request
    │
    ▼
FormRequest       → Validação de entrada (Rules, Authorize)
    │
    ▼
Controller        → Orquestração (recebe, delega, responde)
    │
    ▼
DTO               → Transporte tipado de dados entre camadas
    │
    ▼
Service           → Regras de negócio (hash, token, queries)
    │
    ▼
Model / Eloquent  → Acesso ao banco de dados
    │
    ▼
Resource          → Serialização da resposta JSON
```

---

## Endpoints da API

### Autenticação (público)
| Método | Rota | Descrição |
|---|---|---|
| `POST` | `/api/register` | Cria novo usuário |
| `POST` | `/api/login` | Autentica e retorna token |

### Recursos protegidos (`Bearer Token` obrigatório)
| Método | Rota | Descrição |
|---|---|---|
| `POST` | `/api/logout` | Revoga o token atual |
| `GET` | `/api/user` | Retorna usuário autenticado |
| `GET` | `/api/home` | Dashboard do usuário |
| `GET/POST/PUT/DELETE` | `/api/team` | CRUD de times |
| `GET/POST/PUT/DELETE` | `/api/member` | Membros de times |
| `GET/POST/PUT/DELETE` | `/api/projects` | CRUD de projetos |
| `GET/POST/PUT/DELETE` | `/api/tasks` | CRUD de tarefas |
| `GET/POST/PUT/DELETE` | `/api/tasks/{task}/comments` | Comentários de tarefas |
| `GET/POST/DELETE` | `/api/tasks/{task}/attachments` | Anexos de tarefas |

---

## Como rodar

### Pré-requisitos
- Docker e Docker Compose instalados

### 1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/unitask-backend.git
cd unitask-backend
```

### 2. Configure o ambiente
```bash
cp .env.example .env
```

Edite o `.env` com as credenciais do banco e Redis (já configuradas no `docker-compose.yml`):
```env
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=unitask
DB_USERNAME=postgres
DB_PASSWORD=postgres

REDIS_HOST=redis
```

### 3. Suba os containers
```bash
docker compose up -d
```

### 4. Execute as migrations e seeders
```bash
docker exec laravel_app php artisan migrate --seed
```

### 5. Gere a chave da aplicação
```bash
docker exec laravel_app php artisan key:generate
```

A API estará disponível em `http://localhost:9000`.
WebSocket disponível em `ws://localhost:8080`.

---

## Testes

```bash
php artisan test
```

---

## Estrutura de Pastas

```
app/
├── DTO/                  # Data Transfer Objects por domínio
├── Events/               # Eventos para broadcasting (Reverb)
├── Http/
│   ├── Controllers/      # Controladores por domínio
│   ├── Middleware/
│   ├── Requests/         # Form Requests com validação
│   └── Resources/        # API Resources (serialização JSON)
├── Models/               # Eloquent Models
├── Policies/             # Autorização por recurso
└── Services/             # Regras de negócio
database/
├── factories/
├── migrations/
└── seeders/
routes/
├── api.php               # Rotas da API REST
└── channels.php          # Canais WebSocket
```

---

## Autor

Desenvolvido por **Gabriel Gomes Mendes**

[![LinkedIn](https://img.shields.io/badge/LinkedIn-0077B5?style=flat-square&logo=linkedin&logoColor=white)](https://linkedin.com/in/seu-perfil)
[![GitHub](https://img.shields.io/badge/GitHub-181717?style=flat-square&logo=github&logoColor=white)](https://github.com/seu-usuario)

---

## Licença

Este projeto está sob a licença MIT. Consulte o arquivo [LICENSE](LICENSE) para mais detalhes.
