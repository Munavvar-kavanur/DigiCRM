<?php

namespace App\Livewire\Client;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Component;

class TeamManager extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ];
    }

    public function addUser()
    {
        $this->validate();

        $client = auth()->user()->client;

        if (!$client) {
            // Should not happen if middleware is correct
            return;
        }

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'role' => 'client',
            'client_id' => $client->id,
            'branch_id' => $client->branch_id, // Inherit branch from client
        ]);

        $this->reset(['name', 'email', 'password', 'password_confirmation']);
        session()->flash('success', 'Team member added successfully.');
    }

    public function deleteUser($userId)
    {
        $client = auth()->user()->client;
        $user = User::find($userId);

        if ($user && $user->client_id === $client->id) {
            // Prevent deleting the primary user
            if ($client->user_id === $user->id) {
                session()->flash('error', 'Cannot delete the primary account holder.');
                return;
            }
            
            // Prevent deleting self
            if (auth()->id() === $user->id) {
                session()->flash('error', 'You cannot delete your own account.');
                return;
            }

            $user->delete();
            session()->flash('success', 'Team member removed successfully.');
        }
    }

    public function render()
    {
        $client = auth()->user()->client;
        $users = $client ? $client->users()->latest()->get() : collect();

        return view('livewire.client.team-manager', compact('users', 'client'));
    }
}
