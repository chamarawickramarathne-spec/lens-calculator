# AGENTS.md - Lens Calculator

## Application Overview
**Name:** Package Price Calculator  
**Purpose:** Photography equipment package pricing tool for Hire Artist Studio  
**Stack:** HTML5, CSS3, JavaScript ES6, PHP 7.4+, MySQL  
**Version:** 1.1.0

---

## Modification Log

### Mod 1.0.0 — Initial Release (2025-11-10)
- 4-step wizard: Setup > Gear > Labor > Final
- 8 equipment categories, 5 predefined templates
- Equipment selection with Select2 dropdowns
- Real-time cost calculation with profit margins
- PDF quotation generation (HTML print-to-PDF)
- Side drawer for ecosystem navigation
- Responsive mobile-first UI

### Mod 1.1.0 — Bug Fixes & Security Hardening (2026-08-18)
- **SECURITY:** Removed `cmd.exe` from web root
- **SECURITY:** Fixed `.htaccess` to use Apache 2.4 `Require all denied` for config.php protection
- **SECURITY:** Added `escapeHtml()` utility to prevent XSS in equipment names and category names
- **BUG:** Fixed `schema.sql` column name `type_id` → `type` to match actual queries
- **BUG:** Fixed `add_equipment.php` bind_param: `$typeId` type `s` → `i`
- **BUG:** Fixed CSS undefined variables (`--dark`, `--accent-lighter`, `--primary-color`, `--accent-red`, `--dark-light`, `--shadow-md`) with proper values
- **BUG:** Removed ~400 lines dead code from `generate_pdf.php`
- **BUG:** Consolidated duplicate Escape key handlers into one
- **FEATURE:** Added `savePackage()` function and Save Package button
- **FEATURE:** Added additional cost columns to `packages` table (extra_gear_cost, transportation_cost, assistant_pay, editing_cost, additional_cost, additional_costs_total)
- **FEATURE:** Created SQL migration `database/add_additional_cost_columns.sql`
- **UX:** Standardized currency formatting with `formatCurrency()` helper
- **UX:** Removed duplicate Google Fonts import from CSS
- **UX:** Updated copyright year 2025 → 2026
- **CLEANUP:** Moved `temp_users.sql` to `database/` folder

---

## Build Commands
- **No build step** — static HTML/CSS/JS served directly
- **Linting:** No linter configured
- **Testing:** No test framework configured

## File Structure
```
lens-calculator/
├── index.html              # Main app entry
├── css/style.css           # All styles
├── js/app.js               # All frontend logic
├── api/                    # PHP backend
│   ├── config.php          # DB config (DO NOT COMMIT SECRETS)
│   ├── add_equipment.php   # POST: Add new gear
│   ├── generate_pdf.php    # GET: Quotation PDF
│   ├── generate_package_pdf.php # GET: Package details PDF
│   ├── get_categories.php  # GET: Equipment categories
│   ├── get_equipment.php   # GET: Equipment list
│   ├── get_equipment_types.php # GET: Equipment types
│   ├── get_templates.php   # GET: Package templates
│   ├── log_access.php      # POST: Log page access
│   └── save_package.php    # POST: Save package to DB
├── database/
│   ├── schema.sql          # Full database schema
│   ├── sample_data.sql     # Seed data
│   ├── add_access_logs.sql # Access logs table
│   ├── add_additional_cost_columns.sql # Migration
│   └── temp_users.sql      # Temp user data
├── images/
│   └── logo.png
└── .htaccess               # Apache config
```

## Key Conventions
- **Currency:** LKR (Sri Lankan Rupee)
- **Number format:** `en-US` locale via `formatCurrency()`
- **API pattern:** JSON request/response, `success: true/false` envelope
- **XSS prevention:** Always use `escapeHtml()` for user/DB data in innerHTML
- **State management:** Single `state` object in app.js

## Known Limitations
- No user authentication
- PDF generation requires browser print dialog
- Single currency (LKR)
- No equipment availability tracking
