<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class LeadershipUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', 'direktur')->get();
        return view('leadership_users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('leadership_users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'security_pin' => 'nullable|string|size:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'security_pin' => $request->security_pin ? Hash::make($request->security_pin) : null,
            'role' => 'direktur',
        ];

        if ($request->hasFile('photo')) {
            $imageName = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('photos'), $imageName);
            $data['photo'] = $imageName;
        }

        User::create($data);

        return redirect()->route('leadership-users.index')
            ->with('success', 'User Pimpinan (Direktur) berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $leadership_user)
    {
        if ($leadership_user->role !== 'direktur') {
            abort(404);
        }
        return view('leadership_users.edit', compact('leadership_user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $leadership_user)
    {
        if ($leadership_user->role !== 'direktur') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($leadership_user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($leadership_user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'security_pin' => 'nullable|string|size:6',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $leadership_user->name = $request->name;
        $leadership_user->username = $request->username;
        $leadership_user->email = $request->email;
        
        if ($request->filled('security_pin')) {
            $leadership_user->security_pin = Hash::make($request->security_pin);
        }
        
        if ($request->filled('password')) {
            $leadership_user->password = Hash::make($request->password);
        }

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($leadership_user->photo && file_exists(public_path('photos/' . $leadership_user->photo))) {
                @unlink(public_path('photos/' . $leadership_user->photo));
            }

            $imageName = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('photos'), $imageName);
            $leadership_user->photo = $imageName;
        }

        $leadership_user->save();

        return redirect()->route('leadership-users.index')
            ->with('success', 'User Pimpinan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $leadership_user)
    {
        if ($leadership_user->role !== 'direktur') {
            abort(404);
        }

        if ($leadership_user->photo && file_exists(public_path('photos/' . $leadership_user->photo))) {
            @unlink(public_path('photos/' . $leadership_user->photo));
        }

        $leadership_user->delete();

        return redirect()->route('leadership-users.index')
            ->with('success', 'User Pimpinan berhasil dihapus.');
    }
}
