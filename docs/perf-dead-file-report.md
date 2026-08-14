# Performance dead-file risk report (Phase 0 → Phase 4)

Generated during BrokersCourt performance audit.

| File | Evidence | Risk | Action |
|------|----------|------|--------|
| `public/js/app.js` | No Blade/Mix reference | Low | **Deleted** Phase 4 |
| `js/app.js` | Duplicate orphan bundle | Low | **Deleted** Phase 4 |
| `public/css/typography.css` | Zero references | Low | **Deleted** Phase 4 |
| `public/css/nav-topbar.css` | Zero references | Low | **Deleted** Phase 4 |
| `public/css/admin-topbar.css` | Comment-only mention | Low | **Deleted** Phase 4 |
| `public/dist/fonts/vazir/**` | Only self-refs (~5.3 MB) | Low | **Deleted** Phase 4 |
| `public/dist-front/js/*` | No Blade refs | Low | **Deleted** Phase 4 |
| `react-icons` (npm) | No app imports | Low | **Removed** Phase 4 |
| `uploads/` vs `public/uploads/` | ~14MB path mirror | Medium | Report only; confirm deploy |
| Oversized upload rasters | May be DB-referenced | High | Favicon compressed 1.3MB→2.8KB; banners left |
| Placeholder SVGs | May be seed/DB | Medium | Keep |

## Also completed
- Sidebar ads extracted to `front.partials.sidebar_ad`
- Prefetch HTML removed; CSS/JS warm on hover only
- `.hidden !important` removed; Mix rebuilt (~212 KiB app.css)
