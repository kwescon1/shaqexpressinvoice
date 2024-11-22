# Shaq Express Invoice System

## Overview

A robust and scalable API built with Laravel for invoice management and authentication, using Docker for seamless setup and deployment.

## Project Structure

```
shaqexpressinvoice/
│
├── docker-files/
│   ├── nginx/
│   │   ├── certs/     # SSL certificates (to be generated locally)
│   │   └── ...
│   └── ...
├── docker-compose.yml # Docker Compose file for orchestrating containers
├── app/              # Laravel application files
├── docs/             # Documentation for API routes and other functionalities
│   ├── authentication/ # Authentication-related route documentation
│   │   └── README.md  # Documentation for authentication-related routes
│   └── ...
├── .env.example      # Example environment configuration file
├── README.md         # This README file
└── ...              # Other backend-related files
```

## Prerequisites

-   **Docker**: Must be installed on your machine ([Install Docker](https://docs.docker.com/get-docker/))
-   **Make**: Required for managing API commands

## Setup Instructions

### Step 1: SSL Certificate Setup

1. Install mkcert:

    ```bash
    # macOS
    brew install mkcert
    brew install nss  # if you use Firefox

    # Other Platforms
    # Follow mkcert instructions from official documentation
    ```

2. Generate certificates:
    ```bash
    mkcert -install
    mkcert localhost 127.0.0.1 ::1
    ```

### Step 2: Environment Configuration

1. Copy the example environment file:

    ```bash
    cp .env.example .env
    ```

2. Configure the following variables in `.env`:

    ```env
    DB_DATABASE=shaqexpress_db
    DB_USERNAME=shaquser
    DB_PASSWORD=shaqpassword

    MAIL_MAILER=smtp
    MAIL_HOST=smtp.gmail.com
    MAIL_PORT=465
    MAIL_USERNAME=
    MAIL_PASSWORD=
    MAIL_ENCRYPTION=ssl
    MAIL_FROM_ADDRESS=
    MAIL_FROM_NAME="Shaq Express"

    ADMIN_EMAIL=
    ADMIN_PASSWORD=
    ```

### Step 3: Full Setup

1. Run the setup command:

    ```bash
    make build-api
    ```

    This will:

    - Build Docker containers
    - Install dependencies
    - Set up pre-commit hooks

2. Start the application:
    ```bash
    make up-api
    ```

## Development Commands

### Basic Operations

| Command          | Description                             |
| ---------------- | --------------------------------------- |
| `make build-api` | Set up the environment and dependencies |
| `make up-api`    | Start the application containers        |
| `make down-api`  | Stop the application containers         |
| `make shell`     | Access the API container shell          |
| `make logs-api`  | View API logs in real-time              |
| `make lint`      | Run linting checks with Pint            |
| `make test`      | Run all tests                           |

### Running Tests

```bash
make shell

php artisan test
```

## API Endpoints

### Authentication

-   `POST /api/v1/login` - User login

_For detailed documentation, refer to the [`docs` folder](./docs)._

## Quality Assurance

### Code Quality Tools

-   **Pre-Push Hooks**: Automated test runs before code pushing
-   **Static Analysis**:
    -   PSR standards compliance
    -   Tools: PHPStan and PHP_CodeSniffer
-   **Code Refactoring**:
    -   Automated improvements using Rector

## Support

### Contact Information

-   **Team**: Shaq Express Team
-   **Email**: support@shaqexpress.com
