# Route Directory Structure

## Required Structure

```
routes/
├── web/                    # Web routes (session-based auth)
│   ├── index.php          # Home/welcome routes
│   ├── auth.php           # Authentication (login, register, password)
│   ├── dashboard.php      # User dashboard
│   ├── settings.php       # User settings
│   ├── admin.php          # Admin panel routes
│   └── {feature}.php      # One file per feature
├── api/                    # API routes (token/Sanctum auth)
│   ├── v1/                # API version 1
│   │   ├── index.php      # API v1 root routes
│   │   ├── users.php      # User endpoints
│   │   └── {resource}.php # One file per resource
│   └── v2/                # API version 2 (future)
├── console.php            # Artisan command routes
└── channel.php            # Broadcast channels (if needed)
```

## Rules

1. **One file per feature/domain** - Never put all routes in one file
2. **Feature-based grouping** - Group by business feature, not HTTP method
3. **Version API routes** - Always version API routes (v1, v2)
4. **Separate web and API** - Different auth mechanisms, different files
5. **Feature files are optional** - Only create when feature has routes

## Web Routes (`routes/web/`)

| File | Purpose | Example Routes |
|------|---------|----------------|
| `index.php` | Home, public pages | `/`, `/about`, `/contact` |
| `auth.php` | Authentication | `/login`, `/register`, `/forgot-password` |
| `dashboard.php` | User dashboard | `/dashboard`, `/notifications` |
| `settings.php` | User settings | `/settings/profile`, `/settings/password` |
| `admin.php` | Admin panel | `/admin/users`, `/admin/settings` |
| `{feature}.php` | Feature-specific | `/posts`, `/comments`, `/orders` |

## API Routes (`routes/api/`)

| File | Purpose | Example Routes |
|------|---------|----------------|
| `v1/index.php` | API root, version routing | `/api/v1/health` |
| `v1/users.php` | User endpoints | `/api/v1/users`, `/api/v1/users/{user}` |
| `v1/{resource}.php` | Resource endpoints | `/api/v1/posts`, `/api/v1/comments` |

## Naming Pattern

- Feature files use lowercase, singular or plural nouns
- Use descriptive names: `auth.php`, not `authentication.php`
- Keep file names short but clear
