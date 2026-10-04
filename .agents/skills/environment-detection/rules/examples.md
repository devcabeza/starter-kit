# Detection Examples

## Example 1: Using Makefile (Preferred)

**User request:** "Create a new model for Product"

**Detection:**
```bash
# Check for Makefile
test -f "./Makefile" && echo "Makefile found"
# Result: Makefile detected
```

**Command:**
```bash
make artisan cmd="make:model Product -mrf"
```

## Example 2: Running Tests with Makefile

**User request:** "Run the tests"

**Detection:**
```bash
# Check for Makefile + environment
if [ -f "./Makefile" ]; then
    make test
else
    # Fallback to direct commands
    vendor/bin/pest
fi
```

## Example 3: Installing Package with Makefile

**User request:** "Install spatie/laravel-permission"

**Detection:**
```bash
# Check for Makefile
if [ -f "./Makefile" ]; then
    make composer cmd="require spatie/laravel-permission"
else
    composer require spatie/laravel-permission
fi
```

## Example 4: Database Migration with Makefile

**User request:** "Run migrations"

**Detection:**
```bash
# With Makefile
make migrate

# Without Makefile - detect environment
if [ -f "./vendor/bin/sail" ]; then
    ./vendor/bin/sail artisan migrate
elif [ -f "./docker-compose.yml" ]; then
    docker compose exec app php artisan migrate
else
    php artisan migrate
fi
```

## Example 5: Clear Cache with Makefile

**User request:** "Clear all caches"

**Detection:**
```bash
# With Makefile
make optimize-clear

# Without Makefile - detect and execute
ENV_PREFIX=""
if [ -f "./vendor/bin/sail" ] && docker info > /dev/null 2>&1; then
    ENV_PREFIX="./vendor/bin/sail "
fi

${ENV_PREFIX}php artisan optimize:clear
```

## Example 6: Start Development Server

**User request:** "Start the dev server"

**Detection:**
```bash
# With Makefile
make up      # Start containers
make dev     # Start Laravel server

# Without Makefile
./vendor/bin/sail up -d
./vendor/bin/sail artisan serve --host=0.0.0.0
```

## Example 7: Format Code

**User request:** "Format the code with Pint"

**Detection:**
```bash
# With Makefile
make pint

# Without Makefile
./vendor/bin/sail vendor/bin/pint
```

## Best Practices

1. **Always prefer Make commands** when Makefile exists
2. **Check for Makefile first** in detection algorithm
3. **Provide fallback** to direct commands when Makefile doesn't exist
4. **Run `make help`** to see available commands
5. **Use `make artisan cmd="..."`** for any artisan command
6. **Test detection logic** - ensure it works in all environments
