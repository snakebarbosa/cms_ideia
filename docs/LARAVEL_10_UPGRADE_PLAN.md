# Laravel 10 Upgrade Plan - IPIAAM CMS

## Current State
- **Current Version:** Laravel 9.52.17
- **Target Version:** Laravel 10.x
- **PHP Version:** 8.1.8 ✅ (Laravel 10 requires PHP 8.1+)
- **Database:** MySQL (using polymorphic relationships)

---

## Pre-Upgrade Checklist

### 1. Backup Everything
- [ ] **Database backup**
  ```bash
  mysqldump -u root -p ipiaam_db > backup_$(date +%Y%m%d).sql
  ```
- [ ] **Code backup** (commit all changes to git)
  ```bash
  git add .
  git commit -m "Pre Laravel 10 upgrade commit"
  git tag pre-laravel-10-upgrade
  ```
- [ ] **Storage and uploads backup**
  ```bash
  tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/ public/storage/
  ```

### 2. Test Current Application
- [ ] Run full test suite
  ```bash
  php artisan test
  ```
- [ ] Manually test critical features:
  - [ ] User authentication
  - [ ] Artigo CRUD (polymorphic conteudos)
  - [ ] Documento CRUD
  - [ ] Slide management
  - [ ] Item/Menu management
  - [ ] Link tracking (polymorphic contadores)
  - [ ] File uploads
  - [ ] Activity logging

### 3. Environment Requirements
- [x] PHP 8.1+ (Current: 8.1.8) ✅
- [ ] Composer 2.x
  ```bash
  composer --version
  ```
- [ ] MySQL 5.7+ / MariaDB 10.3+
- [ ] Update XAMPP if needed

---

## Breaking Changes - Laravel 10

### 1. **Route Syntax Changes** ⚠️ CRITICAL
**Issue:** String-based controller references deprecated
**Current Code (routes/web.php):**
```php
Route::get('/Administrator', 'AdminController@getIndex');
Route::resource('/Administrator/Artigo', 'ArtigoController');
```

**Update Required:**
```php
use App\Http\Controllers\Administrator\AdminController;
use App\Http\Controllers\Administrator\ArtigoController;

Route::get('/Administrator', [AdminController::class, 'getIndex']);
Route::resource('/Administrator/Artigo', ArtigoController::class);
```

**Action Items:**
- [ ] Update all routes in `routes/web.php` (est. ~100+ route definitions)
- [ ] Update all routes in `routes/api.php`
- [ ] Add controller imports at top of route files

### 2. **Package Compatibility Updates** ⚠️

**Packages Requiring Updates:**

| Package | Current | Laravel 10 | Action |
|---------|---------|------------|--------|
| `laravel/framework` | ^9.0 | ^10.0 | Update |
| `laravel/ui` | ^3.0 | ^4.0 | Update |
| `laravel/tinker` | ^2.5 | ^2.8 | Update |
| `laravelcollective/html` | ^6.0 | ^6.4 | Update |
| `spatie/laravel-activitylog` | ^4.0 | ^4.7 | Test compatibility |
| `spatie/laravel-ignition` | ^1.0 | ^2.0 | Update |
| `yajra/laravel-datatables-oracle` | ^9.0 | ^10.0 | Update |
| `santigarcor/laratrust` | ^7.0 | ^8.0 | Update |
| `kreait/laravel-firebase` | ^4.2 | ^5.0 | Check compatibility |
| `nunomaduro/collision` | ^6.1 | ^7.0 | Update |
| `phpunit/phpunit` | ^9.0 | ^10.0 | Update |
| `fideloper/proxy` | ^4.2 | **REMOVED** | Replace with config |

- [ ] Review each package changelog
- [ ] Test compatibility in staging

### 3. **Removed TrustProxies Middleware** ⚠️

**Current:** `fideloper/proxy` package
**Laravel 10:** Built-in configuration

**Action:**
- [ ] Remove `fideloper/proxy` from composer.json
- [ ] Update `app/Http/Middleware/TrustProxies.php`:
```php
// OLD
use Fideloper\Proxy\TrustProxies as Middleware;

// NEW
use Illuminate\Http\Middleware\TrustProxies as Middleware;
```
- [ ] Update `protected $proxies` property if needed

### 4. **Validation Rule Changes**

**Deprecated:** `Rule::unique()` behavior changes
**Action:**
- [ ] Review all validation rules in Request classes
- [ ] Test unique validation in:
  - UserRequest
  - ArtigoRequest
  - DocumentoRequest
  - CategoryRequest

### 5. **Faker Package Replacement**

**Current:** `fzaninotto/faker` (abandoned)
**Replace with:** `fakerphp/faker`

**Action:**
- [ ] Update composer.json:
```json
"require-dev": {
  "fakerphp/faker": "^1.21"
}
```
- [ ] Update factory imports if using Faker

### 6. **Model Changes**

**Soft Deletes:** Query builder behavior changes
**Action:**
- [ ] Test models using soft deletes
- [ ] Verify trash/restore functionality

### 7. **Date Formatting**

**Change:** Default date serialization format
**Action:**
- [ ] Test API responses with dates
- [ ] Check frontend date displays
- [ ] Review `created_at`, `updated_at` displays in views

---

## Step-by-Step Upgrade Process

### Phase 1: Preparation (1-2 hours)
```bash
# 1. Create upgrade branch
git checkout -b upgrade/laravel-10

# 2. Backup database
php artisan down
mysqldump -u root -p ipiaam_db > pre_upgrade_backup.sql

# 3. Update composer.json dependencies
```

### Phase 2: Update composer.json (15 minutes)

Edit `composer.json`:
```json
{
  "require": {
    "php": "^8.1",
    "laravel/framework": "^10.0",
    "laravel/ui": "^4.0",
    "laravel/tinker": "^2.8",
    "laravelcollective/html": "^6.4",
    "spatie/laravel-ignition": "^2.0",
    "yajra/laravel-datatables-oracle": "^10.0",
    "santigarcor/laratrust": "^8.0",
    "nunomaduro/collision": "^7.0",
    "guzzlehttp/guzzle": "^7.5"
  },
  "require-dev": {
    "fakerphp/faker": "^1.21",
    "mockery/mockery": "^1.5",
    "phpunit/phpunit": "^10.0"
  }
}
```

**Remove:**
```json
"fideloper/proxy": "^4.2",
"fzaninotto/faker": "^1.4"
```

### Phase 3: Composer Update (30 minutes)
```bash
# 1. Clear caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 2. Remove vendor and lock
rm -rf vendor composer.lock

# 3. Update composer
composer update

# 4. Publish new config files
php artisan vendor:publish --tag=laravel-assets --force
```

### Phase 4: Route Updates (2-3 hours) ⚠️ MOST TIME-CONSUMING

**File:** `routes/web.php`

1. Add controller imports at top:
```php
use App\Http\Controllers\Administrator\{
    AdminController,
    ArtigoController,
    CategoriaController,
    ConfigController,
    ConteudoController,
    ContadorController,
    DocumentoController,
    EventoController,
    FaqController,
    ImagemController,
    ItemController,
    LanguageController,
    LinkController,
    ParceiroController,
    SlideController,
    TagController,
    TipoController,
    UserController,
    ModuloController,
    ActivityLogController
};
use App\Http\Controllers\{
    PagesController,
    Auth\LoginController,
    Auth\ForgotPasswordController,
    Auth\ResetPasswordController
};
```

2. Update all route definitions:
```php
// OLD
Route::get('/Administrator', 'AdminController@getIndex');
Route::resource('/Administrator/Artigo', 'ArtigoController');

// NEW
Route::get('/Administrator', [AdminController::class, 'getIndex']);
Route::resource('/Administrator/Artigo', ArtigoController::class);
```

**Tools to help:**
```bash
# Find all routes to update
grep -n "@" routes/web.php | wc -l

# Use regex find/replace in VS Code:
# Find: '([A-Za-z]+Controller)@([a-zA-Z]+)'
# Replace: [$1::class, '$2']
```

- [ ] Update routes/web.php (~200+ routes)
- [ ] Update routes/api.php
- [ ] Update routes/console.php if exists

### Phase 5: Middleware Updates (30 minutes)

**File:** `app/Http/Middleware/TrustProxies.php`
```php
<?php
namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    protected $proxies;
    protected $headers = Request::HEADER_X_FORWARDED_FOR |
                         Request::HEADER_X_FORWARDED_HOST |
                         Request::HEADER_X_FORWARDED_PORT |
                         Request::HEADER_X_FORWARDED_PROTO |
                         Request::HEADER_X_FORWARDED_AWS_ELB;
}
```

### Phase 6: Config Updates (15 minutes)
```bash
# Publish Laravel 10 config files
php artisan config:publish

# Review changes in:
# - config/app.php
# - config/auth.php
# - config/database.php
# - config/session.php
```

### Phase 7: Testing (2-3 hours)

**Critical Tests:**
- [ ] Authentication & Authorization
  ```bash
  # Test login/logout
  # Test password reset
  # Test user permissions (Laratrust)
  ```

- [ ] Polymorphic Relationships
  ```bash
  php artisan tinker
  # Test Artigo::with('conteudos')->first()
  # Test Documento::with('conteudos')->first()
  # Test Link::with('contadores')->first()
  ```

- [ ] CRUD Operations
  - [ ] Create Artigo with PT/EN content
  - [ ] Update Artigo
  - [ ] Delete Artigo (verify cascade)
  - [ ] Same for Documento, Slide, Item
  
- [ ] File Uploads
  - [ ] Test image upload
  - [ ] Test document upload
  - [ ] Verify storage links work

- [ ] Frontend Routes
  - [ ] Homepage
  - [ ] Artigo detail pages
  - [ ] Documento detail pages
  - [ ] Navigation menus
  - [ ] Search functionality

- [ ] Activity Logging
  ```bash
  # Verify Spatie ActivityLog still working
  php artisan tinker
  # activity()->log('test');
  ```

### Phase 8: Performance Optimization
```bash
# Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload -o
```

---

## Potential Issues & Solutions

### Issue 1: Route Caching Errors
**Symptom:** Routes don't work after `route:cache`
**Solution:** 
- Ensure all routes use array syntax `[Controller::class, 'method']`
- Clear route cache: `php artisan route:clear`

### Issue 2: Middleware Conflicts
**Symptom:** 500 errors on protected routes
**Solution:**
- Check `app/Http/Kernel.php` middleware aliases
- Verify TrustProxies updated correctly

### Issue 3: Package Incompatibility
**Symptom:** Composer conflicts or runtime errors
**Solution:**
- Check package GitHub issues for Laravel 10 compatibility
- Consider alternatives or wait for updates
- Test each package individually

### Issue 4: Date Format Changes
**Symptom:** JSON date formats different
**Solution:**
- Add to models if needed:
```php
protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
];
```

### Issue 5: Validation Errors
**Symptom:** Unique validation fails unexpectedly
**Solution:**
- Update validation rules to new syntax
- Use `Rule::unique()` explicitly

---

## Rollback Plan

If upgrade fails:
```bash
# 1. Restore database
mysql -u root -p ipiaam_db < pre_upgrade_backup.sql

# 2. Restore code
git checkout main
composer install

# 3. Clear caches
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# 4. Bring site back up
php artisan up
```

---

## Post-Upgrade Verification

- [ ] All pages load without errors
- [ ] No console errors in browser
- [ ] Authentication works
- [ ] CRUD operations work
- [ ] File uploads work
- [ ] Polymorphic relationships intact
- [ ] Activity logging works
- [ ] Email sending works
- [ ] Queue processing works (if used)
- [ ] Scheduled tasks work (if used)

---

## Timeline Estimate

| Phase | Time | Priority |
|-------|------|----------|
| Backup & Preparation | 1-2 hours | CRITICAL |
| composer.json updates | 15 min | CRITICAL |
| Composer update | 30 min | CRITICAL |
| Route syntax updates | 2-3 hours | CRITICAL |
| Middleware updates | 30 min | HIGH |
| Config updates | 15 min | MEDIUM |
| Testing | 2-3 hours | CRITICAL |
| Bug fixes | 1-2 hours | VARIABLE |
| **Total Estimated Time** | **8-12 hours** | |

---

## Recommended Approach

### Option A: Staged Upgrade (RECOMMENDED)
1. Upgrade on local development first
2. Test thoroughly (1-2 days)
3. Deploy to staging environment
4. Test again (1 day)
5. Schedule production upgrade with maintenance window

### Option B: Direct Upgrade
1. Schedule maintenance window (4-6 hours)
2. Backup everything
3. Run upgrade
4. Test critical paths
5. Fix issues or rollback

---

## Important Notes

⚠️ **CRITICAL CONCERNS:**

1. **Route Updates are Mandatory** - Laravel 10 will throw errors with old syntax
2. **Package Compatibility** - Some packages may not support Laravel 10 yet
3. **Database Integrity** - Polymorphic relationships must be tested thoroughly
4. **No Downtime for Critical Sites** - Use staging environment first

✅ **ADVANTAGES OF UPGRADING:**

1. Performance improvements
2. Better error handling
3. Security updates
4. Long-term support (Laravel 10 LTS)
5. Better developer experience
6. Modern dependencies

---

## Contact Checklist

Before starting:
- [ ] Notify users of maintenance window
- [ ] Inform team of upgrade timeline
- [ ] Have rollback plan ready
- [ ] Keep database admin on standby
- [ ] Document any custom code that might break

---

## Additional Resources

- [Laravel 10 Upgrade Guide](https://laravel.com/docs/10.x/upgrade)
- [Laravel 10 Release Notes](https://laravel.com/docs/10.x/releases)
- [Laracasts Laravel 10 Updates](https://laracasts.com)

---

**Last Updated:** November 7, 2025  
**Document Version:** 1.0  
**Prepared For:** IPIAAM CMS Laravel 10 Upgrade
