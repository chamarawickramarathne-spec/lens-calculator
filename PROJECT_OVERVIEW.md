# Lens Calculator - Project Structure

```
lens-calculator/
│
├── 📄 index.html                    # Main application page
│   └── Sections: Templates → Equipment → Labor → Pricing
│
├── 📁 css/
│   └── 📄 style.css                # Complete styling (responsive design)
│
├── 📁 js/
│   └── 📄 app.js                   # Application logic & functionality
│       ├── State management
│       ├── API calls
│       ├── Dynamic UI rendering
│       ├── Calculations
│       └── Event handlers
│
├── 📁 api/                         # PHP Backend
│   ├── 📄 config.php               # Database configuration
│   ├── 📄 get_categories.php       # Fetch equipment categories
│   ├── 📄 get_equipment.php        # Fetch equipment items
│   ├── 📄 get_templates.php        # Fetch package templates
│   ├── 📄 save_package.php         # Save package to database
│   └── 📄 generate_pdf.php         # Generate PDF quotations
│
├── 📁 database/                    # Database Setup
│   ├── 📄 schema.sql               # Database structure (9 tables)
│   └── 📄 sample_data.sql          # Sample data (50+ items, 5 templates)
│
├── 📄 README.md                    # Full documentation
├── 📄 SETUP.md                     # Quick setup guide
└── 📄 CONFIG_NOTES.txt             # Configuration reference

```

---

## Database Schema

```
lens_calculator (database)
│
├── 📊 category_types               # Equipment categories (8 items)
│   └── Camera Body, Lenses, Lighting, Audio, etc.
│
├── 📊 equipment_details            # Equipment items (50+ items)
│   └── type, model, name, value, category_id
│
├── 📊 templates                    # Package templates (5 templates)
│   └── Birthday, Wedding, Portrait, Product, Event
│
├── 📊 template_equipment           # Template → Equipment mapping
│   └── Links equipment to templates
│
├── 📊 packages                     # Saved customer packages
│   └── Stores complete quotations
│
└── 📊 package_equipment            # Package → Equipment mapping
    └── Links equipment to saved packages
```

---

## Application Flow

```
1. LOAD APPLICATION
   ↓
   ├── Fetch Categories (API)
   ├── Fetch Equipment (API)
   └── Fetch Templates (API)

2. USER SELECTS TEMPLATE (Optional)
   ↓
   └── Auto-populate equipment & settings

3. USER SELECTS CATEGORIES
   ↓
   └── Show equipment dropdowns for selected categories

4. USER ADDS EQUIPMENT
   ↓
   ├── Select equipment from dropdown
   ├── Set quantity
   └── Add to selection list

5. USER ENTERS LABOR & MARGIN
   ↓
   └── Real-time calculation updates

6. USER SAVES OR EXPORTS
   ↓
   ├── Save Package → Database
   └── Download PDF → Generate quotation
```

---

## Features Overview

### ✅ Template System

- Pre-configured packages
- One-click application
- Customizable after application

### ✅ Equipment Management

- Category-based organization
- Dynamic dropdown system
- Quantity support
- Easy add/remove

### ✅ Pricing Calculator

- Equipment total
- Labor cost (hours × rate)
- Profit margin calculation
- Real-time updates

### ✅ Package Management

- Save to database
- Client information
- Notes/comments
- PDF generation

### ✅ Professional UI

- Clean, modern design
- Responsive layout
- Intuitive workflow
- Section-based organization

---

## Technology Stack

```
Frontend:
├── HTML5 (Structure)
├── CSS3 (Styling with CSS Variables)
└── JavaScript ES6 (Functionality)

Backend:
├── PHP 7.4+ (Server-side logic)
└── MySQL 5.7+ (Database)

Server:
└── Apache (XAMPP/WAMP)
```

---

## Key Files Explained

### 🔵 index.html

- Main application page
- 4 main sections as requested
- Semantic HTML structure
- Accessible form elements

### 🔵 css/style.css

- Professional styling
- Responsive design (mobile-friendly)
- CSS variables for easy customization
- Modern card-based layout

### 🔵 js/app.js

- Complete application logic
- API integration
- State management
- Real-time calculations
- Dynamic UI updates

### 🔵 api/\*.php

- RESTful API endpoints
- Database operations
- JSON responses
- Error handling

### 🔵 database/\*.sql

- Complete schema
- Sample data
- Ready to import

---

## Section Layout (as requested)

```
┌─────────────────────────────────────────┐
│  LENS CALCULATOR                        │
│  Hire Artist Studio                     │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  1️⃣ TEMPLATES                           │
│  ├─ Select template dropdown            │
│  ├─ Apply button                        │
│  └─ Template details display            │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  2️⃣ EQUIPMENT SELECTION                 │
│  ├─ Category checkboxes                 │
│  ├─ Equipment dropdowns (dynamic)       │
│  └─ Selected equipment list              │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  3️⃣ LABOR & MARGINS                     │
│  ├─ Labor hours input                   │
│  ├─ Hourly rate input                   │
│  └─ Margin percentage input             │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│  4️⃣ PACKAGE PRICE                       │
│  ├─ Cost breakdown                      │
│  ├─ Package details form                │
│  └─ Save & Download buttons             │
└─────────────────────────────────────────┘
```

---

## Installation Summary

1. ✅ Copy files to `htdocs/lens-calculator/`
2. ✅ Create MySQL database: `lens_calculator`
3. ✅ Import `schema.sql`
4. ✅ Import `sample_data.sql`
5. ✅ Access: `http://localhost/lens-calculator/`

---

## Sample Templates Included

1. **Birthday Shoot - Basic** ($287.50)
2. **Wedding Shoot - Professional** ($1,250.00)
3. **Portrait Studio - Standard** ($362.50)
4. **Product Photography - Basic** ($256.25)
5. **Event Coverage - Standard** ($545.00)

---

## Next Steps

📖 Read: SETUP.md for installation
📖 Read: README.md for full documentation
🔧 Customize: Equipment values in database
🎨 Customize: Colors in style.css
📸 Use: Create your first package!

---

**Built with ❤️ for Hire Artist Studio**
_Helping photographers price their work professionally_
