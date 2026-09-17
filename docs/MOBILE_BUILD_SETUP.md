# 📱 Guía de Configuración: Build Móvil con GitHub Actions

## 📋 Tabla de Contenidos

1. [Requisitos Previos](#requisitos-previos)
2. [Configuración del Repositorio](#configuración-del-repositorio)
3. [Secrets de GitHub](#secrets-de-github)
4. [Variables de Entorno](#variables-de-entorno)
5. [Flujo de CI/CD](#flujo-de-cicd)
6. [Generación de APK](#generación-de-apk)
7. [Troubleshooting](#troubleshooting)

---

## Requisitos Previos

Antes de comenzar, asegúrate de tener:

- ✅ Una cuenta de GitHub
- ✅ Acceso de administrador al repositorio
- ✅ Tu aplicación Laravel desplegada en un servidor (para producción)
- ✅ Node.js 20+ instalado localmente (para pruebas)

---

## Configuración del Repositorio

### 1. Clonar el Repositorio

```bash
git clone https://github.com/tu-usuario/starter-kit.git
cd starter-kit
```

### 2. Instalar Dependencias

```bash
npm install
```

### 3. Configurar Variables Locales

```bash
cp .env.example .env
```

Edita el archivo `.env` con tus configuraciones:

```env
# Capacitor Configuration
CAPACITOR_APP_ID=com.tuempresa.app
CAPACITOR_APP_NAME=MiApp
CAPACITOR_SERVER_URL=https://app.tuempresa.com
CAPACITOR_SERVER_CLEARTEXT=false
CAPACITOR_ANDROID_SCHEME=https
```

---

## Secrets de GitHub

Los **secrets** son variables sensibles que GitHub almacena de forma segura. **Nunca** las subas a Git.

### Cómo Agregar un Secret

1. Ve a tu repositorio en GitHub
2. Haz clic en **Settings** (pestaña superior)
3. En el menú izquierdo, selecciona **Secrets and variables** → **Actions**
4. Haz clic en **New repository secret**
5. Ingresa el **Name** y **Value**
6. Haz clic en **Add secret**

### Secrets Requeridos

| Secret Name | Descripción | Ejemplo |
|-------------|-------------|---------|
| `PRODUCTION_URL` | URL de tu servidor Laravel en producción | `https://app.tuempresa.com` |

### Secrets Opcionales (para Release Firmado)

| Secret Name | Descripción | Cómo Obtenerlo |
|-------------|-------------|----------------|
| `ANDROID_KEYSTORE_BASE64` | Keystore para firmar APK | Ver [Generar Keystore](#generar-keystore-para-android) |
| `ANDROID_KEYSTORE_PASSWORD` | Password del keystore | Lo defines al crear el keystore |
| `ANDROID_KEY_ALIAS` | Alias de la clave | Lo defines al crear el keystore |
| `ANDROID_KEY_PASSWORD` | Password de la clave | Lo defines al crear el keystore |

---

## Variables de Entorno

### Variables de Capacitor (`.env`)

Estas variables se usan para configurar la app móvil:

```env
# Identificación de la app
CAPACITOR_APP_ID=com.tuempresa.app          # ID único de la app
CAPACITOR_APP_NAME=MiApp                     # Nombre visible de la app

# Build
CAPACITOR_WEB_DIR=dist                       # Directorio de salida de Vite

# Servidor Remoto
CAPACITOR_SERVER_URL=https://app.tuempresa.com  # URL de tu backend Laravel
CAPACITOR_SERVER_CLEARTEXT=false                 # Permitir HTTP (false en prod)
CAPACITOR_ANDROID_SCHEME=https                   # Scheme para Android

# CORS
FRONTEND_URL=https://app.tuempresa.com      # URL del frontend para CORS
```

### Variables de GitHub Actions

Estas variables se definen en el workflow (no en secrets):

| Variable | Valor | Descripción |
|----------|-------|-------------|
| `ANDROID_PACKAGE_NAME` | `com.tuempresa.app` | ID de la app Android |
| `APP_NAME` | `MiApp` | Nombre de la app |

---

## Flujo de CI/CD

### Diagrama del Flujo

```
Push a main/develop
        ↓
GitHub Actions se activa
        ↓
┌─────────────────────────────────┐
│  1. Checkout del código         │
│  2. Setup Node.js 20            │
│  3. npm ci (instalar deps)      │
│  4. Crear .env con secrets      │
│  5. npm run build (Vite → dist) │
│  6. Setup Java 17 + Android SDK │
│  7. npx cap add android         │
│  8. npx cap sync android        │
│  9. Build APK (gradlew)         │
│ 10. Build AAB (gradlew)         │
│ 11. Upload artifacts            │
└─────────────────────────────────┘
        ↓
APK disponible en Artifacts
```

### Triggers del Workflow

El workflow se ejecuta automáticamente cuando:

| Evento | Acción |
|--------|--------|
| `push` a `main` | ✅ Build Android APK/AAB |
| `push` a `develop` | ✅ Build Android APK/AAB |
| `pull_request` a `main` | ✅ Build Android APK/AAB |
| `workflow_dispatch` | ✅ Build manual |
| Tag `v*.*.*` | ✅ Build Android + iOS |

---

## Generación de APK

### APK Debug (Para Pruebas)

Se genera automáticamente en cada push:

1. Ve a la pestaña **Actions** en GitHub
2. Haz clic en el workflow más reciente
3. En la sección **Artifacts**, descarga `android-apk-{sha}.zip`
4. Descomprime y usa el archivo `.apk`

### APK/AAB Release (Para Play Store)

Para generar versiones firmadas:

1. **Crea un tag** en Git:
   ```bash
   git tag -a v1.0.0 -m "Release 1.0.0"
   git push origin v1.0.0
   ```

2. **GitHub Actions** generará:
   - `app-debug.apk` (para pruebas)
   - `app-debug.aab` (para Play Store)

3. **Los archivos estarán en**:
   - **Artifacts**: Descarga directa
   - **Releases**: Draft release con los archivos

---

## Generar Keystore para Android

Si quieres firmar tus APK para producción:

### 1. Generar el Keystore

```bash
keytool -genkey -v \
  -keystore mi-app.keystore \
  -alias mi-app \
  -keyalg RSA \
  -keysize 2048 \
  -validity 10000
```

### 2. Convertir a Base64

```bash
base64 -i mi-app.keystore | tr -d '\n'
```

### 3. Agregar como Secret en GitHub

- **Name**: `ANDROID_KEYSTORE_BASE64`
- **Value**: El resultado del comando anterior

### 4. Agregar otros Secrets

- `ANDROID_KEYSTORE_PASSWORD`: Password del keystore
- `ANDROID_KEY_ALIAS`: `mi-app` (el alias que definiste)
- `ANDROID_KEY_PASSWORD`: Password de la clave

---

## Estructura de Archivos

```
starter-kit/
├── .github/
│   └── workflows/
│       └── build-mobile.yml    # Workflow de CI/CD
├── capacitor.config.ts         # Configuración de Capacitor
├── config/
│   └── cors.php               # Configuración CORS
├── docs/
│   └── MOBILE_BUILD_SETUP.md  # Este archivo
├── .env.example               # Template de variables
└── package.json               # Scripts de Capacitor
```

---

## Comandos Útiles

### Desarrollo Local

```bash
# Build web assets
npm run build

# Inicializar Capacitor (solo primera vez)
npm run cap:init

# Agregar Android (solo primera vez)
npm run cap:add:android

# Sincronizar cambios
npm run cap:sync

# Abrir en Android Studio
npm run cap:open:android

# Build completo de Android
npm run build:android
```

### GitHub CLI

```bash
# Ver workflows
gh run list

# Ver logs de un workflow
gh run view {run-id} --log

# Descargar artifacts
gh run download {run-id}
```

---

## Troubleshooting

### Error: "CAPACITOR_SERVER_URL not set"

**Causa**: No se ha configurado el secret `PRODUCTION_URL` en GitHub.

**Solución**:
1. Ve a Settings → Secrets → Actions
2. Agrega `PRODUCTION_URL` con la URL de tu servidor

### Error: "CORS policy blocked"

**Causa**: Tu servidor Laravel no permite conexiones desde la app móvil.

**Solución**:
1. Agrega `capacitor://localhost` a `allowed_origins` en `config/cors.php`
2. Agrega `http://localhost` para Android

### Error: "Build failed in gradlew"

**Causa**: Problemas con la configuración de Android SDK.

**Solución**:
1. Verifica que Java 17 esté configurado en el workflow
2. Revisa los logs del workflow para errores específicos

### Error: "Module not found: @capacitor/cli"

**Causa**: Las dependencias de Capacitor no están instaladas.

**Solución**:
```bash
npm install @capacitor/core @capacitor/cli @capacitor/android
```

---

## Recursos Adicionales

- [Documentación de Capacitor](https://capacitorjs.com/docs)
- [GitHub Actions Documentation](https://docs.github.com/en/actions)
- [Android Developer Guide](https://developer.android.com/guide)
- [Laravel CORS Documentation](https://laravel.com/docs/routing#cors)

---

## Soporte

Si tienes problemas:

1. Revisa los [Troubleshooting](#troubleshooting)
2. Consulta la [documentación de Capacitor](https://capacitorjs.com/docs)
3. Abre un issue en el repositorio

---

**Última actualización**: 2026-09-17
