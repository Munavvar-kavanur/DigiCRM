<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['super_admin', 'branch_admin']);

        // Branch Filter (Super Admin)
        if (auth()->user()->isSuperAdmin()) {
            if ($request->has('branch_id')) {
                // Explicit filter from UI
                if ($request->branch_id != '') {
                    $query->where('branch_id', $request->branch_id);
                }
                // If branch_id is present but empty, it means "All Branches", so we don't filter.
                // This overrides the session context for this page.
            } elseif (session()->has('settings_branch_context')) {
                // Fallback to session context if no explicit filter
                $branchId = session('settings_branch_context');
                if ($branchId) {
                     $query->where('branch_id', $branchId);
                }
            }
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Role Filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Clone query for stats to respect filters (except role filter for distribution stats usually, but let's keep it simple or separate)
        // Actually, stats usually show the overview. Let's calculate stats based on the *current branch context* but ignoring the specific role filter so we see the distribution.
        
        $statsQuery = User::whereIn('role', ['super_admin', 'branch_admin']);
        
        // Apply Branch Filter to Stats
        if (auth()->user()->isSuperAdmin()) {
            if ($request->has('branch_id') && $request->branch_id != '') {
                $statsQuery->where('branch_id', $request->branch_id);
            } elseif (session()->has('settings_branch_context') && !$request->has('branch_id')) {
                $branchId = session('settings_branch_context');
                if ($branchId) {
                     $statsQuery->where('branch_id', $branchId);
                }
            }
        }

        $totalUsers = (clone $statsQuery)->count();
        $totalSuperAdmins = (clone $statsQuery)->where('role', 'super_admin')->count();
        $totalBranchAdmins = (clone $statsQuery)->where('role', 'branch_admin')->count();

        $users = $query->latest()->paginate(10)->withQueryString();
            
        return view('settings.users.index', compact('users', 'totalUsers', 'totalSuperAdmins', 'totalBranchAdmins'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('settings.users.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:super_admin,branch_admin'],
            'branch_id' => ['nullable', 'exists:branches,id', 'required_if:role,branch_admin'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'branch_id' => $request->role === 'super_admin' ? null : $request->branch_id,
            'is_employee' => false, // System users are not necessarily employees in the HR sense
        ]);

        return redirect()->route('settings.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $branches = Branch::all();
        return view('settings.users.edit', compact('user', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'role' => ['required', 'in:super_admin,branch_admin'],
            'branch_id' => ['nullable', 'exists:branches,id', 'required_if:role,branch_admin'],
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'branch_id' => $request->role === 'super_admin' ? null : $request->branch_id,
        ];

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('settings.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('settings.users.index')->with('success', 'User deleted successfully.');
    }
}
