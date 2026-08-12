# BrokersCourt — Deployment Notes & Issue Report

> Prepared during repo cleanup + staging preparation.
> Repo: https://github.com/coderbiozed/brokerscourt
> Branches: `development` (work) → `staging` (staging server) → `main` (production)

---

## 1. Critical issues found (and status)

These are things that should **NEVER** exist on a live site.

| # | Issue | Why it's dangerous | Status |
|---|-------|--------------------|--------|
| 1 | `.env` committed & pushed to GitHub | Leaks DB, mail, reCAPTCHA & app secrets publicly | ✅ Removed from tracking + purged from git history |
| 2 | `APP_DEBUG=true` in production | Exposes stack traces, env vars, DB paths to any visitor | ✅ Set to `false` |
| 3 | `public/index.php` hand-edited to force `display_errors` | Prints raw PHP errors to the public | ✅ Reverted to stock Laravel bootstrap |
| 4 | Real secrets in `.env` (mail password, reCAPTCHA secret, APP_KEY) | Were public on GitHub = compromised | ⚠️ **ACTION NEEDED: rotate them** (see §5) |
| 5 | No forced HTTPS | Traffic/cookies can be sent in clear text | ✅ Added HTTPS redirect in `public/.htaccess` |
| 6 | No security headers | Clickjacking, MIME sniffing, referrer leak risks | ✅ Added X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy, HSTS |
| 7 | Session cookies not hardened | Session hijacking risk | ✅ Added SESSION_SECURE_COOKIE / SAME_SITE / ENCRYPT (staging env) |
| 8 | Sensitive files web-accessible | `.env`, `.git`, `*.sql`, `*.sqlite`, `*.bak` could be downloaded | ✅ Blocked via `<FilesMatch>` in `public/.htaccess` |

---

## 2. Git / version-control issues

| # | Issue | Impact | Status |
|---|-------|--------|--------|
| 1 | `.gitignore` was 100% commented out | Nothing was ignored — everything got tracked | ✅ Restored proper Laravel `.gitignore` |
| 2 | `vendor/` committed (~22,000 files) | Huge repo bloat; deps should be installed, not tracked | ✅ Untracked (install via `composer install`) |
| 3 | `node_modules/` committed | Same bloat problem | ✅ Untracked (install via `npm install`) |
| 4 | Live SQLite DB + `database.zip` + `.sql` dump committed | Data in version control; DB can get overwritten on pull | ✅ Untracked; `*.sqlite`/`*.sql`/`*.zip` now ignored |
| 5 | `.env` tracked (see §1) | Secret leak | ✅ Untracked + history purged |
| 6 | Duplicate "Initial commit" roots / vague messages | Messy history | ⚠️ Minor — left as-is |
| 7 | Nested junk `database/database/` duplicate folder | Confusing duplicate migrations | ⚠️ Optional cleanup later |

> **Note:** git history was rewritten to remove `.env`. If anyone else clones,
> they must re-clone fresh — old local copies will have mismatched history.

---

## 3. Junk / debug files removed

Things that should never ship to a live site — all removed from the repo:

- `as $b) {`  — accidental `less` command output saved as a file
- `error_log`  — 83 KB stray log
- `fix_nullables.php`, `fix_nullables_v2.php`, `fix_lang_null.php`  — one-off patch scripts
- `.env.bak`, `.gitignore.bak`  — backup files
- `database.zip`, `DB/news_portal_project.sql`  — DB dumps
- empty `Brokers_court/` folder

---

## 4. Outdated / version concerns (plan to address later)

| Item | Current | Note |
|------|---------|------|
| Laravel | 9.x | **End-of-Life** (security support ended Feb 2024). Plan upgrade to a supported release (10/11/12). |
| PHP (local) | 8.4 | Laravel 9 only supports up to 8.2. **Staging/production must use PHP 8.2.** |
| Tailwind CSS | 2.x | Old major; current is 3/4. |
| axios | 0.25 | Old; has known advisories. |
| `fruitcake/laravel-cors` | abandoned | Works on L9; can switch to built-in CORS. |
| npm audit | 42 vulns | Dev-only deps; not shipped to users. Address during a deps refresh. |

---

## 5. ACTION REQUIRED — rotate the exposed credentials

These were public on GitHub, so treat them as compromised:

1. **Mail password** (`info@brokerscourt.com`) — reset in cPanel Email Accounts, update server `.env`.
2. **reCAPTCHA secret** — regenerate in the Google reCAPTCHA admin console.
3. **APP_KEY** — for staging, `php artisan key:generate`. For production, rotating logs users out & breaks encrypted data, so only do it deliberately.

---

## 6. Staging deployment checklist (cPanel)

**Target:** `staging.brokerscourt.com` · **DB:** SQLite · **PHP:** 8.2

### A. Subdomain
- cPanel → Subdomains → create `staging.brokerscourt.com`
- Document root → `/home/USER/staging.brokerscourt.com/public`  (must end in `/public`)
- MultiPHP Manager → set subdomain to **PHP 8.2**

### B. Get the code (SSH or cPanel Terminal / Git Version Control)
```bash
cd ~
git clone https://github.com/coderbiozed/brokerscourt.git staging.brokerscourt.com
cd staging.brokerscourt.com
git checkout staging
```

### C. Install dependencies + build assets
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run production
```

### D. Environment
```bash
cp .env.staging.example .env
php artisan key:generate
```
Confirm in `.env`:
```env
APP_ENV=staging
APP_DEBUG=false
APP_URL=https://staging.brokerscourt.com
DB_CONNECTION=sqlite
# DB_DATABASE left commented -> resolves to database/database.sqlite
DB_FOREIGN_KEYS=true
MAIL_MAILER=log
```

### E. Database (SQLite is NOT in git — upload it)
Upload `database/database.sqlite` via File Manager or scp, then:
```bash
php artisan migrate --force
chmod -R 775 storage bootstrap/cache database
```

### F. Cache (run each SEPARATELY — do not chain on one line)
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### G. Protect staging
- cPanel → Directory Privacy → password-protect the staging folder (basic auth)
- staging `robots.txt` → `Disallow: /` so it isn't indexed

### H. Verify
Open `https://staging.brokerscourt.com` (behind basic auth).
On a 500 error, check:
```bash
tail -50 storage/logs/laravel.log
```

Also hit the health endpoint after deploy:
```bash
curl -s https://staging.brokerscourt.com/health
php artisan db:health --strict
```

---

## 9. Database hardening (local + production)

### Why the local error happened
On **Windows/XAMPP**, MariaDB stores accounts in `mysql.global_priv`. If that table gets
corrupted (improper shutdown, copying data folders, disk issues), you see:

`Host 'localhost' is not allowed to connect to this MariaDB server`

This is a **local dev machine issue** — production on Linux/cPanel uses separate MySQL
users created in the hosting panel and is not affected the same way.

### Local development (XAMPP)
| Rule | Why |
|------|-----|
| `DB_HOST=127.0.0.1` | Avoids Windows localhost/socket quirks |
| `APP_ENV=local` | Never run production env locally |
| Dedicated user, not `root` | Limits blast radius if credentials leak |
| Run `scripts/setup-local-db.ps1` once | Creates `brokerscourt_local` user |
| Don't copy `C:\xampp\mysql\data` blindly | Can corrupt `global_priv` / InnoDB logs |

**One-time local setup:**
```powershell
# XAMPP MySQL must be running
.\scripts\setup-local-db.ps1
copy .env.local.example .env   # or update existing .env
php artisan config:clear
php artisan db:health
```

**If MariaDB breaks again locally:**
```powershell
Get-Process mysqld -ErrorAction SilentlyContinue | Stop-Process -Force
C:\xampp\mysql\bin\aria_chk.exe -r C:\xampp\mysql\data\mysql\global_priv
# Start MySQL from XAMPP Control Panel
```

### Production (cPanel MySQL)
| Rule | Why |
|------|-----|
| Create DB + user in cPanel → MySQL Databases | Hosting-managed credentials |
| `DB_USERNAME` ≠ `root` | Root must never be in the web app |
| Strong `DB_PASSWORD` | Required — empty password fails deploy check |
| `DB_STRICT_PRODUCTION=true` | Blocks boot/deploy with unsafe DB config |
| `APP_DEBUG=false` | No stack traces on live site |
| Use `.env.production.example` as template | Keeps secrets out of git |

**Production `.env` (MySQL on same server):**
```env
DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=brokerscourt_data
DB_USERNAME=brokerscourt_app
DB_PASSWORD=<strong-password-from-cpanel>
DB_STRICT_PRODUCTION=true
```

### Pre-deploy check (always run on server)
```bash
php artisan config:clear
php artisan db:health --strict
php artisan migrate --force
php artisan config:cache
```

Or use the helper script:
```bash
bash scripts/verify-deploy.sh
```

### Monitoring
- **Health URL:** `GET /health` → JSON `{ "status": "ok", "database": "ok" }`
- Use this in uptime monitors (Pingdom, UptimeRobot, etc.)
- Schedule cPanel **JetBackup** or manual mysqldump for `brokerscourt_data`

---

## 7. Common gotchas (already hit during setup)

- **"Database file ... does not exist"** → don't hardcode a placeholder `DB_DATABASE`
  path; leave it commented so Laravel uses `database/database.sqlite`.
- **"No arguments expected for config:cache, got route:cache"** → run cache
  commands on separate lines, not chained.
- **`cp: same file`** → harmless; the DB was already in place.
- **CSS was ~2.9 MiB** → Tailwind purge is now enabled in `tailwind.config.js`;
  `npm run production` shrinks it. If styles look broken after purge, add a `safelist`.

---

## 8. Ongoing workflow

```bash
# work on development
git add -A && git commit -m "..."
git push origin development

# promote to staging
git checkout staging
git merge development
git push origin staging

# on the server
git pull
composer install --no-dev --optimize-autoloader
npm run production
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 10. Performance (page speed)

Every front-end page was running **15+ uncached database queries** on boot
(`AppServiceProvider`), plus footer/nav queries, plus page-specific work. That
is now cached.

### What changed
| Layer | Before | After |
|-------|--------|-------|
| Global boot (ads, settings, languages) | ~15 queries / request | 1 cache read (or 1 DB round-trip on miss) |
| Footer mega-menu | 4+ queries / request | Cached 1 hour |
| Navbar top brokers | Uncached every page | Cached 1 hour |
| Homepage | ~45 queries, broken `rand()` cache key | ~8 queries on cache miss; ~2 on hit |
| Broker promos | ~19 promo queries | 1 catalog query (cached 15 min) |
| Nav prefetch JS | Hover prefetched full Laravel pages | Removed progress/overlay; idle prefetch on 3 key nav links only |

### Progress bar / overlay
Removed. Navigation uses normal full page loads — speed comes from server-side
caching (see table above), not client-side loading chrome.
```env
CACHE_DRIVER=redis          # or file if Redis unavailable
SESSION_DRIVER=redis        # optional but helps under load
APP_DEBUG=false
```

After deploy always run:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Clear front-end caches after content changes
```bash
php artisan cache:forget global_view_data_v2
php artisan cache:forget footer_index_v2
php artisan cache:forget promotions_active_catalog_v3
php artisan cache:clear   # nuclear option
```

Or call `App\Services\GlobalViewDataService::flush()` from admin save hooks when
settings/ads/social items change.

### Production `.env` recommendations
