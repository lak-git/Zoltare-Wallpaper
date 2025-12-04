## Zoltare Wallpaper Marketplace

Zoltare is a modern Laravel 12 + Livewire e-commerce experience for discovering,
buying, and managing digital wallpapers. The original vanilla PHP project was
migrated to this stack with MongoDB persistence, Stripe-powered checkout, local
storage for the assets, and a refreshed UI that respects the original color
palette (indigo accents + neutral grays) while supporting dark mode.

### Features

- Breeze authentication with Livewire UI and role-based admin access
- MongoDB storage for users, wallpapers, purchases, and error logs
- Local wallpaper uploads with secure, access-controlled downloads
- Stripe Checkout for premium wallpapers, including webhook fulfillment
- Modern landing page, gallery, admin dashboard, and upload flow with dark mode
- Automated feature tests covering auth, purchases, and admin CRUD

---

## Getting Started

### Requirements

- PHP 8.2+
- MongoDB 6+
- Node 20+ / npm 10+
- Stripe account with API + webhook keys

### Installation

```bash
composer install
cp .env.example .env   # Populate with the values described below
php artisan key:generate
npm install
npm run build          # or npm run dev for hot reload
php artisan storage:link
php artisan migrate    # optional for job/cache tables (Mongo uses its own collections)
php artisan db:seed
```

### Environment Variables

| Key | Description |
| --- | --- |
| `APP_URL` | Base URL used for signed download links and Stripe redirects |
| `MONGODB_URI` | Full MongoDB connection string (include credentials if required) |
| `MONGODB_DATABASE` | Database name; defaults to `zoltare` |
| `STRIPE_KEY` / `STRIPE_SECRET` | Live or test keys used for Checkout |
| `STRIPE_WEBHOOK_SECRET` | Secret for verifying `checkout.session.completed` events |
| `STRIPE_SUCCESS_URL` / `STRIPE_CANCEL_URL` | Optional overrides for redirect URLs |

> Tip: Leave `DB_CONNECTION=mongodb` so Laravel uses MongoDB collections by
default. SQL migrations (jobs/cache) still run when needed.

---

## Project Structure

- `app/Models` – MongoDB models for wallpapers, purchases, errors, and users
- `app/Http/Controllers` – Landing, gallery, upload, admin, Stripe, and download flows
- `resources/views` – Tailwind-based pages + partials with dark-mode toggle
- `routes/web.php` – Public site, member uploads, and admin panel routes
- `routes/api.php` – Stripe webhook endpoint
- `tests/Feature` – Coverage for auth, uploads, purchases, and admin CRUD

Wallpapers are stored under `storage/app/wallpapers`. Files are never exposed
through `public/storage`; downloads are streamed after ownership checks.

---

## Stripe + Webhooks

Run the Stripe CLI to relay events during development:

```bash
stripe listen --forward-to http://localhost:8000/api/stripe/webhook
```

Set `STRIPE_WEBHOOK_SECRET` to the secret returned by the CLI (or the dashboard)
so the webhook signature can be verified.

---

## Testing

```bash
php artisan test
```

The suite seeds canonical data, fakes Stripe when appropriate, and ensures admin
authorization plus purchase gating behave as expected.

---

## Security

- See `docs/security.md` for a short threat model, mitigations, and operational
	guidance (HTTPS, token lifecycle, encryption, CSRF/XSS mitigations).

