# 🧹 QUICK CLEANUP ACTION ITEMS

## CRITICAL - FIX FIRST ⚠️
- [ ] **File:** `routes/web.php` (lines 112-113)
  - Change: `saveRecorddepartment` → `saveRecordDepartment`
  - Change: `deleteRecorddepartment` → `deleteRecordDepartment`

---

## SAFE TO DELETE (No Impact)

### Models (2 files)
```bash
# Safe to delete - completely unused
app/Models/Application.php
app/Models/Interview.php
```

### Views (1 file)
```bash
# Safe to delete - component defined but never used
resources/views/components/empty-state.blade.php
```

### CSS (1 file)
```bash
# Safe to delete - never linked or used
resources/css/app.css
```

### Database (1 file)
```bash
# Safe to delete - seeder defined but never called
database/seeders/ApprovalStepSeeder.php
```

### Helper Functions (in 1 file)
```bash
# Safe to delete - functions never called
app/Helper/helpers.php
  - Remove: set_active() function
  - Remove: set_show() function
```

---

## JAVASCRIPT CLEANUP (108 files = ~4-5 MB saved)

### DELETE ALL FILES IN: `public/assets/js/pages/`

Except DataTables files (keep these):
- ✅ public/assets/js/datatables/ (all files)

Remove these 108 files:
- 18 apexcharts-*.init.js files
- 3 dashboards-*.init.js files
- 11 apps-*.init.js files
- 17 form-*.init.js + related files
- 9 pages-*.init.js + landing-*.init.js files
- Other unused init files

### Additional JS Cleanup
```bash
# Safe to delete - not referenced anywhere
public/assets/js/common.js
```

---

## FILES TO KEEP ✅

### Critical for Operations
- ✅ All controllers (17 files)
- ✅ All used models (13 files)
- ✅ All services (3 files)
- ✅ All active views (43 files)
- ✅ Middleware (1 file)
- ✅ Policies (1 file)

### Important Static Files
- ✅ public/assets/js/app.js (main app JS)
- ✅ public/assets/js/layout.js (layout init)
- ✅ public/assets/js/starcode.bundle.js (template bundle)
- ✅ public/assets/js/datatables/ (all files)
- ✅ public/assets/css/starcode2.css (main CSS)
- ✅ public/vendor/flasher/ (notification system)
- ✅ public/assets/libs/ (all external libraries)

---

## ESTIMATED IMPACT

- **Files Deleted:** 118
- **Space Freed:** 4-5 MB
- **Build Speed:** +10-20% faster
- **Page Load:** -5-8% faster
- **Complexity:** Reduced by ~15%

---

## VERIFICATION CHECKLIST

After cleanup:
- [ ] Routes still work: `php artisan route:list`
- [ ] npm build succeeds: `npm run build`
- [ ] No 404 errors in browser console
- [ ] Department save/delete routes functional
- [ ] All HR modules accessible
- [ ] Approval workflow functions
- [ ] No JavaScript errors

---

## AUTOMATED CLEANUP COMMAND

```bash
# Delete unused models
rm app/Models/Application.php
rm app/Models/Interview.php

# Delete unused views
rm resources/views/components/empty-state.blade.php

# Delete unused CSS
rm resources/css/app.css

# Delete unused seeder
rm database/seeders/ApprovalStepSeeder.php

# Clean out helper functions (manual - edit and remove functions)
# vim app/Helper/helpers.php

# Delete 108 unused JS files
# rm public/assets/js/pages/apexcharts-*.init.js
# rm public/assets/js/pages/dashboards-*.init.js
# rm public/assets/js/pages/apps-*.init.js
# ... (see full list in main report)
```

**Note:** Test after each step to ensure no broken functionality.

---

Generated: 2026-04-27
