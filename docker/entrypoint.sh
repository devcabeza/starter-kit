#!/bin/bash
set -e

# Detectar el rol del contenedor
ROLE="${CONTAINER_ROLE:-app}"

if [[ "$*" == *"horizon"* ]]; then
    ROLE="worker"
elif [[ "$*" == *"schedule"* ]]; then
    ROLE="scheduler"
fi

# Si es worker o scheduler, esperar a que Composer esté listo y arrancar
if [ "$ROLE" = "worker" ] || [ "$ROLE" = "scheduler" ]; then
    echo "⚡ Contenedor iniciado con rol: $ROLE"
    if [ ! -f "vendor/autoload.php" ]; then
        echo "⏳ Esperando a que las dependencias de Composer estén instaladas..."
        while [ ! -f "vendor/autoload.php" ]; do
            sleep 2
        done
    fi
    exec "$@"
fi

echo "🚀 Iniciando contenedor principal (app)..."

# 1. Instalar dependencias de Composer si no existen
if [ ! -d "vendor" ] || [ ! -f "vendor/autoload.php" ]; then
    echo "📦 [Composer] Instalando dependencias..."
    composer install --no-interaction --prefer-dist
fi

# 2. Instalar paquetes de Node si no existen o si falta el binario de vite
if [ ! -d "node_modules" ] || [ ! -f "node_modules/.bin/vite" ]; then
    echo "📦 [NPM] Instalando paquetes de Node..."
    npm install
fi

# 3. Enlace simbólico de storage si no existe
if [ ! -L "public/storage" ]; then
    echo "🔗 Verificando enlace simbólico storage..."
    php artisan storage:link --force 2>/dev/null || true
fi

# 4. Esperar a que la base de datos esté lista y ejecutar migraciones
echo "⏳ Verificando conexión a la base de datos..."
MAX_RETRIES=30
COUNT=0
until php artisan db:show > /dev/null 2>&1; do
    COUNT=$((COUNT + 1))
    if [ $COUNT -ge $MAX_RETRIES ]; then
        echo "⚠️ No se pudo conectar a la base de datos tras $MAX_RETRIES intentos. Continuando sin migraciones."
        break
    fi
    echo "⏳ Base de datos no disponible aún, esperando... ($COUNT/$MAX_RETRIES)"
    sleep 2
done

if [ $COUNT -lt $MAX_RETRIES ]; then
    echo "🔄 Ejecutando migraciones de la base de datos..."
    php artisan migrate --force
fi

echo "✅ Inicialización completada. Ejecutando comando..."
exec "$@"
