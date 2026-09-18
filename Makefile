.PHONY: help up down restart status logs
.PHONY: capacitor-icons capacitor-sync capacitor-open-android
.PHONY: artisan composer npm node
.PHONY: migrate migrate-fresh migrate-rollback seed db-wipe
.PHONY: test test-coverage pest pest-filter pest-parallel
.PHONY: tinker db-shell db-import db-export
.PHONY: cache-clear config-clear route-clear view-clear optimize-clear
.PHONY: dev dev-frontend build
.PHONY: pint phpstan
.PHONY: queue-work queue-listen queue-restart
.PHONY: schedule-run schedule-work
.PHONY: log tail
.PHONY: route-list route-cache
.PHONY: key-generate storage-link
.PHONY: horizon horizon-status horizon-pause horizon-continue horizon-terminate horizon-clear horizon-clear-metrics horizon-forget horizon-list horizon-snapshot horizon-publish horizon-install horizon-supervisors horizon-supervisor-status horizon-purge horizon-link
.PHONY: make-model make-controller make-livewire make-migration make-seeder make-factory
.PHONY: filament install update
.PHONY: telescope telescope-clear telescope-install telescope-publish telescope-link

# ============================================================================
# Docker/Sail Commands
# ============================================================================

# Detect if Sail is available, otherwise use docker compose
SAIL := docker compose exec app

# ============================================================================
# GENERAL
# ============================================================================

help: ## Show this help message
	@echo "Usage: make [command]"
	@echo ""
	@echo "Available commands:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-25s\033[0m %s\n", $$1, $$2}'

# ----------------------------------------------------------------------------
# Docker
# ----------------------------------------------------------------------------
up: ## Start Docker containers
	@if [ -f "./vendor/bin/sail" ]; then \
		./vendor/bin/sail up -d; \
	else \
		docker compose up -d; \
	fi

down: ## Stop Docker containers
	@if [ -f "./vendor/bin/sail" ]; then \
		./vendor/bin/sail down; \
	else \
		docker compose down; \
	fi

restart: ## Restart Docker containers
	@if [ -f "./vendor/bin/sail" ]; then \
		./vendor/bin/sail restart; \
	else \
		docker compose restart; \
	fi

status: ## Show Docker container status
	@if [ -f "./vendor/bin/sail" ]; then \
		./vendor/bin/sail ps; \
	else \
		docker compose ps; \
	fi

logs: ## Show Docker container logs (tail)
	@if [ -f "./vendor/bin/sail" ]; then \
		./vendor/bin/sail logs -f; \
	else \
		docker compose logs -f; \
	fi

# ============================================================================
# ARTISAN
# ============================================================================

artisan: ## Run Artisan command (usage: make artisan cmd="migrate")
	$(SAIL) php artisan $(cmd)

# ============================================================================
# COMPOSER
# ============================================================================

composer: ## Run Composer command (usage: make composer cmd="install")
	$(SAIL) composer $(cmd)

# ============================================================================
# NPM / NODE
# ============================================================================

npm: ## Run NPM command (usage: make npm cmd="install")
	$(SAIL) npm $(cmd)

node: ## Run Node command (usage: make node cmd="yarn dev")
	$(SAIL) node $(cmd)

# ============================================================================
# MIGRATIONS & DATABASE
# ============================================================================

migrate: ## Run migrations
	$(SAIL) php artisan migrate

migrate-fresh: ## Drop all tables and re-run migrations
	$(SAIL) php artisan migrate:fresh

migrate-rollback: ## Rollback last migration
	$(SAIL) php artisan migrate:rollback

migrate-status: ## Show migration status
	$(SAIL) php artisan migrate:status

seed: ## Run database seeders
	$(SAIL) php artisan db:seed

db-wipe: ## Drop all tables (WARNING: destructive)
	$(SAIL) php artisan db:wipe

db-shell: ## Open database shell (mysql/psql/sqlite)
	$(SAIL) php artisan db:shell

db-import: ## Import database from SQL file (usage: make db-import file=backup.sql)
	$(SAIL) php artisan db:import $(file)

db-export: ## Export database to SQL file (usage: make db-export file=backup.sql)
	$(SAIL) php artisan db:export $(file)

# ============================================================================
# TESTING
# ============================================================================

test: ## Run all tests with Pest
	$(SAIL) php artisan test

test-coverage: ## Run tests with coverage report
	$(SAIL) php artisan test --coverage

test-coverage-html: ## Run tests with HTML coverage report
	$(SAIL) php artisan test --coverage --min=80

pest: ## Run Pest directly
	$(SAIL) vendor/bin/pest

pest-filter: ## Run specific test by name (usage: make pest-filter name="test name")
	$(SAIL) vendor/bin/pest --filter="$(name)"

pest-parallel: ## Run tests in parallel
	$(SAIL) vendor/bin/pest --parallel

pest-compact: ## Run tests in compact mode
	$(SAIL) php artisan test --compact

# ============================================================================
# TINKER
# ============================================================================

tinker: ## Start Tinker REPL
	$(SAIL) php artisan tinker

# ============================================================================
# CACHE
# ============================================================================

cache-clear: ## Clear application cache
	$(SAIL) php artisan cache:clear

config-clear: ## Clear configuration cache
	$(SAIL) php artisan config:clear

route-clear: ## Clear route cache
	$(SAIL) php artisan route:clear

view-clear: ## Clear compiled views
	$(SAIL) php artisan view:clear

optimize-clear: ## Clear all caches (config, route, view, event, cache)
	$(SAIL) php artisan optimize:clear

cache-table: ## Cache database tables in memory (performance)
	$(SAIL) php artisan cache:table

# ============================================================================
# DEVELOPMENT
# ============================================================================

dev: ## Start Laravel development server
	$(SAIL) php artisan serve --host=0.0.0.0

dev-frontend: ## Start Vite dev server (hot reload)
	$(SAIL) npm run dev

build: ## Build production frontend assets
	$(SAIL) npm run build

# ============================================================================
# QUEUE
# ============================================================================

queue-work: ## Process queue jobs
	$(SAIL) php artisan queue:work

queue-listen: ## Listen to queue (for development)
	$(SAIL) php artisan queue:listen

queue-restart: ## Restart queue workers
	$(SAIL) php artisan queue:restart

queue-failed: ## List failed jobs
	$(SAIL) php artisan queue:failed

queue-retry: ## Retry a failed job (usage: make queue-retry id=1)
	$(SAIL) php artisan queue:retry $(id)

queue-flush: ## Flush all failed jobs
	$(SAIL) php artisan queue:flush

# ============================================================================
# SCHEDULE
# ============================================================================

schedule-run: ## Run scheduled commands once
	$(SAIL) php artisan schedule:run

schedule-work: ## Start scheduler daemon (for production)
	$(SAIL) php artisan schedule:work

schedule-list: ## List all scheduled tasks
	$(SAIL) php artisan schedule:list

# ============================================================================
# LOGS
# ============================================================================

log: ## Tail application log
	$(SAIL) tail -f storage/logs/laravel.log

log-clear: ## Clear log file
	$(SAIL) > storage/logs/laravel.log

log-error: ## Show recent errors from log
	$(SAIL) grep -i "error\|exception" storage/logs/laravel.log | tail -50

# ============================================================================
# ROUTES
# ============================================================================

route-list: ## List all routes
	$(SAIL) php artisan route:list

route-cache: ## Cache routes for production
	$(SAIL) php artisan route:cache

route-filter: ## Filter routes by name/uri (usage: make route-filter search="user")
	$(SAIL) php artisan route:list --name=$(search)

route-middleware: ## List route middleware groups
	$(SAIL) php artisan route:list --middleware

# ============================================================================
# CODE QUALITY
# ============================================================================

pint: ## Run Laravel Pint formatter
	$(SAIL) vendor/bin/pint

pint-dirty: ## Run Pint on dirty files only
	$(SAIL) vendor/bin/pint --dirty

pint-test: ## Run Pint in test mode (dry-run)
	$(SAIL) vendor/bin/pint --test

phpstan: ## Run PHPStan static analysis
	$(SAIL) vendor/bin/phpstan analyse

phpstan-baseline: ## Generate PHPStan baseline
	$(SAIL) vendor/bin/phpstan analyse --generate-baseline

# ============================================================================
# MAKE: GENERATE FILES
# ============================================================================

make-model: ## Create model with migration (usage: make make-model name=Product)
	$(SAIL) php artisan make:model $(name) -mrf

make-controller: ## Create controller (usage: make make-controller name=UserController)
	$(SAIL) php artisan make:controller $(name) --resource

make-livewire: ## Create Livewire component (usage: make make-livewire name=Counter)
	$(SAIL) php artisan make:livewire $(name)

make-migration: ## Create migration (usage: make make-migration name=create_products_table)
	$(SAIL) php artisan make:migration $(name)

make-seeder: ## Create seeder (usage: make make-seeder name=ProductSeeder)
	$(SAIL) php artisan make:seeder $(name)

make-factory: ## Create factory (usage: make make-factory name=ProductFactory)
	$(SAIL) php artisan make:factory $(name) --model=$(model)

# ============================================================================
# FILAMENT (Admin Panel)
# ============================================================================

filament: ## Run Filament command (usage: make filament cmd="make:resource UserResource")
	$(SAIL) php artisan filament:$(cmd)

filament-install: ## Install Filament
	$(SAIL) php artisan filament:install

filament-update: ## Update Filament resources
	$(SAIL) php artisan filament:upgrade

# ============================================================================
# LARAVEL UTILITIES
# ============================================================================

key-generate: ## Generate application key
	$(SAIL) php artisan key:generate

storage-link: ## Create storage symbolic link
	$(SAIL) php artisan storage:link

event-cache: ## Cache events and listeners
	$(SAIL) php artisan event:cache

view-cache: ## Compile all views
	$(SAIL) php artisan view:cache

# ============================================================================
# HELPER SHORTCUTS (for common tasks)
# ============================================================================

setup: ## Full project setup (install + migrate + seed)
	$(SAIL) composer install
	$(SAIL) npm install
	$(SAIL) php artisan key:generate
	$(SAIL) php artisan migrate --seed
	$(SAIL) php artisan storage:link
	@echo "✅ Setup complete!"

reset: ## Reset database and re-seed (WARNING: destructive)
	$(SAIL) php artisan migrate:fresh --seed
	@echo "✅ Database reset complete!"

fresh: ## Fresh install (migrate:fresh + seed)
	$(SAIL) php artisan migrate:fresh --seed

test-all: ## Run full test suite (tests + static analysis)
	$(SAIL) vendor/bin/pest
	$(SAIL) vendor/bin/phpstan analyse
	@echo "✅ All checks passed!"

format: ## Format code (Pint + organize imports)
	$(SAIL) vendor/bin/pint
	@echo "✅ Code formatted!"

# ============================================================================
# TELESCOPE (Debug Dashboard)
# ============================================================================

.PHONY: telescope telescope-clear telescope-install telescope-publish telescope-link

telescope: ## Start Telescope dashboard (open /telescope in browser)
	@echo "🔭 Telescope dashboard: http://localhost:8000/telescope"

telescope-clear: ## Clear all Telescope data
	$(SAIL) php artisan telescope:clear

telescope-install: ## Install Telescope scaffolding
	$(SAIL) php artisan telescope:install
	$(SAIL) php artisan migrate

telescope-publish: ## Publish Telescope configuration
	$(SAIL) php artisan vendor:publish --tag=telescope-config
	$(SAIL) php artisan vendor:publish --tag=telescope-migrations
	$(SAIL) php artisan vendor:publish --tag=telescope-provider

telescope-link: ## Show Telescope URL
	@$(SAIL) php artisan route:list --name=telescope 2>/dev/null || echo "🔭 Telescope: http://localhost:8000/telescope"

# ============================================================================
# HORIZON (Queue Dashboard)
# ============================================================================

.PHONY: horizon horizon-status horizon-pause horizon-continue horizon-terminate
.PHONY: horizon-clear horizon-clear-metrics horizon-forget horizon-list
.PHONY: horizon-snapshot horizon-publish horizon-install

horizon: ## Start Horizon queue supervisor
	$(SAIL) php artisan horizon

horizon-status: ## Show Horizon status
	$(SAIL) php artisan horizon:status

horizon-pause: ## Pause Horizon supervisor
	$(SAIL) php artisan horizon:pause

horizon-continue: ## Resume Horizon supervisor
	$(SAIL) php artisan horizon:continue

horizon-terminate: ## Terminate Horizon (restart with `make horizon`)
	$(SAIL) php artisan horizon:terminate

horizon-clear: ## Clear all jobs from queue
	$(SAIL) php artisan horizon:clear

horizon-clear-metrics: ## Clear Horizon metrics
	$(SAIL) php artisan horizon:clear-metrics

horizon-forget: ## Forget a failed job (usage: make horizon-forget id=1)
	$(SAIL) php artisan horizon:forget $(id)

horizon-list: ## List deployed machines
	$(SAIL) php artisan horizon:list

horizon-snapshot: ## Store queue metrics snapshot
	$(SAIL) php artisan horizon:snapshot

horizon-publish: ## Publish Horizon configuration
	$(SAIL) php artisan vendor:publish --tag=horizon-config

horizon-install: ## Install Horizon scaffolding
	$(SAIL) php artisan horizon:install

horizon-supervisors: ## List all supervisors
	$(SAIL) php artisan horizon:supervisors

horizon-supervisor-status: ## Show supervisor status
	$(SAIL) php artisan horizon:supervisor-status

horizon-purge: ## Terminate rogue Horizon processes
	$(SAIL) php artisan horizon:purge

horizon-link: ## Show Horizon URL
	@echo "📊 Horizon dashboard: http://localhost:8000/horizon"

# ============================================================================
# CAPACITOR (Mobile App)
# ============================================================================

.PHONY: capacitor-icons capacitor-sync capacitor-open-android capacitor-build

capacitor-icons: ## Generate mobile icons from public/mobile-icons/logo.png
	@./scripts/generate-mobile-icons.sh
	@echo "Run 'make capacitor-sync' to apply changes"

capacitor-sync: ## Sync Capacitor with web assets and icons
	npm run cap:sync

capacitor-build: ## Build Android APK with branding assets
	npm run build:android

capacitor-open-android: ## Open Android project in Android Studio
	npx cap open android
