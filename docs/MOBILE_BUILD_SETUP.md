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
- ✅ Node.js 22+ instalado localmente (para pruebas)

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
| `STAGING_URL` | URL de tu servidor de staging. Si falta, el build de `staging` falla a propósito para que una canary nunca apunte a producción | `https://staging.tuempresa.com` |
| `R2_ACCOUNT_ID` | Account ID de Cloudflare (también acepta el endpoint completo) | `a1b2c3d4e5f6...` |
| `R2_ACCESS_KEY_ID` | Access Key del token API de R2 | `AKIA...` |
| `R2_SECRET_ACCESS_KEY` | Secret Key del token API de R2 | `...` |
| `R2_BUCKET_PRODUCTION` | Bucket de producción | `laravertex-prod` |
| `R2_BUCKET_STAGING` | Bucket de staging (si falta, usa el de producción) | `laravertex-staging` |

### Secret Opcional (enlace público)

| Secret Name | Descripción | Ejemplo |
|-------------|-------------|---------|
| `R2_PUBLIC_URL` | URL pública del bucket de producción. Solo se usa para escribir el enlace de descarga en el resumen del run | `https://downloads.tuempresa.com` |

### Configurar Cloudflare R2

1. En el dashboard de Cloudflare, entra a **R2** y crea los buckets `laravertex-prod` y `laravertex-staging`.
2. En cada bucket, **Settings → Public access → Custom Domains**: conectá un dominio propio (ej. `downloads.tudominio.com`). El subdominio `r2.dev` sirve para probar, pero Cloudflare lo limita por tasa y no lo cachea; no lo uses para distribución real.
3. En **R2 → Manage R2 API Tokens**, crea un token con permiso **Object Read & Write** limitado a esos buckets.
4. Copia `Access Key ID` y `Secret Access Key` a los secrets `R2_ACCESS_KEY_ID` y `R2_SECRET_ACCESS_KEY`.
5. Si usás dominio propio, agregá `R2_PUBLIC_URL` para que el workflow imprima el enlace final.

Los objetos se suben con nombre estable (`laravertex.apk`, `laravertex-canary.apk`), así que el enlace público nunca cambia entre builds.

### Secrets Opcionales (para Release Firmado)

> **El workflow actual no firma.** Compila `assembleDebug` / `bundleDebug` con la keystore de debug, y GitHub Actions genera una nueva en cada runner. Consecuencia: cada compilación sale con una firma distinta y Android rechaza instalar el APK nuevo sobre el anterior (`INSTALL_FAILED_UPDATE_INCOMPATIBLE`). Para distribución real hay que cablear estos secrets y compilar `assembleRelease`.

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

# Personalización de Marca e Iconos
CAPACITOR_ICON_BG_COLOR=#ffffff              # Color de fondo del icono adaptativo
CAPACITOR_SPLASH_BG_COLOR=#ffffff            # Color de fondo del splash screen

# CORS
FRONTEND_URL=https://app.tuempresa.com      # URL del frontend para CORS
```

### Variables de GitHub Actions

Estas variables se definen en el workflow (no en secrets):

| Variable | Valor | Descripción |
|----------|-------|-------------|
| `ANDROID_PACKAGE_NAME` | `com.tuempresa.app` | `applicationId` de producción; el workflow resuelve el de cada entorno |
| `APP_NAME` | `MiApp` | Nombre de la app |

---

## Flujo de CI/CD

### Diagrama del Flujo

```
Push a main/staging, tag o ejecución manual
        ↓
GitHub Actions resuelve el entorno (production | staging)
        ↓
┌──────────────────────────────────────────────┐
│  1. Checkout del código                      │
│  2. Resolver entorno (URL, appId, nombres)   │
│  3. Setup Node.js 22 + npm ci                │
│  4. Crear .env con secrets                   │
│  5. npm run build (Vite → dist)              │
│  6. Setup Java 21 + Android SDK              │
│  7. npx cap add android + iconos + sync      │
│  8. Build APK y AAB (gradlew)                │
│  9. Upload artifacts (GitHub Actions)        │
│ 10. Upload APK/AAB a Cloudflare R2           │
└──────────────────────────────────────────────┘
        ↓
APK público en el bucket R2 del entorno
```

### Triggers del Workflow

El workflow se ejecuta automáticamente cuando:

| Evento | Entorno | Acción |
|--------|---------|--------|
| `push` a `main` | production | Build + subida a R2 (`laravertex.apk`) |
| `push` a `staging` | staging | Build canary + subida a R2 (`laravertex-canary.apk`) |
| Tag `v*.*.*` | production | Build + R2 + copia versionada (`laravertex-v1.0.1.apk`) + iOS |
| `workflow_dispatch` | input `target_env` | Build manual del entorno elegido |

La rama `dev` no genera builds. Cada entorno tiene su propio `applicationId`, así que la canary y la de producción se instalan en paralelo en el mismo dispositivo.

---

## Generación de APK

### Distribución vía Cloudflare R2

Los binarios se publican en un bucket de Cloudflare R2 para que la descarga sea pública aunque el repositorio sea privado. GitHub Releases ya no distribuye el APK.

1. **Producción (`main`)**:
   - Cada push o merge a `main` compila el APK de producción (`com.laravertex.app`) y lo sube como `laravertex.apk`.
   - El nombre es estable, así que el enlace público siempre sirve la última compilación.

2. **Staging (`staging`)**:
   - Cada push a `staging` compila la canary (`com.laravertex.app.canary`) y la sube como `laravertex-canary.apk`.
   - Al usar otro `applicationId`, convive instalada junto a la de producción.

3. **Versiones (Git Tags)**:
   - Cuando quieras publicar una versión formal numerada (ej. `v1.0.1`):
     ```bash
     git tag -a v1.0.1 -m "Release v1.0.1"
     git push origin v1.0.1
     ```
   - Además del nombre estable, sube copias versionadas `laravertex-v1.0.1.apk` y `laravertex-v1.0.1.aab`.
   - `versionName` y `versionCode` se generan con el número de run; en staging se agrega el sufijo `-canary`.

4. **Disponibilidad de los Archivos**:
   - **En tu Web**: el botón *"Descargar APK"* lee `APK_DOWNLOAD_URL` del `.env`. Apuntalo a `{R2_PUBLIC_URL}/laravertex.apk`.
   - **En R2**: `{R2_PUBLIC_URL}/laravertex.apk` (producción) y `{R2_PUBLIC_URL}/laravertex-canary.apk` (staging).
   - **En GitHub Actions Artifacts**: historial de compilaciones, retenido 30 días y solo visible para miembros del repo.

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
# Linux
base64 -w0 mi-app.keystore > keystore.b64

# macOS
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
│   ├── cors.php               # Configuración CORS
│   └── mobile.php             # URL pública de descarga del APK
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

# Build completo de Android (incluye build web, generación de iconos y sync)
npm run build:android

# Generar iconos y splash screens manualmente
npm run cap:icons
# o con Make
make capacitor-icons
```

### 🎨 Personalización de Iconos y Splash Screen

Para cambiar la identidad de la app móvil en cualquier nuevo proyecto:

1. **Reemplaza el logo**:
   - Ubicación por defecto: `public/mobile-icons/logo.png` (PNG transparente recomendado, min 512x512 o 1024x1024).
   - Opcionalmente puedes usar `public/logo.png`, `public/apple-touch-icon.png` o `public/favicon.svg`.
2. **Generar los recursos**:
   - Ejecuta `npm run cap:icons` o compila con `npm run build:android`.
   - El script genera automáticamente:
     - Iconos normales y redondeados para todas las densidades (`mdpi`, `hdpi`, `xhdpi`, `xxhdpi`, `xxxhdpi`).
     - Iconos adaptativos de Android (`ic_launcher_foreground.png`) con zona segura (safe-zone 66%) para evitar recortes en Android 8+.
     - Splash screens en todas las resoluciones y orientaciones (portrait y landscape).
     - Elimina los vectores XML por defecto de Capacitor para garantizar que no se sobreescriba tu icono en dispositivos modernos.

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

**Causa**: No se ha configurado el secret `PRODUCTION_URL` (producción) o `STAGING_URL` (staging) en GitHub.

**Solución**:
1. Ve a Settings → Secrets → Actions
2. Agrega `PRODUCTION_URL` y `STAGING_URL` con las URLs de cada servidor

### Error: "No R2 bucket configured for 'staging'"

**Causa**: Faltan `R2_BUCKET_STAGING` / `R2_BUCKET_PRODUCTION`.

**Solución**: Crealos en Cloudflare R2 y agregalos como secrets. Si faltan `R2_ACCOUNT_ID`, `R2_ACCESS_KEY_ID` o `R2_SECRET_ACCESS_KEY`, el paso se salta con un warning en vez de fallar.

### Error: "App not installed" al actualizar el APK

**Causa**: Cada run firma con una keystore de debug distinta, así que Android no reconoce la actualización como la misma app.

**Solución**: Configurá una keystore de release estable (ver [Generar Keystore](#generar-keystore-para-android)) y cambiá `assembleDebug` por `assembleRelease` en el workflow.

### Error: "CORS policy blocked"

**Causa**: Tu servidor Laravel no permite conexiones desde la app móvil.

**Solución**:
1. Agrega `capacitor://localhost` a `allowed_origins` en `config/cors.php`
2. Agrega `http://localhost` para Android

### Error: "Build failed in gradlew"

**Causa**: Problemas con la configuración de Android SDK.

**Solución**:
1. Verifica que Java 21 esté configurado en el workflow
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

**Última actualización**: 2026-11-19
