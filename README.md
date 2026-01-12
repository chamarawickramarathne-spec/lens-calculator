# Lens Calculator

**Photography Pricing Calculator for Professional Photographers**

_Hire Artist Studio_

---

## Overview

Lens Calculator is a comprehensive web application designed to help photographers calculate accurate pricing for their photography packages. The application allows photographers to:

- Select from predefined package templates (e.g., Birthday Shoot, Wedding, Portrait Studio)
- Choose equipment categories and individual items
- Calculate labor costs based on hours and hourly rate
- Apply profit margins
- Generate detailed pricing breakdowns
- Save packages to database
- Download professional PDF quotations

---

## Features

### 1. **Template System**

- Pre-configured packages for common photography scenarios
- Templates include: Birthday Shoot, Wedding Photography, Portrait Studio, Product Photography, Event Coverage
- One-click application of template settings and equipment

### 2. **Equipment Management**

- 8 equipment categories: Camera Bodies, Lenses, Lighting, Audio, Stabilization, Accessories, Backdrops, Props
- Comprehensive equipment database with values
- Dynamic category selection with cascading dropdowns
- Quantity support for multiple items

### 3. **Labor & Margin Calculator**

- Labor hours input
- Hourly rate configuration
- Profit margin percentage calculation
- Real-time total updates

### 4. **Package Pricing**

- Detailed cost breakdown
- Equipment total
- Labor cost calculation
- Subtotal and final total with margins
- Package naming and client information

### 5. **PDF Generation**

- Professional quotation PDFs
- Complete equipment list
- Cost breakdown
- Client and package details
- Print-ready format

---

## Installation

### Prerequisites

- **XAMPP/WAMP** or similar web server with:
  - PHP 7.4 or higher
  - MySQL 5.7 or higher
  - Apache web server

### Setup Steps

1. **Copy Files**

   - Place the `lens-calculator` folder in your web server's `htdocs` directory
   - Path should be: `htdocs/lens-calculator/`

2. **Database Setup**

   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `lens_calculator`
   - Import the database schema:
     - Go to the Import tab
     - Select `database/schema.sql`
     - Click "Go" to execute
   - Import sample data:
     - Select `database/sample_data.sql`
     - Click "Go" to execute

3. **Database Configuration**

   - Open `api/config.php`
   - Verify/update database credentials:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_USER', 'root');
     define('DB_PASS', '');
     define('DB_NAME', 'lens_calculator');
     ```

4. **Access Application**
   - Open browser and navigate to: `http://localhost/lens-calculator/`
   - The application should load with all features ready

---

## Usage Guide

### Getting Started

1. **Using Templates (Recommended for Beginners)**

   - Select a template from the dropdown in Section 1
   - Click "Apply Template" to auto-populate equipment and settings
   - Review and modify as needed

2. **Custom Package Creation**

   - Select equipment categories by checking boxes in Section 2
   - Choose specific equipment items from the dropdowns
   - Set quantity and click "Add" for each item
   - Enter labor hours and hourly rate in Section 3
   - Set your profit margin percentage
   - View the final pricing in Section 4

3. **Saving & Exporting**
   - Enter a package name (required)
   - Optionally add client name and notes
   - Click "Save Package" to store in database
   - Click "Download PDF" to generate a quotation document

### Tips

- Start with templates to understand typical package configurations
- Adjust equipment selections to match your actual gear
- Update hourly rates based on your experience level
- Use profit margins between 20-35% for sustainable business
- Save packages for future reference and client follow-ups

---

## Database Structure

### Tables

1. **category_types** - Equipment categories
2. **equipment_details** - Individual equipment items with values
3. **templates** - Predefined package templates
4. **template_equipment** - Equipment assigned to templates
5. **packages** - Saved customer packages
6. **package_equipment** - Equipment in saved packages

### Sample Data Included

- **8 Equipment Categories**
- **50+ Equipment Items** with realistic pricing
- **5 Package Templates**:
  - Birthday Shoot - Basic
  - Wedding Shoot - Professional
  - Portrait Studio - Standard
  - Product Photography - Basic
  - Event Coverage - Standard

---

## File Structure

```
lens-calculator/
├── index.html              # Main application page
├── css/
│   └── style.css          # Application styles
├── js/
│   └── app.js             # JavaScript functionality
├── api/
│   ├── config.php         # Database configuration
│   ├── get_categories.php # Fetch categories
│   ├── get_equipment.php  # Fetch equipment
│   ├── get_templates.php  # Fetch templates
│   ├── save_package.php   # Save package to database
│   └── generate_pdf.php   # Generate PDF quotation
└── database/
    ├── schema.sql         # Database schema
    └── sample_data.sql    # Sample data
```

---

## Customization

### Adding Equipment

1. Open phpMyAdmin
2. Go to `equipment_details` table
3. Insert new row with:
   - category_id (1-8 for existing categories)
   - type, model, name
   - value (daily rental/usage cost)

### Creating Templates

1. Insert into `templates` table with package details
2. Link equipment items in `template_equipment` table

### Modifying Values

- Update equipment values in `equipment_details` table
- Adjust default labor rates in templates
- Modify profit margins as needed

---

## Browser Compatibility

- Chrome (recommended)
- Firefox
- Edge
- Safari

---

## Troubleshooting

### Database Connection Error

- Verify MySQL is running in XAMPP/WAMP
- Check database credentials in `api/config.php`
- Ensure `lens_calculator` database exists

### No Data Showing

- Verify both SQL files were imported successfully
- Check browser console for JavaScript errors
- Ensure API files have correct file paths

### PDF Not Generating

- Check if PHP is configured correctly
- Verify `api/generate_pdf.php` is accessible
- Ensure no special characters in package name

---

## Future Enhancements

- User authentication system
- Multi-currency support
- Equipment inventory tracking
- Client management system
- Invoice generation
- Email quotation sending
- Mobile app version
- Advanced reporting and analytics

---

## Support

For issues or questions:

1. Check the troubleshooting section
2. Review database configuration
3. Verify all files are properly uploaded
4. Check PHP error logs in XAMPP/WAMP

---

## Credits

**Lens Calculator** - Photography Pricing Calculator
Developed for Hire Artist Studio

Built with:

- HTML5, CSS3, JavaScript
- PHP 7.4+
- MySQL
- Responsive Design

---

## License

This application is provided as-is for use by Hire Artist Studio.

---

_Last Updated: November 10, 2025_
