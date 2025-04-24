<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function assignClientsForm()
{
    $trainers = User::where('role', 'trainer')->get();
    $clients = User::where('role', 'user')->get();

    return view('admin.assign-clients', compact('trainers', 'clients'));
}

public function assignClients(Request $request)
{
    $request->validate([
        'trainer_id' => 'required|exists:users,id',
        'client_ids' => 'array',
        'client_ids.*' => 'exists:users,id',
    ]);

    foreach ($request->client_ids as $clientId) {
        $client = User::find($clientId);
        $client->trainer_id = $request->trainer_id;
        $client->save();
    }

    return redirect()->back()->with('success', 'Clients assigned successfully.');
}

public function index()
{
    $users = \App\Models\User::orderBy('created_at', 'desc')->get();

    return view('admin.users.index', compact('users'));
}







public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6',
        'role' => 'required|in:admin,trainer,user',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
    ]);

    return redirect()->route('users.index')->with('success', 'User created successfully.');
}

public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:admin,trainer,user',
    ]);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
    ]);

    return redirect()->route('users.index')->with('success', 'User updated successfully.');
}

public function destroy(User $user)
{
    $user->delete();

    return redirect()->route('users.index')->with('success', 'User deleted successfully.');
}
}
