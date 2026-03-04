# Betel Info — Claude Project Config

## Project Overview

**Betel Info** is a monorepo containing:
- **`apps/web/`** — React/Vite PWA for *Biserica Betel Cluj*. Displays church events, announcements, and community info. Page config is fetched from the Laravel API; content feed is still a static JSON file.
- **`apps/admin/`** — Laravel 12 + Breeze PHP admin app. DB-backed CMS with secure authentication. Serves branding/theme via a public API. `content.json` is still the source of truth for content items.

---

## Monorepo Structure

```
betel-info/
├── .gitignore        (monorepo-aware)
├── CLAUDE.md
├── package.json      (root orchestrator — no workspaces)
└── apps/
    ├── web/          (React/Vite PWA)
    └── admin/        (Laravel 12 + Breeze)
```

---

## Tech Stack

### Web App (`apps/web/`)

| Layer | Technology |
|---|---|
| Framework | React 18.3.1 |
| Build tool | Vite 5.4.10 |
| Language | TypeScript 5.6.2 (strict) |
| Styling | Tailwind CSS 3.4.14 |
| PWA | vite-plugin-pwa + Workbox 7.3.0 |
| Markdown | react-markdown 9.0.1 |

### Admin App (`apps/admin/`)

| Layer | Technology |
|---|---|
| Framework | Laravel 12 (PHP 8.3) |
| Auth | Laravel Breeze (Blade stack) |
| Testing | Pest |
| DB (local) | SQLite |
| DB (production) | PostgreSQL 16 |
| Containerisation | Docker + Docker Compose |

---

## Architecture

### Web App Data Flow

```
apps/web/src/main.tsx
  └─ App.tsx
       └─ useContent hook
            ├─ fetches VITE_API_BASE_URL/api/page-config/{slug?}  (branding & theme — Laravel API)
            ├─ fetches VITE_CONTENT_BASE_URL/content.json  (feed items — still static)
            ├─ injects CSS custom properties for theming
            └─ returns { pageConfig, items, loadState }

App.tsx
  ├─ SplashScreen  (loading state)
  ├─ Error UI      (error state)
  └─ Header + ContentFeed  (ready state)

ContentFeed
  ├─ RichText   (markdown)
  ├─ Card       (event/link card, standalone or in group)
  ├─ Poster     (9:16 vertical image, standalone or in group)
  └─ Group      (container with smart grid layout)
```

### Key Web App Files

| Path | Purpose |
|---|---|
| `apps/web/src/App.tsx` | Root shell; handles loading/error states |
| `apps/web/src/hooks/useContent.ts` | Fetches both JSON files; injects theme |
| `apps/web/src/types/index.ts` | All TypeScript types for content items |
| `apps/web/src/components/ContentFeed.tsx` | Routes each item to its component |
| `apps/web/src/components/Card.tsx` | Event/link card (thumbnail, date, CTA) |
| `apps/web/src/components/Poster.tsx` | Vertical 9:16 poster with optional link |
| `apps/web/src/components/Group.tsx` | Smart grouped container |
| `apps/web/src/components/RichText.tsx` | Prose-styled markdown renderer |
| `apps/web/src/components/Header.tsx` | Logo, title, description |
| `apps/web/src/components/SplashScreen.tsx` | Animated loading screen |
| `apps/web/public/content.json` | Feed content: array of content items |

### Key Admin App Files

| Path | Purpose |
|---|---|
| `apps/admin/app/Models/Location.php` | Location model (owns all page config fields) |
| `apps/admin/app/Models/ContentItem.php` | Content item model |
| `apps/admin/app/Http/Controllers/Api/PageConfigController.php` | Public API: GET /api/page-config/{slug?} |
| `apps/admin/app/Models/User.php` | User model (with role + location) |
| `apps/admin/database/migrations/` | All DB migrations |
| `apps/admin/database/seeders/DatabaseSeeder.php` | Seeds locations, page configs, admin user |
| `apps/admin/.env` | Local config (SQLite, not committed) |
| `apps/admin/.env.example` | Production template (PostgreSQL) |
| `apps/admin/Dockerfile` | PHP 8.3 container |
| `apps/admin/docker-compose.yml` | App + PostgreSQL 16 |

---

## Content Types

All items live in `apps/web/public/content.json` as a flat array. Each item has a `type` field.

> **Note:** Live `content.json` uses the key `cta` for card CTA labels, but the TypeScript type says `linkText`. The DB column is `link_text`. Seeders must read from `cta` when importing JSON.

### `richtext`
Markdown rendered with Tailwind prose classes.

```json
{ "type": "richtext", "content": "## Heading\nMarkdown here..." }
```

### `card`
Event or link card. Fields: `title`, `description`, `thumbnail?`, `date?`, `time?`, `link?`, `linkText?` (defaults to `"Află mai multe"`).

```json
{ "type": "card", "title": "...", "description": "...", "date": "2026-03-01", "link": "https://..." }
```

### `poster`
Vertical 9:16 image. Fields: `title`, `image`, `link?`.

```json
{ "type": "poster", "title": "...", "image": "/poster.jpg" }
```

### `group`
Container for multiple cards/posters. Fields: `title?`, `items` (array of card/poster objects). Layout is auto-detected:
- Poster-only → 2–3 column responsive grid
- Card-only → 1–2 column grid
- Mixed → single column

```json
{ "type": "group", "title": "Evenimente", "items": [...] }
```

---

## Admin App — Database Schema Overview

| Table | Purpose |
|---|---|
| `locations` | Church locations — owns all page config: slug, title, description, logo_path, 6 theme colors, is_default |
| `content_items` | Feed items (self-referential for groups via parent_id) |
| `content_item_locations` | Pivot: item ↔ location (empty = visible everywhere) |
| `users` | Breeze users with `role` (admin/editor) and optional `location_id` |

---

## Theming

Theme is fetched from the Laravel API (`/api/page-config/{slug}`) and injected at runtime as CSS custom properties on `:root`:

```
--color-brand         (primary)
--color-brand-light
--color-brand-dark
--color-accent        (optional)
--color-accent-light
--color-accent-dark
```

Tailwind uses these via `brand-*` and `accent-*` utilities defined in `apps/web/tailwind.config.ts`.

---

## PWA & Caching

Service worker is auto-generated by Workbox with three strategies:

| Resource | Strategy | Max Age |
|---|---|---|
| App shell (JS/CSS/HTML/icons) | Cache-first | Hashed filenames |
| `/content.json` | Stale-while-revalidate | 7 days |
| Images | Cache-first | 30 days |

---

## Page Config API

`GET /api/page-config/{slug?}` — public, no auth required. Returns the page.json shape:

```json
{
  "logo": "/locations/betel-centru/logo.jpg",
  "title": "Betel Centru",
  "description": "...",
  "theme": {
    "primaryColor": "#0f0f0f",
    "primaryLightColor": "#f2f2f2",
    "primaryDarkColor": "#000000",
    "accentColor": "#ff6200",
    "accentLightColor": "#fff0e6",
    "accentDarkColor": "#cc4e00"
  }
}
```

Omit slug to get the `is_default = true` location. Set `VITE_API_BASE_URL` in the web app to point at the Laravel app.

---

## Scripts

### Monorepo root
```bash
npm run web:dev      # Start web dev server
npm run web:build    # Build web app
npm run web:preview  # Preview web production build
```

### Web app (`cd apps/web`)
```bash
npm run dev      # Vite dev server
npm run build    # tsc -b && vite build
npm run preview  # Preview production build
```

### Admin app (`cd apps/admin`)
```bash
php artisan serve          # Dev server at localhost:8000
php artisan migrate        # Run migrations
php artisan db:seed        # Seed database
php artisan test           # Run Pest tests
docker compose up          # Start app + PostgreSQL via Docker
```

---

## Commits

- All commits must be **atomic** (one logical change per commit)
- Follow the **gitmoji** standard: begin every commit subject with the appropriate emoji
  - Examples: `✨ add poster link support`, `🐛 fix card date formatting`, `♻️ refactor content fetching hook`, `💄 improve splash screen animation`
  - Reference: https://gitmoji.dev
