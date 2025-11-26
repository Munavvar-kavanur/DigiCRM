<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Project;
use App\Models\Invoice;
use App\Models\Estimate;

class GlobalSearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return response()->json([]);
        }

        $results = [];

        // Search Clients
        $clients = Client::where('name', 'like', "%{$query}%")
            ->orWhere('id', $query)
            ->take(5)
            ->get()
            ->map(function ($client) {
                return [
                    'id' => $client->id,
                    'title' => $client->name,
                    'subtitle' => 'Client',
                    'url' => route('clients.show', $client->id),
                    'type' => 'Client'
                ];
            });

        // Search Projects
        $projects = Project::where('title', 'like', "%{$query}%")
            ->orWhere('id', $query)
            ->take(5)
            ->get()
            ->map(function ($project) {
                return [
                    'id' => $project->id,
                    'title' => $project->title,
                    'subtitle' => 'Project',
                    'url' => route('projects.show', $project->id),
                    'type' => 'Project'
                ];
            });

        // Search Invoices
        $invoices = Invoice::with(['client', 'project'])
            ->where('invoice_number', 'like', "%{$query}%")
            ->orWhere('id', $query)
            ->orWhereHas('client', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('project', function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%");
            })
            ->take(5)
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'title' => $invoice->invoice_number,
                    'subtitle' => 'Invoice - ' . ($invoice->client->name ?? 'Unknown Client'),
                    'url' => route('invoices.show', $invoice->id),
                    'type' => 'Invoice'
                ];
            });

        // Search Estimates
        $estimates = Estimate::with(['client', 'project'])
            ->where('estimate_number', 'like', "%{$query}%")
            ->orWhere('id', $query)
            ->orWhereHas('client', function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('project', function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%");
            })
            ->take(5)
            ->get()
            ->map(function ($estimate) {
                return [
                    'id' => $estimate->id,
                    'title' => $estimate->estimate_number,
                    'subtitle' => 'Estimate - ' . ($estimate->client->name ?? 'Unknown Client'),
                    'url' => route('estimates.show', $estimate->id),
                    'type' => 'Estimate'
                ];
            });

        $results = $clients->merge($projects)->merge($invoices)->merge($estimates);

        return response()->json($results);
    }
}
