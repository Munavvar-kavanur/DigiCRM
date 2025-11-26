<?php

use App\Http\Controllers\ProfileController;
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

    Route::get('/clients/create', \App\Livewire\Clients\ClientForm::class)->name('clients.create');
    Route::get('/clients/{client}/edit', \App\Livewire\Clients\ClientForm::class)->name('clients.edit');
    Route::resource('clients', \App\Http\Controllers\ClientController::class)->except(['create', 'store', 'edit', 'update']);
    Route::resource('projects', \App\Http\Controllers\ProjectController::class);
    Route::resource('invoices', \App\Http\Controllers\InvoiceController::class);
    Route::get('invoices/{invoice}/pdf', [\App\Http\Controllers\InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::resource('estimates', \App\Http\Controllers\EstimateController::class);
    Route::get('estimates/{estimate}/pdf', [\App\Http\Controllers\EstimateController::class, 'downloadPdf'])->name('estimates.pdf');
    Route::post('invoices/{invoice}/payments', [\App\Http\Controllers\PaymentController::class, 'store'])->name('invoices.payments.store');
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export', [\App\Http\Controllers\ReportController::class, 'export'])->name('reports.export');
    Route::get('/global-search', [\App\Http\Controllers\GlobalSearchController::class, 'search'])->name('global.search');
    Route::get('settings', [\App\Http\Controllers\SettingController::class, 'edit'])->name('settings.edit');
    Route::patch('settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    Route::resource('expenses', \App\Http\Controllers\ExpenseController::class);
    Route::post('expense-categories', [\App\Http\Controllers\ExpenseCategoryController::class, 'store'])->name('expense-categories.store');
    Route::delete('expense-categories/{expenseCategory}', [\App\Http\Controllers\ExpenseCategoryController::class, 'destroy'])->name('expense-categories.destroy');
    
    Route::post('employee-types', [\App\Http\Controllers\EmployeeTypeController::class, 'store'])->name('employee-types.store');
    Route::delete('employee-types/{employeeType}', [\App\Http\Controllers\EmployeeTypeController::class, 'destroy'])->name('employee-types.destroy');

    Route::post('payroll-types', [\App\Http\Controllers\PayrollTypeController::class, 'store'])->name('payroll-types.store');
    Route::delete('payroll-types/{payrollType}', [\App\Http\Controllers\PayrollTypeController::class, 'destroy'])->name('payroll-types.destroy');

    Route::post('projects/{project}/tasks', [\App\Http\Controllers\TaskController::class, 'store'])->name('projects.tasks.store');
    Route::put('tasks/{task}', [\App\Http\Controllers\TaskController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [\App\Http\Controllers\TaskController::class, 'destroy'])->name('tasks.destroy');

    Route::resource('payrolls', \App\Http\Controllers\PayrollController::class);
    Route::get('payrolls/{payroll}/pdf', [\App\Http\Controllers\PayrollController::class, 'downloadPdf'])->name('payrolls.downloadPdf');

    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);

    Route::resource('reminders', \App\Http\Controllers\ReminderController::class);
    Route::patch('reminders/{reminder}/complete', [\App\Http\Controllers\ReminderController::class, 'markAsComplete'])->name('reminders.complete');
});

require __DIR__.'/auth.php';


