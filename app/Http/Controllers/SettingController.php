<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::getAll();
        $expenseCategories = \App\Models\ExpenseCategory::all();
        $employeeTypes = \App\Models\EmployeeType::all();
        $payrollTypes = \App\Models\PayrollType::all();
        return view('settings.edit', compact('settings', 'expenseCategories', 'employeeTypes', 'payrollTypes'));
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
        ]);

        $data = $request->except(['_token', '_method', 'crm_logo_light', 'crm_logo_dark', 'crm_logo_collapsed_light', 'crm_logo_collapsed_dark', 'invoice_logo_light', 'invoice_logo_dark']);

        // Handle File Uploads
        $files = ['crm_logo_light', 'crm_logo_dark', 'crm_logo_collapsed_light', 'crm_logo_collapsed_dark', 'invoice_logo_light', 'invoice_logo_dark'];
        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                // Delete old file if exists
                $oldFile = Setting::get($file);
                if ($oldFile && Storage::disk('public')->exists($oldFile)) {
                    Storage::disk('public')->delete($oldFile);
                }

                // Store new file
                $path = $request->file($file)->store('logos', 'public');
                Setting::set($file, $path);
            }
        }

        // Save other settings
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('settings.edit')->with('success', 'Settings updated successfully.');
    }
}
