# BrokersCourt — Changelog (8 August 2026)

Summary of work completed on **8 August 2026** across the front-end, admin panel, and platform infrastructure.

---

## Settings & Admin

- **Removed news ticker** project-wide (`news_ticker_total` and related fields).
  - Fixed `Attempt to read property "news_ticker_total" on null` by using safe settings fallbacks (`first() ?? new Setting()`, `updateOrCreate(['id' => 1])`).
  - Migration: `2026_08_07_240000_drop_news_ticker_from_settings_table.php`.
  - Cleaned admin settings UI and `AdminSettingController`.
- **Site management settings** — maintenance mode, theme toggles, and related options.
  - Migration: `2026_08_07_235000_add_site_management_settings.php`.
  - Middleware: `CheckSiteMaintenance`.
  - Support class: `SiteTheme`.
- **Google OAuth** — sign-in via Google for front-end users.
  - `GoogleAuthController`, `GoogleOAuth` support class.
  - Migrations: `add_google_auth_to_users_table`, `add_google_oauth_to_settings_table`.
- **Author social links** — admin CRUD extended for author social profiles.
  - Migration: `2026_08_07_120000_add_social_links_to_authors_table.php`.

---

## Awards

- **Redesigned `/awards`** with dark ocean theme (compact stats, plain title, no card hover shadows, methodology moved outside main layout).
- **Dynamic award URLs** — `/awards/{slug}` (e.g. `/awards/best-low-spread-brokers`).
  - `AwardController@show`, `AwardTaxonomy::routeSlugs()`.
  - Legacy `/brokers/award/{award}` redirects (301) to new URLs.
- New assets: `public/css/awards-index.css`, `public/js/awards-index.js`.

---

## Trading Tools

- **Redesigned `/trading-tools`** to match site dark theme.
- Extracted page CSS/JS: `public/css/trading-tools.css`, `public/js/trading-tools.js`.

---

## Homepage

- **Live Market Widgets** — TradingView embeds for currency rates, heatmap, and economic calendar.
  - Partial: `resources/views/front/homepage/inc/live_markets.blade.php`.
  - Assets: `public/css/live-markets.css`, `public/js/live-markets.js`.
- Homepage layout refresh: broker picks, sentiment cards, hero search, explore categories, trust/scam cards.
- Removed legacy sections: `compare_preview`, `tools_strip`.

---

## Navigation & Page Loading

- **Prefetch-only navigation optimizer** — normal full page reloads (no Turbo / no DOM swapping).
  - Hover and viewport prefetch via `<link rel="prefetch">`.
  - Top progress bar + light veil on click (persists across navigation via `sessionStorage`).
  - Ensures jQuery tabs, Owl Carousel, and page scripts re-initialize correctly on every visit.
  - Files: `public/js/bc-nav-optimizer.js`, `public/css/bc-nav-optimizer.css`.
  - Wired in `resources/views/front/layout/app.blade.php` (`data-bc-nav="prefetch"`).
- **Quick access drawers** and **broker spotlight dock** (optional via `SiteTheme`).

---

## Prop Firms Module (new)

Full admin CRUD and front-end listing/detail for prop trading firms.

| Layer | Files |
|-------|-------|
| Models | `PropFirm`, `PropFirmCategory`, `PropFirmProgram`, `PropFirmAttribute`, `PropFirmFaq`, `PropFirmReview`, `PropFirmModuleSetting` |
| Admin | Controllers, form requests, Blade views under `resources/views/admin/prop-firms/` |
| Front | `PropFirmController`, index/detail views, design system CSS |
| Services | `PropFirmAdminService`, `PropFirmIndexService`, `PropFirmNavService`, `PropFirmPresenter` |
| Migration | `2026_08_07_200000_create_prop_firms_module_tables.php` |
| Seeder | `PropFirmDemoSeeder` |

---

## Broker Scam Checker (new)

- Front-end scam checker with report form, dashboard, and compare modal.
- `BrokerScamCheckerController`, `BrokerAssessmentService`, `BrokerSafetyScoreService`, `BrokerPopularityService`.
- Assets: `public/css/broker-scam-checker.css`, `public/js/broker-scam-checker.js`.

---

## Broker Reports (admin)

- Admin panel for broker reports.
- Model: `BrokerReport`.
- Migration: `2026_08_07_180000_create_broker_reports_table.php`.
- Seeder: `BrokerReportDemoSeeder`.

---

## Promotions

- New `/broker-promos` index with tabbed types and load-more.
- `PromotionsController`, `PromotionsIndexService`.
- Redirects from legacy `/active-promotions` URLs.

---

## Broker & Comparison UX

- Broker review, compare, find-my-broker, best-broker-guide, and reviews index pages refreshed (dark theme, improved cards/filters).
- `BrokerComparisonService` expanded; comparison result partials split for maintainability.
- `BrokerFeaturePresenter`, `BrokerReviewPresenter` enhancements.
- Blog post detail sidebar and `BlogPostDetailService`.

---

## Auth & User Account

- Login/register pages updated; Google OAuth button partial.
- User account views under `resources/views/front/account/`.
- `public/css/user-account.css`.

---

## Migrations to Run

After pulling, run:

```bash
php artisan migrate
```

Optional demo data:

```bash
php artisan db:seed --class=PropFirmDemoSeeder
php artisan db:seed --class=BrokerReportDemoSeeder
```

---

## Notes

- **Do not commit** `.env.deploy-backup` — local deploy credentials only.
- Navigation optimizer uses **prefetch + full reload** only. Turbo-style DOM swapping was removed after it broke tabs/sliders.
- Hard refresh (`Ctrl+F5`) after deploy to pick up `bc-nav-optimizer` v2 assets.
