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
        ];
    }

    public function save()
    {
        $validated = $this->validate();

        if ($this->client && $this->client->exists) {
            $this->client->update($validated);
            session()->flash('status', 'Client updated successfully.');
        } else {
            Client::create($validated);
            session()->flash('status', 'Client created successfully.');
        }

        return $this->redirect(route('clients.index'), true);
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.clients.client-form');
    }
}
