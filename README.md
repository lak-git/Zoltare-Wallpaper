# Zoltare Wallpaper

Zoltare is a modern Laravel 12 + Livewire Volt experience for discovering,
buying, and managing digital wallpapers. The original vanilla PHP project was
migrated to this stack with MongoDB persistence, a simple simulated checkout
that records purchases in MongoDB, local storage for the assets, and a refreshed
UI that respects the original color palette (indigo accents + neutral grays)
while supporting dark mode.

---

## Features

- Breeze Sanctum Authentication with Livewire Volt UI and role-based admin access
- MongoDB storage for users, wallpapers, purchases, and error logs
- Local wallpaper uploads with secure, access-controlled downloads
- Simulated checkout for premium wallpapers with purchases recorded in MongoDB
- Modern landing page, gallery, admin dashboard, and upload flow with dark mode
- Automated feature tests covering auth, uploads, purchases, and admin CRUD

---

## Getting Started

### Requirements

- PHP 8.2+
- MongoDB 6+
- Node 20+ / npm 10+

### Installation

```bash
composer install
cp .env.example .env   # Populate with the values described below
php artisan key:generate
npm install
npm run build          # or npm run dev for hot reload
php artisan storage:link
php artisan migrate    # optional for job/cache tables (MongoDB collections are created automatically)
php artisan db:seed
```

### Environment Variables

| Key | Description |
| --- | --- |
| `APP_URL` | Base URL used for signed download links and image URLs |
| `DB_CONNECTION` | Set to `mongodb` so MongoDB is used for all app data |
| `MONGODB_URI` | Full MongoDB connection string (include credentials if required) |
| `MONGODB_DATABASE` | Database name; defaults to `zoltare` |

> Tip: Keep `DB_CONNECTION=mongodb` so Laravel uses MongoDB collections for
all application data.

---

## Project Structure

- `app/Models` – MongoDB models for wallpapers, purchases, errors, and users
- `app/Http/Controllers` – Landing, gallery, upload, checkout, admin, and download flows
- `resources/views` – Tailwind-based pages + partials with dark-mode toggle
- `routes/web.php` – Public site, member uploads, checkout, and admin panel routes
- `routes/api.php` – JSON API endpoints for issuing tokens and listing wallpapers
- `tests/Feature` – Coverage for auth, uploads, purchases, and admin CRUD

Wallpapers are stored under `storage/app/wallpapers`. Files are never exposed
through `public/storage`; downloads are streamed after ownership checks.

---

## Testing

```bash
php artisan test
```

The suite ensures authentication, uploads, purchases, and admin authorization
and gating behave as expected.

---

## Security

- See `docs/security.md` for a short threat model, mitigations, and operational
  guidance (HTTPS, token lifecycle, encryption, CSRF/XSS mitigations).

