<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BranchController;
use Illuminate\Support\Facades\Route;





Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Branch Routes (Super Admin only)
    Route::middleware(['role:super_admin'])->resource('branches', BranchController::class);

    // Dynamic Dropdown Data Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/branches/{branch}/clients', [\App\Http\Controllers\BranchDataController::class, 'getClients'])->name('branches.clients');
        Route::get('/branches/{branch}/projects', [\App\Http\Controllers\BranchDataController::class, 'getProjects'])->name('branches.projects');
        Route::get('/branches/{branch}/employees', [\App\Http\Controllers\BranchDataController::class, 'getEmployees'])->name('branches.employees');
        Route::get('/clients/{client}/projects', [\App\Http\Controllers\ClientController::class, 'getProjects'])->name('clients.projects');
    });


    Route::get('/clients/create', \App\Livewire\Clients\ClientForm::class)->name('clients.create');
    Route::get('/clients/{client}/edit', \App\Livewire\Clients\ClientForm::class)->name('clients.edit');
    Route::resource('clients', \App\Http\Controllers\ClientController::class)->except(['create', 'store', 'edit', 'update']);
    Route::resource('projects', \App\Http\Controllers\ProjectController::class);
    Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);
    Route::get('invoices/{invoice}/pdf', [\App\Http\Controllers\InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::get('invoices/{invoice}/preview', [\App\Http\Controllers\InvoiceController::class, 'previewPdf'])->name('invoices.preview');
    Route::resource('estimates', \App\Http\Controllers\EstimateController::class);
    Route::get('estimates/{estimate}/pdf', [\App\Http\Controllers\EstimateController::class, 'downloadPdf'])->name('estimates.pdf');
    Route::post('invoices/{invoice}/payments', [\App\Http\Controllers\PaymentController::class, 'store'])->name('invoices.payments.store');
    Route::post('invoices/{invoice}/razorpay/order', [\App\Http\Controllers\PaymentController::class, 'createRazorpayOrder'])->name('invoices.razorpay.order');
    Route::post('invoices/{invoice}/razorpay/verify', [\App\Http\Controllers\PaymentController::class, 'verifyRazorpayPayment'])->name('invoices.razorpay.verify');
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
    Route::get('/global-search', [\App\Http\Controllers\GlobalSearchController::class, 'search'])->name('global.search');
    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'edit'])->name('settings.edit');
    Route::patch('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    
    // User Management (Settings)
    Route::middleware(['role:super_admin'])->resource('settings/users', \App\Http\Controllers\UserController::class, ['as' => 'settings']);

    // Backup & Restore (Super Admin only)
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/settings/backup', [\App\Http\Controllers\BackupController::class, 'download'])->name('settings.backup');
        Route::post('/settings/restore', [\App\Http\Controllers\BackupController::class, 'restore'])->name('settings.restore');
    });

    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    Route::post('expense-categories', [\App\Http\Controllers\ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
    Route::put('expense-categories/{expenseCategory}', [\App\Http\Controllers\ExpenseCategoryController::class, 'update'])->name('expense-categories.update');
    Route::delete('expense-categories/{expenseCategory}', [\App\Http\Controllers\ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy');
    
    Route::post('employee-types', [\App\Http\Controllers\EmployeeTypeController::class, 'store'])->name('employee-types.store');
    Route::delete('employee-types/{employeeType}', [\App\Http\Controllers\EmployeeTypeController::class, 'destroy'])->name('employee-types.destroy');

    Route::post('payroll-types', [\App\Http\Controllers\PayrollTypeController::class, 'store'])->name('payroll-types.store');
    Route::delete('payroll-types/{payrollType}', [\App\Http\Controllers\PayrollTypeController::class, 'destroy'])->name('payroll-types.destroy');

    Route::post('expense-payers', [\App\Http\Controllers\ExpensePayerController::class, 'store'])->name('expense-payers.store');
    Route::put('expense-payers/{expensePayer}', [\App\Http\Controllers\ExpensePayerController::class, 'update'])->name('expense-payers.update');
    Route::delete('expense-payers/{expensePayer}', [\App\Http\Controllers\ExpensePayerController::class, 'destroy'])->name('expense-payers.destroy');

    Route::post('projects/{project}/tasks', [\App\Http\Controllers\TaskController::class, 'store'])->name('projects.tasks.store');
    Route::put('tasks/{task}', [\App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [\App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::resource('payrolls', \App\Http\Controllers\PayrollController::class);
    Route::get('payrolls/{payroll}/pdf', [\App\Http\Controllers\PayrollController::class, 'downloadPdf'])->name('payrolls.downloadPdf');

    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);

    // Chat Routes
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');

    Route::resource('reminders', \App\Http\Controllers\ReminderController::class);
    Route::patch('reminders/{reminder}/complete', [\App\Http\Controllers\ReminderController::class, 'markAsComplete'])->name('reminders.complete');

    // Client Portal Routes
    Route::get('/portal', [\App\Http\Controllers\ClientDashboardController::class, 'index'])->name('client.dashboard');
});

require __DIR__.'/auth.php';


