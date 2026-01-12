# Quick Setup Guide - Lens Calculator

## Step-by-Step Installation

### 1. Start Your Web Server

- Open **XAMPP Control Panel** or **WAMP**
- Start **Apache** and **MySQL** services
- Wait for both to show green/running status

### 2. Create the Database

1. Open browser and go to: `http://localhost/phpmyadmin`
2. Click "New" in the left sidebar
3. Database name: `lens_calculator`
4. Collation: `utf8mb4_general_ci`
5. Click "Create"

### 3. Import Database Schema

1. Select the `lens_calculator` database you just created
2. Click the "Import" tab at the top
3. Click "Choose File"
4. Navigate to: `lens-calculator/database/schema.sql`
5. Click "Go" at the bottom
6. Wait for success message

### 4. Import Sample Data

1. Stay on the Import tab
2. Click "Choose File" again
3. Navigate to: `lens-calculator/database/sample_data.sql`
4. Click "Go"
5. Wait for success message

### 5. Verify Database Configuration

1. Open: `lens-calculator/api/config.php` in a text editor
2. Confirm these settings match your setup:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');  // Empty for default XAMPP/WAMP
   define('DB_NAME', 'lens_calculator');
   ```

### 6. Launch the Application

1. Open your browser
2. Go to: `http://localhost/lens-calculator/`
3. The app should load with all features working

### 7. Test the Application

1. **Test Template**: Select "Birthday Shoot - Basic" from the dropdown
2. Click "Apply Template" button
3. Equipment should populate automatically
4. Scroll down to see the calculated totals
5. Try downloading a PDF

---

## Expected Result

You should see:

- ✅ Page loads with "Lens Calculator" title
- ✅ Template dropdown has 5 options
- ✅ 8 equipment categories available
- ✅ All calculations work in real-time
- ✅ PDF download generates quotation

---

## Common Issues & Solutions

### Issue: "Database connection failed"

**Solution:**

- Make sure MySQL is running in XAMPP/WAMP
- Check if database `lens_calculator` exists
- Verify credentials in `api/config.php`

### Issue: "No templates showing"

**Solution:**

- Check if sample_data.sql was imported successfully
- Go to phpMyAdmin → lens_calculator → templates table
- Should have 5 rows of data

### Issue: "Categories not loading"

**Solution:**

- Open browser console (F12)
- Check for JavaScript errors
- Verify API files are in the correct location

### Issue: "PDF button does nothing"

**Solution:**

- Check if popup blocker is enabled
- Try right-click on button → "Open in new tab"
- Check if PHP is processing correctly

---

## File Locations Reference

```
Your XAMPP/WAMP htdocs folder:
├── lens-calculator/
    ├── index.html              ← Main page
    ├── css/style.css          ← Styles
    ├── js/app.js              ← JavaScript
    ├── api/                   ← PHP backend
    │   ├── config.php
    │   ├── get_categories.php
    │   ├── get_equipment.php
    │   ├── get_templates.php
    │   ├── save_package.php
    │   └── generate_pdf.php
    └── database/              ← SQL files
        ├── schema.sql
        └── sample_data.sql
```

---

## Testing Checklist

- [ ] Database created successfully
- [ ] Schema imported (9 tables created)
- [ ] Sample data imported (50+ equipment items)
- [ ] Application loads at localhost
- [ ] Templates dropdown populated
- [ ] Categories checkboxes visible
- [ ] Can select categories
- [ ] Equipment dropdowns appear
- [ ] Can add equipment items
- [ ] Calculations update in real-time
- [ ] Can save packages
- [ ] PDF download works

---

## Next Steps

1. **Customize Equipment Values**

   - Go to phpMyAdmin → equipment_details table
   - Update values to match your actual rental/usage costs

2. **Create Custom Templates**

   - Insert new templates in the templates table
   - Link equipment in template_equipment table

3. **Start Using**
   - Create your first package
   - Generate a PDF quotation
   - Send to clients

---

## Need Help?

1. Check the main README.md file for detailed documentation
2. Review the Troubleshooting section
3. Verify all installation steps were completed
4. Check XAMPP/WAMP error logs

---

**You're all set! Start calculating your photography packages! 📸**
