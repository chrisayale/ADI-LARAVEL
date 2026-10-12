# Fix for Admin Dashboard 500 Error

## Issue
The admin dashboard was returning a 500 Internal Server Error due to:
1. Missing table `request_saccos` (actually a mismatch between expected table name and actual table name)
2. Sidebar attempting to access a non-existent route `admin.request-sacco.index`

## Root Cause
1. The `RequestSacco` model was using Laravel's default table naming convention, which converts `RequestSacco` to `request_saccos` (snake_case pluralized). However, the table had been created as `request_sacos` (to match the `sacos` table pattern).
2. The sidebar view was linking to `admin.request-sacco.index`, but the RequestSacco controller is in the root namespace (`App\Http\Controllers\RequestSaccoController`) and its routes are defined under the `request-sacco` prefix, not `admin/request-sacco`.

## Fixes Applied

### 1. Fixed RequestSacco Model Table Name
**File:** `app/Models/RequestSacco.php`
**Change:** Added explicit table name definition:
```php
protected $table = 'request_sacos';
```

### 2. Fixed Sidebar Route Link
**File:** `resources/views/partials/sidebar.blade.php`
**Change:** 
- Changed `route('admin.request-sacco.index')` to `route('request-sacco.index')`
- Changed `request()->routeIs('admin.request-sacco.index')` to `request()->routeIs('request-sacco.index')`

## Verification
After applying these changes, the admin dashboard loads successfully with a 200 status code, displaying the dashboard charts and statistics.

## Additional Notes
- The `request_sacos` table was verified to exist with the correct schema.
- The RequestSacco model was confirmed to use the correct table name after the fix.
- The sidebar now correctly links to the request-sacco index route for authenticated users.