# Laravel HR System - Unused PHP Code Audit Report

**Date:** April 27, 2026  
**Project:** Laravel 12 HR System Management  
**Audit Type:** Comprehensive Dead Code Analysis

---

## Executive Summary

This audit identified **3 unused models**, **1 critical routing bug**, and **0 unused services, middleware, or policies**. The project is relatively clean with most components in use. The main concern is the `Application` and `Interview` models which have no active usage in the system.

---

## 1. SERVICES AUDIT

**Location:** `app/Services/`  
**Total Services:** 3  
**Unused Services:** 0  
**Status:** ✅ ALL USED

### Details

| Service | Status | Used By | Notes |
|---------|--------|---------|-------|
| [ApprovalService.php](app/Services/ApprovalService.php) | ✅ USED | SuratController | Handles approval workflow logic (initApproval, getStatus, approve, reject, etc.) |
| [PinVerificationService.php](app/Services/PinVerificationService.php) | ✅ USED | SuratController | Verifies PIN for document approvals |
| [ApprovalCoverService.php](app/Services/ApprovalCoverService.php) | ✅ USED | SuratController | Generates PDF cover for approved documents |

**Recommendation:** ✅ **KEEP ALL** - Services are essential for the approval workflow.

---

## 2. MODELS AUDIT

**Location:** `app/Models/`  
**Total Models:** 15  
**Unused Models:** 2  
**Status:** ⚠️ 2 UNUSED MODELS FOUND

### Active Models (13 - ALL USED)

| Model | Status | Used By | Relationships | Notes |
|-------|--------|---------|---------------|-------|
| [User.php](app/Models/User.php) | ✅ USED | All modules | hasMany(Absensi, JadwalShift, Penggajian, Leave) | Core user model with Spatie permissions |
| [Absensi.php](app/Models/Absensi.php) | ✅ USED | AbsensiController, HomeController | belongsTo(User) | Attendance records |
| [Leave.php](app/Models/Leave.php) | ✅ USED | HRController, HomeController | belongsTo(User) | Leave requests |
| [Penggajian.php](app/Models/Penggajian.php) | ✅ USED | PenggajianController, HomeController | belongsTo(User) | Payroll/salary records |
| [Surat.php](app/Models/Surat.php) | ✅ USED | SuratController, ApprovalFlowController, HomeController | belongsTo(User), hasMany(DocumentApproval) | Document approval system |
| [Department.php](app/Models/Department.php) | ✅ USED | HRController, HomeController, SearchController | None defined | Department data |
| [Holiday.php](app/Models/Holiday.php) | ✅ USED | HRController | None defined | Holiday management |
| [LeaveInformation.php](app/Models/LeaveInformation.php) | ✅ USED | HRController | None defined | Leave type information |
| [Shift.php](app/Models/Shift.php) | ✅ USED | ShiftController | None defined | Work shifts |
| [JadwalShift.php](app/Models/JadwalShift.php) | ✅ USED | ShiftController | belongsTo(User, Shift) | Shift schedules |
| [EmployeeProfile.php](app/Models/EmployeeProfile.php) | ✅ USED | AccountController | hasOne relationship from User | Extended employee information |
| [ApprovalStep.php](app/Models/ApprovalStep.php) | ✅ USED | ApprovalFlowController, ApprovalService | None defined | Approval workflow steps |
| [DocumentApproval.php](app/Models/DocumentApproval.php) | ✅ USED | ApprovalFlowController, HomeController, SuratController | belongsTo(User) | Approval records |

### Unused Models (2 - CANDIDATES FOR DELETION)

| Model | Status | References | Recommendation |
|-------|--------|------------|-----------------|
| [Application.php](app/Models/Application.php) | ❌ **UNUSED** | Factory exists: `ApplicationFactory` | 🗑️ **DELETE** |
| [Interview.php](app/Models/Interview.php) | ❌ **UNUSED** | Factory exists: `InterviewFactory`; Migration: `create_interviews_table` | 🗑️ **DELETE** |

### Detailed Analysis of Unused Models

#### Application.php
```php
class Application extends Model
{
    use HasFactory;
}
```
- **Current Status:** No methods, no relationships defined
- **Where it's looked for:** Routes, controllers, seeders - NOT FOUND
- **Database Migration:** Exists but no active usage
- **Factory:** `ApplicationFactory` exists but not used in seeders
- **Recommendation:** 🗑️ **SAFE TO DELETE**
- **Delete Steps:**
  1. Delete `app/Models/Application.php`
  2. Delete `database/factories/ApplicationFactory.php` (if migration doesn't depend on it)
  3. Consider creating a data migration to drop `applications` table if needed

#### Interview.php
```php
class Interview extends Model
{
    use HasFactory;
}
```
- **Current Status:** No methods, no relationships defined
- **Where it's looked for:** Routes, controllers, seeders - NOT FOUND
- **Database Migration:** `2026_04_03_163941_create_interviews_table.php` exists
- **Factory:** `InterviewFactory` exists but not used
- **Related:** Chart mentions "HR Interview" in JS but no functionality
- **Recommendation:** 🗑️ **SAFE TO DELETE**
- **Delete Steps:**
  1. Delete `app/Models/Interview.php`
  2. Delete `database/factories/InterviewFactory.php`
  3. Create rollback migration or data migration to drop `interviews` table if needed

**Models Recommendation Summary:**
- ✅ KEEP: All 13 active models
- 🗑️ DELETE: Application, Interview models (orphaned, no functionality)

---

## 3. MIDDLEWARE AUDIT

**Location:** `app/Http/Middleware/`  
**Total Middleware:** 1  
**Unused Middleware:** 0  
**Status:** ✅ ACTIVE

| Middleware | Status | Usage | Notes |
|------------|--------|-------|-------|
| [CheckOnboarding.php](app/Http/Middleware/CheckOnboarding.php) | ✅ USED | Registered in `bootstrap/app.php` as `'onboarding'` | Enforces user TTD + PIN verification before accessing main routes |

**Usage in Routes:**
```php
Route::middleware('onboarding')->group(function () {
    // All authenticated routes except onboarding setup
});
```

**Recommendation:** ✅ **KEEP** - Critical for security workflow.

---

## 4. POLICIES AUDIT

**Location:** `app/Policies/`  
**Total Policies:** 1  
**Unused Policies:** 0  
**Status:** ✅ ACTIVE

| Policy | Status | Registered In | Used By | Notes |
|--------|--------|---------------|---------|-------|
| [SuratPolicy.php](app/Policies/SuratPolicy.php) | ✅ USED | `AppServiceProvider` (via `Gate::policy()`) | SuratController | Controls create, view, edit, update, delete, download permissions |

**Registration:**
```php
// AppServiceProvider.php
Gate::policy(Surat::class, SuratPolicy::class);
```

**Recommendation:** ✅ **KEEP** - Essential for document authorization.

---

## 5. UNUSED CONTROLLER METHODS

**Status:** ✅ ALL ROUTED - No orphaned methods found

### Method Coverage Summary

| Controller | Total Methods | Routed | Unrouted | Status |
|------------|---------------|--------|----------|--------|
| [HomeController.php](app/Http/Controllers/HomeController.php) | 3 | 3 | 0 | ✅ |
| [AccountController.php](app/Http/Controllers/AccountController.php) | 8 | 8 | 0 | ✅ |
| [SearchController.php](app/Http/Controllers/SearchController.php) | 1 | 1 | 0 | ✅ |
| [HRController.php](app/Http/Controllers/HRController.php) | 16 | 16 | 0 | ✅ |
| [AbsensiController.php](app/Http/Controllers/AbsensiController.php) | 4 | 4 | 0 | ✅ |
| [AbsensiImportController.php](app/Http/Controllers/AbsensiImportController.php) | 2 | 2 | 0 | ✅ |
| [ShiftController.php](app/Http/Controllers/ShiftController.php) | 5 | 5 | 0 | ✅ |
| [ApprovalFlowController.php](app/Http/Controllers/ApprovalFlowController.php) | 8 | 8 | 0 | ✅ |
| [PenggajianController.php](app/Http/Controllers/PenggajianController.php) | 5 | 5 | 0 | ✅ |
| [SuratController.php](app/Http/Controllers/SuratController.php) | 9 | 9 | 0 | ✅ |
| **TOTAL** | **61** | **61** | **0** | ✅ |

**Recommendation:** ✅ **ALL CLEAN** - No orphaned controller methods.

---

## ⚠️ CRITICAL ISSUES FOUND

### 1. METHOD NAME CASE SENSITIVITY BUG

**Severity:** 🔴 **HIGH**  
**Location:** [app/Http/Controllers/HRController.php](app/Http/Controllers/HRController.php)  
**Issue:** Routes reference methods with incorrect casing

**Problem:**
```php
// ROUTES (web.php line 112-113)
Route::post('department/save', 'saveRecorddepartment');      // lowercase 'd'
Route::post('department/delete', 'deleteRecorddepartment');  // lowercase 'd'

// CONTROLLER (HRController.php)
public function saveRecordDepartment(Request $request)   // uppercase 'D'
public function deleteRecordDepartment(Request $request) // uppercase 'D'
```

**Impact:** 
- These routes will **throw 404 errors** when called
- Department save/delete functionality is **BROKEN**
- Users cannot manage departments

**Fix Required:**
Change route method names to match controller casing (or vice versa):
```php
// OPTION 1: Fix routes (recommended)
Route::post('department/save', 'saveRecordDepartment');      // uppercase 'D'
Route::post('department/delete', 'deleteRecordDepartment');  // uppercase 'D'

// OPTION 2: Fix controller method names
public function saveRecorddepartment(Request $request) {}
public function deleteRecorddepartment(Request $request) {}
```

**Recommendation:** Fix routes to use correct camelCase: `saveRecordDepartment`, `deleteRecordDepartment`

---

## 6. DUPLICATE RELATIONSHIPS

**Location:** [app/Models/JadwalShift.php](app/Models/JadwalShift.php)  
**Issue:** Two methods returning the same relationship

```php
public function user()      // Both return same relationship
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}

public function karyawan()  // Duplicate
{
    return $this->belongsTo(\App\Models\User::class, 'user_id');
}
```

**Recommendation:** Remove `karyawan()` method (minor cleanup, not breaking)

---

## 7. FACTORIES ANALYSIS

**Location:** `database/factories/`  
**Total Factories:** 3

| Factory | Model | Used In | Status |
|---------|-------|---------|--------|
| [ApplicationFactory.php](database/factories/ApplicationFactory.php) | Application | None | ❌ ORPHANED |
| [InterviewFactory.php](database/factories/InterviewFactory.php) | Interview | None | ❌ ORPHANED |
| [UserFactory.php](database/factories/UserFactory.php) | User | Seeders | ✅ USED |

**Recommendation:** Delete orphaned factories along with their models.

---

## SUMMARY & ACTION ITEMS

### 🟢 Green Status (No Action Needed)
- ✅ **Services:** All 3 are actively used
- ✅ **Middleware:** 1/1 in use
- ✅ **Policies:** 1/1 in use
- ✅ **Controller Methods:** All 61 methods are routed

### 🟡 Yellow Status (Minor Issues)
- ⚠️ **Duplicate Relationships:** `JadwalShift.php` has redundant `karyawan()` method
- ⚠️ **Code Comments:** Old references in VIEW_DEPENDENCY_ANALYSIS.md mention deprecated method names

### 🔴 Red Status (Action Required)
- ❌ **2 Unused Models:** `Application.php`, `Interview.php` (safe to delete)
- ❌ **2 Orphaned Factories:** `ApplicationFactory.php`, `InterviewFactory.php`
- ❌ **CRITICAL BUG:** Routes have method name casing mismatch in HRController (department save/delete will 404)

---

## RECOMMENDED CLEANUP (Priority Order)

### Priority 1 - FIX BUG (URGENT)
```bash
# File: routes/web.php (line 112-113)
# Change from:
Route::post('department/save', 'saveRecorddepartment');
Route::post('department/delete', 'deleteRecorddepartment');

# Change to:
Route::post('department/save', 'saveRecordDepartment');
Route::post('department/delete', 'deleteRecordDepartment');
```

### Priority 2 - DELETE UNUSED MODELS
```bash
# Delete files:
rm app/Models/Application.php
rm app/Models/Interview.php
rm database/factories/ApplicationFactory.php
rm database/factories/InterviewFactory.php

# Create migration to drop unused tables (if schema cleanup needed):
php artisan make:migration drop_unused_tables --create=false
```

### Priority 3 - MINOR CLEANUP
```bash
# File: app/Models/JadwalShift.php
# Remove duplicate method (keep 'user', remove 'karyawan'):
# public function karyawan() { ... }  <- DELETE THIS
```

### Priority 4 - VERIFICATION
```bash
# Run tests to verify no broken functionality:
php artisan test

# Check routes are accessible:
php artisan route:list | grep -i department
```

---

## CONCLUSION

The Laravel project is **in good condition** with only:
- **2 orphaned models** (Application, Interview) - safe to delete
- **1 critical bug** with department route method naming - MUST FIX
- **Minor code duplication** in JadwalShift relationships

**Overall Code Health:** 7/10 (would be 9/10 after cleanup)

