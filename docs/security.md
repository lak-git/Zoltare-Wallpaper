# Security Notes and Threat Model

This document summarises primary threats considered for the Zoltare application
and the mitigations implemented or recommended.

Threats
- Credential theft: stolen passwords or leaked tokens.
- Token leakage: stolen personal access tokens used to call APIs.
- CSRF / session hijacking for web UI.
- XSS: malicious scripts injected into user-provided fields.
- Unauthorized access to wallpaper assets.
- Data exposure in backups or logs.

Mitigations
- Passwords: all passwords are hashed using Laravel's default hasher (bcrypt/argon2)
  via `Hash::make` and factories use hashed passwords.
- Token-based API: use Laravel Sanctum personal access tokens. Tokens should be
  stored client-side securely and revoked on suspicion. Server-side revocation
  is supported via `user->tokens()->delete()` or per-token deletion.
- CSRF: Blade templates include CSRF tokens for form POSTs. API endpoints
  use token-based auth avoiding CSRF exposure for JSON endpoints.
- XSS: use Blade escaping for output. Validate and sanitize user input server-side.
- Asset protection: wallpaper downloads are streamed via controller checks ensuring
  ownership/authorization before sending files.
- TLS/HTTPS: enforce HTTPS in production and set `APP_URL` to `https://...`.

Operational guidance
- Do not commit secrets into `git`. Keep `.env` out of version control.
- Rotate API keys and Stripe webhook secret when a leak is suspected.
- Configure backups with encryption and restrict access via IAM/ACLs.

Sanctum token lifecycle
- Issue tokens with limited abilities when appropriate. Revoke tokens when users
  log out or when admin revokes access. Consider token expiry policies (store
  expiry metadata and purge tokens older than desired window).
