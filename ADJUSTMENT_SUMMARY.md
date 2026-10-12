# Adjustments for Admin SACCO Requests

## Changes Made

### 1. Sidebar Link for Super Admin
**File:** `resources/views/partials/sidebar.blade.php`  
**Changed:** The SACCO Requests link in the admin section now points to `admin.request-sacco.index` instead of `request-sacco.index`.  
- Updated `href` and `request()->routeIs` to use `admin.request-sacco.index`.

### 2. Admin Request-Sacco Routes
**File:** `routes/web.php`  
**Added:**  
- Use statement: `use App\Http\Controllers\Admin\RequestSaccoController as AdminRequestSaccoController;`  
- Index route inside the admin/request-sacco group:  
  `Route::get('/', [AdminRequestSaccoController::class, 'index'])->name('index');`

### 3. Admin Request-Sacco Controller
**File:** `app/Http/Controllers/Admin/RequestSaccoController.php`  
**Verified:** The controller already exists and includes an `index` method that:
- Restricts access to super_admin users.
- Accepts a status filter parameter (pending, approved, rejected, all).
- Retrieves requests with eager loading of the requester relationship.
- Returns the view `admin.sacco-requests.index` with the requests and current status.

### 4. Admin Request-Sacco View
**File:** `resources/views/admin/sacco-requests/index.blade.php`  
**Verified:** The view already exists and includes:
- Status filter buttons (All, Pending, Approved, Rejected).
- Table with columns: Nom du SACCO, Demandeur, Localisation, Téléphone Mobile Money, Statut, Date de soumission, Actions.
- Pagination.
- Actions buttons (View, Approve, Reject) for pending requests.

### 5. Cache Clearing
**Commands Run:**  
- `php artisan route:clear`
- `php artisan view:clear`

## Result
The admin dashboard sidebar now correctly links to `/admin/request-sacco`, which displays the administrative interface for managing SACCO requests with full filtering and tabular data, as required.