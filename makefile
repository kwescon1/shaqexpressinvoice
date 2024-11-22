.ONESHELL:

# Default help target
help: ## Print help
	@echo -e "\nUsage:\n  make \033[36m<target>\033[0m\n"
	@echo -e "Targets:\n"
	@awk 'BEGIN {FS = ":.*##"; printf ""} \
	/^[a-zA-Z_-]+:.*?##/ { printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2 }' $(MAKEFILE_LIST)

# === Development and Linting (Executed within the app container) ===
lint: ## Run Pint linting
	@echo -e "\033[33mRunning Pint linting...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_api composer run lint

lint-full: ## Run full linting including PHP_CodeSniffer and Pint
	@echo -e "\033[33mRunning full linting...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_api composer run lint:full

lint-fix: ## Run linting with auto-fix
	@echo -e "\033[33mRunning linting with auto-fix...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_api composer run lint:fix

refactor: ## Run Rector for code refactoring
	@echo -e "\033[33mRunning Rector refactor...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_api composer run refactor

# === Testing and Static Analysis (Executed within the app container) ===
test-lint: ## Test linting with Pint
	@echo -e "\033[33mTesting linting with Pint...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_api composer run test:lint

test-refactor: ## Run Rector in dry-run mode to check potential refactoring
	@echo -e "\033[33mTesting refactor changes with dry-run...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_api composer run test:refactor

test-types: ## Run PHPStan for static analysis
	@echo -e "\033[33mRunning PHPStan for type checking...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_api composer run test:types

test-type-coverage: ## Run Pest type coverage
	@echo -e "\033[33mRunning Pest type coverage tests...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_api composer run test:type-coverage

test-arch: ## Run Pest Arch Testt
	@echo -e "\033[33mRunning Arch tests...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_api composer run test:arch

test-unit: ## Run unit tests with Pest
	@echo -e "\033[33mRunning Pest unit tests with coverage...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_api composer run test:unit

test-security: ## Run security checks
	@echo -e "\033[33mRunning security check...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_api composer run test:security

test: ## Run all tests
	@echo -e "\033[33mRunning all tests...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_api composer run test

# === Development Helpers (Executed within the app container) ===
dev-ide-helper: ## Run IDE Helper generators
	@echo -e "\033[33mGenerating IDE helper files...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_api composer run dev:ide-helper

# === Docker Management for API Services ===
build-api: ## Build the Docker container for the shaqexpressinvoice API without using cache
	@echo -e "\033[33mBuilding the shaqexpressinvoice API Docker container...\033[0m"
	@docker-compose build --no-cache

up-api: ## Start the shaqexpressinvoice API Docker container in detached mode
	@echo -e "\033[33mStarting the shaqexpressinvoice API Docker container...\033[0m"
	@docker-compose up -d

down-api: ## Stop and remove the shaqexpressinvoice API Docker container
	@echo -e "\033[33mStopping the shaqexpressinvoice API Docker container...\033[0m"
	@docker-compose down

logs-api: ## Show real-time logs from the shaqexpressinvoice API Docker container
	@echo -e "\033[33mShowing logs from the shaqexpressinvoice API Docker container...\033[0m"
	@docker-compose logs -f app

shell: ## Access the shell of the API container
	@echo -e "\033[33mEntering API shell...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpress /bin/bash

# === Docker Management for Queue Worker ===
build-queue: ## Build the Docker container for the Queue worker
	@echo -e "\033[33mBuilding the Queue worker Docker container...\033[0m"
	@docker-compose build --no-cache queue-worker

up-queue: ## Start the Queue worker Docker container
	@echo -e "\033[33mStarting the Queue worker Docker container...\033[0m"
	@docker-compose up -d queue-worker

down-queue: ## Stop and remove the Queue worker Docker container
	@echo -e "\033[33mStopping the Queue worker Docker container...\033[0m"
	@docker-compose down queue-worker

logs-queue: ## Show real-time logs from the Queue worker Docker container
	@echo -e "\033[33mShowing logs from the Queue worker Docker container...\033[0m"
	@docker-compose logs -f queue-worker

shell-queue: ## Access the shell of the Queue worker container
	@echo -e "\033[33mEntering Queue worker shell...\033[0m"
	@docker exec -it -u shaqexpressinvoice shaqexpressinvoice_queue_worker /bin/bash

# === Additional Services (Nginx, DB, Redis, Cron, MinIO) ===
build-nginx: ## Build the Nginx Docker container
	@echo -e "\033[33mBuilding the Nginx Docker container...\033[0m"
	@docker-compose build --no-cache shaqexpressinvoice_webserver

up-nginx: ## Start the Nginx Docker container
	@echo -e "\033[33mStarting the Nginx Docker container...\033[0m"
	@docker-compose up -d shaqexpressinvoice_webserver

down-nginx: ## Stop and remove the Nginx Docker container
	@echo -e "\033[33mStopping the Nginx Docker container...\033[0m"
	@docker-compose down shaqexpressinvoice_webserver

logs-nginx: ## Show real-time logs from the Nginx Docker container
	@echo -e "\033[33mShowing logs from the Nginx Docker container...\033[0m"
	@docker-compose logs -f shaqexpressinvoice_webserver

shell-nginx: ## Access the shell of the Nginx container
	@echo -e "\033[33mEntering Nginx shell...\033[0m"
	@docker exec -it shaqexpressinvoice_webserver /bin/bash

# Database commands
build-db: ## Build the MySQL database Docker container
	@echo -e "\033[33mBuilding the MySQL database Docker container...\033[0m"
	@docker-compose build --no-cache db

up-db: ## Start the MySQL database Docker container
	@echo -e "\033[33mStarting the MySQL database Docker container...\033[0m"
	@docker-compose up -d db

down-db: ## Stop and remove the MySQL database Docker container
	@echo -e "\033[33mStopping the MySQL database Docker container...\033[0m"
	@docker-compose down db

logs-db: ## Show real-time logs from the MySQL database Docker container
	@echo -e "\033[33mShowing logs from the MySQL database Docker container...\033[0m"
	@docker-compose logs -f db

shell-db: ## Access the shell of the MySQL database container
	@echo -e "\033[33mEntering MySQL shell...\033[0m"
	@docker exec -it shaqexpressinvoice_db /bin/bash

# Redis commands
build-redis: ## Build the Redis Docker container
	@echo -e "\033[33mBuilding the Redis Docker container...\033[0m"
	@docker-compose build --no-cache redis

up-redis: ## Start the Redis Docker container
