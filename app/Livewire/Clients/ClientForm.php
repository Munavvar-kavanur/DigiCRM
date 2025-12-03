<?php

namespace App\Livewire\Clients;

use App\Models\Client;
use Livewire\Component;
use Livewire\Attributes\Layout;

class ClientForm extends Component
{
    public ?Client $client = null;
    public $name = '';
    public $email = '';
    public $phone = '';
    public $company_name = '';
    public $website = '';
    public $tax_id = '';
    public $status = 'active';
    public $address = '';
    public $notes = '';
    public $password = '';

    public $branch_id = null;

    public function mount(Client $client = null)
    {
        $this->client = $client;

        if ($client && $client->exists) {
            $this->name = $client->name;
            $this->email = $client->email;
            $this->phone = $client->phone;
            $this->company_name = $client->company_name;
            $this->website = $client->website;
            $this->tax_id = $client->tax_id;
            $this->status = $client->status;
            $this->address = $client->address;
            $this->notes = $client->notes;
            $this->branch_id = $client->branch_id;
        }
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:clients,email,' . ($this->client->id ?? 'NULL'),
            'phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'tax_id' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'branch_id' => 'nullable|exists:branches,id',
            'password' => 'nullable|string|min:8',
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        // Only allow Super Admin to set branch_id
        if (!auth()->user()->isSuperAdmin()) {
            unset($validated['branch_id']);
        }

        if ($this->client && $this->client->exists) {
            $this->client->update($validated);
            
            // Update associated user email if it exists
            if ($this->client->user) {
                $userData = [
                    'name' => $this->name,
                    'email' => $this->email,
                ];
                
                if (!empty($this->password)) {
                    $userData['password'] = \Illuminate\Support\Facades\Hash::make($this->password);
                }
                
                $this->client->user->update($userData);
            } else {
                // Create User for existing Client if missing
                $password = !empty($this->password) ? $this->password : 'password';
                
                $user = \App\Models\User::firstOrCreate(
                    ['email' => $this->email],
                    [
                        'name' => $this->name,
                        'password' => \Illuminate\Support\Facades\Hash::make($password),
                        'role' => 'client',
                    ]
                );
                 // Ensure the user has the client role
                if ($user->role !== 'client' && $user->role !== 'super_admin' && $user->role !== 'branch_admin') {
                     $user->update(['role' => 'client']);
                }
                
                $this->client->update(['user_id' => $user->id]);
            }

            session()->flash('status', 'Client updated successfully.');
        } else {
            // Create User for the Client
            $password = !empty($this->password) ? $this->password : 'password';
            
            $user = \App\Models\User::firstOrCreate(
                ['email' => $this->email],
                [
                    'name' => $this->name,
                    'password' => \Illuminate\Support\Facades\Hash::make($password), // Default password
                    'role' => 'client',
                ]
            );

            // Ensure the user has the client role if they existed but didn't have it (optional, but good for safety)
            if ($user->role !== 'client' && $user->role !== 'super_admin' && $user->role !== 'branch_admin') {
                 $user->update(['role' => 'client']);
            }

            $validated['user_id'] = $user->id;
            Client::create($validated);
            session()->flash('status', 'Client created successfully. Default password is "' . $password . '".');
        }

        return $this->redirect(route('clients.index'), true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $branches = \App\Models\Branch::orderBy('name')->get();
        return view('livewire.clients.client-form', compact('branches'));
    }
}
