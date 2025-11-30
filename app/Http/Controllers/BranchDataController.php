<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;

class BranchDataController extends Controller
{
    public function getClients(Branch $branch)
    {
        return response()->json($branch->clients()->where('status', 'active')->orderBy('name')->get(['id', 'name']));
    }

    public function getProjects(Branch $branch)
    {
        return response()->json($branch->projects()->orderBy('name')->get(['id', 'name']));
    }

    public function getEmployees(Branch $branch)
    {
        return response()->json($branch->users()->where('is_employee', true)->orderBy('name')->get(['id', 'name']));
    }
}
