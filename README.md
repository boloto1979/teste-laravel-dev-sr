# Sistema de Gestão de Chamados

Sistema desenvolvido para gerenciamento de chamados, com painel administrativo e API REST.

## Requisitos

- Docker
- Docker Compose
- Node.js
- NPM

## Instalação

1. Clone o repositório:
```bash
git clone <repositório>
cd <pasta-do-projeto>
```

2. Construa e inicie os containers Docker:
```bash
docker compose build
docker compose up -d
```

3. Entre no container da aplicação:
```bash
docker exec -it chamado-app bash
```

4. Dentro do container, instale as dependências PHP:
```bash
composer install
```

5. Verifique se as migrations foram executadas (já deve ter ocorrido via entrypoint.sh):
```bash
php artisan migrate:status
```

6. Fora do container, instale as dependências JavaScript:
```bash
npm install
npm run build
npm run dev
```

## Funcionalidades

- **Painel Administrativo**
  - Gerenciamento de categorias
  - Criação e gestão de chamados
  - Interface responsiva e intuitiva
  - Implementação completa das regras do desafio

- **API REST**
  - Endpoints para todas as operações necessárias
  - Documentação disponível via Swagger/OpenAPI
  - Autenticação e autorização implementadas

## Testes

Para executar os testes:

```bash
# Dentro do container
php artisan test

# Ou para testes específicos
php artisan test --filter=NomeDoTeste
```

## Agradecimentos

Obrigado pela oportunidade de participar deste desafio! Foi uma experiência enriquecedora desenvolver este projeto utilizando as melhores práticas e tecnologias modernas.