<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $client = $user->client;

        if (!$client) {
            abort(403, 'User is not associated with a client account.');
        }

        $projects = $client->projects()->with('tasks')->latest()->get();
        $invoices = $client->invoices()->latest()->get();
        $payments = $client->payments()->latest()->get();
        $estimates = $client->estimates()->latest()->get();

        $totalDue = $invoices->where('status', '!=', 'paid')->sum('total') - $invoices->where('status', '!=', 'paid')->sum('paid_amount'); // Simplified calculation

        return view('client.dashboard', compact('client', 'projects', 'invoices', 'payments', 'estimates', 'totalDue'));
    }
}
