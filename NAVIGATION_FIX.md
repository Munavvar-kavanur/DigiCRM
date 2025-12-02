# Navigation Fix - Summary

## Problem Identified
After implementing mobile APIs (commit `d3963a295006cd090b2d7681d6713fce12efd66f`), the admin panel navigation stopped working for the following pages:
- Clients
- Projects
- Invoices
- Estimates
- Expenses
- Payroll
- Employees

These pages would redirect to the dashboard instead of loading correctly.

## Root Cause
When the mobile API routes were added using `Route::apiResource()`, they created route names that **conflicted** with the web route names:

### Before the fix:
- API routes: `clients.index`, `projects.index`, etc. → `/api/clients`, `/api/projects`
- Web routes: `clients.index`, `projects.index`, etc. → `/clients`, `/projects`

Laravel gave priority to the API routes (loaded first), so when the sidebar called `route('clients.index')`, it generated `/api/clients` instead of `/clients`.

## Solution Applied
Modified `routes/api.php` to prefix all API route names with `'api.'`:

```php
// Before:
Route::apiResource('clients', ClientController::class);

// After:
Route::apiResource('clients', ClientController::class, ['as' => 'api']);
```

### After the fix:
- API routes: `api.clients.index`, `api.projects.index`, etc. → `/api/clients`, `/api/projects`
- Web routes: `clients.index`, `projects.index`, etc. → `/clients`, `/projects`

## Result
✅ **Admin panel navigation**: Now works correctly - all pages are accessible
✅ **Mobile APIs**: Still work perfectly - all endpoints remain at the same URLs

## API Endpoints (unchanged)
Your mobile app can still use all the same endpoints:
- `GET /api/clients` - List all clients
- `POST /api/clients` - Create a client
- `GET /api/clients/{id}` - Get specific client
- `PUT /api/clients/{id}` - Update a client
- `DELETE /api/clients/{id}` - Delete a client

Same pattern applies for: projects, invoices, estimates, expenses, tasks, employees, payrolls

## Files Modified
1. `routes/api.php` - Added route name prefixes
2. `resources/views/components/sidebar-link.blade.php` - Removed `wire:navigate` (reverted later, not needed)

## Testing
- ✅ Admin navigation tested on localhost - working
- ✅ API routes verified - all endpoints accessible
- ✅ Route names verified - no conflicts

## Notes for Mobile App Development
If you need to generate API route URLs in Laravel (for documentation, etc.), use:
```php
route('api.clients.index')  // Generates: http://domain.com/api/clients
route('api.projects.show', 1)  // Generates: http://domain.com/api/projects/1
```

For web routes, continue using:
```php
route('clients.index')  // Generates: http://domain.com/clients
route('projects.show', 1)  // Generates: http://domain.com/projects/1
```
