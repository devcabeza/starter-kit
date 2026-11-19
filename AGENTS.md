# AGENTS.md

Laravel 13 + Livewire 4 starter kit ("Laravertex"): passwordless magic-link auth, Polar payments, and a Capacitor mobile shell. PHP 8.4, Node 22, Pest 5, Tailwind 4 + daisyUI 5. No `vendor/` or `node_modules/` in a fresh checkout.

## Commands

- `composer setup` — first-time install: Composer, `.env`, app key, migrations, npm install/build, git hooks. Needs reachable Postgres/Redis, so start `make up` first when using Docker.
- `composer dev` — starts the dev servers (`php artisan dev`).
- `composer test` — the CI gate, run it before finishing: `config:clear` → `pint --test` → `phpstan analyse` → `php artisan test`. CI runs `composer setup` then `composer ci:check`.
- Single test: `php artisan test --filter="test name"` (or `vendor/bin/pest --filter="..."`).
- `composer lint` (Pint fix) / `composer lint:check` / `composer types:check` (PHPStan level 7).
- Git pre-commit hook (`.githooks/pre-commit`, enabled by `composer setup`) runs `pint --test --dirty` and `phpstan analyse`. `--dirty` checks only changed files; `composer test` is the full gate.
- `make help` lists Docker/Artisan shortcuts. `make up` serves app on `:8000`, Vite on `:5173`; host ports: Postgres `:5433`, Redis `:6380`, Mailpit UI `:8026`.

## Architecture (enforced by `tests/Feature/ArchitectureTest.php`)

Layers and dependency direction:

- `app/Domain` — pure PHP (models, exceptions). Must not use Eloquent, `App\Infrastructure`, `App\Http`, or `App\Livewire`.
- `app/Application` — actions and DTOs. Must not use `App\Infrastructure`, `App\Http`, or `App\Livewire`.
- `app/Ports` — `In`/`Out` interfaces; implementations live in `app/Infrastructure` and are bound in `AppServiceProvider`.
- `app/Livewire` + `resources/views/livewire` — presentation; `app/Http` — HTTP layer.
- `declare(strict_types=1)` is required in `App\Domain`, `App\Application`, and `App\Ports`. No `dd`, `dump`, `ray`, or `var_dump` anywhere.

Consistency first: follow the pattern in sibling files over generic Laravel defaults. Application actions expose `execute(...)`, DTOs are `final readonly` classes, and there are no Form Requests — Livewire validates inline with `#[Validate]`/`$this->validate()`.

Livewire components are class-based: `app/Livewire/...` plus `resources/views/livewire/...`. Do not create Livewire v4 single-file components; `php artisan make:livewire Foo` defaults to an SFC in `resources/views/components`, so pass `--class`. Use `wire:key` in loops and `wire:model.live` only when real-time updates are intended.

Gotcha: there are two user types. `App\Models\User` is the Eloquent auth model (also `config/auth.php`); `App\Domain\User\Models\User` is an immutable domain object. Do not merge or swap them.

New feature flow: domain model/exception → application action + DTO → outbound port interface → infrastructure adapter → container binding → Livewire component/route → Pest test.

## Routing and integrations

- Root web routes are `routes/web/index.php`, which requires `auth.php`, `dashboard.php`, and `settings.php`. New feature routes belong in their own file under `routes/web/`, not a nonexistent `routes/web.php`.
- API lives in `routes/api/v1/index.php` with prefix `api/v1` (wired in `bootstrap/app.php`).
- Auth is passwordless magic link (6-digit code + link) with rate limiting; see `app/Livewire/Auth` and `app/Application/Auth`.
- Polar payment webhooks bypass CSRF (`polar/*` in `bootstrap/app.php`) and are handled by `app/Listeners/PolarEventListener.php`; config in `config/polar.php`.
- Mail can use the custom `sendrix` transport registered in `AppServiceProvider` (`app/Mail/SendrixTransport.php`, `MAIL_MAILER=sendrix`).
- Queues run through Horizon (`worker` container); schedule entries are in `routes/console.php`.
- Heavy work must be queued, never inline: mail (Mailables already implement `ShouldQueue`), exports, file processing, and third-party calls go through jobs/Horizon. Horizon requires Redis; keep job `timeout` < supervisor `timeout` < `retry_after`.

## Frontend and mobile

- Never inline daisyUI classes (`btn btn-primary`, `card ...`) in page or Livewire views. Use the wrapper components in `resources/views/components/` (`<x-button>`, `<x-input>`, `<x-card>`, ...); create a new wrapper if one is missing. Rules: `.ai/rules/index.md` and `.ai/rules/components/daisyui-wrappers.md`.
- Wrappers use `@props([...])` plus `$attributes->class([...])` and keep a 44px minimum touch target.
- Tailwind is v4 CSS-first: `@import 'tailwindcss'`, `@plugin 'daisyui'`, and `@theme` live in `resources/css/app.css`. There is no `tailwind.config.js`; do not create one or copy v3 config examples.
- Mobile layouts use the project's safe-area utilities from `resources/css/app.css` (`pt-safe`, `pb-safe`, `p-safe`, `min-h-screen-safe`) on headers and full-screen containers.
- `uiux-design-trends` is visual guidance only; wrapper rules and the v4 CSS-first setup win over its raw-class and v3-config snippets.
- User-facing copy is Spanish; code, identifiers, and comments are English.
- Vite is wrapped by `vite-plus`; `vite.config.js` builds to `dist/` and a plugin copies it to `public/build/`. Both are generated and gitignored — never edit them. Capacitor consumes `dist/`, so run `npm run build` before `npm run cap:sync`.
- Mobile setup: `docs/MOBILE_BUILD_SETUP.md`; `android/` and `ios/` are generated and gitignored. Tagged `v*.*.*` builds and merged PRs trigger the mobile workflows.

## Testing notes

- Write a Feature test first for every behavior reachable through a request; write unit tests only for logic that does not use the framework. Test observable behavior, not implementation details.
- Real-browser tests are unavailable (`pest-plugin-browser` is not installed); do not add it unless the user asks.
- `tests/Pest.php` applies `RefreshDatabase` to all Feature tests automatically.
- `phpunit.xml` runs tests on SQLite `:memory:`; CI runs Postgres 17 with PHP 8.4. Do not assume a local Postgres is needed for tests.
- `tests/Feature/ArchitectureTest.php` enforces the layer rules, strict types, and banned debug helpers. `tests/Feature/Components/DaisyUiWrappersTest.php` pins wrapper markup, touch targets, and attribute passthrough — keep both green when touching architecture or components.

## Debugging

- Prefer Telescope over the web UI: `php artisan telescope:list request` (also `exception`, `job`, `query`, `cache`), then `php artisan telescope:show <uuid>` or `latest:request`. Use `--json` for exact values; `DUP` flags N+1 query candidates. Telescope is disabled during tests.

## Other references

- The Laravel, Livewire, Tailwind, testing, Horizon, background-processing, and Telescope skills were folded into the sections above and removed; do not recreate them from generic templates. Remaining repo skills live in `.agents/skills/` and are indexed in `.atl/skill-registry.md`.
- Conditional skills: `sendrix` for email changes; `erp-module-standard` before adding a dashboard module (it assumes `organization_id`/`TenantContextInterface`, which this starter kit does not have yet); `landing-page-architect` + `elite-copywriter` for landing/marketing pages; `infer-conventions` only when the user explicitly asks to record conventions.
- CI: `.github/workflows/tests.yml` (PRs to `main`) and `.github/workflows/build-mobile.yml` (tags/merged PRs).
