# Command Mapping

## Makefile Commands (Preferred when available)

When a Makefile exists in the project root, ALWAYS use Make commands:

```bash
# General
make help              # Show all available commands
make up                # Start Docker containers
make down              # Stop Docker containers
make restart           # Restart containers
make status            # Show container status
make logs              # Show container logs

# Artisan
make artisan cmd="migrate"           # Run any artisan command
make artisan cmd="make:model User -mrf"
make artisan cmd="cache:clear"

# Composer
make composer cmd="install"          # Run any composer command
make composer cmd="require laravel/sanctum"

# NPM
make npm cmd="install"               # Run any npm command
make npm cmd="run build"

# Migrations
make migrate                          # Run migrations
make migrate-fresh                    # Drop and re-migrate
make migrate-rollback                 # Rollback last migration
make seed                             # Run seeders

# Testing
make test                             # Run all tests
make test-coverage                    # Run with coverage
make pest                             # Run Pest directly

# Database
make tinker                           # Start Tinker
make db-shell                         # Open database shell

# Cache
make cache-clear                      # Clear app cache
make config-clear                     # Clear config cache
make route-clear                      # Clear route cache
make view-clear                       # Clear views
make optimize-clear                   # Clear all caches

# Development
make dev                              # Start dev server
make build                            # Build frontend assets

# Code Quality
make pint                             # Run Pint formatter
make pint-dirty                       # Format dirty files only
make phpstan                          # Run static analysis
```

---

## Direct Commands (When Makefile doesn't exist)

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

### Testing
| Environment | Command |
|-------------|---------|
| Local | `php artisan test` or `vendor/bin/pest` |
| Sail | `./vendor/bin/sail artisan test` or `./vendor/bin/sail test` |
| Docker | `docker compose exec app php artisan test` or `docker compose exec app vendor/bin/pest` |

### Database Access
| Environment | Command |
|-------------|---------|
| Local | `php artisan tinker` |
| Sail | `./vendor/bin/sail artisan tinker` |
| Docker | `docker compose exec app php artisan tinker` |
