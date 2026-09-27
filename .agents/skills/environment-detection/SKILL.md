---
name: environment-detection
description: "Apply this skill BEFORE executing any artisan, composer, npm, or system command. This skill detects the current development environment (local PHP, Docker, Laravel Sail, production) and provides the correct command syntax. When Docker is detected and Makefile exists, prefer Make commands. Use when: running artisan commands, installing packages, running migrations, starting dev servers, debugging, or any task that requires knowing the runtime environment. CRITICAL for command execution."
license: MIT
metadata:
  author: starter-kit
---

# Environment Detection

**CRITICAL:** Always detect the environment BEFORE executing any command. Wrong command syntax = failed execution.

## Supported Environments

| Environment | Detection Method | Command Strategy |
|-------------|------------------|------------------|
| **Local PHP** | No Docker, no Sail | Direct: `php artisan`, `composer`, `npm` |
| **Laravel Sail** | `vendor/bin/sail` exists + docker running | Make (if exists) or Sail directly |
| **Docker (custom)** | `docker-compose.yml` exists, no Sail | Make (if exists) or `docker compose exec` |
| **Laravel Cloud** | `CLOUD_ENVIRONMENT` env var | Standard (auto-detected) |
| **Production** | `APP_ENV=production` | Standard (no dev commands) |

## Makefile Integration

When a `Makefile` exists in the project root, **ALWAYS prefer Make commands** over direct Docker/Sail commands.

### Why Use Makefile?
- **Abstraction:** Single interface regardless of Docker/Sail
- **Consistency:** Same commands work in any environment
- **Maintainability:** Easy to update without changing skill
- **Documentation:** `make help` shows all available commands

### Detection Priority with Makefile

```bash
# 1. Check for Makefile + Docker
if [ -f "./Makefile" ] && [ -f "./docker-compose.yml" ]; then
    ENVIRONMENT="docker-make"
    PREFIX="make"
    
# 2. Check for Laravel Sail (with or without Makefile)
elif [ -f "./vendor/bin/sail" ] && docker info > /dev/null 2>&1; then
    if [ -f "./Makefile" ]; then
        ENVIRONMENT="sail-make"
        PREFIX="make"
    else
        ENVIRONMENT="sail"
        PREFIX="./vendor/bin/sail"
    fi
    
# 3. Check for Docker without Makefile
elif [ -f "./docker-compose.yml" ] && command -v docker &> /dev/null; then
    ENVIRONMENT="docker"
    PREFIX="docker compose exec app"
    
# 4. Local PHP
elif command -v php &> /dev/null; then
    ENVIRONMENT="local"
    PREFIX=""
    
# 5. Unknown
else
    ENVIRONMENT="unknown"
    PREFIX=""
fi
```

### Available Make Commands

Run `make help` to see all commands. Common commands:

| Task | Make Command | Direct Command (fallback) |
|------|--------------|---------------------------|
| Start containers | `make up` | `./vendor/bin/sail up -d` |
| Stop containers | `make down` | `./vendor/bin/sail down` |
| Run artisan | `make artisan cmd="migrate"` | `./vendor/bin/sail artisan migrate` |
| Run tests | `make test` | `./vendor/bin/sail test` |
| Run pest | `make pest` | `./vendor/bin/sail vendor/bin/pest` |
| Clear cache | `make cache-clear` | `./vendor/bin/sail artisan cache:clear` |
| Start dev server | `make dev` | `./vendor/bin/sail artisan serve` |
| Build assets | `make build` | `./vendor/bin/sail npm run build` |
| Format code | `make pint` | `./vendor/bin/sail vendor/bin/pint` |
| Static analysis | `make phpstan` | `./vendor/bin/sail vendor/bin/phpstan analyse` |

### Command Examples

```bash
# Using Make (preferred)
make artisan cmd="make:model Product -mrf"
make test
make migrate
make pest

# Direct (when Makefile doesn't exist)
./vendor/bin/sail artisan make:model Product -mrf
./vendor/bin/sail test
./vendor/bin/sail artisan migrate
./vendor/bin/sail vendor/bin/pest
```

## Command Mapping (Without Makefile)

When Makefile doesn't exist, use direct commands:

### Artisan Commands
| Environment | Command |
|-------------|---------|
| Local | `php artisan {command}` |
| Sail | `./vendor/bin/sail artisan {command}` |
| Docker | `docker compose exec app php artisan {command}` |

### Composer Commands
| Environment | Command |
|-------------|---------|
| Local | `composer {command}` |
| Sail | `./vendor/bin/sail composer {command}` |
| Docker | `docker compose exec app composer {command}` |

### NPM Commands
| Environment | Command |
|-------------|---------|
| Local | `npm {command}` |
| Sail | `./vendor/bin/sail npm {command}` |
| Docker | `docker compose exec app npm {command}` |

## Common Tasks by Environment

### Starting Development Server
```bash
# Local
php artisan serve

# With Makefile
make up
make dev

# Without Makefile (Sail)
./vendor/bin/sail up -d
./vendor/bin/sail artisan serve --host=0.0.0.0

# Without Makefile (Docker)
docker compose up -d
docker compose exec app php artisan serve --host=0.0.0.0
```

### Running Migrations
```bash
# With Makefile
make migrate

# Without Makefile
php artisan migrate  # Local
./vendor/bin/sail artisan migrate  # Sail
docker compose exec app php artisan migrate  # Docker
```

### Running Tests
```bash
# With Makefile
make test
make pest

# Without Makefile
vendor/bin/pest  # Local
./vendor/bin/sail test  # Sail
docker compose exec app vendor/bin/pest  # Docker
```

## Environment Variables

Check `.env` file for:
```
APP_ENV=local|testing|production
APP_DEBUG=true|false
DB_CONNECTION=mysql|pgsql|sqlite
```

## Safety Rules

1. **NEVER run destructive commands in production** (migrations, cache:clear, etc.)
2. **ALWAYS check environment before running artisan migrate:fresh**
3. **PREFER Make commands when available** - they handle environment detection internally
4. **USE `vendor/bin/pest` instead of `php artisan test` for faster execution**

## Error Handling

If command fails:
1. Check if Docker/Sail is running: `make status` or `docker ps`
2. Check if container is healthy: `docker inspect --format='{{.State.Health.Status}}' {container}`
3. Check logs: `make logs` or `docker compose logs`
4. Verify Makefile exists: `ls -la Makefile`

## Integration with Other Skills

- **hexagonal-architecture:** Use correct paths for each layer
- **testing-best-practices:** Run tests with `make test` or `make pest`
- **deploying-to-cloud:** Different commands for cloud environments

---

**REMEMBER:** 
- Makefile exists → Use `make {command}`
- No Makefile + Docker → Use direct Docker commands
- No Docker → Use local commands
- Wrong environment = wrong commands = failed execution
