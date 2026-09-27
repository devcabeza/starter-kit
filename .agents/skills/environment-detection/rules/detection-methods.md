# Detection Methods

## File-Based Detection

### Laravel Sail
```bash
# Check if Sail exists
test -f "./vendor/bin/sail" && echo "Sail available"

# Check if Docker is running
docker info > /dev/null 2>&1 && echo "Docker running"
```

### Docker
```bash
# Check for docker-compose.yml
test -f "./docker-compose.yml" && echo "Docker Compose config found"

# Check for Dockerfile
test -f "./Dockerfile" && echo "Dockerfile found"
```

### Local PHP
```bash
# Check PHP availability
command -v php && php -v
```

## Environment Variable Detection

```bash
# Check APP_ENV
grep APP_ENV .env

# Check if running in Sail container
env | grep -i sail
```

## Runtime Detection (PHP)

```php
// Check if running in Docker
function isInDocker(): bool
{
    return file_exists('/.dockerenv') || 
           getenv('DOCKER') === 'true';
}

// Check if running in Sail
function isSail(): bool
{
    return isInDocker() && 
           strpos(getenv('HOSTNAME') ?? '', 'sail') !== false;
}

// Check environment
function getEnvironment(): string
{
    return config('app.env', 'local');
}
```

## Detection Priority

1. **Sail** (if vendor/bin/sail exists + Docker running)
2. **Docker** (if docker-compose.yml exists)
3. **Local** (if PHP available)
4. **Unknown** (fallback to local commands)
