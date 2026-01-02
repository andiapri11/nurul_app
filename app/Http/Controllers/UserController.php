<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'mading')->with('unit')->get();
        $roles = User::ROLES;
        $units = \App\Models\Unit::all();
        return view('users.index', compact('users', 'roles', 'units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => ['required', Rule::in(User::ROLES)],
            'password' => 'required|string|min:8',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'unit_id' => $request->unit_id,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }
}
