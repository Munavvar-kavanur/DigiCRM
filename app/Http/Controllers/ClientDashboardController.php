<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Use the client relationship which now relies on client_id
        $client = $user->client; 
        
        // Fallback: if user->client is null (maybe relationship issue), try finding client where user_id matches
        if (!$client) {
             $client = \App\Models\Client::where('user_id', $user->id)->first();
        }

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
