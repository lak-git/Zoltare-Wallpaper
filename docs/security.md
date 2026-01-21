# Security Notes and Threat Model

This document summarises primary threats considered for the Zoltare application and the mitigations implemented.
It also documents how this project addresses common web application risks.

## Threats

- Credential theft: stolen passwords or leaked tokens.
- Token leakage: stolen personal access tokens used to call APIs.
- CSRF / session hijacking for the web UI.
- XSS: malicious scripts injected into user-provided fields.
- Unauthorized access to wallpaper assets.
- Data exposure in backups or logs.

## Mitigations (high level)

- Password storage: passwords are never stored in plaintext; they are hashed via Laravel's `Hash` facade.
- Token-based API: the API is protected using Laravel Sanctum personal access tokens.
- CSRF: web routes are protected via CSRF middleware and `@csrf` tokens in forms.
- XSS: Blade escapes output by default; input is validated server-side.
- Asset protection: downloads are gated by authorization checks.
- TLS/HTTPS: enforce HTTPS in production.

## 1) SQL Injection (and query injection)

### What we use

Zoltare primarily uses MongoDB as its default database connection (`DB_CONNECTION=mongodb`).
However, some components (notably Sanctum's `personal_access_tokens`) are stored using a relational table (`personal_access_tokens`).

### How we prevent injection

- Prefer Eloquent / Query Builder methods (`where`, `first`, `find`, route-model binding) rather than concatenating strings into raw SQL.
  Laravel's query builder uses parameter binding, which protects against classic SQL injection.
- Avoid `DB::raw`, `whereRaw`, and manual string interpolation. If a raw query is unavoidable, always use bindings.
- Validate and constrain user input before it reaches a query:
  - IDs are treated as strings (especially for MongoDB keys) and should come from route-model binding when possible.
  - Numeric values (e.g. price) are validated and cast.

### Secure patterns (recommended)

- Use request validation (`$request->validate(...)`) before calling `Model::create(...)` or building queries.
- Use pagination and limit output to reduce data exposure.

### Risk notes

- Even though most data is in MongoDB, "query injection" can still happen if untrusted input is used to build query operators.
  Do not allow user-controlled keys/operators (e.g. `$where`, `$regex`, nested arrays) to be injected directly into query constraints.

## 2) CSRF

### Web UI

- CSRF protection is enabled for the `web` middleware group.
- HTML forms include CSRF tokens using Blade's `@csrf` directive.
- The logout route also regenerates the session token after logout to reduce session fixation risk.

Implementation references:

- `web` middleware group includes `\App\Http\Middleware\VerifyCsrfToken`.
- Forms in `resources/views/**` use `@csrf`.

### API

- API routes are authenticated via bearer tokens (`auth:sanctum`) and are not intended to rely on browser cookies.
  This avoids CSRF exposure for the JSON API.

### Sanctum SPA mode (if enabled)

The `api` middleware group includes `EnsureFrontendRequestsAreStateful`. If the project is used as a first-party SPA with cookie-based auth:

- Configure stateful domains via `SANCTUM_STATEFUL_DOMAINS` (see `config/sanctum.php`).
- Fetch `/sanctum/csrf-cookie` from the frontend and send the `X-XSRF-TOKEN` header on state-changing requests.

## 3) Hashing Passwords

### Current behavior

- Registration hashes passwords before storing them:
  - `resources/views/livewire/pages/auth/register.blade.php` uses `Hash::make(...)`.
- Password resets also hash the new password:
  - `resources/views/livewire/pages/auth/reset-password.blade.php` uses `Hash::make(...)`.
- Users changing their password via profile settings hash the new password:
  - `resources/views/livewire/profile/update-password-form.blade.php` uses `Hash::make(...)`.
- Authentication uses `Auth::attempt(...)`, which compares the plaintext password against the hashed value using Laravel's hasher.

### Requirements / guidance

- Never store or log plaintext passwords.
- Use strong password validation rules (`Rules\Password::defaults()` is already used in the auth UI).
- If you change hashing algorithms, rely on Laravel's built-in hashing configuration and rehashing strategy.

## 4) User Roles

### Role model

- Users have a `role` attribute (stored on the user document).
- The `User::isAdmin()` helper returns true when `role === 'admin'`.
- New users are assigned `role = 'user'` at registration.

### Authorization enforcement

- Admin pages are under the `/admin` route prefix and are protected by:
  - `auth` middleware (user must be logged in)
  - `admin` middleware alias, which maps to `\App\Http\Middleware\EnsureUserIsAdmin` (configured in `bootstrap/app.php`)
- Requests that mutate wallpaper data are validated via FormRequest classes.
  Access control is expected to be enforced by route middleware (admin group), not the FormRequest `authorize()` method.

### Operational guidance

- Promote a user by setting `role` to `admin` in the database.
- Consider expanding roles/permissions using Gates/Policies if you need more than a simple admin/user split.

## 5) Sanctum

### What Sanctum is used for

Zoltare uses Laravel Sanctum for personal access tokens to authenticate API requests.

### How tokens are issued

- `POST /api/token` accepts `email` + `password` and returns a bearer token.
- The token is created via `$user->createToken('api-token')->plainTextToken`.

### How API routes are protected

- API routes are grouped under `Route::middleware('auth:sanctum')`.
- Clients must send `Authorization: Bearer <token>`.

### Token storage details (important for this project)

- The `User` model is MongoDB-backed.
- Sanctum tokens are persisted using the SQL `PersonalAccessToken` model.
- `App\Models\User::createToken(...)` is overridden to explicitly create and save a `PersonalAccessToken` record and return the plaintext token.
  This prevents MongoDB connection issues when creating Sanctum tokens.

### Lifecycle and hardening guidance

- Store tokens securely on clients (never in localStorage for browser apps if you can avoid it; prefer httpOnly cookies for first-party SPAs).
- Revoke tokens on suspicion or logout (`$user->tokens()->delete()` or per-token deletion).
- Consider scoping abilities (least privilege) and adding expiration (`expires_at`) if tokens should not be long-lived.

## Operational guidance

- Do not commit secrets into git; keep `.env` out of version control.
- Rotate API keys / webhook secrets when a leak is suspected.
- Restrict access to logs and backups; encrypt backups where possible.
