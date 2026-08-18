# Changelog - Lens Calculator

All notable changes to the Lens Calculator project will be documented in this file.

---

## [1.1.0] - 2026-08-18

### Bug Fixes & Security Hardening

#### Security
- ✅ Removed `cmd.exe` from web root directory
- ✅ Fixed `.htaccess` to use Apache 2.4 `Require all denied` for config.php protection
- ✅ Added `escapeHtml()` utility to prevent XSS in all innerHTML injections

#### Bug Fixes
- ✅ Fixed `schema.sql` column name `type_id` → `type` to match actual queries
- ✅ Fixed `add_equipment.php` bind_param type: `$typeId` from `s` (string) to `i` (integer)
- ✅ Fixed 6 undefined CSS variables in user guide styles
- ✅ Removed ~400 lines dead code from `generate_pdf.php`
- ✅ Consolidated duplicate Escape key handlers into one unified handler
- ✅ Moved `temp_users.sql` to `database/` folder

#### Features
- ✅ Added `savePackage()` function and Save Package button in UI
- ✅ Added additional cost columns to `packages` table schema
- ✅ Created SQL migration for existing databases
- ✅ `save_package.php` now stores all cost breakdowns

#### Improvements
- ✅ Standardized currency formatting with `formatCurrency()` helper
- ✅ Removed duplicate Google Fonts import from CSS
- ✅ Updated copyright year 2025 → 2026
- ✅ `.htaccess` now whitelists only known API files instead of all `.php`

---

## [1.0.0] - 2025-11-10

### Initial Release 🎉

#### Added

- ✅ Complete application structure with 4 main sections
- ✅ Database schema with 9 tables
- ✅ Sample data with 50+ equipment items
- ✅ 5 predefined package templates:
  - Birthday Shoot - Basic
  - Wedding Shoot - Professional
  - Portrait Studio - Standard
  - Product Photography - Basic
  - Event Coverage - Standard
- ✅ 8 equipment categories:
  - Camera Body
  - Lenses
  - Lighting
  - Audio
  - Stabilization
  - Accessories
  - Backdrops
  - Props

#### Features

- ✅ Template selection system
- ✅ Dynamic category-based equipment selection
- ✅ Real-time pricing calculations
- ✅ Labor cost calculator
- ✅ Profit margin calculator
- ✅ Package saving to database
- ✅ PDF quotation generation
- ✅ Responsive design (mobile-friendly)
- ✅ Professional UI with modern styling

#### Technical Implementation

- ✅ Frontend: HTML5, CSS3, JavaScript ES6
- ✅ Backend: PHP 7.4+ with MySQL
- ✅ RESTful API structure
- ✅ JSON data exchange
- ✅ Prepared statements for security
- ✅ Transaction support for data integrity

#### Documentation

- ✅ README.md - Full documentation
- ✅ SETUP.md - Quick setup guide
- ✅ PROJECT_OVERVIEW.md - Project structure
- ✅ CONFIG_NOTES.txt - Configuration reference

---

## Future Enhancements (Planned)

### Version 1.1.0 (Planned)

- [ ] User authentication system
- [ ] User roles (Admin, Photographer)
- [ ] Dashboard with statistics
- [ ] Recent packages list
- [ ] Quick edit saved packages

### Version 1.2.0 (Planned)

- [ ] Equipment inventory management
- [ ] Track equipment availability
- [ ] Booking calendar integration
- [ ] Conflict detection

### Version 1.3.0 (Planned)

- [ ] Client management system
- [ ] Client database
- [ ] Quote history per client
- [ ] Email quotations directly

### Version 2.0.0 (Planned)

- [ ] Multi-currency support
- [ ] Tax calculation options
- [ ] Invoice generation (not just quotes)
- [ ] Payment tracking
- [ ] Advanced reporting
- [ ] Export to Excel/CSV
- [ ] Mobile app version

### Additional Features (Under Consideration)

- [ ] Equipment depreciation tracking
- [ ] Maintenance schedule tracking
- [ ] Insurance value calculations
- [ ] Time tracking integration
- [ ] Expense tracking
- [ ] Profit/loss reports
- [ ] Client portal access
- [ ] Online booking system
- [ ] Integration with accounting software
- [ ] WhatsApp/SMS quotation sharing
- [ ] Digital signature on quotes
- [ ] Contract generation
- [ ] Photo gallery integration
- [ ] Review/testimonial collection

---

## Bug Fixes

### Version 1.0.0

- Initial release - no prior bugs to fix

---

## Database Changes

### Version 1.0.0

- Initial schema with 9 tables
- Comprehensive relationships with foreign keys
- Indexes for performance
- Sample data for immediate use

---

## API Changes

### Version 1.0.0

- Initial API endpoints:
  - GET `/api/get_categories.php`
  - GET `/api/get_equipment.php`
  - GET `/api/get_templates.php`
  - POST `/api/save_package.php`
  - GET `/api/generate_pdf.php`

---

## Security Updates

### Version 1.0.0

- Prepared statements for SQL injection prevention
- Input sanitization
- XSS protection headers
- CORS headers configured

---

## Performance Improvements

### Version 1.0.0

- Database indexes on frequently queried columns
- Efficient queries with JOINs
- Client-side caching of API responses
- Optimized JavaScript calculations

---

## Known Issues

### Version 1.0.0

- PDF generation opens in new window (by design, requires print)
- No user authentication (planned for v1.1.0)
- Single currency only (USD)
- No equipment availability tracking
- Manual equipment value updates required

---

## Breaking Changes

### Version 1.0.0

- Initial release - no breaking changes

---

## Deprecations

### Version 1.0.0

- None

---

## Notes

### Version 1.0.0

- Built specifically for **Hire Artist Studio**
- Focused on photography equipment pricing
- Beginner-friendly with templates
- Professional output with PDF generation
- Scalable architecture for future enhancements

---

## Contributors

- Initial Development: November 10, 2025
- Client: Hire Artist Studio
- Purpose: Photography Package Pricing Calculator

---

## Support

For bug reports, feature requests, or questions:

1. Review the README.md documentation
2. Check SETUP.md for installation issues
3. Consult CONFIG_NOTES.txt for configuration
4. Review this CHANGELOG for version history

---

## Version History Summary

| Version | Date       | Status  | Notes                  |
| ------- | ---------- | ------- | ---------------------- |
| 1.0.0   | 2025-11-10 | Current | Initial stable release |

---

_Last Updated: November 10, 2025_
