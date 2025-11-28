<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit(Request $request)
    {
        $branches = auth()->user()->isSuperAdmin() ? \App\Models\Branch::all() : collect();
        $branchId = null;

        if (auth()->user()->isSuperAdmin()) {
            if ($request->has('branch_id')) {
                $branchId = $request->branch_id;
                // Treat empty string as null (Super Admin Context)
                if ($branchId === '') {
                    $branchId = null;
                }
                session(['settings_branch_context' => $branchId]);
            } else {
                $branchId = session('settings_branch_context');
            }
            
            // Removed forced default branch selection to allow Super Admin Context (null)
        } else {
            $branchId = auth()->user()->branch_id;
        }

        $settings = Setting::getAll($branchId);
        $superAdminSettings = auth()->user()->isSuperAdmin() ? Setting::getAll(null) : collect();
        
        // Filter types by branch context
        $expenseCategories = \App\Models\ExpenseCategory::where('branch_id', $branchId)->get();
        $employeeTypes = \App\Models\EmployeeType::where('branch_id', $branchId)->get();
        $payrollTypes = \App\Models\PayrollType::where('branch_id', $branchId)->get();
        
        return view('settings.edit', compact('settings', 'superAdminSettings', 'expenseCategories', 'employeeTypes', 'payrollTypes', 'branches', 'branchId'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_email' => 'nullable|email|max:255',
            'company_phone' => 'nullable|string|max:255',
            'company_website' => 'nullable|url|max:255',
            'company_address' => 'nullable|string|max:1000',
            'company_city' => 'nullable|string|max:255',
            'company_state' => 'nullable|string|max:255',
            'company_zip' => 'nullable|string|max:20',
            'company_country' => 'nullable|string|max:255',
            'tax_id' => 'nullable|string|max:255',
            'crm_logo_light' => 'nullable|image|max:2048',
            'crm_logo_dark' => 'nullable|image|max:2048',
            'crm_logo_collapsed_light' => 'nullable|image|max:2048',
            'crm_logo_collapsed_dark' => 'nullable|image|max:2048',
            'invoice_logo_light' => 'nullable|image|max:2048',
            'invoice_logo_dark' => 'nullable|image|max:2048',
            'favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:1024',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $branchId = null;
        if (auth()->user()->isSuperAdmin()) {
            $branchId = $request->branch_id;
        } else {
            $branchId = auth()->user()->branch_id;
        }

        $data = $request->except(['_token', '_method', 'crm_logo_light', 'crm_logo_dark', 'crm_logo_collapsed_light', 'crm_logo_collapsed_dark', 'invoice_logo_light', 'invoice_logo_dark', 'favicon', 'branch_id']);

        // Handle File Uploads
        $files = ['crm_logo_light', 'crm_logo_dark', 'crm_logo_collapsed_light', 'crm_logo_collapsed_dark', 'invoice_logo_light', 'invoice_logo_dark', 'favicon'];
        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $path = $request->file($file)->store('logos', 'public');
                Setting::set($file, $path, $branchId);
            }
        }

        // Save other settings
        foreach ($data as $key => $value) {
            Setting::set($key, $value, $branchId);
        }

        return redirect()->route('settings.edit', ['branch_id' => $branchId, 'tab' => $request->tab])->with('success', 'Settings updated successfully.');
    }
}
